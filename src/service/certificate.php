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

// print_r($_POST); die;

// now you can access $conn from connection.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST['type'];

  switch ($type) {
    case 'create':
      createCertificate();
      $conn->close();
      break;
    default:
      header('Location: ../index.php');
      break;
  }
}

function createCertificate()
{
  global $conn;

  // get all user input
  $name = htmlspecialchars($_POST['title']);
  $desc = htmlspecialchars($_POST['desc']);
  $id_participation = htmlspecialchars($_POST['id_peserta']);
  $id_courses = htmlspecialchars($_POST['id_courses']);
  $template = htmlspecialchars($_POST['template']);

  $cert_id = generateRandomString() . "-" . date("Y");

  // echo $cert_id; die;

  // $sql = "INSERT INTO courses (event_name, event_description, event_date, organizer, created_at) VALUES ('$name', '$desc', '$course_date', '$organizer', current_timestamp())";
  $createCertificate = "INSERT INTO certificates (user_id, event_id, certificate_code, issued_at, certificate_template)
VALUES ($id_participation, $id_courses, '$cert_id', current_timestamp(), '$template')";

  if ($conn->query($createCertificate)) {
    $certificate = $conn->query("SELECT * FROM certificates WHERE certificate_code = '$cert_id' ")->fetch_array();
  }

  $createCertificateField = "INSERT INTO certificate_fields (certificate_id, field_name, field_value)
VALUES (" . $certificate['id'] . ", '$name', '$desc')";

  if ($conn->query($createCertificateField)) {
    return redirect("dashboard/certificate", "berhasil membuat pelatihan baru");
  }
}
