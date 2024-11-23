<?php
session_start();

include 'utility.php';
include 'send.php';

(new DotEnvEnvironment)->load(__DIR__ . '/../../');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  header('Location: ../index.php');
}

$mail = new MailSender();

include 'connection.php';

// now you can access $conn from connection.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST['type'];

  switch ($type) {
    case 'login':
      login();
      $conn->close();
      break;
    case 'logout':
      logout();
      $conn->close();
      break;
    case 'register':
      register();
      $conn->close();
      break;
    case 'find_email':
      find_email();
      $conn->close();
      break;
    case 'edit_password':
      edit_password();
      $conn->close();
      break;
    case 'setup_account':
      setupAccount();
      $conn->close();
      break;
    default:
      header('Location: ../index.php');
      break;
  }
}

function edit_password()
{
  global $conn, $db;

  $reset = htmlspecialchars($_POST['reset']);

  if (!isset($reset)) {
    return redirect("auth/forgot.php", "Something error, please try again from start.", "error");
  }

  $sql = "SELECT * FROM reset_password WHERE reset_token = '$reset'";

  $res = $conn->query($sql)->fetch_array();

  $email = $res['email'];

  if (!isset($res)) {
    return redirect("auth/forgot.php", "Don't do that bro!", "error");
  }

  // $oldPassword = htmlspecialchars($_POST['old_password']);
  $newPassword = htmlspecialchars($_POST['new_password']);
  $confirmNewPassword = htmlspecialchars($_POST['confirm_new_password']);

  if ($newPassword !== $confirmNewPassword) {
    return redirect('auth/forgot.php?email=' . $res['reset_token']);
  }

  //hash new password
  $salt = generateSalt();
  $hashNewPassword = generateHashWithSalt($newPassword, $salt);

  if ($conn->query("UPDATE users SET password = '$salt;$hashNewPassword' WHERE email = '$email'") && $conn->query("DELETE FROM reset_password WHERE reset_token = '" . $res['reset_token'] . "'")) {
    return redirect("auth/login.php", "Berhasil mengubah password, silahkan login!");
  }

  return redirect("auth/forgot.php", "Failed while reset your password.", "error");
}

function find_email()
{
  global $conn, $mail;
  $email = htmlspecialchars($_POST['email']);

  $sql = "SELECT * FROM users WHERE email = '$email'";

  $res = $conn->query($sql);

  if ($res->num_rows > 0) {
    // add record reset_password

    $reset = bin2hex(random_bytes(40));
    $userDetail = $res->fetch_array();
    $email = $userDetail['email']; // get the email
    $full_name = $userDetail['full_name']; // get the email

    // create new reset token
    $sql = "INSERT INTO reset_password (`reset_token`, `email`) VALUES('$reset', '$email')";

    if ($conn->query($sql)) {
      // send email here
      $content = array();
      $content['user_name'] = $full_name;
      $content['reset_url'] = $_ENV['APP_URL'] . "/auth/change.php?reset=" . $reset;

      if (!$mail->sendMail($email, $full_name, 'Reset Your Password', $content, MailSender::$resetPassword)) {
        return redirect('auth/login.php', "Failed to send reset email token. please try again later.");
      }
    }

    return redirect('auth/login.php', "Silahkan lihat email anda");
  } else {
    return redirect("auth/forgot.php", "Username atau password tidak di temukan.", "error");
  }
}

function login()
{
  global $conn, $db;
  // get email and password
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

  $sql = "SELECT * FROM users WHERE email = '$email'";

  $res = $conn->query($sql)->fetch_array();

  // get salt
  $salt = explode(";", $res['password'])[0];
  $hashPassword = explode(";", $res['password'])[1];

  $currentHashPassword = generateHashWithSalt($password, $salt);

  if ($currentHashPassword !== $hashPassword) {
    return redirect("auth/login.php", "email atau password tidak di temukan.", "error");
  }

  if ($res != null) {
    $db->createActivity([$res['id'], 'login', "Create new session with id: {$res['id']}"]);
    // set 1st user to admin
    if ($res['id'] == 1 && $res['role'] != 'admin') {
      $conn->query("UPDATE users SET role = 'admin' WHERE email = '$email'");
    }

    $_SESSION['email'] = $res['email'];
    $_SESSION['full_name'] = $res['full_name'];
    $_SESSION['role'] = $res['role'];
    $_SESSION['is_auth'] = true;
    $_SESSION['id'] = $res['id'];

    // $_SESSION['success'] = "Berhasil Login";
    // header('Location: ../dashboard.php');
    // exit();
    if ($_SESSION['role'] == 'admin') {
      return redirect("dashboard", "Berhasil Login");
    }

    return redirect("akun.php", "Berhasil Login");
  } else {
    return redirect("auth/login.php", "email atau password tidak di temukan.", "error");
  }
}

function logout()
{
  global $conn, $db;
  // session_start();
  $db->createActivity([$_SESSION['id'], "logout", "Success logout with id: " . $_SESSION['id']]);
  session_destroy();
  session_start();

  return redirect("index.php", "Berhasil logout", "success");
}

function register()
{
  global $conn, $mail;

  // get all user input
  $nik = htmlspecialchars($_POST['nik']);
  $f_name = htmlspecialchars($_POST['f_name']);
  $phone_number = htmlspecialchars($_POST['phone_number']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $c_password = htmlspecialchars($_POST['c_password']);

  if ($password !== $c_password) {
    return redirect("auth/register.php", "Password yang dimasukan harus sama", 'error');
  }

  // insert hash password like this
  // "salt;hash" ex: eb74a563c05dcb66b3f54e26fdfc39dd;197f1c1a6124171a77e28c7e2539c06c6c4c6852e63181030516495e2f049d99
  $salt = generateSalt();
  $hashPassword = generateHashWithSalt($password, $salt);

  $avatar = get_gravatar($email);

  // add data to database
  $user = $conn->query("SELECT * FROM users WHERE nik = '$nik' OR email = '$email'");

  if ($user->num_rows > 0) {
    return redirect("auth/register.php", "email sudah digunakan", 'error');
  } else {
    $sql = "INSERT INTO users (nik, full_name, email, phone_number, password, role, created_at) VALUES ('$nik', '$f_name', '$email', '$phone_number', '$salt;$hashPassword', 'participant', current_timestamp())";

    if ($conn->query($sql)) {
      // send email here
      $content = array();
      $content['user_name'] = $f_name;
      $content['login_url'] = $_ENV['APP_URL'];

      if (!$mail->sendMail($email, $f_name, 'Welcome To Digicert', $content, MailSender::$successRegister)) {
        return redirect('auth/login.php', "Failed to send reset email token. please try again later.");
      }
      // sendMail($email, $f_name, , "Welcome To Digicert, you ccan access account now, please contact administrator when something wrong!");
    }
    return redirect("auth/login.php", "berhasil membuat akun baru");
  }
}

function setupAccount()
{
  global $conn, $db, $mail;

  $token = htmlspecialchars($_POST['token']);

  if (!isset($token)) {
    return redirect("auth/forgot.php", "Something error, please try again from start.", "error");
  }

  $sql = "SELECT * FROM reset_password WHERE reset_token = '$token'";

  $res = $conn->query($sql)->fetch_array();

  $email = $res['email'];

  if (!isset($res)) {
    return redirect("auth/forgot.php", "Don't do that bro!", "error");
  }

  // $oldPassword = htmlspecialchars($_POST['old_password']);
  $newPassword = htmlspecialchars($_POST['new_password']);
  $confirmNewPassword = htmlspecialchars($_POST['confirm_new_password']);
  $f_name = htmlspecialchars($_POST['f_name']);
  $phone_number = htmlspecialchars($_POST['phone_number']);

  if ($newPassword !== $confirmNewPassword) {
    return redirect('auth/forgot.php?email=' . $res['email'], "error", "error");
  }

  //hash new password
  $salt = generateSalt();
  $hashNewPassword = generateHashWithSalt($newPassword, $salt);

  if ($conn->query("UPDATE users SET full_name = '$f_name', phone_number = '$phone_number', password = '$salt;$hashNewPassword' WHERE email = '$email'") && $conn->query("DELETE FROM reset_password WHERE reset_token = '" . $res['reset_token'] . "'")) {
    return redirect("auth/login.php", "Berhasil mengubah data, silahkan login!");
  }

  return redirect("auth/forgot.php", "Failed while reset your password.", "error");
}

function generateSalt($length = 16)
{
  // Menghasilkan salt acak dengan panjang tertentu
  return bin2hex(random_bytes($length));
}

function generateHashWithSalt($password, $salt)
{
  // Menggabungkan password dengan salt dan menghasilkan hash SHA-256
  return hash('sha256', $salt . $password);
}
