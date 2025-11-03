<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$crop = isset($_GET['crop']) ? trim($_GET['crop']) : '';
if (empty($crop)) {
    echo json_encode(["success" => false, "message" => "Crop name is required"]);
    exit;
}

// Fetch pesticides for the crop
$sqlPesticides = "
    SELECT id, name, description, price, category
    FROM pesticides
    WHERE crop = '" . mysqli_real_escape_string($conn, $crop) . "'
";

$pesticideResult = mysqli_query($conn, $sqlPesticides);
if (!$pesticideResult) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to load pesticides", "error" => mysqli_error($conn)]);
    exit;
}

$pesticides = [];
while ($row = mysqli_fetch_assoc($pesticideResult)) {
    $pesticides[] = $row;
}

// Fetch all stores
$sqlStores = "SELECT id, name, address, phone_number, map_link FROM stores";
$storeResult = mysqli_query($conn, $sqlStores);

if (!$storeResult) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to load stores", "error" => mysqli_error($conn)]);
    exit;
}

$stores = [];
while ($row = mysqli_fetch_assoc($storeResult)) {
    $stores[] = $row;
}

// Respond
echo json_encode([
    "success" => true,
    "crop" => $crop,
    "pesticides" => $pesticides,
    "stores" => $stores
]);

mysqli_close($conn);
?>
