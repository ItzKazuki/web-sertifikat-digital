<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Login Form</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f2f2f2;
        font-family: Arial, sans-serif;
    }

    .login-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
    }

    h2 {
        font-weight: bold;
    }

    label {
        font-weight: normal;
        display: block;
        margin-bottom: 5px;
    }

    input[type="email"],
    input[type="password"] {
        border-radius: 5px;
        padding: 10px;
        border: 1px solid #ddd;
        width: 100%;
    }

    button {
        background-color: #458FF6;
        color: #ffffff;
        padding: 10px;
        border-radius: 5px;
        border: none;
        font-size: 16px;
    }

    button:hover {
        background-color: #2962FF;
    }

    .footer {
        background-color: #294486;
        color: #ffffff;
        padding: 10px 0;
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    footer p {
        margin: 0;
    }

    a {
        color: #458FF6;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .back-button {
        font-size: 1.2rem;
        text-decoration: none;
        color: black;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .back-button:hover {
        background-color: #eee;
    }

    .login-box {
        margin-top: 80px;
        margin-bottom: 200px;
    }
</style>

<body>
    <div class="w-100">
        <a href="auth/login.php" class="text-dark">
            <i class="bi bi-arrow-left fs-3"></i>
        </a>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-lg-4 col-md-6 col-sm-8 col-10"> <!-- Bootstrap grid -->
            <div class="login-box shadow p-4 rounded bg-white">

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
                        <a href="change.php"><button type="submit" class="btn btn-primary">Reset</button></a>
                    </div>
                </center>

                </form>
            </div>
        </div>

        <footer class="footer text-center mt-5">
            <p>© 2024 Kelompok 1. Semua hak dilindungi.</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>