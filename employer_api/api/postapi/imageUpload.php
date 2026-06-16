<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
  'https://jaldihire.com',
  'https://admin.jaldihire.com',
  'https://employer.jaldihire.com',
  'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
  header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
  'https://jaldihire.com',
  'https://admin.jaldihire.com',
  'https://employer.jaldihire.com',
  'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
  header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit();
}

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
  $fileName = uniqid() . '_' . basename($_FILES['file']['name']); // Safer, prevents overwrite

  // Absolute server path to your PUBLIC uploads/Profile folder
  $targetDirectory = '/var/www/html/uploads/Profile';
  if (!file_exists($targetDirectory)) {
    mkdir($targetDirectory, 0777, true);
  }

  $targetPath = $targetDirectory . '/' . $fileName;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    $publicURL = '../../uploads/Profile/' . $fileName;

    $response = array(
      'status' => 'success',
      'fileName' => $fileName,
      'fileURL'  => $publicURL
    );

    echo json_encode($response);
  } else {
    echo json_encode([
      'status' => 'error',
      'message' => 'Error moving uploaded file.'
    ]);
  }
} else {
  echo json_encode([
    'status' => 'error',
    'message' => 'Error uploading file.'
  ]);
}
