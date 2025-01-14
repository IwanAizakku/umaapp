<?php
// Include the database connection
require_once '../../PHP/db.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $userId = $_POST['user_id'] ?? null;
    $totalPrice = $_POST['total_price'] ?? null;
    $paymentMethod = $_POST['payment_method'] ?? null;
    $deliveryAddress = $_POST['delivery_address'] ?? null;

    // Validate input
    if (!$userId || !$totalPrice || !$paymentMethod || !$deliveryAddress) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // Validate totalPrice is a valid number
    if (!is_numeric($totalPrice) || $totalPrice <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid total price.']);
        exit;
    }

    // Optional: Validate payment method if there's a list of accepted methods
    $validPaymentMethods = ['fpx', 'card', 'cod']; 
    if (!in_array($paymentMethod, $validPaymentMethods)) {
        echo json_encode(['success' => false, 'message' => 'Invalid payment method.']);
        exit;
    }

    try {
        // Insert order into the orders table
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, total_price, status, created_at, delivery_address)
            VALUES (:user_id, :total_price, 'received', NOW(), :delivery_address)
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':total_price' => $totalPrice,
            ':delivery_address' => $deliveryAddress
        ]);

        // Get the last inserted order ID
        $orderId = $db->lastInsertId();

        // Fetch the items in the user's cart
        $stmt = $db->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert order items into the orderitems table
        foreach ($cart as $item) {
            $stmt = $db->prepare("
                INSERT INTO orderitems (order_id, product_id, quantity, price)
                VALUES (:order_id, :product_id, :quantity, :price)
            ");
            $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price'],
            ]);
        }

        // Insert payment into the payments table
        $stmt = $db->prepare("
            INSERT INTO payments (order_id, payment_method, payment_status, transaction_date)
            VALUES (:order_id, :payment_method, 'pending', NOW())
        ");
        $stmt->execute([
            ':order_id' => $orderId,
            ':payment_method' => $paymentMethod
        ]);

        // Get the last inserted payment ID (payment_id)
        $paymentId = $db->lastInsertId();

        // Respond with success and include the payment_id
        echo json_encode(['success' => true, 'message' => 'Checkout processed successfully.', 'order_id' => $orderId, 'payment_id' => $paymentId]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to process checkout.', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>





