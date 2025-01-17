<?php
// Include the database configuration file
require '../../PHP/db.php';

// Decode the JSON payload if the Content-Type is application/json
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Get raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if product_id is provided
    if (isset($data['product_id'])) {
        $product_id = intval($data['product_id']); // Sanitize input as integer

        try {
            // Prepare the DELETE statement
            $stmt = $db->prepare("DELETE FROM products WHERE product_id = :product_id");

            // Bind the product_id parameter
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Respond with success message
                echo json_encode([
                    "success" => true,
                    "message" => "Product deleted successfully.",
                ]);
            } else {
                // Respond with failure message
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete the product.",
                ]);
            }
        } catch (PDOException $e) {
            // Respond with error message
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . $e->getMessage(),
            ]);
        }
    } else {
        // Respond with invalid product_id message
        echo json_encode([
            "success" => false,
            "message" => "Invalid product_id.",
        ]);
    }
} else {
    // Respond with invalid request message
    echo json_encode([
        "success" => false,
        "message" => "Invalid request.",
    ]);
}
?>
