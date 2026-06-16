<?php
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
  $fileName = $_FILES['file']['name'];
  $fileType = $_FILES['file']['type'];
  $fileSize = $_FILES['file']['size'];

  $targetDirectory = '../../assets/dbImages';
  $targetPath = $targetDirectory . '/' . $fileName;

  if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    $response = array(
      'status' => 'success',
      'fileName' => $fileName,
      'fileType' => $fileType,
      'fileSize' => $fileSize
    );

    echo json_encode($response);
  } else {
    $response = array(
      'status' => 'error',
      'message' => 'An error occurred while moving the uploaded file to the target directory.'
    );

    echo json_encode($response);
  }
} else {
  $response = array(
    'status' => 'error',
    'message' => 'An error occurred while uploading the file.'
  );

  echo json_encode($response);
}
