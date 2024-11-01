<?php
session_start();

include 'service/utility.php';

if(!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
    return redirect("index.php");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sertifikat</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link href="assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-white text-dark font-sans">

    <header class="d-flex justify-content-between align-items-center p-4 bg-white shadow-sm">
        <div class="d-flex align-items-center">
            <img src="assets/logo.png" alt="Logo" class="img-fluid" style="height: 50px; width: auto; margin-right: 1rem;">
            <h1 class="h4 text-dark font-weight-bold">E-Sertifikat</h1>
        </div>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a href="index.php" class="nav-link font-weight-bold text-dark">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-dark">Tentang Kami</a></li>
                <li class="nav-item"><a href="cekindex.php" class="nav-link font-weight-bold text-dark">Cari Sertifikat</a></li>
                <li class="nav-item"><a href="#" class="nav-link font-weight-bold text-dark"><?= $_SESSION['full_name'] ?></a></li>
            </ul>
        </nav>
    </header>

    <main class="container text-center my-5 p-4">
        <h1 class="display-5 font-weight-semibold mb-3">Selamat Datang <?= $_SESSION['full_name'] ?></h1>
        <h2 class="h5 text-dark mb-4">Lihat Sertifikat yang kamu punya</h2>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-light p-4 text-center shadow-sm">
                    <h3 class="card-title h5">Kategori</h3>
                    <div class="card-body bg-secondary text-light small">Dimiliki</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-light p-4 text-center shadow-sm">
                    <h3 class="card-title h5">Kategori</h3>
                    <div class="card-body bg-secondary text-light small">Dimiliki</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-light p-4 text-center shadow-sm">
                    <h3 class="card-title h5">Kategori</h3>
                    <div class="card-body bg-secondary text-light small">Dimiliki</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-light p-4 text-center shadow-sm">
                    <h3 class="card-title h5">Kategori</h3>
                    <div class="card-body bg-secondary text-light small">Dimiliki</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-light p-4 text-center shadow-sm">
                    <h3 class="card-title h5">Kategori</h3>
                    <div class="card-body bg-secondary text-light small">Dimiliki</div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-light p-4 text-center shadow-sm">
                    <h3 class="card-title h5">Kategori</h3>
                    <div class="card-body bg-secondary text-light small">Dimiliki</div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center p-4 bg-dark text-white mt-5">
        <p>© 2024 Kelompok 1. Semua hak dilindungi.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . $_SESSION['success'] . "',
                showConfirmButton: true
            });
        </script>";
        unset($_SESSION['success']); // Clear the session variable
    }
    
    if (isset($_SESSION['error'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . $_SESSION['error'] . "',
                showConfirmButton: true
            });
        </script>";
        unset($_SESSION['error']); // Clear the session variable
    }
    ?>
</body>

</html>