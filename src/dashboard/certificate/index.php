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

$getAllCertificateWithField = $conn->query("SELECT c.*, cf.field_name, cf.field_value, cf.file_name, u.full_name
FROM certificates c
JOIN certificate_fields cf ON c.id = cf.certificate_id 
JOIN users u ON c.user_id = u.id");

while ($row = $getAllCertificateWithField->fetch_array()) {
    $certificates[] = $row;
}

// print_r($certificates); die;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
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


    <!-- Main Content -->
    <div class="content flex-grow-1">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Daftar Sertifikat</h2>
                <div class="d-flex justify-content-end align-items-center p-3">
                    <span><?= $_SESSION['full_name'] ?></span>
                    <div class="dropdown">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="bi bi-person-circle ms-2 dropdown-toggle" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="..">Landing Page</a></li>
                            <li><a class="dropdown-item" href="../akun.php">Homepage</a></li>
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div class="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari Sertif Di Sini">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <a href="create.php">
                <button class="btn btn-primary">Tambah Sertifikat</button>
            </a>
        </div>


        <div class="table-responsive">
            <?php if (isset($certificates)) { ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Preview</th>
                            <th scope="col">Nama Sertifikat</th>
                            <th scope="col">Tanggal Diterbitkan</th>
                            <th scope="col">Cert ID</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($certificates as $key => $cert) : ?>
                            <tr>
                                <th scope="row"><?= $key + 1 ?></th>
                                <td><img class="center-image" src="../../assets/uploads/certificates/<?= $cert['file_name'] ?>" width="200px" alt=""></td>
                                <td><?= $cert['full_name'] ?></td>
                                <td><?= hummanDate($cert['issued_at']) ?></td>
                                <td><?= $cert['certificate_code'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="downloadCertificate('<?= $cert['file_name'] ?>', '<?= $cert['certificate_code'] ?>')" data-bs-toggle="modal" data-bs-target="#viewCertificateModal">View</button>
                                    <a href="edit.php?id=<?= $cert['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCertificate('<?= $cert['id'] ?>')" data-bs-toggle="modal" data-bs-target="#deleteCertificateModal">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <strong>Tidak ada sertifikat yang tersedia.</strong>
            <?php } ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteCertificateModal" tabindex="-1" aria-labelledby="deleteCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCertificateModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Setelah di hapus, data tidak dapat dikembalikan.
                </div>
                <div class="modal-footer">
                    <form action="../../service/certificate.php" method="post">
                        <input type="hidden" id="deleteCertificateByID" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="type" value="delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
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
                    <img src="../../assets/uploads/certificates/" width="400px" class="center-image" id="imgCertificate" alt="">
                </div>
                <div class="modal-footer">
                    <form action="../../service/certificate.php" method="post">
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
        function deleteCertificate(id) {
            document.getElementById('deleteCertificateByID').value = id;
        }

        function downloadCertificate(fileName, code) {
            document.getElementById('setFileName').value = fileName;
            document.getElementById('setCodeCertificate').value = code;
            document.getElementById('imgCertificate').src = "../../assets/uploads/certificates/" + fileName;
        }
    </script>
</body>

</html>