<?php
session_start();

include '../service/utility.php';
include '../service/connection.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth']) && $_SESSION['role'] != "admin") {
    return redirect("index.php");
}

$countCertificate = $conn->query('SELECT count(*) FROM certificates')->fetch_array();
$countUsers = $conn->query('SELECT count(*) FROM users')->fetch_array();
$countDownloadedCertificate = $conn->query("SELECT SUM(download_count) AS total_downloads FROM certificates")->fetch_array();

// debug($countCertificate);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Sidebar styling */
        .sidebar {
            background-color: #1d3c6e;
            color: white;
            height: 100vh;
            width: 250px;
            position: fixed;
        }

        .sidebar h4 {
            margin-top: 20px;
            font-size: 18px;
        }

        .nav-link {
            color: white;
            padding-left: 20px;
        }

        .nav-link:hover,
        .dropdown-item:hover {
            background-color: #2a4b8e;
            color: #ffffff !important;
        }

        .dropdown-item {
            padding-left: 30px;
        }

        /* Main content styling */
        .content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f1f1f1;
            min-height: 100vh;
        }

        .stat-box {
            background-color: #1d3c6e;
            color: white;
            border-radius: 8px;
        }

        .cert-box {
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn-dark {
            background-color: #4c4c4c;
            color: white;
            border: none;
        }

        .btn-dark:hover {
            background-color: #333333;
        }

        .container {
            width: 1201px;
            height: 200px;
            background-color: gray;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-box {
            width: 300px;
            height: 150px;
            background-color: #0A3067;
            /* Navy blue color */
            color: white;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            margin: 0 10px;
            border-radius: 10px;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="text-center my-3">
            <img src="../assets/logo.png" alt="Logo" style="max-width: 80px;">
            <h4>Dashboard Sertifikat</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link">Beranda</a></li>
            <!-- Manajemen Sertifikat Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#sertifikatMenu" role="button" aria-expanded="false" aria-controls="sertifikatMenu">Manajemen Sertifikat</a>
                <div class="collapse" id="sertifikatMenu">
                    <a href="certificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                    <a href="certificate/index.php" class="dropdown-item">Daftar Sertifikat</a>
                </div>
            </li>
            <!-- Manajemen Pengguna Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#penggunaMenu" role="button" aria-expanded="false" aria-controls="penggunaMenu">Manajemen Pengguna</a>
                <div class="collapse" id="penggunaMenu">
                    <a href="users/create.php" class="dropdown-item">Tambah Pengguna</a>
                    <a href="users/" class="dropdown-item">Daftar Pengguna</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                <div class="collapse" id="pelatihanMenu">
                    <a href="courses/create.php" class="dropdown-item">Tambah Pelatihan</a>
                    <a href="courses/index.php" class="dropdown-item">Daftar Pelatihan</a>
                </div>
            </li>
            <li class="nav-item"><a href="reports.php" class="nav-link">Laporan</a></li>
            <li class="nav-item">
                <form action="../service/auth.php" method="post"><button type="submit" name="type" value="logout" class="nav-link">Log out</button></form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kotak Statistik</h2>
            <div class="d-flex justify-content-end p-3">
                <span><?= $_SESSION['full_name'] ?></span>
                <a href="dashboard.php" class="bi bi-person-circle ms-2" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
            </div>

        </div>


        <!-- Statistics Boxes -->
        <div class="container" style="width: 1240px; height: 250px; background-color: gray; display:flex; align-items: center;
    justify-content: center;">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-box p-3 text-center">Total Sertifikat <span style="margin-left: 7rem;font-size: 40px;position: absolute;margin-top: 2rem;"><?= $countCertificate[0] ?></span></div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box p-3 text-center">Pengguna Terdaftar <span style="margin-left: 7rem;font-size: 40px;position: absolute;margin-top: 2rem;"><?= $countUsers[0] ?></span></div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box p-3 text-center">Sertifikat Diunduh <span style="margin-left: 7rem;font-size: 40px;position: absolute;margin-top: 2rem;"><?= $countDownloadedCertificate['total_downloads'] ?></span></div>
                </div>
            </div>
        </div>


        <!-- Daftar Sertifikat Header -->
        <div class="row mt-3">
            <div class="col-6">
                <h5>Daftar Sertifikat</h5>
            </div>
            <div class="col-6 text-end mb-4">
                <a href="certificate/create.php" class="btn btn-dark">Buat Sertifikat</a>
            </div>
        </div>

        <!-- Sertifikat Cards -->

        <div class="row g-3">
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm">Sertif 1</div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
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