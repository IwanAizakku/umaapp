<?php
require '../../PHP/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_item_id = $_GET['cart_item_id'] ?? null;
$new_quantity = $_GET['quantity'] ?? null;

if (!$cart_item_id || !$new_quantity || $new_quantity < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

try {
    $query = "
        UPDATE cart 
        SET quantity = ? 
        WHERE cart_item_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$new_quantity, $cart_item_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => 'Cart item updated.']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Cart item not found.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update cart item.']);
}
