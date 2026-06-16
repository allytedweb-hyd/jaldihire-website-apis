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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



include "../../includes/db-connection/db.php";

$json = file_get_contents('php://input');
$rounds = [];

if (isset($json) && !empty($json)) {
  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $empid = $data->companyID;
  $rounds = $data->rounds;

  foreach ($rounds as $round) {
    if (isset($round->round_number) && !empty($round->round_number)) {
      if (isset($round->id) && !empty($round->id)) {
        $insert = mysqli_query($con, 'UPDATE tbl_interview_rounds SET round_title="' . $round->round_title . '",round_description="' . $round->round_description . '",emp_id="' . $empid . '" WHERE id="' . $round->id . '" && round_number="' . $round->round_number . '" && status=1');
      } else {
        $getInterviewRounds = mysqli_query($con, 'SELECT * FROM tbl_interview_rounds WHERE round_number="' . $round->round_number . '" && emp_id="' . $empid . '" && status=1');

        if (mysqli_num_rows($getInterviewRounds) == 0) {
          $insert = mysqli_query($con, 'INSERT INTO tbl_interview_rounds SET emp_id="' . $empid . '",round_number="' . $round->round_number . '",round_title="' . $round->round_title . '",round_description="' . $round->round_description . '",status=1');
        }
      }
    }
  }

  if ($insert == true) {
    $stat = ['message' => 'Added Success!', 'status' => true];
  } else {
    $stat = ['message' => 'Failed!', 'status' => false];
  }
} else {
  $stat = ['message' => 'Something went wrong!', 'status' => false];
}
echo json_encode($stat);
