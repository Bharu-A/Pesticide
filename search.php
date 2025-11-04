<?php
// search.php  — fetch pesticide recommendations and nearby stores
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// include DB connection file (make sure db_connect.php defines $conn)
include 'db_connect.php';
if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

function log_debug($msg) {
    $file = __DIR__ . '/debug_log.txt';
    if (is_writable(__DIR__)) {
        file_put_contents($file, date('Y-m-d H:i:s') . " - $msg\n", FILE_APPEND);
    }
}

// Get crop param
$crop = isset($_GET['crop']) ? trim($_GET['crop']) : '';
if ($crop === '') {
    echo json_encode(["success" => false, "message" => "Crop name is required"]);
    exit;
}

// Normalization / aliases
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

// ✅ New dynamic pesticide selection
// Fetch all pesticides from DB for this crop, or randomize if crop not found
$pesticides = [];
$stmt = $conn->prepare("SELECT * FROM pesticides WHERE LOWER(crop)=LOWER(?)");
$stmt->bind_param("s", $crop);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $pesticides[] = $row;
}
$stmt->close();

// If no pesticides found specifically for that crop, fetch random 10 from master list
if (empty($pesticides)) {
    $randQuery = $conn->query("SELECT * FROM pesticides ORDER BY RAND() LIMIT 10");
    while ($row = $randQuery->fetch_assoc()) {
        $pesticides[] = $row;
    }
}

// Now get all stores and assign random fertilizers for this crop
$storeRes = $conn->query("SELECT * FROM stores");
$stores = [];
while ($store = $storeRes->fetch_assoc()) {
    $latitude = isset($store['latitude']) && is_numeric($store['latitude']) ? (float)$store['latitude'] : 0.0;
    $longitude = isset($store['longitude']) && is_numeric($store['longitude']) ? (float)$store['longitude'] : 0.0;

    // randomly assign 4–6 fertilizers for each store
    $randKeys = array_rand($pesticides, rand(4, min(6, count($pesticides))));
    if (!is_array($randKeys)) $randKeys = [$randKeys];

    $randFerts = [];
    foreach ($randKeys as $k) {
        $randFerts[] = $pesticides[$k];
    }

    $stores[] = [
        'id' => (int)$store['id'],
        'name' => $store['name'],
        'address' => $store['address'],
        'latitude' => $latitude,
        'longitude' => $longitude,
        'lat' => $latitude,
        'lng' => $longitude,
        'pesticides' => $randFerts
    ];
}

// final response
$response = [
    "success" => true,
    "crop" => ucfirst($crop),
    "stores" => $stores
];
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;


// Check if mapping table exists
$tableExists = false;
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'store_pesticides'");
if ($tableCheck && mysqli_num_rows($tableCheck) > 0) {
    $tableExists = true;
}
log_debug("Crop: $key | store_pesticides table exists: " . ($tableExists ? 'YES' : 'NO'));

// Build SQL
if ($tableExists) {
    $inList = implode(',', array_map(function($p) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, strtolower($p)) . "'";
    }, $pesticides));
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
    // fallback: return all stores and attach recommended pesticides in code
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
$result = mysqli_query($conn, $sql);
if (!$result) {
    log_debug("SQL Error: " . mysqli_error($conn));
    echo json_encode(["success" => false, "message" => "Query failed", "error" => mysqli_error($conn)]);
    exit;
}

$stores = [];
if ($tableExists) {
    // build stores grouped by store_id
    while ($row = mysqli_fetch_assoc($result)) {
        $id = (int)$row['store_id'];
        $latitude = isset($row['latitude']) && is_numeric($row['latitude']) ? (float)$row['latitude'] : 0.0;
        $longitude = isset($row['longitude']) && is_numeric($row['longitude']) ? (float)$row['longitude'] : 0.0;

        if (!isset($stores[$id])) {
            $stores[$id] = [
                'id' => $id,
                'name' => $row['store_name'],
                'address' => $row['address'],
                'latitude' => $latitude,
                'longitude' => $longitude,
                // duplicate keys for frontend compatibility
                'lat' => $latitude,
                'lng' => $longitude,
                'pesticides' => []
            ];
        }

        $stores[$id]['pesticides'][] = [
            'id' => $row['pesticide_id'],
            'name' => $row['pesticide_name'],
            'description' => $row['pesticide_description'],
            'price' => isset($row['price']) ? $row['price'] : null,
            'category' => $row['category']
        ];
    }
} else {
    // fallback: attach same recommended pesticides to each store
    $pResult = [];
    // Build a simple pesticide array from $pesticides (no DB lookup)
    $allPesticides = array_map(function($p, $i){
        return [
            'id' => $i+1,
            'name' => $p,
            'description' => '',
            'price' => null,
            'category' => null
        ];
    }, $pesticides, array_keys($pesticides));

    while ($row = mysqli_fetch_assoc($result)) {
        $latitude = isset($row['latitude']) && is_numeric($row['latitude']) ? (float)$row['latitude'] : 0.0;
        $longitude = isset($row['longitude']) && is_numeric($row['longitude']) ? (float)$row['longitude'] : 0.0;

        $stores[] = [
            'id' => (int)$row['store_id'],
            'name' => $row['store_name'],
            'address' => $row['address'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'lat' => $latitude,
            'lng' => $longitude,
            'pesticides' => $allPesticides
        ];
    }
}

// Final response
$response = [
    "success" => true,
    "crop" => $key,
    "recommended_pesticides" => $pesticides,
    "stores" => array_values($stores)
];

$json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    echo json_encode(["success" => false, "message" => "JSON encoding failed", "error" => json_last_error_msg()]);
    log_debug("JSON Error: " . json_last_error_msg());
} else {
    echo $json;
}

if (isset($result) && $result instanceof mysqli_result) {
    mysqli_free_result($result);
}
mysqli_close($conn);
