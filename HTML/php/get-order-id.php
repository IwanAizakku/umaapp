<?php
// Include the database connection
require_once '../../PHP/db.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['user_id'] ?? null;

    // Validate if user_id is provided
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User ID is required.']);
        exit;
    }

    try {
        // Fetch the latest order ID for the user
        $stmt = $db->prepare("
            SELECT order_id 
            FROM orders 
            WHERE user_id = :user_id 
            ORDER BY updated_at DESC
            LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            echo json_encode(['success' => true, 'order_id' => $order['order_id']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No orders found for the user.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve order ID.', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
