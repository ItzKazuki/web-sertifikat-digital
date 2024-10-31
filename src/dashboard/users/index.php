<?php
session_start();

include '../../service/utility.php';
include '../../service/connection.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth']) && $_SESSION['role'] != "admin") {
    return redirect("index.php");
}

$getUser = $conn->query("SELECT * FROM users");

while ($row = $getUser->fetch_row()) {
    $users[] = $row;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #2f4b7c;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
        }

        .sidebar img {
            width: 80px;
            margin-bottom: 10px;
        }

        .sidebar h2 {
            font-size: 18px;
            text-align: center;
            margin: 0;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            width: 100%;
        }

        .sidebar ul li {
            padding: 10px 20px;
            font-size: 14px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        /* Sub-menu styling */
        .sidebar ul li ul {
            list-style-type: disc;
            /* Show bullets */
            padding-left: 20px;
            /* Indent sub-items */
            margin-top: 5px;
        }

        /* Header */
        .header {
            height: 60px;
            background-color: #e9ecef;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: calc(100% - 240px);
            /* Additional margin to accommodate sidebar */
            margin-left: 220px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            top: 0;
            z-index: 1000;
        }

        .header .title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #333;
            gap: 10px;
        }

        .header .user-info i {
            cursor: pointer;
            color: gray;
            margin-left: 8px;
            /* Added margin for logout icon visibility */
        }

        /* Main content */
        .main-content {
            margin-left: 220px;
            margin-top: 60px;
            padding: 20px;
            background-color: #ffffff;
            min-height: 100vh;
            padding-top: 60px;
        }

        .main-content h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 0;
        }

        /* Button */
        .add-button {
            background-color: #2f4b7c;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .add-button i {
            margin-left: 8px;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            margin-top: 10px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #2f4b7c;
            color: white;
        }

        .table td {
            color: #333;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #2f4b7c;
            color: white;
            font-size: 14px;
            margin-top: 20px;
        }

        /* Button Container */
        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .header {
                width: 100%;
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
                padding-top: 60px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="../../assets/logo.png" alt="Logo Sekolah">
        <h2>Dashboard Sertifikat</h2>
        <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Manajemen Sertifikat</a>
                <ul>
                    <li><a href="#">Buat Sertifikat</a></li>
                    <li><a href="#">Daftar Sertifikat</a></li>
                </ul>
            </li>
            <li><a href="#">Manajemen Pengguna</a>
                <ul>
                    <li><a href="#">Tambah Pengguna</a></li>
                    <li><a href="#">Daftar Pengguna</a></li>
                </ul>
            </li>
            <li><a href="#">Laporan</a></li>
        </ul>
    </div>

    <div class="header">
        <div class="title">Manajemen Pengguna</div>
        <div class="user-info">
            <?= $_SESSION['full_name'] ?>
            <i class="fas fa-user-circle"></i>
            <i class="fas fa-sign-out-alt"></i> <!-- Icon logout lebih ke kiri -->
        </div>
    </div>

    <div class="main-content">
        <h2>Daftar Pengguna</h2>
        <div class="button-container">
            <button class="add-button">Tambah Pengguna +</button>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email Pengguna</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $user[2] ?></td>
                            <td><?= $user[3] ?></td>
                            <td><?= $user[7] ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary">Edit</button>
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 Kelompok 1. Semua hak dilindungi.
    </div>
</body>

</html>