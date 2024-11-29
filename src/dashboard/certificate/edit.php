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
    return redirect('certificate', "Sertifikat tidak ditemukan", 'error');
}

$id = $_GET['id'];

$getCert = $conn->query("SELECT c.*, cf.id AS certificate_details_id, cf.field_name, cf.field_value, cf.file_name
FROM certificates c
JOIN certificate_fields cf ON c.id = cf.certificate_id 
WHERE c.id = '$id'");

if ($getCert->num_rows < 1) {
    return redirect("certificate", "Sertifikat tidak tersedia", "error");
}


$certDetails = $getCert->fetch_array(MYSQLI_ASSOC);

// debug($certDetails);

$getCourses = $conn->query("SELECT * FROM courses");

if ($getCourses->num_rows < 1) {
    return redirect("courses", "Tambahkan pealatihan terlebih dahulu", "error");
}

while ($row = $getCourses->fetch_array()) {
    $courses[] = $row;
}

$getUsers = $conn->query("SELECT * FROM users WHERE role = 'participant' ORDER BY full_name ASC");

if ($getUsers->num_rows < 1) {
    return redirect("courses", "Tambahkan user terlebih dahulu", "error");
}

while ($row = $getUsers->fetch_array()) {
    $users[] = $row;
}

$getTemplates = $conn->query("SELECT * FROM certificate_templates");

if ($getTemplates->num_rows < 1) {
    return redirect("certificate-template", "Tambahkan template terlebih dahulu", "error");
}

while ($row = $getTemplates->fetch_array()) {
    $templates[] = $row;
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
                    <a href="index.php" class="dropdown-item">List Sertifikat</a>
                    <a href="create.php" class="dropdown-item">Buat Sertifikat</a>
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
                    <a href="../users" class="dropdown-item">List Pengguna</a>
                    <a href="../users/create.php" class="dropdown-item">Tambah Pengguna</a>
                </div>
            </li>
            <li class="nav-item"><a href="../reports.php" class="nav-link">Laporan</a></li>
        </ul>
    </div>

    <div class="content flex-grow-1">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Sertifikat</h2>
                <div class="d-flex justify-content-end align-items-center p-3">
                    <span><?= $_SESSION['full_name'] ?></span>
                    <div class="dropdown">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="bi bi-person-circle ms-2 dropdown-toggle" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../../">Landing Page</a></li>
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
        <div class="form-container">
            <h4>Preview</h4>
            <div style="display: flex; justify-content: center; align-items: center;">
                <img src="../../assets/uploads/certificates/<?= $certDetails['file_name'] ?>" width="450" alt="">
            </div>
        </div>
        <div class="form-container mt-4">
            <form action="../../service/certificate.php" method="POST">
                <input type="hidden" name="id" value="<?= $certDetails['id'] ?>">
                <div class="mb-3">
                    <label for="judulSertifikat">
                        Judul Sertifikat :
                    </label>
                    <input id="judulSertifikat" placeholder="Ketik judul di sini" name="title" type="text" value="<?= $certDetails['field_name'] ?>" required />
                </div>
                <div class="mb-3">
                    <label for="namaPeserta">
                        Nama Peserta :
                    </label>
                    <select name="id_peserta" id="namaPeserta" required>
                        <option selected="selected">Pilih User</option>
                        <?php foreach ($users as $user) : ?>
                            <option value="<?= $user[0] ?>"><?= $user[2] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="pilihPelatihan">
                        Pelatihan :
                    </label>
                    <select name="id_courses" id="pilihPelatihan" required>
                        <option selected="selected">Pilih Pelatihan</option>
                        <?php foreach ($courses as $course) : ?>
                            <option value="<?= $course[0] ?>"><?= $course[1] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="deskripsiSertifikat">
                        Deskripsi Sertifikat :
                    </label>
                    <textarea id="deskripsiSertifikat" name="desc" placeholder="Masukan Deskripsi Sertifikat" rows="4" required><?= $certDetails['field_value'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="unggahTemplate">
                        Pilih Template Sertifikat :
                    </label>
                    <input type="hidden" name="template" id="select_template">
                    <div class="row g-3" style="display: flex; justify-content: center;">
                        <?php foreach ($templates as $template) : ?>
                            <div class="col-md-2">
                                <img width="200px" src="../../assets/uploads/templates/<?= $template['file_name'] ?>" class="cert-box p-2 text-center shadow-sm box" data-value="<?= $template['id'] ?>" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>


                <div class="d-flex justify-content-end">
                    <a href="index.php" class="btn btn-danger" type="button">
                        Batal
                    </a>
                    <button class="btn btn-success" name="type" value="edit" type="submit">
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

        document.getElementById('namaPeserta').value = "<?= $certDetails['user_id'] ?>";
        document.getElementById('pilihPelatihan').value = "<?= $certDetails['event_id'] ?>";

        boxes.forEach(box => {
            box.addEventListener('click', () => {
                if (selectedBox) {
                    selectedBox.classList.remove('selected');
                }

                box.classList.add('selected');
                selectedBox = box;
                document.getElementById('select_template').value = box.getAttribute('data-value');
            });

            if (box.getAttribute('data-value') == "<?= $certDetails['certificate_template_id'] ?>") {
                box.classList.add('selected');
                selectedBox = box;
                document.getElementById('select_template').value = box.getAttribute('data-value');
            }
        });
    </script>
</body>

</html>