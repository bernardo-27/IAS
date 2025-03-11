<?php
require 'db.php'; // Include database connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validate password: Minimum 8 characters, letters, and numbers only
if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9]*$/', $password)) {
    header("Location: index.html?alert=password_error");
    exit;
}

$password = password_hash($password, PASSWORD_BCRYPT);

// Check if email already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: index.html?alert=email_in_use");
    exit;
}

$sql = "INSERT INTO users (full_name, phone, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $full_name, $phone, $email, $password);

if ($stmt->execute()) {
    header("Location: index.html?alert=signup_success");
    exit;
} else {
    if ($conn->errno === 1062) { // Duplicate entry error code
        header("Location: index.html?alert=email_in_use");
    } else {
        header("Location: index.html?alert=signup_error");
    }
    exit;
}

$stmt->close();
$conn->close();
?>
