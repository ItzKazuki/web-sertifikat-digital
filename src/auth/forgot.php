<?php
session_start();
include '../service/utility.php';

if (isset($_SESSION['email'])) {
    return redirect("dashboard");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Digicert SMKN 71</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <div class="w-100">
        <a href="auth/login.php" class="text-dark">
            <i class="bi bi-arrow-left fs-3"></i>
        </a>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-lg-4 col-md-6 col-sm-8 col-10"> <!-- Bootstrap grid -->
            <div class="login-box shadow p-4 rounded bg-white">
                <form action="../service/auth.php" method="post">

                    <div class="mb-4">
                        <h2 class="text-center">Reset Password</h2>
                    </div>

                    <!-- Input Email dengan Ikon -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>
                    </div>

                    <div>
                        <a href="login.php">already have an account?</a>
                    </div>
                    <br>
                    <center>
                        <div class="d-grid mb-4">
                            <button type="submit" name="type" value="find_email" class="btn btn-primary">Reset</button>
                        </div>
                    </center>

                </form>
            </div>
        </div>

        <footer class="footer text-center mt-5">
            <p>© 2024 Kelompok 1. Semua hak dilindungi.</p>
        </footer>

        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

        <?php
        if (isset($_SESSION['success'])) {
            if (strlen($_SESSION['success']) > 3) {
                echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . $_SESSION['success'] . "',
                showConfirmButton: true
            });
        </script>";
            }
            unset($_SESSION['success']); // Clear the session variable
        }

        if (isset($_SESSION['error'])) {
            if (strlen($_SESSION['error']) > 3) {
                echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . $_SESSION['error'] . "',
                showConfirmButton: true
            });
        </script>";
            }
            unset($_SESSION['error']); // Clear the session variable
        }
        ?>
</body>

</html>