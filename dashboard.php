<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark px-4 p-4">
        <span class="navbar-text text-white">
            Welcome, <em><span class="name fw-bold "> <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?> </span></em>!
        </span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <!-- Alert Message (Initially Hidden) -->
    <div class="container mt-5">
        <div id="loginAlert" class="alert alert-success alert-dismissible fade show " role="alert">
            You have successfully logged in!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            // Show Bootstrap alert
            $("#loginAlert").fadeIn(500).delay(1000).fadeOut(500);
        });
    </script>


</body>
</html>
