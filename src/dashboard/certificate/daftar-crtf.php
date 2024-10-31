<html>

<head>
    <title>
        Dashboard Sertifikat
    </title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #1a3e72;
            color: white;
            height: 100vh;
            padding: 20px;
            width: 250px;
            position: fixed;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            background-color: #e9ecef;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header input {
            width: 300px;
        }

        .header .user-info {
            display: flex;
            align-items: center;
        }

        .header .user-info span {
            margin-right: 10px;
        }

        .header .user-info i {
            font-size: 1.5rem;
        }

        .table-container {
            margin-top: 20px;
        }

        .table thead {
            background-color: #1a3e72;
            color: white;
        }

        .table tbody tr td:first-child {
            color: #1a3e72;
        }

        .footer {
            background-color: #1a3e72;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            left: 250px;
        }

        .btn-primary {
            background-color: #1a3e72;
            border: none;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <img alt="Logo" class="img-fluid" height="100" src="https://storage.googleapis.com/a1aa/image/Te8abeel4SIHtoWCGdfQ17Cw3qnrneygfExZ41v0rIB6ECD7E.jpg" width="100" />
        </div>
        <h2>
            Dashboard Sertifikat
        </h2>
        <a href="#">
            Beranda
        </a>
        <h3>
            Manajemen Sertifikat
        </h3>
        <a href="#">
            Buat Sertifikat
        </a>
        <a href="#">
            Daftar Sertifikat
        </a>
        <h3>
            Manajemen Pengguna
        </h3>
        <a href="#">
            Tambah Pengguna
        </a>
        <a href="#">
            Daftar Pengguna
        </a>
        <a href="#">
            Laporan
        </a>
    </div>
    <div class="content">
        <div class="header">
            <input class="form-control" placeholder="Cari Sertif Di Sini" type="text" />
            <div class="user-info">
                <span>
                    Administrator
                </span>
                <i class="fas fa-user-circle">
                </i>
                <i class="fas fa-sign-out-alt ms-3">
                </i>
            </div>
        </div>
        <div class="table-container">
            <h3>
                Daftar Sertifikat
            </h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            Nama Sertifikat
                        </th>
                        <th>
                            Tanggal Penerbitan
                        </th>
                        <th>
                            Jumlah Pengguna
                        </th>
                        <th>
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add rows here -->
                </tbody>
            </table>
        </div>
        <button class="btn btn-primary mt-3">
            Buat Sertifikat
            <i class="fas fa-plus">
            </i>
        </button>
    </div>
    <div class="footer">
        © 2024 Kelompok 1. Semua hak dilindungi.
    </div>
</body>

</html>