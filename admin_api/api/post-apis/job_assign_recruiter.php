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

include "../notificationApis/notificationFunctions.php";
include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";
include '../lib/mails.php';
include "../lib/jwt.php";

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
  echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
  exit();
}

@$token = str_replace('Bearer ', '', $authHeader);




try {
  // decoded contains your userId and role
  $decoded = TokenVerify($token);
} catch (Throwable $e) {
  http_response_code(401);
  echo json_encode(["status" => false, "message" => "Invalid or Expired Token"]);
  exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json);
$stat = [];

if (isset($json) && !empty($json)) {
  $data = json_decode($json);
  $assign = $data->to;
  $assignedJobId = $data->assignedJobId;
  $assignedRecruiterId = $data->assignedRecruiterId;
  @$agentid = $data->agentid;
  @$adminId = $data->adminId;

  $finalRecuitersList = [];
  $existStatus = [];
  $nonExistStatus = [];
  $assignBy = '';

  if (isset($agentid) && !empty($agentid)) {
    if ($assign == 'jobs') {
      $q = "SELECT * FROM tbl_post_a_job T1, employer_agent_relation T2 WHERE T1.id='" . $assignedJobId . "' AND T2.auto_create_jobid = T1.auto_create_jobid AND T1.status=1 AND T2.status=1";
      $checkJobAccess = mysqli_num_rows(mysqli_query($con, $q));
      if ($checkJobAccess == 0) {
        $status = ['status' => false, 'message' => 'You Do not Have Access For This Job'];
        exit(json_encode($status));
      }
    }
  }

  if ($assign == 'jobs') {
    $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE id='" . $assignedJobId . "' && job_status='closed' && close_status=1");
    if (mysqli_num_rows($checkJobStatus) > 0) {
      $status = ['status' => false, 'message' => 'This Job is Closed'];
      exit(json_encode($status));
    }
  }

  if ($assign == 'jobs') {
    //  $job = explode(",",$assignedJobId);
    foreach ($assignedRecruiterId as $item) {
      $query = "SELECT * FROM assign_job_recruiter WHERE assigned_job='" . $assignedJobId . "'  && assigned_reqruiter='" . $item . "' && status=1";
      $assignedRecruiter = mysqli_query($con, $query);
      $assignedRecruiterCount = mysqli_num_rows($assignedRecruiter);
      $recruiterData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $item . "'"));
      if ($assignedRecruiterCount == 0) {
        array_push($finalRecuitersList, $item);
        array_push($nonExistStatus, $recruiterData['member_name']);
      } else {
        array_push($existStatus, $recruiterData['member_name']);
      }
    }
  }

  if (isset($adminId) && !empty($adminId)) {
    $assignBy = "admin_id='" . $adminId . "',assigned_by='admin',";
  }

  if (isset($agentid) && !empty($agentid)) {
    $assignBy = "agent_id='" . $agentid . "',assigned_by='agent',";
  }

  if ($assign == 'jobs') {
    //  $recruiter = explode(",",$assignedJobId);
    if (count($finalRecuitersList) > 0) {
      foreach ($finalRecuitersList as $item) {
        $myJobsQuery = "INSERT INTO assign_job_recruiter SET  assigned_job='" . $assignedJobId . "' ,assigned_reqruiter='" . $item . "',$assignBy status=1";

        $recruitersmails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $item . "'"));

        $Jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && id= '" . $assignedJobId . "'"));


        $inserted = mysqli_query($con, $myJobsQuery);
        if ($myJobsQuery == true) {

          $getAllowedNoti = getAgentAllowedNotify($recruitersmails['recruiter_auto_create_id']);

          if ($getAllowedNoti['status']) {
            $d = $getAllowedNoti['data'];

            if ($d['recruiter_alerts'] == '1') {

              $RecuiterId = $recruitersmails['recruiter_auto_create_id'];

              $text = "<$assignedJobId> assigned to you !";

              $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Recruiter',notification_text='" . $text . "',activity='Masters',recruiter_id='" . $RecuiterId . "'");
            }
          }






          if ($existStatus) {
            $status .= " Already Assigned to " . implode(',', $existStatus);
          }

          if (count($nonExistStatus) > 0) {


            $subject = "Acknwoledgement: Recruiter Job Assign";
            // $heading = "Job   Acknwoledgement";
            $nameRecepient = $recruitersmails['member_name'];
            $jobidRecepient = $Jobdata['auto_create_jobid'];

            $body = "Hi " . $recruitersmails['member_name'] . " (Recruiter).You are added to $jobidRecepient(job) start working and add applicants";

            jobAssignToRecruiter($recruitersmails['member_email'], $subject, $body);
            // $status = 'Assigned Succesfully'; commented for api response status
            $status = '';

            $status .= " Assigned to " . implode(',', $nonExistStatus) . " Successfully";
          }

          $stat = ['status' => true, 'message' => $status];
        } else {
          $stat = ['status' => false, 'message' => 'Failed to Assign'];
        }
      }
    } else {
      $stat = ['status' => false, 'message' => 'Already Members are Assigned'];
    }
  } else {
    $stat = ['status' => false, 'message' => 'Failed to Assign'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Failed! Something went wrong'];
}

echo json_encode($stat);
