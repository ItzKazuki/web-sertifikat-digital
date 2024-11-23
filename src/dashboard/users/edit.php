<?php
session_start();

include '../../service/utility.php';
include '../../service/connection.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
    return redirect("index.php");
}

if ($_SESSION['role'] != "admin") {
    return redirect("index.php");
}

if (!isset($_GET['id'])) {
    return redirect("/users");
}

$getUser = $conn->query("SELECT * FROM users WHERE id =" . $_GET['id'])->fetch_array();
// print_r($getUser);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <link href="../../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="sidebar">
        <div class="text-center my-3">
            <img src="../../assets/logo.png" alt="Logo" style="max-width: 80px;">
            <h4>Dashboard Sertifikat</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="../index.php" class="nav-link">Beranda</a></li>
            <!-- Manajemen Sertifikat Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#sertifikatMenu" role="button" aria-expanded="false" aria-controls="sertifikatMenu">Manajemen Sertifikat</a>
                <div class="collapse" id="sertifikatMenu">
                    <a href="../certificate" class="dropdown-item">List Sertifikat</a>
                    <a href="../certificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                <div class="collapse" id="pelatihanMenu">
                    <a href="../courses" class="dropdown-item">List Pelatihan</a>
                    <a href="../courses/create.php" class="dropdown-item">Tambah Pelatihan</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#templateSertifikat" role="button" aria-expanded="false" aria-controls="templateSertifikat">Manajemen Template Sertifikat</a>
                <div class="collapse" id="templateSertifikat">
                    <a href="../certificate-template/" class="dropdown-item">List Template</a>
                    <a href="../certificate-template/create.php" class="dropdown-item">Tambah Template</a>
                </div>
            </li>
            <!-- Manajemen Pengguna Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#penggunaMenu" role="button" aria-expanded="false" aria-controls="penggunaMenu">Manajemen Pengguna</a>
                <div class="collapse" id="penggunaMenu">
                    <a href="index.php" class="dropdown-item">List Pengguna</a>
                    <a href="create.php" class="dropdown-item">Tambah Pengguna</a>
                </div>
            </li>
            <li class="nav-item"><a href="../reports.php" class="nav-link">Laporan</a></li>
        </ul>
    </div>

    </div>
    <div class="content flex-grow-1">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Pengguna</h2>
                <div class="d-flex justify-content-end align-items-center p-3">
                    <span><?= $_SESSION['full_name'] ?></span>
                    <div class="dropdown">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="bi bi-person-circle ms-2 dropdown-toggle" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../../index.php">Landing Page</a></li>
                            <li><a class="dropdown-item" href="../../akun.php">Homepage</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form class="dropdown-item" action="../../service/auth.php" method="post">
                                    <button type="submit" name="type" value="logout" style="background-color: transparent; border: none; width:100%; text-align:justify; ">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="form-container">
                <form method="POST" action="../../service/users.php">
                    <input type="hidden" name="id" value="<?= $getUser['id'] ?>">

                    <label for="nik">
                        NIK
                    </label>
                    <input id="nik" name="nik" placeholder="Ketik nik di sini" type="number" value="<?= $getUser['nik'] ?>" required />

                    <label for="username">
                        Nama Pengguna
                    </label>
                    <input id="username" name="full_name" placeholder="Ketik nama di sini" type="text" value="<?= $getUser['full_name'] ?>" required />

                    <label for="phone_number">
                        Nomor Telepon Pengguna
                    </label>
                    <input id="phone_number" name="phone_number" placeholder="Ketik nomor telepon di sini" type="number" value="<?= $getUser['phone_number'] ?>" required />

                    <label for="email">
                        Email Pengguna
                    </label>
                    <input id="email" name="email" placeholder="Ketik email di sini" type="email" value="<?= $getUser['email'] ?>" required />

                    <label for="password">
                        Kata Sandi
                    </label>
                    <input id="password" name="password" placeholder="Ketik kata sandi di sini" type="password" />

                    <label for="confirm-password">
                        Konfirmasi Kata Sandi
                    </label>
                    <input id="confirm-password" name="c_password" placeholder="Ketik ulang kata sandi di sini" type="password" />

                    <label for="category">
                        Kategori Pengguna
                    </label>
                    <select id="category" name="role" required>
                        <option value="<?= $getUser["role"] ?>" selected>default: <?= $getUser["role"] ?></option>
                        <option value="participant">participant</option>
                        <option value="admin">admin</option>
                    </select>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="index.php" class="btn btn-danger me-2" type="button">
                            Batal
                        </a>
                        <button class="btn btn-success" type="submit" name="type" value="edit">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 Kelompok 1. Semua hak dilindungi. Ver: v<?= $_ENV['APP_VER'] ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="../../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
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