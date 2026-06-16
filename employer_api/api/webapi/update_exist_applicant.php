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
  $finalstatus = $data->finalstatus;
  $scheduled_id = $data->sheduledId;
  $remarks = $data->remarks;
  $applicant_Id = $data->applicant_Id;
  @$onBoardingDate = $data->OnBoardingDate;
  @$hiredPackage = $data->SalaryPackage;
  $whereClause = '';

  if (isset($data->OnBoardingDate) && !empty($data->OnBoardingDate)) {
    $whereClause = ", OnBoardingDate='" . $onBoardingDate . "'";
  }

  if (isset($data->SalaryPackage) && !empty($data->SalaryPackage)) {
    $whereClause .= ", hired_package='" . $hiredPackage . "'";
  }

  if (isset($scheduled_id) && !empty($scheduled_id)) {
    $exit_applicantStatus = mysqli_query($con, "UPDATE applicant_scheduled SET remarks='" . $remarks . "', status='" . $finalstatus . "' $whereClause WHERE schedule_id ='" . $scheduled_id . "' && applicant_id ='" . $applicant_Id . "' ");

    if ($exit_applicantStatus == true) {

      $update_IntervireRecord = mysqli_query($con, "UPDATE interview_record SET remarks='" . $remarks . "', interview_status='" . $finalstatus . "' $whereClause WHERE schedule_id ='" . $scheduled_id . "' && applicant_id ='" . $applicant_Id . "'");


      $getMailsData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE schedule_id ='" . $scheduled_id . "'"));

      $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT a.e_mail as email,a.full_name as applicant_name FROM tbl_applicant_add a,applicant_scheduled b WHERE a.applicant_id = b.applicant_id and b.schedule_id ='" . $scheduled_id . "'"));

      $id = $getDetails['email'];

      $Subject = "Interview Update";

      $recruiterBody = " Hello, " . $getMailsData['job_recruitername'] . " interviewer update your interview status to $finalstatus  ";
      $applicantBody = " Hello, " . $getDetails['applicant_name'] . " interviewer update your interview status to $finalstatus  ";



      recruiter([$getMailsData['jobrecruitermail'], $getMailsData['recruiter_mail']], $Subject, $recruiterBody);

      scheduleApplicant($id, $Subject, $applicantBody);

      $stat = ['status' => true, 'message' => 'Updated Successfully'];
    } else {
      $stat = ['status' => false, 'message' => 'Failed To Update'];
    }
  } else {
    $stat = ['status' => false, 'error' => "Missing Parameters"];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong please try again'];
}

echo json_encode($stat);
