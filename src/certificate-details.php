<?php
session_start();

include 'service/connection.php';
include 'service/utility.php';

require('service/fpdf186/fpdf.php');

// if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
//     return redirect("index.php");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cari'])) {
        $id = htmlspecialchars($_POST['id']);

        $getCert = $conn->query("SELECT c.*, cf.*, u.*, e.*
            FROM certificates c
            JOIN certificate_fields cf ON c.id = cf.certificate_id 
            JOIN users u ON c.user_id = u.id 
            JOIN courses e ON c.event_id = e.id 
            WHERE c.certificate_code = '$id'");

        if ($getCert->num_rows < 1) {
            return redirect("src/cek-sertifikat.php", "Sertifikat tidak tersedia", "error");
        }

        $certDetails = $getCert->fetch_array();
    }

    if (isset($_POST['download'])) {
        $sql = "UPDATE certificates SET download_count = download_count + 1 WHERE certificate_code = '" . $_POST['code'] . "'";
        if ($conn->query($sql)) {
            downloadCertificate($_POST['file_name']);
        } else {
            return redirect("src/index.php");
        }
    }
} else {
    return redirect("src/index.php");
}

function downloadCertificate($file_name)
{
    // header("content-type: application/pdf");
    // debug("assets/uploads/certificates/" . $file_name);
    $pdf = new FPDF();
    $pdf->AddPage("L", "A5");

    $pdf->Image("assets/uploads/certificates/" . $file_name, 0, 0, 210, 148);
    $pdf->Output("$file_name", 'D');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sertifikat</title>
    <link href="assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header-logo {
            height: 40px;
            margin-right: 10px;
        }

        .navbar {
            padding: 10px 20px;
        }

        .content {
            text-align: center;
            padding: 50px;
        }

        .certificate-placeholder {
            width: 100%;
            height: 700px;
            background-color: #e0e0e0;
            margin: 20px 0;
        }

        .info-section {
            text-align: left;
            margin-top: 30px;
        }

        .download-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        footer {
            background-color: #3b82f6;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <header style="background-color: white; border-bottom: 1px solid #ddd; padding: 1rem;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <div class="logo" style="display: flex; align-items: center;">
                <img src="assets/logo.png" alt="Logo" style="width: 60px; height: 60px;">
                <h1 style="font-size: 24px; font-weight: bold; margin-left: 10px;">E-Sertifikat</h1>
            </div>
            <nav style="display: flex; align-items: center;">
                <a href="index.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Home</a>
                <a href="#" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Tentang Kami</a>
                <a href="cek-sertifikat.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Cek Sertifikat</a>
                <?php if (isset($_SESSION['role'])) { ?>
                    <?php if ($_SESSION['role'] != "admin") { ?>
                        <a href="akun.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Akun</a>
                    <?php } else { ?>
                        <a href="dashboard/" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Dashboard</a>
                    <?php } ?>
                <?php } ?>

                <?php if (isset($_SESSION['email'])) { ?>
                    <form style="margin-left: 1em!important;" action="service/auth.php" method="post">
                        <button type="submit" name="type" value="logout" class="btn btn-outline-primary">Logout</button>
                    </form>
                <?php } else { ?>
                    <a href="auth/login.php" class="btn btn-outline-primary">Login</a>
                <?php } ?>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="content">
        <h1 class="display-4">SERTIF Name</h1>
        <img src="assets/uploads/certificates/<?= $certDetails['file_name'] ?>" class="certificate-placeholder">

        <!-- Information Section -->
        <div class="info-section">
            <h3>INFORMASI</h3>
            <p>Nama Pelatihan: <?= $certDetails['event_name'] ?></p>
            <p>Penyelenggara: <?= $certDetails['organizer'] ?></p>
            <p>Peserta: <?= $certDetails['full_name'] ?></p>
            <p>Pelatihan dimulai: <?= $certDetails['event_date'] ?></p>
        </div>

        <!-- Download Button -->
        <form method="post">
            <input type="hidden" name="file_name" value="<?= $certDetails['file_name'] ?>">
            <input type="hidden" name="code" value="<?= $certDetails['certificate_code'] ?>">
            <button type="submit" name="download" class="download-btn mt-3" style="background-color: #294486;">UNDUH SERTIFIKAT</button>
        </form>
    </div>

    <!-- Footer -->
    <footer style="background-color: #294486;">
        &copy; 2024 Kelompok 1. Semua hak dilindungi.
    </footer>

    <!-- Bootstrap JS (locally hosted) -->
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script> <!-- Adjust path as needed -->

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