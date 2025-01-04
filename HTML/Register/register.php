<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "umaapp";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $conn->real_escape_string($_POST['address']);
    $postcode = $conn->real_escape_string($_POST['postcode']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $country = $conn->real_escape_string($_POST['country']);

    // Default role
    $role = 'student'; // Adjust or retrieve from the form if needed

    // Validate password match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle file upload
    $profile_picture_url = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        if ($_FILES["profile_picture"]["size"] > 2000000) {
            die("File is too large.");
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            die("Error uploading file.");
        }

        $profile_picture_url = $target_file;
    }

    // Prepare the SQL query
    $stmt = $conn->prepare("
        INSERT INTO users (username, email, password_hash, role, profile_picture_url, address, postcode, city, state, country)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssssss",
        $username,
        $email,
        $hashed_password,
        $role,
        $profile_picture_url,
        $address,
        $postcode,
        $city,
        $state,
        $country
    );

    // Execute and check
    if ($stmt->execute()) {
        echo "User registered successfully!";
        // Redirect to login.html after 3 seconds
        header("refresh:3; url=../login/login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    

    $stmt->close();
    $conn->close();
}
?>

