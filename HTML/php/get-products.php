<?php
// Database configuration for server hosting
/*$host = "sql305.infinityfree.com";
$username = "if0_37900427";
$password = "Umaapp123";
$database = "if0_37900427_umaapp";
*/

$host = "localhost";
$username = "root";
$password = "";
$database = "umaapp";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the category_id from the GET request (if provided)
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

// SQL query to fetch products
if ($category_id) {
    // Filter products by category_id
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
}

// Fetch results into an array
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Return products as JSON
header('Content-Type: application/json');
echo json_encode($products);

// Close the connection
if (isset($stmt)) $stmt->close();
$conn->close();
?>

