<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: forgot_password.php?alert=email_not_found");
        exit;
    }

    // Generate a unique reset token
    $reset_token = bin2hex(random_bytes(16)); // Random 32-character string
    $expiry_time = time() + 3600; // Token expires in 1 hour

    // Save the token and expiry time in the database
    $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expiry = :expiry WHERE email = :email");
    $stmt->execute([
        'token' => $reset_token,
        'expiry' => $expiry_time,
        'email' => $email
    ]);

    // Simulate the reset link (output it directly)
    $reset_link = "http://localhost/reset_password.php?token=abc123xyz456...";
    echo "Reset Link: $reset_link";

    // Optionally, log the reset link for debugging
    error_log("Reset Link: $reset_link");

    // Redirect with success message
    header("Location: forgot_password.php?alert=password_reset_sent");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php if (isset($_GET['alert'])): ?>
            <p><?php echo htmlspecialchars($_GET['alert']); ?></p>
        <?php endif; ?>
        <form action="forgot_password.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>