<?php
require '../../PHP/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Query to fetch cart items for the logged-in user
    $query = "
        SELECT 
            c.cart_id, 
            c.product_id, 
            p.name, 
            p.price, 
            p.image_url, 
            c.quantity 
        FROM cart c
        INNER JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        echo json_encode(['cartItems' => []]); // Return an empty cart if no items
    } else {
        echo json_encode(['cartItems' => $cartItems]); // Return the cart items
    }
    
} catch (PDOException $e) {
    error_log("Error fetching cart items: " . $e->getMessage()); // Log error for debugging
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch cart items.']);
}

