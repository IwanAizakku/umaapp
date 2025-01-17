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

// Get price from the productID
if ($product_id) {
    $stmt = $db->prepare("SELECT price FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $productData = $stmt->fetch();

    if ($productData) {
        $price = $productData['price'];
    } else {
        echo json_encode(['error' => 'Product not found.']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid product ID.']);
    exit;
}

if ($product_id && $quantity > 0) {
    // Check if the product exists and has enough stock
    $stmt = $db->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product && $product['stock_quantity'] >= $quantity) {
        // Check if the product already exists in the cart
        $stmt = $db->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $cartItem = $stmt->fetch();

        if ($cartItem) {
            // Update the quantity if the product is already in the cart
            $newQuantity = $cartItem['quantity'] + $quantity;

            // Ensure the updated quantity does not exceed stock
            if ($newQuantity <= $product['stock_quantity']) {
                $stmt = $db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$newQuantity, $user_id, $product_id]);

                // Increment added_to_cart for the product
                $stmt = $db->prepare("UPDATE products SET added_to_cart = added_to_cart + ? WHERE product_id = ?");
                $stmt->execute([$quantity, $product_id]);

                echo json_encode(['success' => true, 'message' => 'Cart updated successfully.']);
            } else {
                echo json_encode(['error' => 'Not enough stock available for the updated quantity.']);
            }
        } else {
            // Add as a new item if not already in the cart
            $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity, $price]);

            // Increment added_to_cart for the product
            $stmt = $db->prepare("UPDATE products SET added_to_cart = added_to_cart + ? WHERE product_id = ?");
            $stmt->execute([$quantity, $product_id]);

            echo json_encode(['success' => true, 'message' => 'Product added to cart.']);
        }
    } else {
        echo json_encode(['error' => 'Not enough stock available or invalid product.']);
    }
} else {
    echo json_encode(['error' => 'Invalid product ID or quantity.']);
}
?>
