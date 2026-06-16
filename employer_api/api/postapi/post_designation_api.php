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

include "../../includes/db-connection/db.php";
include '../libs/jwt.php';
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
  echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
  exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


$response=TokenVerify($token);

@$empId = ($_GET['employer_id'] == null) ? $response['userId'] : $_GET['employer_id'];
$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
  $data = json_decode($json);
  $designation = $data->designation;

  $getDesgnitions = mysqli_query($con, "SELECT * FROM tbl_employer_designation WHERE designation='" . $designation . "' AND employer_id='" . $empId . "' AND status=1");

  if (mysqli_num_rows($getDesgnitions) == 0) {
    $insert_data = mysqli_query($con, "INSERT INTO tbl_employer_designation SET designation='" . $designation . "',employer_id='" . $empId . "', status=1 ");
    if ($insert_data == true) {
      $stat = ['successstatus' => 'Success!', 'status' => true];
    } else {
      $stat = ['failurestatus' => 'Failed!', 'status' => false];
    }
  } else {
    $stat = ['status' => false, 'message' => 'Designation Exists'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
