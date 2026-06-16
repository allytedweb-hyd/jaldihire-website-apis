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
require "../lib/mail_function.php";
require "../lib/mailfunction_applicant.php";
include "../lib/jwt.php";
include '../notificationApis/notificationFunctions.php';

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {

    $data = json_decode($json);
    $scheduled_Id = $data->scheduled_id;
    $emp_email = $data->emp_email;
    $job_id = $data->job_id;
    $agent_mail = $data->agent_mail;

    $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $job_id . "' && job_status='closed' && close_status=1");
    if (mysqli_num_rows($checkJobStatus) > 0) {
        $status = ['status' => false, 'message' => 'This Job is Closed'];
        exit(json_encode($status));
    }

    $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT *,a.e_mail as email, a.applicant_auto_create_id as applicant_id, a.full_name as fullName FROM tbl_applicant_add a,applicant_scheduled b WHERE a.applicant_id = b.applicant_id and b.schedule_id ='" . $scheduled_Id . "'"));
    $id = $getDetails['email'];
    $applicant_id = $getDetails['applicant_id'];
    $applicant_name = $getDetails['fullName'];

    $checkAppli = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_auto_create_id='" . $applicant_id . "' AND status=1 AND on_boarded=1"));
    if ($checkAppli > 0) {
        $status = ['status' => false, 'message' => 'Applicant Already On-Boarded'];
        exit(json_encode($status));
    }

    $update_scheduleddata = mysqli_query($con, "UPDATE applicant_scheduled SET  status='1' WHERE schedule_id='" . $scheduled_Id . "' ");

    $updateInterviewRec = mysqli_query($con, "UPDATE interview_record SET interview_status='1',current_round_status='0' WHERE schedule_id='" . $scheduled_Id . "' ");


    if ($update_scheduleddata == true) {


        $message = "Interview for the  <" . $getDetails['job_id'] . "> has confirmed with <" . $getDetails['fullName']  . " >";

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



        sendEmail([$agent_mail], $applicant_id);
        applicant_Email([$id], $applicant_id, $applicant_name,);
        employer_Email([$emp_email], $applicant_id, $job_id);
        $stat = ['status' => true, 'message' => 'Scheduled Success'];
    } else {
        $stat = ['status' => false, 'message' => 'Failed!'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong try again'];
}
echo json_encode($stat);
