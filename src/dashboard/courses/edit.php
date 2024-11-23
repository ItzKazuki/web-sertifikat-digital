<?php
session_start();

include '../../service/utility.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
    return redirect("index.php");
}

if ($_SESSION['role'] != "admin") {
    return redirect("index.php");
}

if (!isset($_GET['id'])) {
    return redirect("dashboard/courses");
}

$getCourse = $conn->query("SELECT * FROM courses WHERE id =" . $_GET['id'])->fetch_array();

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

    <div class="content flex-grow-1">
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
        <div class="form-container mt-4">
            <form action="../../service/courses.php" method="post">
                <input type="hidden" name="id" value="<?= $getCourse[0] ?>">
                <div class="mb-3">
                    <label for="course_name">
                        Nama Pelatihan :
                    </label>
                    <input id="course_name" name="course_name" placeholder="Ketik nama pelatihan di sini" type="text" value="<?= $getCourse[1] ?>" required />
                </div>
                <div class="mb-3">
                    <label for="course_date">
                        Tanggal Pelatihan :
                    </label>
                    <input id="course_date" name="course_date" placeholder="Masukan tanggal Pelatihan" type="date" min="<?php echo date("Y-m-d"); ?>" value="<?= $getCourse[3] ?>" required />
                </div>
                <div class="mb-3">
                    <label for="organization">
                        Pembuat Acara/Organisasi/PT Pelatihan :
                    </label>
                    <input id="organization" name="course_organizer" placeholder="Masukan Tanggal Penerbitan Sertifikat" type="text" value="<?= $getCourse[4] ?>" required />
                </div>
                <div class="mb-3">
                    <label for="descrtiption">
                        Deskripsi Pelatihan :
                    </label>
                    <textarea id="descrtiption" name="description" placeholder="Masukan Deskripsi Sertifikat" rows="4" required><?= $getCourse[2] ?></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="index.php" class="btn btn-danger" type="button">
                        Batal
                    </a>
                    <button class="btn btn-success" type="submit" name="type" value="edit">
                        Simpan
                    </button>
                </div>
            </form>
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
    <script>
        const boxes = document.querySelectorAll('.box');
        let selectedBox = null;

        boxes.forEach(box => {
            box.addEventListener('click', () => {
                if (selectedBox) {
                    selectedBox.classList.remove('selected');
                }
                box.classList.add('selected');
                selectedBox = box;

                document.getElementById('selectedValue').value = box.getAttribute('data-value');
            });
        });
    </script>
</body>

</html>