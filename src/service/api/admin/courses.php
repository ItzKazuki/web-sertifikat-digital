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

//if (!isset($authorization)) {
//  return apiResponse("error", "Unauthenticated.", code: 404);
//}

//if ($db->checkUser($authorization, 'admin')) {
//  return apiResponse("error", "Unauthorizated.", code: 404);
//}

if ($method !== "POST") {
  $res = $conn->query("SELECT * from courses");
  
  while($row = $res->fetch_array(MYSQLI_ASSOC)) {
    $courses[] = $row;
  }

  return apiResponse("success", "Show all courses", [
    'courses' => $courses
  ]);
}

$type = $requestData['type'] ?? null;

if (!$type) {
  return apiResponse("error", "Type parameter is required.");
}