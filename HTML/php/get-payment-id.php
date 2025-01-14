<?php
// Include the database connection
require_once '../../PHP/db.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User ID is required.']);
        exit;
    }

    try {
        // Fetch the latest payment ID for the user
        $stmt = $db->prepare("
            SELECT p.payment_id 
            FROM payments p
            INNER JOIN orders o ON p.order_id = o.order_id
            WHERE o.user_id = :user_id
            ORDER BY p.transaction_date DESC
            LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);

        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            echo json_encode(['success' => true, 'payment_id' => $payment['payment_id']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No payment found for the user.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve payment ID.', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
