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

// error_reporting(E_ALL);
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  header('Location: ../index.php');
}

include 'connection.php';

$width = 2000;
$height = 1414;

// now you can access $conn from connection.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST['type'];

  switch ($type) {
    case 'create':
      createCertificate();
      $conn->close();
      break;
    case 'edit':
      editCertificate();
      $conn->close();
      break;
    case 'delete':
      deleteCertificate();
      $conn->close();
      break;
    case 'download':
      downloadCertificate();
      $conn->close();
      break;
    default:
      header('Location: ../index.php');
      break;
  }
}

function downloadCertificate()
{
  global $conn, $db;

  $sql = "UPDATE certificates SET download_count = download_count + 1 WHERE certificate_code = '" . $_POST['code'] . "'";

  // cek apakah ud login
  if ($_SESSION['id']) {
    $db->createActivity([
      $_SESSION['id'],
      "download",
      "Success download certificate with code: " . $_POST['code'] . " with user id: " . $_SESSION['id']
    ]);
  }

  if ($conn->query($sql)) {
    downloadCertificateAction($_POST['file_name']);
  } else {
    return redirect("src/index.php");
  }
}

function editCertificate()
{
  global $conn, $db;

  // get all user input
  $id = htmlspecialchars($_POST['id']);
  $name = htmlspecialchars($_POST['title']);
  $desc = htmlspecialchars($_POST['desc']);
  $id_participation = htmlspecialchars($_POST['id_peserta']);
  $id_courses = htmlspecialchars($_POST['id_courses']);
  $id_template = htmlspecialchars($_POST['template']);

  // hapus gambar sebelumnya
  $getCertDetail = $conn->query("SELECT c.*, cf.file_name, cf.field_name, cf.field_value FROM certificates c JOIN certificate_fields cf ON c.id = cf.certificate_id WHERE c.id = $id")->fetch_array(MYSQLI_ASSOC);
  $filePath = "../assets/uploads/certificates/" . $getCertDetail['file_name'];

  if (file_exists($filePath)) {
    if (!unlink($filePath)) {
      return redirect("dashboard/certificate", "Error saat menghapus gambar, silahkan update ulang", 'error');
    }
  }

  // update ke database
  $updateCertificates = "UPDATE certificates SET user_id = $id_participation, event_id = $id_courses, certificate_template_id = '$id_template' WHERE id = $id";

  if (!$conn->query($updateCertificates)) {
    return redirect("dashboard/certificate", "Error saat mengubah data, silahkan update ulang", 'error');
  }

  $updateCertificateFields = "UPDATE certificate_fields cf JOIN certificates c ON cf.certificate_id = c.id SET cf.field_name = '$name', cf.field_value = '$desc' WHERE c.id = $id";

  if (!$conn->query($updateCertificateFields)) {
    return redirect("dashboard/certificate", "Error saat mengubah data, silahkan update ulang", 'error');
  }

  // buat gambar baru
  $certification_image = createParticipantCertificate($getCertDetail['certificate_code']);

  if (is_null($certification_image[0])) {
    return redirect("dashboard/certificate", $certification_image[1], "error");
  }

  $updateFileNameQuery = "UPDATE certificate_fields cf JOIN certificates c ON cf.certificate_id = c.id SET cf.file_name = '" . $certification_image[0] . "' WHERE c.id = " . $getCertDetail['id'];

  // update file_name di certificate_field table
  if ($conn->query($updateFileNameQuery)) {
    $db->createActivity([$_SESSION['id'], "update", "Success edit certificate with id: $id"]);
    return redirect("dashboard/certificate", "Berhasil mengubah sertifikat");
  } else {
    return redirect("dashboard/certificate", "Gagal mengubah sertifikat", "error");
  }
  // selesai.
}

function deleteCertificate()
{
  global $conn, $db;

  $id = htmlspecialchars($_POST['id']);

  if (!isset($id)) {
    return redirect("dashboard/certificate/", "Sertifikat tidak ditemukan", "error");
  }

  // hapus gambar sebelumnya
  $getCertDetail = $conn->query("SELECT c.*, cf.file_name, cf.field_name, cf.field_value FROM certificates c JOIN certificate_fields cf ON c.id = cf.certificate_id WHERE c.id = $id")->fetch_array(MYSQLI_ASSOC);
  $filePath = "../assets/uploads/certificates/" . $getCertDetail['file_name'];

  if (file_exists($filePath)) {
    if (!unlink($filePath)) {
      return redirect("dashboard/certificate", "Error saat menghapus gambar, silahkan update ulang", 'error');
    }
  }

  $sql = "DELETE FROM certificates WHERE id = $id";

  if ($conn->query($sql) == 1) {
    $db->createActivity([$_SESSION['id'], "delete", "Success delete certificate with id: $id"]);
    return redirect("dashboard/certificate/", "Berhasil menghapus sertifikat dengan id: $id");
  } else {
    return redirect("dashboard/certificate", "gagal menghapus sertifikat", "error");
  }
}

function createCertificate()
{
  global $conn, $db;

  // get all user input
  $name = htmlspecialchars($_POST['title']);
  $desc = htmlspecialchars($_POST['desc']);
  $id_participation = htmlspecialchars($_POST['id_peserta']);
  $id_courses = htmlspecialchars($_POST['id_courses']);
  $id_template = htmlspecialchars($_POST['template']);

  $cert_id = generateRandomString() . "-" . date("Y");

  // $sql = "INSERT INTO courses (event_name, event_description, event_date, organizer, created_at) VALUES ('$name', '$desc', '$course_date', '$organizer', current_timestamp())";
  $createCertificate = "INSERT INTO certificates (user_id, event_id, certificate_code, issued_at, certificate_template_id)
VALUES ($id_participation, $id_courses, '$cert_id', current_timestamp(), '$id_template')";

  if ($conn->query($createCertificate)) {
    $certificate = $conn->query("SELECT * FROM certificates WHERE certificate_code = '$cert_id' ")->fetch_array();
  }

  $certification_image = createParticipantCertificate($cert_id);

  if (is_null($certification_image[0])) {
    return redirect("dashboard/certificate", $certification_image[1], "error");
  }

  $createCertificateField = "INSERT INTO certificate_fields (certificate_id, field_name, field_value, file_name)
VALUES (" . $certificate['id'] . ", '$name', '$desc', '" . $certification_image[0] . "')";

  if ($conn->query($createCertificateField)) {
    // createActivity($conn, );
    $db->createActivity([$_SESSION['id'], "create", "Success create new certificate with id: $cert_id"]);
    return redirect("dashboard/certificate", "berhasil membuat sertifikat baru");
  }
}

function createParticipantCertificate($cert_id)
{
  global $conn, $db;

  try {
    // get user details
    $getCert = $conn->query("SELECT c.*, u.full_name, e.event_name, e.organizer, e.event_date, ct.file_name AS template_file_name, ct.font_name, ct.font_file
  FROM certificates c
  JOIN users u ON c.user_id = u.id 
  JOIN courses e ON c.event_id = e.id
  JOIN certificate_templates ct ON c.certificate_template_id = ct.id
  WHERE c.certificate_code = '$cert_id'")->fetch_assoc();

    $fontBold = "../assets/font/" . $getCert['font_name'] . "/bold.ttf";
    $fontParticipant = "../assets/font/" . $getCert['font_name'] . "/" . $getCert['font_file'];
    $font = "../assets/font/" . $getCert['font_name'] . "/light.ttf";

    $time = time();

    $img = imagecreatefrompng("../assets/uploads/templates/" . $getCert['template_file_name']);
    $color = imagecolorallocate($img, 19, 21, 22);

    $firstLineText = "Untuk menyelesaikan pelatihan " . $getCert['event_name'] . " yang";
    $secondLineText = "diselenggarakan oleh " . $getCert['organizer'] . " pada " . hummanDate($getCert['event_date']);

    $participantName = ucwords($getCert['full_name']);

    $certificateIdCenter = calculateTextCenter($getCert['certificate_code'], $fontBold, 25);
    $participantCenterName = calculateTextCenter($participantName, $fontParticipant, 60);
    $firstLineTextCenter = calculateTextCenter($firstLineText, $font, 29);
    $secondLineTextCenter = calculateTextCenter($secondLineText, $font, 29);
    $organizationCenter = calculateHalfWidthTextCenter($getCert['organizer'], $fontBold, 30);

    imagettftext($img, 25, 0, $certificateIdCenter[0], $certificateIdCenter[1] + 600, $color, $fontBold, $getCert['certificate_code']);
    imagettftext($img, 60, 0, $participantCenterName[0], $participantCenterName[1], $color, $fontParticipant, $participantName);
    imagettftext($img, 29, 0, $firstLineTextCenter[0], $firstLineTextCenter[1] + 100, $color, $font, $firstLineText);
    imagettftext($img, 29, 0, $secondLineTextCenter[0], $secondLineTextCenter[1] + 150, $color, $font, $secondLineText);
    imagettftext($img, 30, 0, $organizationCenter[0] + 30, 1130, $color, $fontBold, $getCert['organizer']);
    imagettftext($img, 30, 0, 327.5 * 3.5 + 60, 1130, $color, $fontBold, "Drs. Lambas Pakpahan,MM"); // dont change this!

    // create new file name
    $fileName = "certificates-$time-" . $getCert['certificate_code'] . ".png";
    $filePath = "../assets/uploads/certificates/$fileName"; // set path file

    if (!is_dir("../assets/uploads/certificates")) {
      mkdir("../assets/uploads/certificates", 0777);
    }

    // upload to path
    imagepng($img, $filePath);
    // change owner and group
    chown($filePath, 'www-data');
    chgrp($filePath, 'www-data');

    // remove 
    imagedestroy($img);

    // return file name
    return [$fileName, null];
  } catch (Exception $e) {
    return [null, $e->getMessage()];
  }
}

function calculateTextCenter($text, $typeFont, $fontSize)
{
  global $height, $width;

  // Define the font size and path to the TTF font file
  // $fontSize = 60;

  $bbox = imagettfbbox($fontSize, 0, $typeFont, $text);

  // Calculate the width of the text
  $textWidth = abs($bbox[2] - $bbox[0]);

  // Calculate the x-coordinate to center the text
  $x = ($width - $textWidth) / 2;

  // Set the y-coordinate (you can adjust this value)
  $y = ($height / 2) + ($fontSize / 2);

  return [$x, $y];
}

function calculateHalfWidthTextCenter($text, $typeFont, $fontSize)
{
  global $height, $width;

  // Define the font size and path to the TTF font file
  // $fontSize = 60;

  $width = $width / 2;

  $bbox = imagettfbbox($fontSize, 0, $typeFont, $text);

  // Calculate the width of the text
  $textWidth = abs($bbox[2] - $bbox[0]);

  // Calculate the x-coordinate to center the text
  $x = ($width - $textWidth) / 2;

  // Set the y-coordinate (you can adjust this value)
  $y = ($height / 2) + ($fontSize / 4);

  return [$x, $y];
}
