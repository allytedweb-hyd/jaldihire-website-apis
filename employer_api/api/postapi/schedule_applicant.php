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

require "../libs/mails.php";
include "../../includes/db-connection/db.php";
include '../libs/jwt.php';
include '../notificationApis/notification_functions.php';


// ===== GET TOKEN SAFELY =====
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

$token = '';
if (!empty($authHeader)) {
  $token = str_replace('Bearer ', '', $authHeader);
}

// ===== TOKEN VALIDATION =====
if (empty($token)) {
  http_response_code(401);
  echo json_encode([
    "status" => false,
    "message" => "Token missing"
  ]);
  exit;
}

try {
  $decoded = TokenVerify($token);
} catch (Throwable $e) {
  http_response_code(401);
  echo json_encode([
    "status" => false,
    "message" => "Invalid or Expired Token"
  ]);
  exit;
}

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  $applicant_id = $data->assignedApplicantId;
  $employer_id = $data->companyID;
  $scheduled_date = $data->date;
  $job_id = $data->appJobId;
  $text = $data->text;
  $round = $data->round;
  $interviewerId = $data->interviewer;
  $recruiter_id = $data->recruiterID; // * -> AGENT ID
  $recruiter_mail = $data->recruiterMail; // * -> AGENT Mail
  $recruiter_Name = $data->recruiterName; // * -> AGENT Name
  $epoch_date = $data->epoch_date;
  $employerName = $data->employerName;
  $employerMail = $data->employerMail;
  $jobrecruitername = $data->jobrecruiterName; // * -> recrutier name
  $jobrecruitermail = $data->jobrecruiterMail; // * -> recruter mail
  $jobrecruiterid = $data->jobrecruiterid; // * -> recrutier id
  $applicant_name = $data->applicant_name;
  $applicant_mail = $data->applicant_mail;
  $interviewType = $data->interviewType;


  $checkInterviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT Onboardings ,Interviews FROM tbl_add_employer WHERE employer_auto_create_id='" . $employer_id . "'"));

  if (!intval($checkInterviews['Interviews']) > 0) {
    exit(json_encode(['status' => false, 'message' => 'You Do Not have enough interviews in your wallet']));
  }

  $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $job_id . "' && job_status='closed' && close_status=1");
  if (mysqli_num_rows($checkJobStatus) > 0) {
    $status = ['status' => false, 'message' => 'This Job is Closed'];
    exit(json_encode($status));
  }

  $getCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='{$job_id}' AND applicant_id='{$applicant_id}'"));

  if ($getCount > 0) {
    $stat = ['message' => 'Applicant Already Scheduled', 'status' => false];
    echo json_encode($stat);
    exit();
  }

  $assigned_rounds = $round . ',';

  $scheduledapplicant = mysqli_query($con, "INSERT INTO applicant_scheduled SET epoch_date='" . $epoch_date . "', applicant_id='" . $applicant_id . "',employer_id='" . $employer_id . "',round='" . $round . "',interviewer_id='" . $interviewerId . "',current_round_status=0,job_id='" . $job_id . "',assigned_rounds='" . $assigned_rounds . "',scheduled_date='" . $scheduled_date . "',text='" . $text . "',recruiter_auto_id='" . $recruiter_id . "',recruiter_mail='" . $recruiter_mail . "',recruiter_name='" . $recruiter_Name . "',employer_name='" . $employerName . "',employer_mail='" . $employerMail . "',jobrecruitermail='" . $jobrecruitermail . "',job_recruitername='" . $jobrecruitername . "',jobrecruiterid='" . $jobrecruiterid . "',interviewType='" . $interviewType . "',resheduled_status=0,status=0");
  $last_id = mysqli_insert_id($con);

  if ($scheduledapplicant == true) {

    // ? query to get jobid for reirection to job page
    $getJobid = mysqli_fetch_assoc(mysqli_query($con, "SELECT id as jobid FROM tbl_post_a_job WHERE auto_create_jobid='" . $job_id . "' AND status=1"));

    // ? to get emp notification settings and send notification
    $getAllowNoti = getAllowedNotifications($employer_id);

    if ($getAllowNoti['status']) {
      $data = $getAllowNoti['data'];
      if ($data['interview_alerts'] == 1) {

        $q = "INSERT INTO tbl_notification SET notify_to='Employer',employer_id='" . $employer_id . "',activity='Interview',notification_type='Success',notification_text='Interview scheduled to applicant<" . $applicant_name . ">successfully',jobId='" . $getJobid['jobid'] . "',job_autoId='" . $job_id . "',interviewerId='" . $interviewerId . "',status=1";

        mysqli_query($con, $q);
      }
    }

    // ? to get agent notification setting and send notification
    $getAgentAllowNoti = getAllowedNotifications($recruiter_id);

    if ($getAgentAllowNoti['status']) {
      $Adata = $getAgentAllowNoti['data'];
      if ($Adata['interview_alerts'] == 1) {

        $q = "INSERT INTO tbl_notification SET notify_to='Agent',agent_id='" . $recruiter_id . "',activity='Interview',notification_type='info',notification_text='Interview scheduled to applicant<" . $applicant_name . ">successfully',jobId='" . $getJobid['jobid'] . "',job_autoId='" . $job_id . "',interviewerId='" . $interviewerId . "',status=1";

        mysqli_query($con, $q);
      }
    }

    // ? to get recruiter notification setting and send notification
    $getRecAllowNoti = getAgentAllowedNotify($jobrecruiterid);

    if ($getRecAllowNoti['status']) {
      $rData = $getRecAllowNoti['data'];
      if ($rData['interview_alerts'] == 1) {
        $query = "INSERT INTO tbl_notification SET notify_to='Recruiter',recruiter_id='" . $rData['user_id'] . "',activity='Interview',notification_type='info',notification_text='Interview scheduled to applicant<" . $applicant_name . ">successfully',jobId='" . $getJobid['jobid'] . "',job_autoId='" . $job_id . "',interviewerId='" . $interviewerId . "',status=1";

        mysqli_query($con, $query);
      }
    }



    $insertIntreviewRecord = mysqli_query($con, "INSERT INTO interview_record SET schedule_id='" . $last_id . "', applicant_id='" . $applicant_id . "',employer_id='" . $employer_id . "',interviewer_id='" . $interviewerId . "',job_id='" . $job_id . "',agent_id='" . $recruiter_id . "',agent_name='" . $recruiter_Name . "',agent_mail='" . $recruiter_mail . "',recruiter_id='" . $jobrecruiterid . "',recruiter_name='" . $jobrecruitername . "',recruiter_mail='" . $jobrecruitermail . "',scheduled_date='" . $scheduled_date . "',text='" . $text . "',employer_name='" . $employerName . "',employer_mail='" . $employerMail . "',round='" . $round . "',interviewType='" . $interviewType . "',interview_status=0");

    $applicant_subject = "Interview scheduled from employer";
    $applicant_body = "Hello ($applicant_name) employer requested you for interview round ($round)";
    $recruiter_subject = "Interview scheduled from employer";
    $recruiter_body = "Hello ($jobrecruitername) employer requested ($applicant_name) for interview round ($round)";

    recruiter([$jobrecruitermail, $recruiter_mail], $recruiter_subject, $recruiter_body);
    scheduleApplicant($applicant_mail, $applicant_subject, $applicant_body);
    $stat = ['status' => true, 'message' => 'Applicant scheduled Successfully'];
  } else {
    $stat = ['status' => 'Failed!'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
