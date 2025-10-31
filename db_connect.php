<?php
$servername = "sql12.freesqldatabase.com"; // your MySQL host
$username = "sql12805516";                  // your MySQL username
$password = "lQKYkc1wG6";                 // your MySQL password
$dbname = "sql12805516";                    // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8");
?>
