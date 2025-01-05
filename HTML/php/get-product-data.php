<?php
header('Content-Type: application/json');

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

// Fetch product ID from request
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 1; // Default to product 1

// Fetch product details
$product_query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

// Fetch reviews
$reviews_query = "SELECT r.rating, r.review_text, r.review_image_url, r.created_at, u.username 
                  FROM reviews r 
                  JOIN users u ON r.user_id = u.user_id 
                  WHERE r.product_id = ? 
                  ORDER BY r.created_at DESC";
$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$reviews_result = $stmt->get_result();
$reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);

// Close connections
$stmt->close();
$conn->close();

// Return data as JSON
echo json_encode(['product' => $product, 'reviews' => $reviews]);
?>
