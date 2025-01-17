<?php
// (For debug purposes only)
// Generate a new hashed password
$newPassword = "retailer";
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update this hashed password in your database
echo "New hashed password: " . $newHashedPassword;
?>