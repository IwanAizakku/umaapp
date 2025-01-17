<?php
header('Content-Type: application/json');
require '../../PHP/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

// Retrieve retailer_id from the session if available
$retailer_id = isset($_SESSION['retailer_id']) ? intval($_SESSION['retailer_id']) : null;

// Get the category_id from the GET request (if provided)
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

try {
    if ($retailer_id && $category_id) {
        // Filter by both retailer_id and category_id
        $stmt = $db->prepare("SELECT * FROM products WHERE retailer_id = ? AND category_id = ?");
        $stmt->execute([$retailer_id, $category_id]);
    } elseif ($retailer_id) {
        // Filter by retailer_id only
        $stmt = $db->prepare("SELECT * FROM products WHERE retailer_id = ?");
        $stmt->execute([$retailer_id]);
    } elseif ($category_id) {
        // Filter by category_id only
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->execute([$category_id]);
    } else {
        // Fetch all products
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



