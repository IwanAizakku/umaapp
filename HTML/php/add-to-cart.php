<?php
require '../../PHP/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if ($product_id && $quantity > 0) {
    // Check if the product exists
    $stmt = $db->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product && $product['stock_quantity'] >= $quantity) {
        // Add to cart
        $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);

        echo json_encode(['success' => true, 'message' => 'Product added to cart.']);
    } else {
        echo json_encode(['error' => 'Not enough stock available.']);
    }
} else {
    echo json_encode(['error' => 'Invalid product ID or quantity.']);
}
?>
