<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password.php?error=invalid_email");
        exit();
    }
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: forgot_password.php?error=not_found");
        exit();
    }
    
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    
    // Generate a unique token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store the token in the database
    $stmt = $conn->prepare("INSERT INTO password_reset (user_id, token, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $token, $expires);
    
    if ($stmt->execute()) {
        // Generate the reset link
        $reset_link = "http://localhost/IAS1/reset_password.php?token=$token";
        
        // In a real application, you would send an email with the reset link
        // For localhost testing, we'll just redirect with a success message
        // Optionally, you can also echo the reset link for testing purposes
        
        // Uncomment this line for testing
        echo "Reset link: <a href='$reset_link'>$reset_link</a>"; exit;
        
        header("Location: forgot_password.php?success=1");
        exit();
    } else {
        header("Location: forgot_password.php?error=db_error");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
header("Location: forgot_password.php");
exit();
?>