<?php
session_start();
include '../../PHP/db.php'; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted username and password
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare a statement to prevent SQL injection
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password using password_verify()
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on the role (Need to create page for admin/use existing if available)
            if ($user['role'] == 'admin') {
                header('../Admin/dashboard.html'); // Replace with admin page
            } else if ($user['role'] == 'retailer') {
                header('Location: ../index-retail.html'); // Replace with member home page
            } else {
                header('Location: ../index-member.html'); // Replace with member home page
            }
            exit();
        } else {
            // Incorrect password
            $error = "Invalid username or password.";
        }
    } else {
        // Username not found
        $error = "Invalid username or password.";
    }
    // Assuming login is successful
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['user_id'];
}
?>


<!DOCTYPE html>
<html>
<head>
<style>
            /* Remove default margin and padding */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            /* Remove the body margin and padding */
            body {
                background: linear-gradient(180deg, #d8aad2, #f1c1d1); /* Muted Lilac to Soft Pink */
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                border: none; /* Remove any border on body */
                box-shadow: none; /* Remove any shadow on body */
                outline: none; /* Remove any outline on body */
            }

            /* Header with Gradient */
            .logo-only-header {
                background: linear-gradient(90deg, #d8aad2, #f1c1d1); /* Same gradient for header */
                padding: 10px 20px;
                text-align: center;
                color: white;
                font-size: 24px;
                font-weight: bold;
                border: none; /* Ensure no borders */
                box-shadow: none; /* Remove any shadow */
                outline: none; /* Remove any outline */
                background-color: transparent; /* Remove any background that could cause shapes */
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                border-bottom: none; /* Ensure no bottom border */
            }

            /* Ensures no unwanted effect or shape on the logo link */
            .logo-only-header a {
                text-decoration: none;
                color: white;
                font-weight: bold;
                border: none; /* Ensure no borders around the link */
                outline: none; /* Ensure no outline around the link */
                background-color: transparent; /* No background for the link */
                padding: 0; /* Remove any extra padding */
                box-shadow: none; /* Remove any shadow or box shadow */
            }

            /* Remove any unwanted shadow or effect */
            .logo-only-header,
            .logo-only-header a {
                box-shadow: none !important; /* Force removal of any shadow or border */
                outline: none !important; /* Force removal of any outline */
                border: none !important; /* Remove any border */
            }

            /* Main Content Styling */
            main {
                text-align: center;
                padding: 50px 20px;
                margin: 0;
            }

            h1 {
                font-size: 36px;
                color: #333;
                margin-top: 10px;
                margin-bottom: 20px;
                text-align: center;
            }

            h2 {
                font-size: 24px;
                color: #555;
                margin-bottom: 20px;
            }

            .form-login-main {
                display: flex;
                flex-direction: row;
                justify-content: center;
                gap: 30px;
                max-width: 900px;
                margin: 0 auto;
                padding: 20px;
            }

            .form-login-section, .form-register-section {
                width: 100%;
                max-width: 400px;
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            label {
                display: block;
                font-size: 16px;
                margin-bottom: 5px;
            }

            input, select, button {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            button {
                background-color: #f1c1d1;
                color: white;
                border: none;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #e1a0b2; /* Slightly darker pink for hover effect */
            }

            .header-buttons, .confirm-buttons {
                display: flex;
                justify-content: center;
            }

            /* Ensure no unwanted border or space below the page */
            html, body {
                height: 100%;
                overflow-x: hidden; /* Avoid horizontal scroll */
                text-align: center;
            }

            /* Ensure the footer or body doesn't have any padding */
            main {
                min-height: 100%;
            }

        </style>
    <meta charset="UTF-8">
    <h1>Login Error</h1>
</head>
<body>
    <p><?php echo htmlspecialchars($error); ?></p>
    <a href="login.html">Go back to login</a>
</body>
</html>
