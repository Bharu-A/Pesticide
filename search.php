<?php
// ======================================================================
// get_pesticides.php
// Purpose: Fetch pesticide and nearby store details for a given crop.
// ======================================================================

// --- Response setup ---
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // Allow frontend access
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// --- Error handling setup ---
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// --- Include DB connection ---
include 'db_connect.php';

// --- Verify DB connection ---
if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// --- Simple file-based debug logger ---
function log_debug($msg) {
    $file = 'debug_log.txt';
    if (is_writable(dirname(__FILE__))) {
        file_put_contents($file, date('Y-m-d H:i:s') . " - $msg\n", FILE_APPEND);
    }
}

// --- Get and sanitize crop name ---
$crop = isset($_GET['crop']) ? trim($_GET['crop']) : '';
if (empty($crop)) {
    echo json_encode(["success" => false, "message" => "Crop name is required"]);
    exit;
}

// --- Normalize crop name ---
$aliases = [
    'paddy' => 'Rice',
    'veg' => 'Vegetables',
    'vegetable' => 'Vegetables',
    'fruit' => 'Fruits'
];

$key = ucfirst(strtolower($crop));
if (isset($aliases[strtolower($crop)])) {
    $key = $aliases[strtolower($crop)];
}

// --- Crop to pesticide mapping ---
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

if (!array_key_exists($key, $recommendations)) {
    echo json_encode(["success" => false, "message" => "No recommendations found for this crop"]);
    exit;
}

$pesticides = $recommendations[$key];

// --- Check if store_pesticides mapping table exists ---
$tableExists = false;
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'store_pesticides'");
if ($tableCheck && mysqli_num_rows($tableCheck) > 0) {
    $tableExists = true;
}
log_debug("Crop: $key | Table exists: " . ($tableExists ? 'YES' : 'NO'));

// --- Prepare SQL query ---
if ($tableExists) {
    // ✅ Full join mode
    $inList = implode(',', array_map(fn($p) => "'" . mysqli_real_escape_string($conn, strtolower($p)) . "'", $pesticides));
    $sql = "
        SELECT 
            s.id AS store_id,
            s.name AS store_name,
            s.address,
            s.latitude,
            s.longitude,
            p.id AS pesticide_id,
            p.name AS pesticide_name,
            p.description AS pesticide_description,
            p.price,
            p.category
        FROM stores s
        JOIN store_pesticides sp ON s.id = sp.store_id
        JOIN pesticides p ON sp.pesticide_id = p.id
        WHERE LOWER(p.name) IN ($inList)
        ORDER BY s.name, p.name
    ";
} else {
    // ⚙️ Fallback when no mapping table exists
    $sql = "
        SELECT 
            s.id AS store_id,
            s.name AS store_name,
            s.address,
            s.latitude,
            s.longitude
        FROM stores s
        ORDER BY s.name
    ";
}

log_debug("SQL Executed: $sql");

// --- Execute SQL query ---
$result = mysqli_query($conn, $sql);
if (!$result) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Query failed",
        "error" => mysqli_error($conn)
    ]);
    log_debug("SQL Error: " . mysqli_error($conn));
    exit;
}

// --- Process Results ---
$stores = [];

if ($tableExists) {
    // ✅ With mapping table
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['store_id'];
        $latitude = isset($row['latitude']) ? (float)$row['latitude'] : 0.0;
        $longitude = isset($row['longitude']) ? (float)$row['longitude'] : 0.0;

        if (!isset($stores[$id])) {
            $stores[$id] = [
                'id' => $id,
                'name' => $row['store_name'],
                'address' => $row['address'],
                'latitude' => $latitude,
                'longitude' => $longitude,
                'pesticides' => []
            ];
        }

        $stores[$id]['pesticides'][] = [
            'id' => $row['pesticide_id'],
            'name' => $row['pesticide_name'],
            'description' => $row['pesticide_description'],
            'price' => isset($row['price']) ? (float)$row['price'] : null,
            'category' => $row['category']
        ];
    }
} else {
    // ⚙️ Fallback: link all pesticides to all stores
    $inList = implode(',', array_map(fn($p) => "'" . mysqli_real_escape_string($conn, strtolower($p)) . "'", $pesticides));
    $pesticideQuery = "SELECT * FROM pesticides WHERE LOWER(name) IN ($inList)";
    log_debug("Pesticide fallback query: $pesticideQuery");

    $pResult = mysqli_query($conn, $pesticideQuery);
    $allPesticides = [];

    if ($pResult) {
        while ($pRow = mysqli_fetch_assoc($pResult)) {
            $allPesticides[] = [
                'id' => $pRow['id'],
                'name' => $pRow['name'],
                'description' => $pRow['description'],
                'price' => isset($pRow['price']) ? (float)$pRow['price'] : null,
                'category' => $pRow['category']
            ];
        }
        mysqli_free_result($pResult);
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $stores[] = [
            'id' => $row['store_id'],
            'name' => $row['store_name'],
            'address' => $row['address'],
            'latitude' => isset($row['latitude']) ? (float)$row['latitude'] : 0.0,
            'longitude' => isset($row['longitude']) ? (float)$row['longitude'] : 0.0,
            'pesticides' => $allPesticides
        ];
    }
}

// --- Build final response ---
$response = [
    "success" => true,
    "crop" => $key,
    "recommended_pesticides" => $pesticides,
    "stores" => array_values($stores)
];

// --- Output response safely ---
$json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    echo json_encode([
        "success" => false,
        "message" => "JSON encoding failed",
        "error" => json_last_error_msg()
    ]);
    log_debug("JSON Error: " . json_last_error_msg());
} else {
    echo $json;
}

// --- Cleanup ---
mysqli_free_result($result);
mysqli_close($conn);
?>
