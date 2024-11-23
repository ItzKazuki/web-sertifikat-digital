<?php

include 'connection.php';
require('fpdf186/fpdf.php');

function apiResponse(string $status, string $message, array $data = [], $code = 200)
{
  $res = [
    'status' => $status,
    'code' => $code,
    'message' => $message,
    // 'data' => $data
  ];

  if ($status == "error") {
    $res['code'] = 400;
  }

  if (!empty($data)) {
    $res['data'] = $data;
  }

  echo json_encode($res);
  exit();
}

function redirect(string $fileName, string $message = "", string $type = 'success'): void
{
  if (isset($message)) {
    $_SESSION[$type] = $message;
  }

  header('Location: ../' . $fileName);
  exit();
}

function downloadCertificateAction($file_name)
{
  if (!is_file("../assets/uploads/certificates/" . $file_name)) {
    return redirect("src/index.php", "Certificate not found, please contact Administrator", "error");
  }

  $fileName = explode('.', $file_name);

  $pdf = new FPDF();
  $pdf->AddPage("L", "A5");

  $pdf->Image("../assets/uploads/certificates/" . $file_name, 0, 0, 210, 148);
  $pdf->Output($fileName[0] . ".pdf", 'D');
}

function base_url()
{
  // Determine the protocol
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";

  // Get the host name
  $host = $_SERVER['HTTP_HOST'];

  // Get the base directory
  $baseDir = dirname($_SERVER['SCRIPT_NAME']);

  // Combine to form the base URL
  $baseUrl = $protocol . $host . $baseDir;

  // Return the base URL
  return rtrim($baseUrl, '/'); // Remove trailing slash if necessary
}

function slugify($string)
{
  // Convert to lowercase
  $string = strtolower($string);

  // Remove special characters
  $string = preg_replace('/[^a-z0-9\s-]/', '', $string);

  // Replace spaces and multiple hyphens with a single hyphen
  $string = preg_replace('/[\s-]+/', '-', $string);

  // Trim hyphens from the beginning and end
  $string = trim($string, '-');

  return $string;
}

function debug($var)
{
  print_r($var);
  die;
}

function hummanDate($inputDate)
{
  return date("j F Y", strtotime($inputDate));
}

function get_gravatar(
  $email,
  $size = 64,
  $default_image_type = 'mp',
  $force_default = false,
  $rating = 'g',
  $return_image = false,
  $html_tag_attributes = []
) {
  // Prepare parameters.
  $params = [
    's' => htmlentities($size),
    'd' => htmlentities($default_image_type),
    'r' => htmlentities($rating),
  ];
  if ($force_default) {
    $params['f'] = 'y';
  }

  // Generate url.
  $base_url = 'https://www.gravatar.com/avatar';
  $hash = hash('sha256', strtolower(trim($email)));
  $query = http_build_query($params);
  $url = sprintf('%s/%s?%s', $base_url, $hash, $query);

  // Return image tag if necessary.
  if ($return_image) {
    $attributes = '';
    foreach ($html_tag_attributes as $key => $value) {
      $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
      $attributes .= sprintf('%s="%s" ', $key, $value);
    }

    return sprintf('<img src="%s" %s/>', $url, $attributes);
  }

  return $url;
}

function generateRandomString($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';

  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[random_int(0, $charactersLength - 1)];
  }

  return $randomString;
}
