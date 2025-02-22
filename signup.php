<?php
$conn = new mysqli("localhost", "root", "", "sk_management");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

$sql = "INSERT INTO users (full_name, phone, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $full_name, $phone, $email, $password);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! You can now log in.'); window.location.href='index.html';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.location.href='index.html';</script>";
}

$stmt->close();
$conn->close();
?>
