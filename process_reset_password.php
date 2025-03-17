<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate the token
    $stmt = $conn->prepare("SELECT pr.user_id, pr.expires_at 
                           FROM password_reset pr 
                           WHERE pr.token = ? AND pr.used = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: forgot_password.php?error=invalid_token");
        exit();
    }
    
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $expires_at = strtotime($row['expires_at']);
    
    if (time() > $expires_at) {
        header("Location: forgot_password.php?error=expired_token");
        exit();
    }
    
    // Validate passwords
    if ($password !== $confirm_password) {
        header("Location: reset_password.php?token=$token&error=password_mismatch");
        exit();
    }
    
// Validate password requirements with specific error messages
$password_errors = [];

if (strlen($password) < 8) {
    $password_errors[] = "Password must be at least 8 characters long";
}

if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    $password_errors[] = "Password can only contain letters and numbers";
}

if (!empty($password_errors)) {
    // Combine error messages and encode for URL
    $error_message = implode(", ", $password_errors);
    $encoded_error = urlencode($error_message);
    header("Location: reset_password.php?token=$token&error=password_requirements&message=$encoded_error");
    exit();
}
    
    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Update the user's password
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $hashed_password, $user_id);
    $password_updated = $update_stmt->execute();
    $update_stmt->close();
    
    if ($password_updated) {
        // Mark the token as used
        $mark_used_stmt = $conn->prepare("UPDATE password_reset SET used = 1 WHERE token = ?");
        $mark_used_stmt->bind_param("s", $token);
        $mark_used_stmt->execute();
        $mark_used_stmt->close();
        
        // Redirect to login page with success message
        header("Location: index.html?alert=password_update_success");
        exit();
    } else {
        // Database error
        header("Location: reset_password.php?token=$token&error=db_error");
        exit();
    }
} else {
    // Invalid request method
    header("Location: index.html");
    exit();
}

$conn->close();
?>