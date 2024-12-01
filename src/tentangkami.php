<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami | Digicert SMKN 71</title>
    <link href="./assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <header style="background-color: white; border-bottom: 1px solid #ddd; padding: 1rem;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <div class="logo" style="display: flex; align-items: center;">
                <img src="assets/logo.png" alt="Logo" style="width: 60px; height: 60px;">
                <h1 style="font-size: 24px; font-weight: bold; margin-left: 10px;">Digicert</h1>
            </div>
            <nav style="display: flex; align-items: center;">
                <a href="index.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Home</a>
                <a href="tentangkami.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Tentang Kami</a>
                <a href="cek-sertifikat.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Cek Sertifikat</a>
                <a href="courses.php" style="margin: 0 15px; text-decoration: none; color: black; font-weight: 500;">Pelatihan</a>
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

    <main>
        <section class="about-header text-center py-5" style="background-color: #f8f9fa;">
            <div class="container">
                <h1 class="display-4">Tentang Kami</h1>
                <p class="lead">Selamat datang di layanan Digicert SMKN 71 - solusi digitalisasi sertifikat untuk pendidikan.</p>
            </div>
        </section>

        <section class="about-us py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Visi dan Misi</h2>
                        <p><strong>Visi:</strong> Menjadi sekolah unggulan yang memberikan kontribusi signifikan dalam memajukan pendidikan melalui teknologi digital.</p>
                        <p><strong>Misi:</strong></p>
                        <ul>
                            <li>Menyediakan layanan sertifikasi yang cepat, aman, dan terjangkau untuk semua siswa.</li>
                            <li>Mendorong integrasi teknologi dalam pendidikan melalui sertifikat digital.</li>
                            <li>Membangun komunitas yang mendukung perkembangan keterampilan digital bagi siswa dan pendidik.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <img src="./assets/about us.jpg" alt="About Us" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </section>

        <section class="values-section py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-4">Nilai-Nilai Kami</h2>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h3>Inovasi</h3>
                        <p>Kami berkomitmen untuk selalu menghadirkan solusi inovatif dalam digitalisasi pendidikan, termasuk dalam layanan E-Sertifikat.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h3>Kepercayaan</h3>
                        <p>Kami mengutamakan keamanan dan privasi pengguna dalam setiap proses, memastikan sertifikat yang diterbitkan terlindungi dengan baik.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h3>Kolaborasi</h3>
                        <p>Bekerja sama dengan para pendidik, siswa, dan komunitas untuk menciptakan sistem yang bermanfaat bagi semua.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="history-section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Sejarah Kami</h2>
                        <p>SMKN 71 telah berdiri sejak tahun 2019 dan terus berkembang menjadi institusi pendidikan terkemuka. Dengan adanya Digicert, kami membawa inovasi terbaru untuk mendukung program digitalisasi sekolah.</p>
                    </div>
                    <div class="col-lg-6">
                        <h2>Tim Kami</h2>
                        <p>Kami didukung oleh tim yang terdiri dari para profesional dan pendidik yang berdedikasi untuk memberikan layanan terbaik kepada siswa dan komunitas sekolah.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer text-center mt-5 py-3" style="background-color: #1d3c6e; color: #fff;">
        <p>© 2024 SMKN 71. All rights reserved.</p>
    </footer>

    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

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