<?php
session_start();

include '../service/connection.php';
include '../service/utility.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['is_auth'])) {
    return redirect("index.php");
}

if ($_SESSION['role'] != "admin") {
    return redirect("index.php");
}

// Initialize query and filter variables
$whereClauses = [];
$parameters = [];

// Process filter form input if submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the form inputs are set and not empty
    if (!empty($_GET['type_activity'])) {
        $whereClauses[] = "r.type_activity = ?";
        $parameters[] = $_GET['type_activity'];
    }
    if (!empty($_GET['user_name'])) {
        $whereClauses[] = "u.full_name LIKE ?";
        $parameters[] = "%" . $_GET['user_name'] . "%";
    }
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $whereClauses[] = "r.created_at BETWEEN ? AND ?";
        $parameters[] = date("Y-m-d H:i:s", strtotime($_GET['start_date']));
        $parameters[] = date("Y-m-d 23:59:59", strtotime($_GET['end_date']));
    }
}


// Build SQL query with filters
$sql = "SELECT r.*, u.full_name, u.role
        FROM reports r 
        JOIN users u ON r.user_id = u.id";
if ($whereClauses) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}
$sql .= " ORDER BY r.created_at ASC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($parameters) {
    // Dynamically bind parameters
    $stmt->bind_param(str_repeat("s", count($parameters)), ...$parameters);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch reports
$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

function changeBgTable(string $condition)
{
    switch ($condition) {
        case 'delete':
            return 'table-danger';
            break;
        case 'update':
            return 'table-warning';
            break;
        case 'create':
            return 'table-primary';
            break;
        case 'login':
            return 'table-info';
            break;
        case 'logout':
            return 'table-secondary';
            break;
        case 'download':
            return 'table-success';
            break;
        default:
            return '';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="sidebar">
        <div class="text-center my-3">
            <img src="../assets/logo.png" alt="Logo" style="max-width: 80px;">
            <h4>Dashboard Sertifikat</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="index.php" class="nav-link">Beranda</a></li>
            <!-- Manajemen Sertifikat Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#sertifikatMenu" role="button" aria-expanded="false" aria-controls="sertifikatMenu">Manajemen Sertifikat</a>
                <div class="collapse" id="sertifikatMenu">
                    <a href="certificate/index.php" class="dropdown-item">List Sertifikat</a>
                    <a href="certificate/create.php" class="dropdown-item">Buat Sertifikat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#pelatihanMenu" role="button" aria-expanded="false" aria-controls="pelatihanMenu">Manajemen Pelatihan</a>
                <div class="collapse" id="pelatihanMenu">
                    <a href="courses/index.php" class="dropdown-item">List Pelatihan</a>
                    <a href="courses/create.php" class="dropdown-item">Tambah Pelatihan</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#templateSertifikat" role="button" aria-expanded="false" aria-controls="templateSertifikat">Manajemen Template Sertifikat</a>
                <div class="collapse" id="templateSertifikat">
                    <a href="certificate-template/" class="dropdown-item">List Template</a>
                    <a href="certificate-template/create.php" class="dropdown-item">Tambah Template</a>
                </div>
            </li>
            <!-- Manajemen Pengguna Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#penggunaMenu" role="button" aria-expanded="false" aria-controls="penggunaMenu">Manajemen Pengguna</a>
                <div class="collapse" id="penggunaMenu">
                    <a href="users/" class="dropdown-item">List Pengguna</a>
                    <a href="users/create.php" class="dropdown-item">Tambah Pengguna</a>
                </div>
            </li>
            <li class="nav-item"><a href="reports.php" class="nav-link">Laporan</a></li>
        </ul>
    </div>

    <div class="content flex-grow-1">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Laporan</h1>
                <div class="d-flex justify-content-end align-items-center p-3">
                    <span><?= $_SESSION['full_name'] ?></span>
                    <div class="dropdown">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="bi bi-person-circle ms-2 dropdown-toggle" style="font-size: 1.5em;"></a> <!-- Tambahkan ikon akun di sini -->
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../index.php">Landing Page</a></li>
                            <li><a class="dropdown-item" href="../akun.php">Homepage</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form class="dropdown-item" action="../service/auth.php" method="post">
                                    <button type="submit" name="type" value="logout" style="background-color: transparent; border: none; width:100%; text-align:justify; ">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <form method="GET" class="row g-3 mt-3 mb-4">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="user_name" placeholder="Nama Pengguna" value="<?= htmlspecialchars($_GET['user_name'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="type_activity" class="form-control">
                        <option value="">Pilih Tipe Aktivitas</option>
                        <option value="create" <?= (isset($_GET['type_activity']) && $_GET['type_activity'] === 'create') ? 'selected' : '' ?>>Create</option>
                        <option value="update" <?= (isset($_GET['type_activity']) && $_GET['type_activity'] === 'update') ? 'selected' : '' ?>>Update</option>
                        <option value="delete" <?= (isset($_GET['type_activity']) && $_GET['type_activity'] === 'delete') ? 'selected' : '' ?>>Delete</option>
                        <option value="login" <?= (isset($_GET['type_activity']) && $_GET['type_activity'] === 'login') ? 'selected' : '' ?>>Login</option>
                        <option value="logout" <?= (isset($_GET['type_activity']) && $_GET['type_activity'] === 'logout') ? 'selected' : '' ?>>Logout</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <?php if (!empty($reports)) : ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Role</th>
                            <th>Tipe Aktivitas</th>
                            <th>Info</th>
                            <th>Dibuat pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $key => $report) : ?>
                            <tr class="<?= changeBgTable(htmlspecialchars($report['type_activity'])) ?>">
                                <td><?= $key + 1 ?></td>
                                <td><?= htmlspecialchars($report['full_name']) ?></td>
                                <td><span class="badge <?= $report['role'] == 'admin' ? 'bg-danger' : 'bg-success' ?>"><?= $report['role'] ?></span></td>
                                <td><?= htmlspecialchars($report['type_activity']) ?></td>
                                <td><?= htmlspecialchars($report['info']) ?></td>
                                <td><?= htmlspecialchars($report['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form method="GET" class="row g-3 mt-3 mb-4 ">
                    <div class="col-md-2 ml-auto">
                        <button type="submit" class="btn btn-primary">Download Laporan</button>
                    </div>
                </form>
            <?php else : ?>
                <strong>Tidak ada laporan yang ditemukan.</strong>
            <?php endif; ?>
        </div>


        <!-- Bootstrap JS -->
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>