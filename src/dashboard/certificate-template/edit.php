<?php
session_start();

include '../../service/connection.php';
include '../../service/utility.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
    return redirect("index.php");
}

if ($_SESSION['role'] != "admin") {
    return redirect("index.php");
}

if (!isset($_GET['id'])) {
    return redirect("dashboard/certificate-template", "Sertifikat tidak tersedia", "error");
}

$getTemplateData = $conn->query("SELECT * FROM certificate_templates WHERE id = '" . $_GET['id'] . "'");

if ($getTemplateData->num_rows < 1) {
    return redirect("dashboard/certificate-template", "Sertifikat tidak tersedia", "error");
}

$getTemplateData = $getTemplateData->fetch_array();

// Get all font folders
$fontFolders = scandir('../../assets/font');
$fontFolders = array_diff($fontFolders, array('.', '..'));

// Function to get files in a specific font folder
function getFontFiles($fontFolder)
{
    $fontFiles = scandir('../../assets/font/' . $fontFolder);
    $fontFiles = array_diff($fontFiles, array('.', '..'));
    return $fontFiles;
}

// Initial font folder (e.g., 'calibri')
$selectedFont = $_GET['font'] ?? $getTemplateData['font_name'];

$fontFiles = getFontFiles($selectedFont);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* Main content styling */
        .content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f1f1f1;
            min-height: 100vh;
        }

        .stat-box {
            background-color: #1d3c6e;
            color: white;
            border-radius: 8px;
        }

        .cert-box {
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
            color: #333333;
            cursor: pointer;
        }

        .btn-dark {
            background-color: #4c4c4c;
            color: white;
            border: none;
        }

        .btn-dark:hover {
            background-color: #333333;
        }

        .container {
            width: 1201px;
            height: 200px;
            background-color: gray;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-box {
            width: 300px;
            height: 150px;
            background-color: #0A3067;
            /* Navy blue color */
            color: white;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            margin: 0 10px;
            border-radius: 10px;
            font-size: 1.2em;
        }

        body {
            background-color: #f8f9fa;
        }

        .content {
            padding: 20px;
        }

        .form-container {
            background-color: #003366;
            padding: 20px;
            border-radius: 10px;
            color: white;
        }

        .form-container input,
        select,
        .form-container textarea {
            background-color: #e9ecef;
            border: none;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }

        .form-container input[type="file"] {
            padding: 3px;
        }

        .form-container label {
            margin-bottom: 5px;
        }

        .form-container .btn {
            width: 100px;
            margin: 5px;
        }

        .footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .header {
            background-color: #e9ecef;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selected {
            border: 3px solid blue;
        }
    </style>
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
                    <a href="../certificate/index.php" class="dropdown-item">Daftar Sertifikat</a>
                    <a href="../certificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                <div class="collapse" id="pelatihanMenu">
                    <a href="../courses/index.php" class="dropdown-item">Daftar Pelatihan</a>
                    <a href="../courses/create.php" class="dropdown-item">Tambah Pelatihan</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#templateSertifikat" role="button" aria-expanded="false" aria-controls="templateSertifikat">Manajemen Template Sertifikat</a>
                <div class="collapse" id="templateSertifikat">
                    <a href="index.php" class="dropdown-item">Daftar Template</a>
                    <a href="create.php" class="dropdown-item">Tambah Template</a>
                </div>
            </li>
            <!-- Manajemen Pengguna Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#penggunaMenu" role="button" aria-expanded="false" aria-controls="penggunaMenu">Manajemen Pengguna</a>
                <div class="collapse" id="penggunaMenu">
                    <a href="../users/" class="dropdown-item">Daftar Pengguna</a>
                    <a href="../users/create.php" class="dropdown-item">Tambah Pengguna</a>
                </div>
            </li>
            <li class="nav-item"><a href="../reports.php" class="nav-link">Laporan</a></li>
            <li class="nav-item">
                <form action="../../service/auth.php" method="post"><button type="submit" name="type" value="logout" class="nav-link">Log out</button></form>
            </li>
        </ul>
    </div>

    <div class="content flex-grow-1">
        <div class="header">
            <h5>
                Buat Pelatihan Baru
            </h5>
            <div>
                <span>
                    <?= $_SESSION['full_name'] ?>
                </span>
                <i class="fas fa-user-circle">
                </i>
                <i class="fas fa-cog">
                </i>
            </div>
        </div>
        <div class="form-container" id="preview">
            <h4>Preview</h4>
            <div style="display: flex; justify-content: center; align-items: center;">
                <img id="previewImg" width="450" alt="">
            </div>
        </div>
        <div class="form-container mt-4">
            <form action="../../service/certificate_template.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $getTemplateData['id'] ?>">
                <div class="mb-3">
                    <label for="course_name">
                        Nama Template :
                    </label>
                    <input id="course_name" name="template_name" placeholder="Ketik nama template di sini" type="text" value="<?= $getTemplateData['template_name'] ?>" required />
                </div>
                <div class="mb-3">
                    <label for="font_name">
                        Pilih Font :
                    </label>
                    <select name="font_name" id="font_name" onchange="updateFontFiles(this.value)">
                        <option value="" selected>Masukan Jenis Font untuk nama participant</option>
                        <?php foreach ($fontFolders as $fontFolder) : ?>
                            <option value="<?php echo $fontFolder; ?>" <?php if ($fontFolder === $selectedFont) echo 'selected'; ?>><?php echo $fontFolder; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="type_font">
                        Pilih Tipe Font :
                    </label>
                    <select name="font_file" id="type_font">
                        <option value="">Masukan Tipe Font untuk nama participant</option>
                        <?php foreach ($fontFiles as $fontFile) : ?>
                            <option value="<?= $fontFile; ?>"><?= $fontFile; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="template_file">
                        File Template :
                    </label>
                    <input id="template_file" name="template_file" type="file" />
                </div>
                <div class="mb-3">
                    <label for="descrtiption">
                        Deskripsi Template :
                    </label>
                    <textarea id="descrtiption" name="description" placeholder="Masukan Deskripsi Singkat template " rows="4" required><?= $getTemplateData['template_desc'] ?></textarea>
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
        const fileInput = document.getElementById('template_file');
        const previewImg = document.getElementById('previewImg');

        previewImg.src = "../../assets/uploads/templates/<?= $getTemplateData['file_name'] ?>";

        fileInput.addEventListener('change', e => {
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = e => {
                    previewImg.src = e.target.result;
                }

                reader.readAsDataURL(file);
            }

            // document.getElementById('preview').style.display = 'block';
        })

        function updateFontFiles(selectedFont) {
            // You would use AJAX to fetch the files for the selected font asynchronously
            // Here, we'll simulate it by reloading the page with the new font
            document.getElementById('type_font').value = "";
            window.location.href = "?id=<?= $_GET['id'] ?>&font=" + selectedFont;
        }

        document.getElementById('type_font').value = "<?= $getTemplateData['font_file'] ?>";
    </script>
</body>

</html>