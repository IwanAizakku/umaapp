<?php
// Include the database connection
require_once '../../PHP/db.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $paymentId = $_POST['payment_id'] ?? null;

    // Validate input
    if (!$paymentId) {
        echo json_encode(['success' => false, 'message' => 'Missing payment ID.']);
        exit;
    }

    try {
        // Update payment status to 'completed'
        $stmt = $db->prepare("
            UPDATE payments 
            SET payment_status = 'completed' 
            WHERE payment_id = :payment_id
        ");
        $stmt->execute([':payment_id' => $paymentId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Payment completed successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No matching payment record found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to update payment status.', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>

