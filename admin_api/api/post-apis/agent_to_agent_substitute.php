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
$stat = [];
if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    @$present_agent = $data->present_agent;
    @$subAgentid = $data->subAgentid;
    @$fromDate = $data->fromDate;
    @$toDate = $data->toDate;
    @$text = $data->text;
    @$assignedrole = $data->assignedrole;
    @$assigned_id = $data->assigned_id;
    @$from_epoch = $data->fromDate_epoch;
    @$to_epoch = $data->toDate_epoch;
    @$job_id = $data->job_id;
    @$employer_id = $data->employerId;

    $getPreAgeDet = getAgentSkills($present_agent);
    $getSubAgeDet = getAgentSkills($subAgentid);
    $skillsMatch = array_values(array_diff($getPreAgeDet, $getSubAgeDet));

    if (count($skillsMatch) == 0) {

        $insert_substitute = mysqli_query($con, "INSERT INTO  substitute_agent SET present_agent='" . $present_agent . "',substitute_agent='" . $subAgentid . "',from_date='" . $fromDate . "',from_epoch='" . $from_epoch . "',to_date='" . $toDate . "',to_epoch='" . $to_epoch . "',text='" . $text . "',assigned_byrole='" . $assignedrole . "',
        assigned_by_id='" . $assigned_id . "',status=1");

        if ($insert_substitute == true) {

            $presentAgentDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $present_agent . "'"));

            $subAgentDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $subAgentid . "'"));

            // $employerData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_add_employer T1, assigned_Recruiters_list T2 WHERE T2.status=1 && T2.assigned_recruiter_id= '" . $present_agent . "'"));

            $SubagentName = $subAgentDetails['member_name'];
            $presentAgentName = $presentAgentDetails['member_name'];

            $getAllowedNoti = getAllowedNotifications($presentAgentDetails['recruiter_auto_create_id']);
            if ($getAllowedNoti['status']) {
                $d = $getAllowedNoti['data'];
                if ($d['advisor_alerts'] == '1') {
                    $Agentautoid = $presentAgentDetails['recruiter_auto_create_id'];
                    $text = "Advisor <" . $presentAgentName . "> substituted with <" . $SubagentName . "> till <" . $toDate . "> !";
                    $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',agent_id='" . $Agentautoid . "' ");
                }
            }
            $getAllowedNoti = getAllowedNotifications($subAgentDetails['recruiter_auto_create_id']);
            if ($getAllowedNoti['status']) {
                $d = $getAllowedNoti['data'];
                if ($d['advisor_alerts'] == '1') {
                    $Agentautoid = $subAgentDetails['recruiter_auto_create_id'];
                    $text = "Advisor <" . $presentAgentName . "> substituted with <" . $SubagentName . "> till <" . $toDate . "> !";
                    $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',agent_id='" . $Agentautoid . "' ");
                }
            }

            $empids = [];

            $getemployers = mysqli_query($con, "SELECT T2.employer_auto_create_id FROM  employer_agent_relation T1, tbl_add_employer T2 WHERE assigned_recruiter_id='" . $present_agent . "' AND T2.employer_id = T1.assigned_employer_id AND T1.status=1 AND T2.status=1 ");

            while ($fet = mysqli_fetch_assoc($getemployers)) {
                $empids[] = $fet['employer_auto_create_id'];
            }

            foreach ($empids as $id) {

                $getAllowedNoti = getAllowedNotifications($id);
                if ($getAllowedNoti['status']) {
                    $employerid = $getAllowedNoti['data'];
                    if ($employerid['employer_alerts'] == '1') {
                        $agentName = $agentData['member_name'];

                        $text = "Advisor <" . $presentAgentName . "> substituted with <" . $SubagentName . "> till <" . $toDate . "> !";

                        $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Employer',notification_text='" . $text . "',activity='Masters',employer_id='" . $id . "' ");
                    }
                }
            }







            $stat = ['message' => 'Substitute Added Successfully!', 'status' => true];
        } else {
            $stat = ['message' => 'Substitute Failed to Add!', 'status' => false];
        }
    } else {
        $notHave = implode(',', $skillsMatch);
        $stat = ['status' => false, 'message' => "Substitute Agent Doesn't Have " . $notHave . "Skills"];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong Please Try Again'];
}
echo json_encode($stat);

function getAgentSkills($agentId)
{
    global $con;
    $get = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id='" . $agentId . "'AND role='Agent' AND status=1"));
    $skillsArray = explode(',', $get['skills']);
    return $skillsArray;
}
