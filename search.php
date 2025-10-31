<?php
header("Content-Type: application/json");
include 'db_connect.php';

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

// Check if crop exists in recommendations
if (!array_key_exists(ucfirst(strtolower($crop)), $recommendations)) {
    echo json_encode(["success" => false, "message" => "No recommendations found for this crop"]);
    exit;
}

$crop = ucfirst(strtolower($crop));
$pesticides = $recommendations[$crop];
$pesticideList = "'" . implode("','", $pesticides) . "'";

// Query to get stores with recommended pesticides including descriptions
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

$result = $conn->query($sql);
$stores = [];

// Group stores and their pesticides
while ($row = $result->fetch_assoc()) {
    $storeId = $row['store_id'];
    
    if (!isset($stores[$storeId])) {
        $stores[$storeId] = [
            'id' => $row['store_id'],
            'name' => $row['store_name'],
            'address' => $row['address'],
            'lat' => floatval($row['lat']),
            'lng' => floatval($row['lng']),
            'pesticides' => []
        ];
    }
    
    $stores[$storeId]['pesticides'][] = [
        'id' => $row['pesticide_id'],
        'name' => $row['pesticide_name'],
        'description' => $row['pesticide_description'],
        'price' => floatval($row['price']),
        'category' => $row['category']
    ];
}

// Convert to simple array
$storeList = array_values($stores);

// Return JSON response
echo json_encode([
    "success" => true,
    "crop" => $crop,
    "recommended_pesticides" => $pesticides,
    "stores" => $storeList
]);

$conn->close();
?>