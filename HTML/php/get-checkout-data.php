<?php
session_start();
require '../../PHP/db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_SESSION['user_id'] ?? null; // Fetch user_id from session

    if ($user_id) {
        try {
            // Fetch cart items for the user
            $cartQuery = "
                SELECT 
                    c.product_id, 
                    p.name AS product_name,
                    p.price AS product_price,
                    p.image_url AS product_image, 
                    c.quantity
                FROM 
                    cart c 
                INNER JOIN 
                    products p 
                ON 
                    c.product_id = p.product_id 
                WHERE 
                    c.user_id = :user_id";
            $stmtCart = $db->prepare($cartQuery);
            $stmtCart->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtCart->execute();
            $cartItems = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

            // Fetch addresses for the user
            $addressQuery = "
                SELECT 
                    user_id, 
                    CONCAT(address, ', ', city, ', ', state) AS full_address 
                FROM 
                    users 
                WHERE 
                    user_id = :user_id";
            $stmtAddress = $db->prepare($addressQuery);
            $stmtAddress->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtAddress->execute();
            $addresses = $stmtAddress->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                "success" => true,
                "cartItems" => $cartItems,
                "addresses" => $addresses
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "error" => "Failed to fetch data: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "User ID is missing."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "error" => "Invalid request method."
    ]);
}
?>


