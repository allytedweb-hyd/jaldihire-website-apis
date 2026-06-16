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
include "../../includes/db-connection/db.php";
include '../libs/mails.php';

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  if (!isset($data->schedule_id) && empty($data->schedule_id)) {
    $status = ['status' => false, 'message' => 'Invalid Params'];
    echo json_encode($status);
    exit();
  }
  $schedule_id = $data->schedule_id;
  $scheduleDate = $data->date;
  $round = $data->round;
  $interViewer = $data->interviewer;
  $epochDate = $data->epoch_date;
  $status = $data->status;

  $whereClause = [];

  $getScheduledInterviewDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='{$schedule_id}'"));

  $updatedRounds = $getScheduledInterviewDetails['assigned_rounds'] . ',' . $data->round;

  $query = "UPDATE applicant_scheduled SET scheduled_date='" . $scheduleDate . "',round='" . $round . "',interviewer_id='" . $interViewer . "',assigned_rounds='" . $updatedRounds . "',epoch_date='" . $epochDate . "',status='" . $status . "' WHERE schedule_id='{$schedule_id}'";

  $scheduledapplicant = mysqli_query($con, $query);

  if ($scheduledapplicant) {

    $createInterviewRounds = mysqli_query($con, "INSERT INTO interview_record SET 
        applicant_id='" . $getScheduledInterviewDetails['applicant_id'] . "',
        employer_id='" . $getScheduledInterviewDetails['employer_id'] . "',
        interviewer_id='" . $data->interviewer . "',
        job_id='" . $getScheduledInterviewDetails['job_id'] . "',
        agent_id='" . $getScheduledInterviewDetails['recruiter_auto_id'] . "',
        agent_name='" . $getScheduledInterviewDetails['recruiter_name'] . "',
        agent_mail='" . $getScheduledInterviewDetails['recruiter_mail'] . "',
        recruiter_id='" . $getScheduledInterviewDetails['jobrecruiterid'] . "',
        recruiter_name='" . $getScheduledInterviewDetails['job_recruitername'] . "',
        recruiter_mail='" . $getScheduledInterviewDetails['jobrecruitermail'] . "',
        scheduled_date='" . $data->date . "',
        text='" . $getScheduledInterviewDetails['text'] . "',
        employer_name='" . $getScheduledInterviewDetails['employer_name'] . "',
        employer_mail='" . $getScheduledInterviewDetails['employer_mail'] . "',
        round='" . $data->round . "',
        interview_status=1");

    $getMailsData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='" . $schedule_id . "'"));

    $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT a.e_mail as email,a.full_name as applicant_name FROM tbl_applicant_add a,applicant_scheduled b WHERE a.applicant_id = b.applicant_id and b.schedule_id ='" . $schedule_id . "'"));

    $id = $getDetails['email'];
    $Subject = "Interview Update";

    $recruiterBody = " Hello, " . $getMailsData['job_recruitername'] . " Employer " . $getScheduledInterviewDetails['employer_name'] . " Scheduled " . $getDetails['applicant_name'] . " For   $data->round Round ";

    $applicantBody = " Hello, " . $getDetails['applicant_name'] . " interviewer " . $getScheduledInterviewDetails['employer_name'] . "  Scheduled Your Interview For Round . $data->round ";


    recruiter([$getMailsData['jobrecruitermail'], $getMailsData['recruiter_mail']], $Subject, $recruiterBody);

    scheduleApplicant($id, $Subject, $applicantBody);

    if ($createInterviewRounds) {
      $status = ['message' => 'Applicant Re-Scheduled successfully', 'added' => 'mail sent successfully', 'status' => true];
    } else {
      $status = ['message' => 'mail failed to sent', 'status' => false];
    }
  } else {
    $status = ['status' => false, 'message' => 'Failed To Re-Schedule'];
  }
} else {
  $status = ['status' => false, 'message' => 'Something Went Wrong! Please Try Again'];
}
echo json_encode($status);
