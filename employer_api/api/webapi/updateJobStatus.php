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
$data = json_decode($json);

if (isset($json) && !empty($json)) {

  $status = $data->status;
  $id = $data->id;
  $finalstatus = $data->finalstatus;
  $remarks = $data->remarks;
  $job_id = $data->job_id;
  $round = $data->round;
  $substatus = $data->Substatus;

  $scheduleStatus = mysqli_query($con, "UPDATE applicant_scheduled SET current_round_status='" . $status . "',remarks='" . $remarks . "',status='" . $finalstatus . "',subStatus='" . $substatus . "' WHERE schedule_id ='" . $id . "'");

  if ($scheduleStatus == true) {
    $updateInterviewRecord = mysqli_query($con, "UPDATE interview_record SET current_round_status='" . $status . "',remarks='" . $remarks . "',interview_status='" . $finalstatus . "',subStatus='" . $substatus . "' WHERE job_id='" . $job_id . "' AND round='" . $round . "'");
    $stat = ['status' => true, 'message' => 'Success!'];
  } else {
    $stat = ['status' => false, 'message' => 'failed'];
  }
} else {
  $stat = ['status' => false, 'Message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
