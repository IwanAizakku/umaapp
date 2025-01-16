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
        // Set the timezone to UTC+8
        $timezone = new DateTimeZone('Asia/Kuala_Lumpur'); // UTC+8 timezone
        $transactionDate = new DateTime('now', $timezone);
        $formattedTransactionDate = $transactionDate->format('Y-m-d H:i:s');

        // Update payment status to 'completed' and set transaction_date to the incremented time
        $stmt = $db->prepare("
            UPDATE payments 
            SET payment_status = 'completed', transaction_date = :transaction_date
            WHERE payment_id = :payment_id
        ");

        $stmt->execute([
            ':payment_id' => $paymentId,
            ':transaction_date' => $formattedTransactionDate
        ]);

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

