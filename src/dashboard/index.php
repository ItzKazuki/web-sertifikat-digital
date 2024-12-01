<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../service/utility.php';
include '../service/connection.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
    return redirect("index.php");
}

if ($_SESSION['role'] != "admin") {
    return redirect("index.php");
}

$countCertificate = $conn->query('SELECT count(*) FROM certificates')->fetch_array();
$countUsers = $conn->query('SELECT count(*) FROM users')->fetch_array();
$countDownloadedCertificate = $conn->query("SELECT SUM(download_count) AS total_downloads FROM certificates")->fetch_array();

$getAllCertificates = $conn->query("SELECT c.*, cf.file_name FROM certificates c JOIN certificate_fields cf ON c.id = cf.certificate_id");

while ($row = $getAllCertificates->fetch_array(MYSQLI_ASSOC)) {
    $certificates[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Digicert SMKN 71</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <a href="certificate/index.php" class="dropdown-item">List Sertifikat</a>
                    <a href="certificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                <div class="collapse" id="pelatihanMenu">
                    <a href="courses/index.php" class="dropdown-item">List Pelatihan</a>
                    <a href="courses/create.php" class="dropdown-item">Tambah Pelatihan</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#templateSertifikat" role="button" aria-expanded="false" aria-controls="templateSertifikat">Manajemen Template Sertifikat</a>
                <div class="collapse" id="templateSertifikat">
                    <a href="certificate-template/" class="dropdown-item">List Template</a>
                    <a href="certificate-template/create.php" class="dropdown-item">Tambah Template</a>
                </div>
            </li>
            <!-- Manajemen Pengguna Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#penggunaMenu" role="button" aria-expanded="false" aria-controls="penggunaMenu">Manajemen Pengguna</a>
                <div class="collapse" id="penggunaMenu">
                    <a href="users/" class="dropdown-item">List Pengguna</a>
                    <a href="users/create.php" class="dropdown-item">Tambah Pengguna</a>
                </div>
            </li>
            <li class="nav-item"><a href="reports.php" class="nav-link">Laporan</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content flex-grow-1">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Statistik Sertifikat</h2>
                <div class="d-flex justify-content-end align-items-center p-3">
                    <span><?= $_SESSION['full_name'] ?></span>
                    <div class="dropdown">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="bi bi-person-circle ms-2 dropdown-toggle" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../index.php">Landing Page</a></li>
                            <li><a class="dropdown-item" href="../akun.php">Homepage</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form class="dropdown-item" action="../service/auth.php" method="post">
                                    <button type="submit" name="type" value="logout" style="background-color: transparent; border: none; width:100%; text-align:justify; ">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Boxes -->
        <div class="container" style="width: 100vw; border-radius: 4px; height: 250px; background-color: gray; display:flex; align-items: center; justify-content: center;">
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
        <div class="row mt-4">
            <div class="col-6">
                <h3><strong>Daftar Sertifikat</strong></h3>
            </div>
            <div class="col-6 text-end mb-2">
                <a href="certificate/create.php" class="btn btn-primary">Buat Sertifikat</a>
            </div>
        </div>

        <!-- Sertifikat Cards -->

        <div class="row g-2 mt-2" style="display: flex;">
            <?php if (isset($certificates)) : ?>
                <?php foreach ($certificates as $certificate) : ?>
                    <div class="col-xl-3">
                        <img width="200px" onclick="downloadCertificate('<?= $certificate['file_name'] ?>', '<?= $certificate['certificate_code'] ?>')" data-bs-toggle="modal" data-bs-target="#viewCertificateModal" src="../assets/uploads/certificates/<?= $certificate['file_name'] ?>" class="cert-box p-2 text-center shadow-sm box" data-value="template1" />
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <strong>Tidak ada sertifikat yang ditemukan.</strong>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCertificateModalLabel">Detail Sertifikat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="../assets/uploads/certificates/" width="400px" class="center-image" id="imgCertificate" alt="">
                </div>
                <div class="modal-footer">
                    <form action="../service/certificate.php" method="post">
                        <input type="hidden" id="setFileName" name="file_name">
                        <input type="hidden" id="setCodeCertificate" name="code">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="type" value="download" class="btn btn-success">Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 Kelompok 1. Semua hak dilindungi. Ver: v<?= $_ENV['APP_VER'] ?>
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

    <script>
        function deleteCertificate(id) {
            document.getElementById('deleteCertificateByID').value = id;
        }

        function downloadCertificate(fileName, code) {
            document.getElementById('setFileName').value = fileName;
            document.getElementById('setCodeCertificate').value = code;
            document.getElementById('imgCertificate').src = "../assets/uploads/certificates/" + fileName;
        }
    </script>
</body>

</html>