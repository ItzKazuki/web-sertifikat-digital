<?php
session_start();

include '../../service/utility.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth']) && $_SESSION['role'] != "admin") {
    return redirect("index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link href="../../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">

</head>
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

    /* Main konten styling */
    .main-content {
        margin-left: 250px;
        /* Sesuaikan dengan lebar sidebar */
        padding: 20px;
        width: calc(100% - 250px);
        /* Mengambil sisa lebar di samping sidebar */
    }

    .search {
        width: 50%;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .table th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: left;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row">
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
                            <a href="sertificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                            <a href="sertificate/index.php" class="dropdown-item">Daftar Sertifikat</a>
                        </div>
                    </li>
                    <!-- Manajemen Pengguna Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#penggunaMenu" role="button" aria-expanded="false" aria-controls="penggunaMenu">Manajemen Pengguna</a>
                        <div class="collapse" id="penggunaMenu">
                            <a href="#" class="dropdown-item">Tambah Pengguna</a>
                            <a href="#" class="dropdown-item">Daftar Pengguna</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                        <div class="collapse" id="pelatihanMenu">
                            <a href="#" class="dropdown-item">Tambah Pelatihan</a>
                            <a href="#" class="dropdown-item">Daftar Pelatihan</a>
                        </div>
                    </li>
                    <li class="nav-item"><a href="#" class="nav-link">Laporan</a></li>
                </ul>
            </div>
            <!-- Main konten -->
            <div class="main-content">
                <div class="col-md-12 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Sertif Di Sini">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <svg class="search-icon" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1-1.415-1.414l-3.85-3.85a1 1 0 0 1 1.414-1.415l3.85 3.85a1 1 0 0 1 1.415 1.414zM6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end p-3">
                            <span><?= $_SESSION['full_name'] ?></span>
                            <a href="../index.php" class="bi bi-person-circle ms-2" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="text-align: left; margin: 0;">Daftar Sertifikat</h2>
                    <a href="create.php">
                        <button class="btn btn-primary">Tambah Sertifikat</button>
                    </a>
                </div>


                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Sertifikat</th>
                                <th scope="col">Tanggal Diterbitkan</th>
                                <th scope="col">Jumlah Pengguna</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>2022-01-01</td>
                                <td>2023-01-01</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Edit</button>
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>

</html>