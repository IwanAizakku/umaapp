<?php
require '../../PHP/db.php'; 
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Sanitize user inputs
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $address = htmlspecialchars($_POST['address']);
        $postcode = htmlspecialchars($_POST['postcode']);
        $city = htmlspecialchars($_POST['city']);
        $state = htmlspecialchars($_POST['state']);
        $country = htmlspecialchars($_POST['country']);
        $role = htmlspecialchars($_POST['User-type']); // Get role dynamically from form

        // Validate password match
        if ($password !== $confirm_password) {
            die("Passwords do not match!");
        }

        // Hash the password before saving into database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Handle file upload
        $profile_picture_url = null;
        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "../../Images/profile-picture/";
            $upload_name = uniqid() . "_" . basename($_FILES["profile_picture"]["name"]); // Generate unique file name
            $target_file = $target_dir . $upload_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate image
            $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
            if ($check === false) {
                die("File is not an image.");
            }

            if ($_FILES["profile_picture"]["size"] > 2000000) {
                die("File is too large. Maximum size is 2MB.");
            }

            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                die("Only JPG, JPEG, PNG, and GIF files are allowed.");
            }

            // Ensure target directory exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                die("Error uploading file.");
            }

            // Save relative path for database
            $profile_picture_url = "/Images/profile-picture/" . $upload_name;
        }

        // Set the timezone to UTC+8
        $timezone = new DateTimeZone('Asia/Kuala_Lumpur'); // UTC+8 timezone
        $createdAt = new DateTime('now', $timezone);
        $formattedCreatedAt = $createdAt->format('Y-m-d H:i:s'); // Format the current time for MySQL

        // Prepare the SQL query
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password_hash, role, profile_picture_url, address, postcode, city, state, country, created_at)
            VALUES (:username, :email, :password_hash, :role, :profile_picture_url, :address, :postcode, :city, :state, :country, :created_at)
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':profile_picture_url', $profile_picture_url);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':postcode', $postcode);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':created_at', $formattedCreatedAt); // Bind the formatted date

        // Execute and check
        if ($stmt->execute()) {
            echo "User registered successfully!";
            // Redirect to login.html after 3 seconds
            header("refresh:3; url=../login/login.html");
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    } catch (PDOException $e) {
        // Handle any database-related errors
        die("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        // Handle general errors
        die("Error: " . $e->getMessage());
    }
}
?>



