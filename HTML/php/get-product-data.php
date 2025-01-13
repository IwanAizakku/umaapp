<?php
header('Content-Type: application/json');
require '../../PHP/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

// Fetch product ID from request
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 1; // Default to product 1

try {
    // Fetch product details
    $product_query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $db->prepare($product_query);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch reviews
    $reviews_query = "SELECT r.rating, r.review_text, r.review_image_url, r.created_at, u.username 
                      FROM reviews r 
                      JOIN users u ON r.user_id = u.user_id 
                      WHERE r.product_id = ? 
                      ORDER BY r.created_at DESC";
    $stmt = $db->prepare($reviews_query);
    $stmt->execute([$product_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode(['product' => $product, 'reviews' => $reviews]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>

