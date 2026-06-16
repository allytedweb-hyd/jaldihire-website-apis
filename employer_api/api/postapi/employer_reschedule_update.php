<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

require "../libs/mails.php";

include "../../includes/db-connection/db.php";

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {

  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $applicant_id = $data->applicant_id;
  $employer_id = $data->employer_id;
  $scheduled_date = $data->date;
  $job_id = $data->job_id;
  $text = $data->text;
  $emp_mail = $data->emp_email;
  $employer_name = $data->employer_name;
  $recruiter_id = $data->recruiter_id;
  $agent_mail = $data->agent_mail;
  $recruiter_Name = $data->recruiterName;
  $epoch_date = $data->epoch_date;
  $schedule_id = $data->schedule_id;
  $jobrecruitermail = $data->jobrecruitermail;

  $getCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE scheduled_date='{$scheduled_date}' AND applicant_id='{$applicant_id}' AND job_id='{$job_id}' AND recruiter_auto_id='{$recruiter_id}' AND resheduled_status=0"));

  if ($getCount > 0) {
    $stat = ['message' => 'Applicant Already Rescheduled', 'status' => false];
    echo json_encode($stat);
    exit();
  }

  $scheduledapplicant = mysqli_query($con, "UPDATE applicant_scheduled set scheduled_date='" . $scheduled_date . "', resheduled_status=0,emp_resch_status=1 WHERE schedule_id='" . $schedule_id . "'");

  $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT a.e_mail as email FROM tbl_applicant_add a,applicant_scheduled b WHERE a.applicant_id = b.applicant_id and b.schedule_id ='" . $schedule_id . "'"));
  $id = $getDetails['email'];


  if ($scheduledapplicant == true) {
    $subject = 'Employer Reshedule Interview';
    $body = 'Hello recruiter,Employer recheduled the interview';

    recruiter([$jobrecruitermail, $agent_mail], $subject, $body);



    $stat = ['status' => true, 'message' => 'Applicant scheduled Successfully'];
  } else {
    $stat = ['failurestatus' => 'Failed!'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong Please Try Again'];
}
echo json_encode($stat);
