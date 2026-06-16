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

date_default_timezone_set("Asia/Calcutta");
$datetime = date("Y-m-d H:i:s");

$json = file_get_contents('php://input');
$stat = [];
if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    @$present_recruiter = $data->present_recruiter;
    @$subRecruiterid = $data->subRecruiterid;
    @$fromDate = $data->fromDate;
    @$toDate = $data->toDate;
    @$text = $data->text;
    @$assignedrole = $data->assignedrole;
    @$assigned_id = $data->assigned_id;
    @$from_epoch = $data->fromDate_epoch;
    @$to_epoch = $data->toDate_epoch;
    @$job_id = $data->job_id;
    @$employer_id = $data->employerId;

    $getPreRecDet = getRecSkills($present_recruiter);
    $getSubRecDet = getRecSkills($subRecruiterid);
    $skillsMatch = array_values(array_diff($getPreRecDet, $getSubRecDet));

    if (count($skillsMatch) == 0) {

        $insert_substitute = mysqli_query($con, "INSERT INTO  substitute_recruiter SET present_recruiter='" . $present_recruiter . "',substitute_recruiter='" . $subRecruiterid . "',from_date='" . $fromDate . "',from_epoch='" . $from_epoch . "',to_date='" . $toDate . "',to_epoch='" . $to_epoch . "',text='" . $text . "',assigned_byrole='" . $assignedrole . "',
        assigned_by_id='" . $assigned_id . "',created_on='" . $datetime . "',status=1");

        if ($insert_substitute == true) {
            $presentRecuiterDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $present_recruiter . "'"));

            $subRecruitertDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $subRecruiterid . "'"));



            $subRecruitertName = $subRecruitertDetails['member_name'];
            $presentRecruiterName = $presentRecuiterDetails['member_name'];

            $getAllowedNoti = getAllowedNotifications($presentRecuiterDetails['recruiter_auto_create_id']);
            if ($getAllowedNoti['status']) {
                $d = $getAllowedNoti['data'];
                if ($d['advisor_alerts'] == '1') {
                    $RecruiterAutoid = $presentRecuiterDetails['recruiter_auto_create_id'];
                    $text = "Recuiter <" . $presentRecruiterName . "> substituted with <" . $subRecruitertName . "> till <" . $toDate . "> !";
                    $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Recruiter',notification_text='" . $text . "',activity='Masters',recruiter_id='" . $RecruiterAutoid . "' ");
                }
            }
            $getAllowedNoti = getAllowedNotifications($subRecruitertDetails['recruiter_auto_create_id']);
            if ($getAllowedNoti['status']) {
                $d = $getAllowedNoti['data'];
                if ($d['advisor_alerts'] == '1') {
                    $RecruiterAutoid = $subRecruitertDetails['recruiter_auto_create_id'];
                    $text = "Recuiter <" . $presentRecruiterName . "> substituted with <" . $subRecruitertName . "> till <" . $toDate . "> !";
                    $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Recruiter',notification_text='" . $text . "',activity='Masters',recruiter_id='" . $RecruiterAutoid . "' ");
                }
            }

            $jobIds = [];

            $getJobs = mysqli_query($con, "SELECT T2.auto_create_jobid FROM assign_job_recruiter T1, tbl_post_a_job T2 WHERE assigned_reqruiter='" . $present_recruiter . "' AND T2.id=T1.assigned_job AND T1.status=1");
            while ($fet = mysqli_fetch_assoc($getJobs)) {
                $jobIds[] = $fet['auto_create_jobid'];
            }

            foreach ($jobIds as $id) {

                $query = mysqli_fetch_assoc(mysqli_query($con, "SELECT T2.recruiter_auto_create_id FROM employer_agent_relation T1,tbl_allyted_team T2  WHERE T1.auto_create_jobid='" . $id . "' AND T2.allyted_member_id = T1.agent_id "));

                if ($query != null) {
                    $getAllowedNoti = getAllowedNotifications($query['recruiter_auto_create_id']);
                    if ($getAllowedNoti['status']) {
                        $d = $getAllowedNoti['data'];
                        if ($d['advisor_alerts'] == '1') {
                            $Agentautoid = $query['recruiter_auto_create_id'];
                            $text = "Recuiter <" . $presentRecruiterName . "> substituted with <" . $subRecruitertName . "> till <" . $toDate . "> !";
                            $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',agent_id='" . $Agentautoid . "' ");
                        }
                    }
                }
            }

            $stat = ['message' => 'Substitute Added Successfully!', 'status' => true];
        } else {
            $stat = ['message' => 'Added to Failed!', 'status' => false];
        }
    } else {
        $notHave = implode(',', $skillsMatch);
        $stat = ['status' => false, 'message' => "Substitute Recruiter Doesn't Have " . $notHave . "Skills"];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong Please Try Again'];
}
echo json_encode($stat);

function getRecSkills($recruiterId)
{
    global $con;
    $get = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id='" . $recruiterId . "'AND role='Recruiter' AND status=1"));
    $skillsArray = explode(',', $get['skills']);
    return $skillsArray;
}
