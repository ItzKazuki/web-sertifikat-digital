<?php

header("Content-Type: application/json");

$requestBody = file_get_contents("php://input");

// Mendekode JSON menjadi array PHP
$dataBody = json_decode($requestBody, true);

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../utility.php'; // pastikan utility.php berisi fungsi yang dibutuhkan
include '../connection.php'; // pastikan connection.php berisi koneksi ke database

if($_SERVER['REQUEST_METHOD'] == "GET") {
    return apiResponse('error', 'Method not allowed', code: 405);
}

// Cek apakah request menggunakan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    return handleRequest(); // Outputkan response sebagai JSON
}

// Fungsi untuk menangani request
function handleRequest()
{
    global $dataBody;
    if (!isset($dataBody['type'])) {
        return apiResponse("error", "Request type is missing");
    }

    $type = $dataBody['type'];

    switch ($type) {
        case 'login':
            return login();
        case 'logout':
            return logout();
        case 'register':
            return register();
        case 'find_email':
            return find_email();
        case 'reset_password':
            return reset_password();
        default:
            return apiResponse("error", "Unknown action type");
    }
}

// Fungsi login
function login()
{
    global $conn, $dataBody;
    $email = $dataBody['email'];
    $password = $dataBody['password'];

    // Cek apakah email ada di database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = $conn->query($sql)->fetch_assoc();

    if (!$res) {
        return apiResponse("error", "Email or password not found.");
    }

    // Verifikasi password
    $salt = explode(";", $res['password'])[0];
    $hashPassword = explode(";", $res['password'])[1];
    $currentHashPassword = generateHashWithSalt($password, $salt);

    if ($currentHashPassword !== $hashPassword) {
        return apiResponse("error", "Invalid password.");
    }

    return apiResponse("success", "Login successful", [
        'user' => $res
    ]);
}

// Fungsi logout
function logout()
{
    return apiResponse("success", "Logout successful");
}

// Fungsi register
function register()
{
    global $conn, $dataBody;

    $nik = $dataBody['nik'];
    $f_name = $dataBody['f_name'];
    $phone_number = $dataBody['phone_number'];
    $email = $dataBody['email'];
    $password = $dataBody['password'];
    $c_password = $dataBody['c_password'];

    // Validasi input
    if ($password !== $c_password) {
        return apiResponse("error", "Passwords do not match.");
    }

    // Generate salt and hashed password
    $salt = generateSalt();
    $hashPassword = generateHashWithSalt($password, $salt);

    // Check if email already exists
    $user = $conn->query("SELECT * FROM users WHERE nik = '$nik' OR email = '$email'");
    if ($user->num_rows > 0) {
        return apiResponse("error", "Email or NIK already used.");
    }

    // Insert user into database
    $sql = "INSERT INTO users (nik, full_name, email, phone_number, password, role, created_at) 
            VALUES ('$nik', '$f_name', '$email', '$phone_number', '$salt;$hashPassword', 'participant', current_timestamp())";

    if ($conn->query($sql)) {
        return apiResponse("success", "Registration successful");
    } else {
        return apiResponse("error", "Failed to register user.");
    }
}

// Fungsi untuk mencari email
function find_email()
{
    global $conn, $dataBody;
    $email = $dataBody['email'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        // Email ditemukan, buat token reset
        $reset = bin2hex(random_bytes(40));
        $email = $res->fetch_array()['email'];

        // Simpan token reset di database
        $sql = "INSERT INTO reset_password (`reset_token`, `email`) VALUES('$reset', '$email')";
        $conn->query($sql);

        return apiResponse("success", "Reset link generated", [
            'reset_token' => $reset
        ]);
    } else {
        return apiResponse("error", "Email not found.");
    }
}

// Fungsi untuk edit password
function reset_password()
{
    global $conn, $dataBody;

    $reset = $dataBody['reset_code'];
    $newPassword = $dataBody['new_password'];
    $confirmNewPassword = $dataBody['confirm_new_password'];

    if (!isset($reset)) {
        return apiResponse("error", "Reset token missing.");
    }

    $sql = "SELECT * FROM reset_password WHERE reset_token = '$reset'";
    $res = $conn->query($sql)->fetch_array();

    if (!$res) {
        return apiResponse("error", "Invalid reset token.");
    }

    if ($newPassword !== $confirmNewPassword) {
        return apiResponse("error", "Passwords do not match.");
    }

    $salt = generateSalt();
    $hashNewPassword = generateHashWithSalt($newPassword, $salt);

    if ($conn->query("UPDATE users SET password = '$salt;$hashNewPassword' WHERE email = '{$res['email']}'")) {
        $conn->query("DELETE FROM reset_password WHERE reset_token = '$reset'");
        return apiResponse("success", "Password successfully changed.");
    }

    return apiResponse("error", "Failed to change password.");
}

// Fungsi untuk generate salt
function generateSalt($length = 16)
{
    return bin2hex(random_bytes($length));
}

// Fungsi untuk generate hash dengan salt
function generateHashWithSalt($password, $salt)
{
    return hash('sha256', $salt . $password);
}
