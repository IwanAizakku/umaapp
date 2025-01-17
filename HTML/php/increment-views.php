<?php
// Include the database configuration
require_once '../../PHP/db.php';

// Check if product_id is provided
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // Sanitize input

    try {
        // Prepare SQL to update the views column
        $sql = "UPDATE products SET views = views + 1 WHERE product_id = :product_id";
        $stmt = $db->prepare($sql);

        // Bind the product_id parameter
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to update views"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "No product ID provided"]);
}
?>
