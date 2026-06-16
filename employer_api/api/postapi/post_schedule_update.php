<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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
include '../notificationApis/notification_functions.php';

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  $applicant_id = $data->applicant_id;
  $employer_id = $data->employer_id;
  $scheduled_date = $data->date;
  $job_id = $data->job_id;
  $text = $data->text;
  $emp_mail = $data->emp_email;
  $employer_name = $data->employer_name;
  $recruiter_id = $data->recruiter_id;
  $recruiter_Name = $data->recruiterName;
  $epoch_date = $data->epoch_date;
  $schedule_id = $data->schedule_id;
  $agent_mail = $data->agent_mail;

  $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $job_id . "' && job_status='closed' && close_status=1");
  if (mysqli_num_rows($checkJobStatus) > 0) {
    $status = ['status' => false, 'message' => 'This Job is Closed'];
    exit(json_encode($status));
  }

  $getCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE scheduled_date='{$scheduled_date}' AND applicant_id='{$applicant_id}' AND job_id='{$job_id}' AND recruiter_auto_id='{$recruiter_id}' "));

  if ($getCount > 0) {
    $stat = ['message' => 'Applicant Already Rescheduled', 'status' => false];
    echo json_encode($stat);
    exit();
  }


  $scheduledapplicant = mysqli_query($con, "UPDATE applicant_scheduled set scheduled_date='" . $scheduled_date . "', resheduled_status=1, emp_resch_status=0 WHERE schedule_id='" . $schedule_id . "'");

  $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT *,a.e_mail as email FROM tbl_applicant_add a,applicant_scheduled b WHERE a.applicant_id = b.applicant_id and b.schedule_id ='" . $schedule_id . "'"));
  $id = $getDetails['email'];

  if ($scheduledapplicant == true) {

    $message = "applicant < " . $getDetails['full_name'] . " >  interview  rescheduled on <" . $scheduled_date . "> ";

    $jobDet = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $getDetails['job_id'] . "'"));

    $empId = $getDetails['employer_id'];
    $jobId = $jobDet['id'];
    $jobautoId = $getDetails['job_id'];
    $interViewerId = $getDetails['interviewer_id'];
    $agentId = $getDetails['recruiter_auto_id'];
    $recruiter_id = $getDetails['jobrecruiterid'];

    // ? code to get emp noti setting and send notification

    $getEmpNoti = getAllowedNotifications($empId);

    if ($getEmpNoti['status']) {
      $eData = $getEmpNoti['data'];
      if ($eData['interview_alerts'] == 1) {
        $eQuery = "INSERT INTO tbl_notification SET notify_to='Employer',employer_id='" . $empId . "',activity='Interview',notification_type='info',notification_text='" . $message . "',jobId='" . $jobId . "',job_autoId='" . $jobautoId . "',interviewerId='" . $interViewerId . "',status=1";

        mysqli_query($con, $eQuery);
      }
    }

    // ? code to get emp noti settings and send notification

    $getAgentNoti = getAllowedNotifications($agentId);

    if ($getAgentNoti['status']) {
      $AData = $getAgentNoti['data'];
      if ($AData['interview_alerts'] == 1) {
        $aQuery = "INSERT INTO tbl_notification SET notify_to='Agent',agent_id='" . $agentId . "',activity='Interview',notification_type='info',notification_text='" . $message . "',jobId='" . $jobId . "',job_autoId='" . $jobautoId . "',interviewerId='" . $interViewerId . "',status=1";

        mysqli_query($con, $aQuery);
      }
    }

    // ? code to get rec noti set and send notification

    $getRecNoti = getAgentAllowedNotify($recruiter_id);

    if ($getRecNoti['status']) {
      $rData = $getRecNoti['data'];
      if ($rData['interview_alerts'] == 1) {
        $rQuery = "INSERT INTO tbl_notification SET notify_to='Recruiter',recruiter_id='" . $recruiter_id . "',activity='Interview',notification_type='Success',notification_text='" . $message . "',jobId='" . $jobId . "',job_autoId='" . $jobautoId . "',interviewerId='" . $interViewerId . "',status=1";

        mysqli_query($con, $aQuery);
      }
    }


    // ? code to get interviewer noti set and send notification

    if ($empId != $interViewerId) {
      $getIntNoti = getAllowedNotifications($interViewerId);

      if ($getIntNoti['status']) {
        $iData = $getIntNoti['data'];
        if ($rData['interview_alerts'] == 1) {
          $iQuery = $eQuery = "INSERT INTO tbl_notification SET notify_to='Interviewer',employer_id='" . $empId . "',activity='Interview',notification_type='info',notification_text='" . $message . "',jobId='" . $jobId . "',job_autoId='" . $jobautoId . "',interviewerId='" . $interViewerId . "',status=1";

          mysqli_query($con, $iQuery);
        }
      }
    }

    $applicant_subject = "Interview re-scheduled from employer";
    $applicant_body = "Hello employer reschedule the interviewapplicant id '$applicant_id' you for interview round";

    $agent_subject = "Interview re-scheduled from employer";
    $agent_body = "Hello agent applicant '$applicant_id' reschedule the interview round";

    $employer_subject = "Interview re-scheduled from employer";
    $employer_body = "Hello agent reschedule the interview round for applicant '$applicant_id'";

    recruiter([$agent_mail], $agent_subject, $agent_body);

    scheduleApplicant($id, $applicant_subject, $applicant_body);

    $stat = ['status' => true, 'message' => 'Applicant scheduled Successfully'];
  } else {
    $stat = ['failurestatus' => 'Failed!'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong Please Try Again'];
}
echo json_encode($stat);
