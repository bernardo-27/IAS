<?php
require_once 'db.php';

// Retrieve form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate unique email and full name
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR full_name = :full_name");
$stmt->execute(['email' => $email, 'full_name' => $full_name]);
$existing_user = $stmt->fetch();

if ($existing_user) {
    if ($existing_user['email'] === $email) {
        header("Location: index.html?alert=email_in_use");
        exit;
    } elseif ($existing_user['full_name'] === $full_name) {
        header("Location: index.html?alert=full_name_in_use");
        exit;
    }
}

// Validate password match
if ($password !== $confirm_password) {
    header("Location: index.html?alert=password_mismatch");
    exit;
}

// Validate password complexity (at least 8 characters, containing letters and numbers)
if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
    header("Location: index.html?alert=password_complexity_error");
    exit;
}

// Hash the password and insert into the database
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (:full_name, :email, :phone, :password)");
$stmt->execute([
    'full_name' => $full_name,
    'email' => $email,
    'phone' => $phone,
    'password' => $hashed_password
]);

header("Location: index.html?alert=signup_success");
exit;
?>