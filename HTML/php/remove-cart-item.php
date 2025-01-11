<?php
require '../../PHP/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = $_GET['cart_id'] ?? null;

if (!$cart_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

try {
    $query = "
        DELETE FROM cart 
        WHERE cart_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$cart_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => 'Cart item removed.']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Cart item not found.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to remove cart item.']);
}
