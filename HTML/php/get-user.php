<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Respond with an error if not authenticated
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Respond with the user's information
echo json_encode([
    'username' => $_SESSION['username']
]);
?>
