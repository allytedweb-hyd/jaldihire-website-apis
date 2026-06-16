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
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit();
}


include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";
include '../lib/mails.php';
include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
  echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
  exit();
}

@$token = str_replace('Bearer ', '', $authHeader);

TokenVerify($token);


$json = file_get_contents('php://input');
$data = json_decode($json);
$stat = [];


if (isset($json) && !empty($json)) {
  $data = json_decode($json);
  $assign = $data->to;
  $assignedApplicantId = $data->assignedApplicantId;
  $assignedRecruiterId = $data->assignedRecruiterId;
  $assignedrole = $data->assignedrole;
  $assignedid = $data->assignedIid;
  $appliacntName = $data->applicantName;
  $applicantMail = $data->applicantMail;
  // echo json_encode($assignedApplicantId);
  // exit();


  if ($assign == 'applicant') {
    //  $job = explode(",",$assignedJobId);
    // for ($i = 0; $i < count($assignedRecruiterId); $i++) {
    $query = "SELECT * FROM applicant_recruiter_assigns T1, tbl_allyted_team T2 WHERE T1.applicant_assigned_id='" . $assignedApplicantId . "' && T2.allyted_member_id = T1.recruiter_assigned_id && T1.status=1";
    $assignRecruiters = mysqli_query($con, $query);
    $recruiterData = mysqli_fetch_assoc($assignRecruiters);
    $assignedRecruitersCount = mysqli_num_rows($assignRecruiters);
    if ($assignedRecruitersCount > 0) {
      $status = ['status' => false, 'message' => 'Already Assigned To ' . $recruiterData['member_name']];
      echo json_encode($status);
      exit();
    }
    // }
  }


  if ($assign == 'applicant') {
    //  $recruiter = explode(",",$assignedJobId);
    // for ($i = 0; $i < count($assignedRecruiterId); $i++) {
    $myApplicantQuery = "INSERT INTO applicant_recruiter_assigns SET  applicant_assigned_id='" . $assignedApplicantId . "' ,recruiter_assigned_id='" . $assignedRecruiterId . "',role='" . $assignedrole . "',role_id='" . $assignedid . "',status=1";
    $inserted = mysqli_query($con, $myApplicantQuery);
    $getRecDet = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id='" . $assignedRecruiterId . "' && status=1"));
    if ($myApplicantQuery == true) {

      $recruiterSubject = "Jaldihire: Recruiter Assign to Applicant";
      $applicantSubject = "Jaldihire: Recruiter Assign to Applicant";

      $recruiterMail = $getRecDet['member_email'];
      $recruiterName = $getRecDet['member_name'];

      $recruiterBody = "Here is the mail to inform  ($recruiterName) Recruiter , you are successfully assigned to ($appliacntName) Applicant";
      $applicantBody = "Here is the mail to inform ($appliacntName) Applicant, The Recruiter ($recruiterName) Successfully Assigned to you ";

      recruiterAssignApplicantToRecruiter($getRecDet['member_email'], $recruiterSubject, $recruiterBody);
      recruiterAssignApplicantToApplicant($applicantMail, $applicantSubject, $applicantBody);

      $stat = ['status' => true, 'message' => 'Successfully Assigned To ' . $getRecDet['member_name']];
    } else {
      $stat = ['status' => false, 'message' => 'Failed to Assign'];
    }
    // }
  } else if ($assign == 'recruiter') {
    // $applicant = explode(",",$assignedApplicantId);
    // for ($i = 0; $i < count($assignedApplicantId); $i++) {
    $myRecruiterQuery = "insert into applicant_recruiter_assigns set  recruiter_assigned_id='" . $assignedRecruiterId . "' ,applicant_assigned_id='" . $assignedApplicantId . "',status=1";
    $inserted = mysqli_query($con, $myRecruiterQuery);
    if ($myRecruiterQuery == true) {

      $stat = ['status' => true, 'message' => 'Successfully Recruiter Assigned'];
    } else {
      $stat = ['status' => false, 'message' => 'Failed to Assign'];
    }
    // }
  } else {
    $stat = ['successstatus' => 'Success!'];
  }
}

echo json_encode($stat);
