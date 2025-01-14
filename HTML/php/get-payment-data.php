<?php
// Include the database connection configuration
require_once '../../PHP/db.php'; // Ensure the path to db.php is correct

try {
    // Validate and retrieve the order ID from the query string
    if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
        throw new Exception("Order ID is required.");
    }

    $orderId = intval($_GET['order_id']); // Sanitize the order ID

    // Fetch the order details
    $orderQuery = $db->prepare("SELECT * FROM orders WHERE order_id = :order_id");
    $orderQuery->execute(['order_id' => $orderId]);
    $order = $orderQuery->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception("Order not found.");
    }

    // Fetch the order items with product details
    $itemsQuery = $db->prepare("
        SELECT oi.*, p.name AS product_name, p.image_url AS product_image 
        FROM orderitems oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = :order_id
    ");

    $itemsQuery->execute(['order_id' => $orderId]);
    $items = $itemsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the payment details
    $paymentQuery = $db->prepare("SELECT * FROM payments WHERE order_id = :order_id");
    $paymentQuery->execute(['order_id' => $orderId]);
    $payment = $paymentQuery->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        throw new Exception("Payment details not found for the order.");
    }

    // Combine the data into a single response
    $response = [
        'order' => $order,
        'items' => $items,
        'payment' => $payment
    ];

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Return an error response in JSON format
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
