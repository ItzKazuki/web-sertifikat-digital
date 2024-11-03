<?php

// how this code work?

/**
 * See how this code work
 * 
 * 1. add this file to your form in action, don'y forget to set method to post
 * 2. set submit button with name="type" and value="purpose" ex: value="login"
 * 3. 
 */

session_start();

include 'utility.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  header('Location: ../index.php');
}

include 'connection.php';

// now you can access $conn from connection.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST['type'];

  switch ($type) {
    case 'create':
      createTemplate();
      $conn->close();
      break;
    case 'edit':
      editTemplate();
      $conn->close();
      break;
    case 'delete':
      deleteTemplate();
      $conn->close();
      break;
    default:
      header('Location: ../index.php');
      break;
  }
}

function createTemplate() {
  global $conn;
}

function editTemplate() {
  global $conn;
}

function deleteTemplate() {
  global $conn;
}

function upload(string $name) {
  $file = $_FILES[$name];

  $fileName = $file['name'];
  $fileSize = $file['size'];
  $fileErr = $file['error'];
  $fileTmp = $file['tmp_name'];

  if($fileErr === 4) {
    $_SESSION['error'] = "harus masukan gambar";
    return false;
  }

  $validExstension = ['jpg', 'jpeg', 'png'];
  $fileExstension = explode('.', $fileName);
  $fileExstension = end($fileExstension);
  $fileExstension = strtolower($fileExstension);

  if(!in_array($fileExstension, $validExstension)) {
    $_SESSION['error'] = "gambar tidak valid";
    return false;
  }

  $fileName = date('m-d-Y', time()) . "-" . $fileName;
  // upload gambar
  move_uploaded_file($fileTmp, "../assets/uploads/templates" . $fileName);

  return $fileName;
}