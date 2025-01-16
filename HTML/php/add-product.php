<?php
// Include the database connection file
include('../../PHP/db.php');

// Function to handle image upload
function handleImageUpload($file) {
    // Define the upload directory (absolute path)
    $target_dir = "../../Images/";  // Absolute path to store the image on the server
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    // Check if image file is a valid image or fake image
    if (isset($file)) {
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Generate a unique file name to avoid conflicts
    $new_filename = time() . '-' . basename($file["name"]);
    $target_file = $target_dir . $new_filename;

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Check if uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Now, save the relative path (or URL path) in the database
            // For example, you may want to store the URL path like this: /uploads/images/filename.jpg
            return '../Images/' . $new_filename;  // Return the relative path to store in the database
        } else {
            return false;
        }
    }
}

// Inserting into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $name = $_POST['item-title'];
    $price = $_POST['item-price'];
    $description = $_POST['item-description'];
    $category = $_POST['category'];
    $shop = $_POST['shop'];

    // Handle image upload
    $image_url = "";
    if (isset($_FILES['image-upload']) && $_FILES['image-upload']['error'] == 0) {
        $image_url = handleImageUpload($_FILES['image-upload']);
    }

    try {
        // Prepare SQL query for inserting data into the products table
        $sql = "INSERT INTO products (retailer_id, category_id, name, description, price, stock_quantity, image_url, created_at) 
                VALUES (:retailer_id, :category_id, :name, :description, :price, :stock_quantity, :image_url, CURRENT_TIMESTAMP)";
        
        $stmt = $db->prepare($sql);
        
        // Bind the form values to the SQL query
        $stmt->bindParam(':retailer_id', $retailer_id);
        $stmt->bindParam(':category_id', $category);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':image_url', $image_url);

        // Set values for the bound parameters
        $retailer_id = 1; // Assuming the retailer_id is 1 (you can modify as per your logic)
        $stock_quantity = 0; // Initial stock quantity (can be adjusted if needed)

        // Execute the statement
        $stmt->execute();

        // Return success response as JSON
        echo json_encode(["status" => "success", "message" => "New item added successfully!"]);
    } catch (PDOException $e) {
        // Return error response as JSON
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}
?>
