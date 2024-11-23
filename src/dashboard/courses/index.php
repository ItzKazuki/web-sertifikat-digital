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

$getCourses = $conn->query('SELECT * FROM courses');

while ($row = $getCourses->fetch_row()) {
    $courses[] = $row;
}
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
                    <a href="../certificate/index.php" class="dropdown-item">List Sertifikat</a>
                    <a href="../certificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                <div class="collapse" id="pelatihanMenu">
                    <a href="index.php" class="dropdown-item">List Pelatihan</a>
                    <a href="create.php" class="dropdown-item">Tambah Pelatihan</a>
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
                    <a href="../users/" class="dropdown-item">List Pengguna</a>
                    <a href="../users/create.php" class="dropdown-item">Tambah Pengguna</a>
                </div>
            </li>
            <li class="nav-item"><a href="../reports.php" class="nav-link">Laporan</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Daftar Pelatihan</h2>
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; margin-top:20px;">
            <div class="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari Pelatihan">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <a href="create.php">
                <a href="create.php" class="btn btn-primary">Tambah Pelatihan Baru</a>
            </a>
        </div>
        <div class="table-responsive">
            <?php if (isset($courses)) { ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Pelatihan</th>
                            <th scope="col">Tanggal Pelatihan</th>
                            <th scope="col">Tanggal Dibuat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $key => $course) : ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= $course[1] ?></td>
                                <td><?= $course[3] ?></td>
                                <td><?= $course[5] ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $course[0] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a class="btn btn-sm btn-danger" onclick="deleteCourse('<?= $course[0] ?>')" data-bs-toggle="modal" data-bs-target="#deleteCourseModal">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>
                NOT FOUND
            <?php } ?>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 Kelompok 1. Semua hak dilindungi. Ver: v<?= $_ENV['APP_VER'] ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCourseModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Setelah di hapus, data tidak dapat dikembalikan.
                </div>
                <div class="modal-footer">
                    <form action="../../service/courses.php" method="post">
                        <input type="hidden" id="deleteCourseByID" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="type" value="delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        function deleteCourse(id) {
            document.getElementById('deleteCourseByID').value = id;
        }
    </script>
</body>

</html>