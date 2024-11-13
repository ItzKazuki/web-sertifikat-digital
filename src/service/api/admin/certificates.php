<?php

session_start();
header("Content-Type: application/json");

include '../../utility.php';
include '../../connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$method = $_SERVER["REQUEST_METHOD"];
$requestData = json_decode(file_get_contents("php://input"), true);
$authorization = getallheaders()['Authorization'] ?? null;
$width = 2000;
$height = 1414;

if (!isset($authorization)) {
  return apiResponse("error", "Unauthenticated.", code: 404);
}

if ($db->checkUser($authorization, 'admin')) {
  return apiResponse("error", "Unauthorizated.", code: 404);
}

if ($method !== "POST") {
  $res = $conn->query("SELECT c.*, u.full_name AS participant_name, e.event_name, e.event_date FROM certificates c JOIN users u ON c.user_id = u.id JOIN courses e ON c.event_id = e.id")->fetch_assoc();

  return apiResponse("success", "Show all certificates", [
    'certificates' => $res
  ]);
}

$type = $requestData['type'] ?? null;

if (!$type) {
  return apiResponse("error", "Type parameter is required.");
}

// check user login is admin or not use auth header

switch ($type) {
  case 'create':
    createCertificate($requestData);
    break;
  case 'edit':
    editCertificate($requestData);
    break;
  case 'delete':
    deleteCertificate($requestData);
    break;
  case 'download':
    downloadCertificate($requestData);
    break;
  default:
    return apiResponse("error", "Invalid type provided.");
    break;
}

$conn->close();

function createCertificate($data)
{
  global $conn, $db;

  $name = htmlspecialchars($data['title']);
  $desc = htmlspecialchars($data['desc']);
  $id_participation = htmlspecialchars($data['id_peserta']);
  $id_courses = htmlspecialchars($data['id_courses']);
  $id_template = htmlspecialchars($data['template']);

  $cert_id = generateRandomString() . "-" . date("Y");

  $createCertificate = "INSERT INTO certificates (user_id, event_id, certificate_code, issued_at, certificate_template_id)
    VALUES ($id_participation, $id_courses, '$cert_id', current_timestamp(), '$id_template')";

  if ($conn->query($createCertificate)) {
    $certificate = $conn->query("SELECT * FROM certificates WHERE certificate_code = '$cert_id'")->fetch_array();
  }

  $certification_image = createParticipantCertificate($cert_id);

  if (is_null($certification_image[0])) {
    return apiResponse("error", $certification_image[1], code: 500);
    exit();
  }

  $createCertificateField = "INSERT INTO certificate_fields (certificate_id, field_name, field_value, file_name)
    VALUES (" . $certificate['id'] . ", '$name', '$desc', '" . $certification_image[0] . "')";

  if ($conn->query($createCertificateField)) {
    // $db->createActivity([$_SESSION['id'], "create", "Success create new certificate with id: $cert_id"]);
    return apiResponse("success", "Certificate created successfully.", [
      'certificate_id' => $cert_id,
      'file_name' => $certification_image[0]
    ]);
  } else {
    return apiResponse("error", "Failed to create certificate fields.");
  }
}

function editCertificate($data)
{
  global $conn, $db;

  $id = htmlspecialchars($data['id']);
  $name = htmlspecialchars($data['title']);
  $desc = htmlspecialchars($data['desc']);
  $id_participation = htmlspecialchars($data['id_peserta']);
  $id_courses = htmlspecialchars($data['id_courses']);
  $id_template = htmlspecialchars($data['template']);

  $getCertDetail = $conn->query("SELECT c.*, cf.file_name FROM certificates c JOIN certificate_fields cf ON c.id = cf.certificate_id WHERE c.id = $id")->fetch_array(MYSQLI_ASSOC);
  $filePath = "../assets/uploads/certificates/" . $getCertDetail['file_name'];

  if (file_exists($filePath)) {
    unlink($filePath);
  }

  $updateCertificates = "UPDATE certificates SET user_id = $id_participation, event_id = $id_courses, certificate_template_id = '$id_template' WHERE id = $id";
  $updateCertificateFields = "UPDATE certificate_fields cf JOIN certificates c ON cf.certificate_id = c.id SET cf.field_name = '$name', cf.field_value = '$desc' WHERE c.id = $id";

  if ($conn->query($updateCertificates) && $conn->query($updateCertificateFields)) {
    $certification_image = createParticipantCertificate($getCertDetail['certificate_code']);
    $updateFileNameQuery = "UPDATE certificate_fields cf JOIN certificates c ON cf.certificate_id = c.id SET cf.file_name = '" . $certification_image[0] . "' WHERE c.id = $id";

    if ($conn->query($updateFileNameQuery)) {
      // $db->createActivity([$_SESSION['id'], "update", "Success edit certificate with id: $id"]);
      return apiResponse("success", "Certificate updated successfully.");
    } else {
      return apiResponse("error", "Failed to update certificate image.");
    }
  } else {
    return apiResponse("error", "Failed to update certificate data.");
  }
}

function deleteCertificate($data)
{
  global $conn, $db;

  $id = htmlspecialchars($data['id']);

  $getCertDetail = $conn->query("SELECT cf.file_name FROM certificates c JOIN certificate_fields cf ON c.id = cf.certificate_id WHERE c.id = $id")->fetch_array(MYSQLI_ASSOC);
  $filePath = "../assets/uploads/certificates/" . $getCertDetail['file_name'];

  if (file_exists($filePath)) {
    unlink($filePath);
  }

  $sql = "DELETE FROM certificates WHERE id = $id";

  if ($conn->query($sql)) {
    // $db->createActivity([$_SESSION['id'], "delete", "Deleted certificate with id: $id"]);
    return apiResponse("success", "Certificate deleted successfully.");
  } else {
    return apiResponse("error", "Failed to delete certificate.");
  }
}

function downloadCertificate($data)
{
  global $conn, $db;

  $sql = "UPDATE certificates SET download_count = download_count + 1 WHERE certificate_code = '" . $data['code'] . "'";

  // if ($_SESSION['id']) {
  //     $db->createActivity([
  //         $_SESSION['id'],
  //         "download",
  //         "Downloaded certificate with code: " . $data['code']
  //     ]);
  // }

  if ($conn->query($sql)) {
    downloadCertificateAction($data['file_name']);
    return apiResponse("message", "Certificate download initiated.");
  } else {
    return apiResponse("error", "Failed to initiate certificate download.");
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
