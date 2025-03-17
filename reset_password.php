<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_GET['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($new_password !== $confirm_password) {
        header("Location: reset_password.php?token=$token&alert=password_mismatch");
        exit;
    }

    // Validate password complexity (at least 8 characters, letters and numbers)
    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $new_password)) {
        header("Location: reset_password.php?token=$token&alert=password_complexity_error");
        exit;
    }

    // Check if the token is valid and not expired
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expiry > :current_time");
    $stmt->execute(['token' => $token, 'current_time' => time()]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: reset_password.php?token=$token&alert=invalid_token");
        exit;
    }

    // Update the password and clear the reset token
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expiry = NULL WHERE id = :id");
    $stmt->execute(['password' => $hashed_password, 'id' => $user['id']]);

    header("Location: index.html?alert=password_update_success");
    exit;
}

// Get the token from the URL
$token = $_GET['token'] ?? null;

// Check if the token exists and is valid
if ($token) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expiry > :current_time");
    $stmt->execute(['token' => $token, 'current_time' => time()]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Invalid or expired token.");
    }
} else {
    die("No token provided.");
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if (isset($_GET['alert'])): ?>
            <p><?php echo htmlspecialchars($_GET['alert']); ?></p>
        <?php endif; ?>
        <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>