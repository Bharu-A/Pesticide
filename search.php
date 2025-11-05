<?php
// search.php — fetch fertilizers/pesticides and nearby stores
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';
if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Get crop name
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

// 1️⃣ Fetch fertilizers for this crop, or random if none found
$pesticides = [];
$stmt = $conn->prepare("SELECT * FROM pesticides WHERE LOWER(crop)=LOWER(?)");
$stmt->bind_param("s", $crop);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $pesticides[] = $row;
}
$stmt->close();

// fallback if no direct matches
if (empty($pesticides)) {
    $randQuery = $conn->query("SELECT * FROM pesticides ORDER BY RAND() LIMIT 20");
    while ($row = $randQuery->fetch_assoc()) {
        $pesticides[] = $row;
    }
}

// 2️⃣ Get all stores and assign random fertilizers
$storeRes = $conn->query("SELECT * FROM stores");
$stores = [];

while ($store = $storeRes->fetch_assoc()) {
    $latitude = isset($store['latitude']) && is_numeric($store['latitude']) ? (float)$store['latitude'] : 0.0;
    $longitude = isset($store['longitude']) && is_numeric($store['longitude']) ? (float)$store['longitude'] : 0.0;

    // randomly select 6–10 fertilizers for each store
    $randKeys = array_rand($pesticides, rand(6, min(10, count($pesticides))));
    if (!is_array($randKeys)) $randKeys = [$randKeys];

    $branded = [];
    $nonBranded = [];

    foreach ($randKeys as $k) {
        $fert = $pesticides[$k];
        if (strtolower($fert['category']) === 'branded') {
            $branded[] = $fert;
        } else {
            $nonBranded[] = $fert;
        }
    }

    $stores[] = [
        'id' => (int)$store['id'],
        'name' => $store['name'],
        'address' => $store['address'],
        'latitude' => $latitude,
        'longitude' => $longitude,
        'lat' => $latitude,
        'lng' => $longitude,
        'branded' => $branded,
        'non_branded' => $nonBranded,
        'map_link' => $store['map_link'],
        'phone_number' => $store['phone_number'],

    ];
}

// 3️⃣ Final JSON response
$response = [
    "success" => true,
    "crop" => ucfirst($key),
    "stores" => $stores
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
?>
