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




$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

    $data = json_decode($json);

    $present_agent = $data->present_agent;
    $subAgentid = $data->subAgentid;
    $fromDate = $data->fromDate;
    $toDate = $data->toDate;
    $text = $data->text;
    $assignedrole = $data->assignedrole;
    $assigned_id = $data->assigned_id;
    $from_epoch = $data->fromDate_epoch;
    $to_epoch = $data->toDate_epoch;
    $job_id = $data->job_id;


    $insert_substitute = mysqli_query($con, "insert into job_agent_substitute set  job_id='" . $job_id . "', present_agent='" . $present_agent . "',substitute_agent='" . $subAgentid . "',from_date='" . $fromDate . "',from_epoch='" . $from_epoch . "',to_date='" . $toDate . "',to_epoch='" . $to_epoch . "',text='" . $text . "',assigned_byrole='" . $assignedrole . "',
    assigned_by_id='" . $assigned_id . "',status=1");



    if ($insert_substitute == true) {


        $presentADetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $present_agent . "'"));

        $subAgentDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $subAgentid . "'"));

        $jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid='" . $job_id . "'  "));
        $empid = $jobdata['company_Id'];
        $SubagentName = $subAgentDetails['member_name'];
        $presentAgentName = $presentADetails['member_name'];

        $getAllowedNoti = getAllowedNotifications($presentADetails['recruiter_auto_create_id']);
        if ($getAllowedNoti['status']) {
            $d = $getAllowedNoti['data'];
            if ($d['advisor_alerts'] == '1') {

                $Agentautoid = $presentADetails['recruiter_auto_create_id'];
                $text = "Advisor <" . $presentAgentName . "> substituted with <" . $SubagentName . "> till <End Date> !";

                $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',agent_id='" . $Agentautoid . "' ");
            }
        }
        $getAllowedNoti = getAllowedNotifications($subAgentDetails['recruiter_auto_create_id']);
        if ($getAllowedNoti['status']) {
            $d = $getAllowedNoti['data'];
            if ($d['advisor_alerts'] == '1') {
                $Agentautoid = $subAgentDetails['recruiter_auto_create_id'];
                $text = "Advisor <" . $presentAgentName . "> substituted with <" . $SubagentName . "> till <End Date> !";

                $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',agent_id='" . $Agentautoid . "' ");
            }
        }

        $getAllowedNoti = getAllowedNotifications($empid);
        if ($getAllowedNoti['status']) {
            $employerid = $getAllowedNoti['data'];
            if ($employerid['employer_alerts'] == '1') {
                $text = "Advisor <" . $presentAgentName . "> substituted with <" . $SubagentName . "> till <End Date> !";

                $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Employer',notification_text='" . $text . "',activity='Masters',employer_id='" . $empid . "' ");
            }
        }
        $stat = ['message' => 'Substitute Added Successfully!', 'status' => true];
    } else {

        $stat = ['message' => 'Substitute Failed to Add!', 'status' => false];
    }
}

echo json_encode($stat);
