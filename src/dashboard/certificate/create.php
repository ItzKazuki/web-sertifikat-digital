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
        .nav-link:hover, .dropdown-item:hover {
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
            background-color: #0A3067; /* Navy blue color */
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
        .sidebar {
            background-color: #003366;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
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
        .form-container input, .form-container textarea {
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

        .selected{
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
                    <a href="#" class="dropdown-item">Buat Sertifikat</a>
                    <a href="#" class="dropdown-item">Daftar Sertifikat</a>
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

<div class="content flex-grow-1">
    <div class="header">
     <h5>
      Buat Sertifikat
     </h5>
     <div>
      <span>
       Administrator
      </span>
      <i class="fas fa-user-circle">
      </i>
      <i class="fas fa-cog">
      </i>
     </div>
    </div>
    <div class="form-container mt-4">
     <form>
      <div class="mb-3">
       <label for="judulSertifikat">
        Judul Sertifikat :
       </label>
       <input id="judulSertifikat" placeholder="Ketik judul di sini" type="text"/>
      </div>
      <div class="mb-3">
       <label for="namaPeserta">
        Nama Peserta :
       </label>
       <input id="namaPeserta" placeholder="Masukan Nama Peserta" type="text"/>
      </div>
      <div class="mb-3">
       <label for="tanggalPenerbitan">
        Tanggal Penerbitan :
       </label>
       <input id="tanggalPenerbitan" placeholder="Masukan Tanggal Penerbitan Sertifikat" type="text"/>
      </div>
      <div class="mb-3">
       <label for="deskripsiSertifikat">
        Deskripsi Sertifikat :
       </label>
       <textarea id="deskripsiSertifikat" placeholder="Masukan Deskripsi Sertifikat" rows="4"></textarea>
      </div>

      <div class="mb-3">
       <label for="unggahTemplate">
        Unggah Template Sertifikat :
       </label>
       <div class="input-group">
        <input aria-describedby="inputGroupFileAddon01" aria-label="Upload" class="form-control" id="unggahTemplate" type="file"/>
        
       </div>
      </div>

      <div class="mb-3">
       <label for="unggahTemplate">
        Unggah Template Sertifikat :
       </label>
       <div class="row g-3" style="display: flex; justify-content:center;">
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm box" data-value="template1" id="tmp1">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm box" data-value="template2" id="tmp2">Sertif 1</div>
            </div>
            <div class="col-md-2">
                <div class="cert-box p-4 text-center shadow-sm box" data-value="template3" id="tmp3">Sertif 1</div>
            </div>
            </div>
        </div>


      <div class="d-flex justify-content-end">
       <button class="btn btn-danger" type="button">
        Batal
       </button>
       <button class="btn btn-success" type="submit">
        Simpan
       </button>
      </div>
     </form>
    </div>
   </div>
  </div>


    <script>
        const boxes =document.querySelectorAll('.box');
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
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
