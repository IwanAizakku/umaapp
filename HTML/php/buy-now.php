<?php
require '../../PHP/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;

if ($user_id && $product_id) {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) 
                           SELECT ?, price, 'received' FROM products WHERE product_id = ?");
    $stmt->execute([$user_id, $product_id]);

    echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
} else {
    echo json_encode(['error' => 'User not logged in or invalid product ID.']);
}
?>

