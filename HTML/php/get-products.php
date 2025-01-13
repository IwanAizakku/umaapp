<?php
header('Content-Type: application/json');
require '../../PHP/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

// Get the category_id from the GET request (if provided)
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

try {
    if ($category_id) {
        // SQL query to filter products by category_id
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->execute([$category_id]);
    } else {
        // SQL query to fetch all products
        $stmt = $db->prepare("SELECT * FROM products");
        $stmt->execute();
    }

    // Fetch all products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return products as JSON
    echo json_encode($products);

} catch (PDOException $e) {
    // Handle any database-related errors
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>


