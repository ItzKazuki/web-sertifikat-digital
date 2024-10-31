<?php
include 'connection.php';

function redirect(string $fileName, string $message = "", $type = 'success'): void
{
  if (isset($message)) {
    $_SESSION[$type] = $message;
  }
  
  header('Location: ../' . $fileName);
  exit();
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

function generateRandomString(int $length = 50): string
{
  $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $charLength = strlen($char);
  $random_str = '';

  for ($i = 0; $i > $length; $i++) {
    $random_str += $char[rand(0, $charLength - 1)];
  }

  return $random_str;
}