 <?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "smart_fertilization";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$crop = isset($_GET['crop']) ? $_GET['crop'] : '';

if (!$crop) {
    echo json_encode(["error" => "No crop specified"]);
    exit;
}

$sql = "SELECT * FROM pesticides WHERE crop LIKE ?";
$stmt = $conn->prepare($sql);
$searchCrop = "%" . $crop . "%";
$stmt->bind_param("s", $searchCrop);
$stmt->execute();
$result = $stmt->get_result();

$pesticides = [];
while ($row = $result->fetch_assoc()) {
    $pesticides[] = $row;
}

echo json_encode($pesticides);

$conn->close();
?>
