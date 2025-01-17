<?php
// Include the database connection file
include('../../PHP/db.php');

// Function to handle image upload
function handleImageUpload($file) {
    $target_dir = "../../Images/";
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = time() . '-' . basename($file["name"]);
    $target_file = $target_dir . $new_filename;

    // Check if the uploaded file is an image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return false; // File is not an image
    }

    // Check file size (limit: 5MB)
    if ($file["size"] > 5000000) {
        return false; // File too large
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        return false; // Invalid file format
    }

    // Try to move the uploaded file to the target directory
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "Images/" . $new_filename; // Return the relative path
    }

    return false; // File upload failed
}

// Updating the database
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST['product-id'];
    $name = $_POST['item-title'];
    $price = $_POST['item-price'];
    $description = $_POST['item-description'];
    $category = $_POST['category'];
    $shop = $_POST['shop'];

    // Image upload handling
    $image_url = "";
    if (isset($_FILES['image-upload']) && $_FILES['image-upload']['error'] === 0) {
        $image_url = handleImageUpload($_FILES['image-upload']);
    }

    try {
        // Prepare SQL statement
        $sql = "UPDATE products SET 
                name = :name, 
                price = :price, 
                description = :description, 
                category_id = :category_id, 
                retailer_id = :retailer_id, 
                updated_at = CURRENT_TIMESTAMP";

        // Add image_url if a new image was uploaded
        if ($image_url) {
            $sql .= ", image_url = :image_url";
        }

        $sql .= " WHERE product_id = :id";

        // Prepare and bind parameters
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category);
        $stmt->bindParam(':retailer_id', $shop);
        $stmt->bindParam(':id', $product_id);

        if ($image_url) {
            $stmt->bindParam(':image_url', $image_url);
        }

        // Execute the query
        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
