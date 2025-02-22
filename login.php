<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sk_management");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        

        echo "<script>alert('Login successful! Welcome, " . $user['full_name'] . "'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Incorrect password!'); window.location.href='index.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('No account found with this email!'); window.location.href='index.html';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>
