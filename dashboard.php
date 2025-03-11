<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

include 'db.php';

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE id = $delete_id";
    $conn->query($sql);
    header("Location: dashboard.php?deleted=true");
    exit();
}

// Fetch users
$sql = "SELECT * FROM users"; 
$result = $conn->query($sql);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark px-4 p-4">
        <span class="navbar-text text-white">
            Welcome, <em><span class="name fw-bold "> <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?> </span></em>!
        </span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <?php

if (isset($_GET['deleted'])) {
    echo '<div class="container mt-5">';
    echo '<div id="deleteAlert" class="alert alert-success alert-dismissible fade show" role="alert">';
    echo 'User successfully deleted!';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    echo '</div>';
}
?>


    <div class="container mt-5">
        <h2 class="text-center">List of Users</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['created_at']}</td>
                                <td>
                                    <a href='?delete_id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>







    <script>
    $(document).ready(function () {
        // Show Bootstrap alert for delete confirmation
        $("#deleteAlert").fadeIn(500).delay(700).fadeOut(500);
        
        // Show confirmation dialog before deleting a user
        $('.btn-danger').on('click', function (e) {
            e.preventDefault(); // Prevent the default action
            var link = $(this).attr('href'); // Get the href attribute
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = link; // Redirect to the delete link
            }
        });
    });
</script>


</body>
</html>
