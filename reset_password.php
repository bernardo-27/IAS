<?php
require_once 'db.php';

// Check if token is valid and not expired
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $conn->prepare("SELECT pr.user_id, pr.expires_at, u.email 
                           FROM password_reset pr 
                           JOIN users u ON pr.user_id = u.id 
                           WHERE pr.token = ? AND pr.used = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $error = "Invalid or expired reset link.";
    } else {
        $row = $result->fetch_assoc();
        $expires_at = strtotime($row['expires_at']);
        
        if (time() > $expires_at) {
            $error = "This reset link has expired.";
        } else {
            $email = $row['email'];
            $valid_token = true;
        }
    }
    $stmt->close();
} else {
    header("Location: forgot_password.php");
    exit();
}
?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'password_requirements'): ?>
    <div class="alert alert-danger">
        <?php 
            if(isset($_GET['message'])) {
                echo htmlspecialchars($_GET['message']);
            } else {
                echo "Password must be at least 8 characters and contain only letters and numbers.";
            }
        ?>
    </div>
<?php endif; ?>



<!doctype html>
<html lang="en">
<head>
    <title>Reset Password - Sangguniang Kabataan Management System</title>
    <link rel="icon" href="sk.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">


    <style>
        .alert {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            width: 300px;
        }
    </style>
</head>
<body>
    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 align-self-center">
                    <div class="section pb-5 pt-5 pt-sm-2">
                        <h1 id="kabataan" class="text-center font-weight-bold font-italic">SANGGUNIANG KABATAAN MANAGEMENT SYSTEM</h1>

                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section">
                                            <?php if (isset($error)): ?>
                                                <div class="alert alert-danger" role="alert">
                                                    <?php echo $error; ?>
                                                </div>
                                                <p class="text-center">
                                                    <a href="forgot_password.php" class="btn mt-4">Request New Reset Link</a>
                                                </p>
                                            <?php else: ?>
                                                <h4 class="mb-4 pb-3 text-center">
                                                    <img src="sk1.png" alt="Logo" class="logo"> Set New Password
                                                </h4>
                                                <p class="mb-3 text-center">Email: <?php echo $email; ?></p>
                                                <form action="process_reset_password.php" method="POST">
                                                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                                                    <div class="form-group">
                                                        <label for="password">New Password:</label>
                                                        <input type="password" name="password" id="password" class="form-style" placeholder="New Password" required minlength="8">
                                                        <i class="input-icon uil uil-lock-alt"></i>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="confirm_password">Confirm Password:</label>
                                                        <input type="password" name="confirm_password" id="confirm_password" class="form-style" placeholder="Confirm Password" required>
                                                        <i class="input-icon uil uil-lock-alt"></i>
                                                    </div>
                                                    <div id="password_match" class="text-danger" style="display:none;">Passwords do not match!</div>
                                                    <button type="submit" class="btn mt-4" id="submit_btn">Reset Password</button>
                                                </form>
                                            <?php endif; ?>
                                            <p class="mb-0 mt-4 text-center">
                                                <a href="index.html" class="link">Back to Login</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="passwordErrorAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none;">
        <span id="errorMessage"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
        const errorMessage = urlParams.get('message');

        if (errorMessage) {
            $("#errorMessage").text(decodeURIComponent(errorMessage));
            $("#passwordErrorAlert").fadeIn(500).delay(1000).fadeOut(500);
            history.replaceState(null, "", window.location.pathname);
        }

            $('#confirm_password').on('keyup', function() {
                var password = $('#password').val();
                var confirm_password = $(this).val();
                
                if (password != confirm_password) {
                    $('#password_match').show();
                    $('#submit_btn').prop('disabled', true);
                } else {
                    $('#password_match').hide();
                    $('#submit_btn').prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>