<!doctype html>
<html lang="en">
<head>
    <title>Forgot Password - Sangguniang Kabataan Management System</title>
    <link rel="icon" href="sk.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">


    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

    <style>
        .alert-container {
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
                                            <h4 class="mb-4 pb-3 text-center">
                                                <img src="sk1.png" alt="Logo" class="logo"> Reset Password
                                            </h4>
                                            <form action="process_forgot_password.php" method="POST">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email:</label>
                                                    <input type="email" name="email" class="form-style" placeholder="Enter your email" required>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4">Send Reset Link</button>
                                            </form>
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

    <div class="alert-container">
        <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            $error = $_GET['error'];
            if($error == 'not_found') {
                echo "Email not found in our records!";
            } else {
                echo "An error occurred. Please try again.";
            }
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Password reset email sent!.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>