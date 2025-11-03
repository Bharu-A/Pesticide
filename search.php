<?php
// Always respond as JSON
header('Content-Type: application/json');

// --- Error handling setup ---
ini_set('display_errors', 0);           // Don't print PHP errors to output
ini_set('log_errors', 1);               // Log them instead
error_reporting(E_ALL);

// Include DB connection
include 'db_connect.php';

// Verify DB connection
if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Get crop name from request
$crop = isset($_GET['crop']) ? trim($_GET['crop']) : '';
if (empty($crop)) {
    echo json_encode(["success" => false, "message" => "Crop name is required"]);
    exit;
}

// Crop to pesticide recommendations
$recommendations = [
    'Rice' => ['Fipronil', 'Thiamethoxam', 'Malathion', 'Chlorpyrifos', 'Carbendazim'],
    'Cotton' => ['Cypermethrin', 'Deltamethrin', 'Acephate', 'Imidacloprid', 'Quinalphos'],
    'Vegetables' => ['Neem Oil', 'Spinosad', 'Mancozeb', 'Carbendazim', 'Deltamethrin', 'Acephate'],
    'Fruits' => ['Captan', 'Copper Oxychloride', 'Sulphur Dust', 'Mancozeb', 'Malathion', 'Carbaryl'],
    'Wheat' => ['Glyphosate', 'Pendimethalin', 'Carbendazim', 'Dimethoate'],
    'Maize' => ['Atrazine', 'Imidacloprid', 'Carbendazim', '2,4-D'],
    'Pulses' => ['Thiamethoxam', 'Dimethoate', 'Malathion', 'Neem Oil', 'Quinalphos'],
    'Sugarcane' => ['Fipronil', 'Atrazine', 'Imidacloprid'],
    'Tomato' => ['Mancozeb', 'Chlorothalonil', 'Imidacloprid'],
    'Potato' => ['Mancozeb', 'Chlorothalonil', 'Metalaxyl']
];

$key = ucfirst(strtolower($crop));
if (!array_key_exists($key, $recommendations)) {
    echo json_encode(["success" => false, "message" => "No recommendations found for this crop"]);
    exit;
}

$pesticides = $recommendations[$key];
$pesticideList = "'" . implode("','", array_map('addslashes', $pesticides)) . "'";

// --- Check if store_pesticides table exists ---
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'store_pesticides'");
$tableExists = mysqli_num_rows($tableCheck) > 0;

if ($tableExists) {
    // ✅ Use join if the linking table exists
    $sql = "
        SELECT 
            s.id AS store_id,
            s.name AS store_name, 
            s.address, 
            s.lat, 
            s.lng,
            p.id AS pesticide_id,
            p.name AS pesticide_name, 
            p.description AS pesticide_description,
            p.price, 
            p.category
        FROM stores s
        JOIN store_pesticides sp ON s.id = sp.store_id
        JOIN pesticides p ON sp.pesticide_id = p.id
        WHERE p.name IN ($pesticideList)
        ORDER BY s.name, p.name
    ";
} else {
    // ⚙️ Fallback: show all recommended pesticides for each store
    $sql = "
        SELECT 
            s.id AS store_id,
            s.name AS store_name, 
            s.address
        
        FROM stores s
        ORDER BY s.name
    ";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Query failed", "error" => mysqli_error($conn)]);
    exit;
}

// --- Build store data ---
$stores = [];
if ($tableExists) {
    // ✅ When mapping table exists
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['store_id'];
        if (!isset($stores[$id])) {
            $stores[$id] = [
                'id' => $id,
                'name' => $row['store_name'],
                'address' => $row['address'],
                'lat' => (float) $row['lat'],
                'lng' => (float) $row['lng'],
                'pesticides' => []
            ];
        }
        $stores[$id]['pesticides'][] = [
            'id' => $row['pesticide_id'],
            'name' => $row['pesticide_name'],
            'description' => $row['pesticide_description'],
            'price' => (float) $row['price'],
            'category' => $row['category']
        ];
    }
} else {
    // ⚙️ Fallback: attach all recommended pesticides to each store
    $allPesticides = [];
    $pesticideQuery = "SELECT * FROM pesticides WHERE name IN ($pesticideList)";
    $pResult = mysqli_query($conn, $pesticideQuery);
    if ($pResult) {
        while ($pRow = mysqli_fetch_assoc($pResult)) {
            $allPesticides[] = [
                'id' => $pRow['id'],
                'name' => $pRow['name'],
                'description' => $pRow['description'],
                'price' => (float) $pRow['price'],
                'category' => $pRow['category']
            ];
        }
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $stores[] = [
            'id' => $row['store_id'],
            'name' => $row['store_name'],
            'address' => $row['address'],
            'lat' => (float) $row['lat'],
            'lng' => (float) $row['lng'],
            'pesticides' => $allPesticides
        ];
    }
}

echo json_encode([
    "success" => true,
    "crop" => $key,
    "recommended_pesticides" => $pesticides,
    "stores" => array_values($stores)
], JSON_PRETTY_PRINT);

mysqli_close($conn);
?>
