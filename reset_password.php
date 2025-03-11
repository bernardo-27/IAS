<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists and is valid
    $query = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires > NOW()");
    $query->execute([$token]);
    $reset = $query->fetch();

    if (!$reset) {
        die("Invalid or expired token.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Update user password
        $query = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $query->execute([$new_password, $reset['email']]);

        // Delete token after reset
        $query = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $query->execute([$reset['email']]);

        echo "Password successfully reset. You can now <a href='login.php'>login</a>.";
        exit();
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <button type="submit">Change Password</button>
    </form>
</body>
</html>
