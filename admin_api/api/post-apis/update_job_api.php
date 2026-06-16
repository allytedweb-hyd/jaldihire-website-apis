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


include "../vendor/autoload.php";
include "../../includes/db-connection/db.php";
include '../lib/jwt.php';
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

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $stat = [];
    @$updateBy = $data->updateBy;
    @$job_id = $data->job_id;
    @$auto_create_jobId = $data->job_autoId;
    @$employer_id = $data->employerId;
    @$role_id = $data->role_id;
    @$jobtitle = $data->job_title;
    @$jobskill = $data->j_skills;
    @$jobrole = $data->job_role;
    @$joblocation = $data->job_location;
    @$j_location = implode(',', $joblocation);
    @$jobduration = $data->job_duration;
    @$jobprice = $data->job_price;
    @$jobboardingtime = $data->job_bordingtime;
    @$jobworkmode = $data->job_workmode;
    @$job_type = $data->job_type;
    @$Project_Type = $data->Project_Type;
    @$Industry_Type = $data->Industry_Type;
    @$jobdescription = $data->job_description;
    @$jobpositions = $data->job_positions;
    @$jobexperience = $data->job_experience;
    @$job_qualification = $data->job_qualification;
    @$Availability = $data->Availability;
    @$skilsImplode = '';
    @$created_at = $data->created_at;
    if (is_array($jobskill)) {
        if (count($jobskill) > 0) {
            for ($i = 0; $i < count($jobskill); $i++) {
                $item = $jobskill[$i];
                if (intval(count($jobskill) - 1) == $i) {
                    $skilsImplode .= $item->item_text;
                } else {
                    $skilsImplode .= $item->item_text . ',';
                }
            }
        }
    }

    if (isset($job_id) && !empty($job_id)) {
        $query = "UPDATE tbl_post_a_job set
        job_title='" . $jobtitle . "',
        skill='" . $skilsImplode . "',
        role='" . $jobrole . "',
        job_location='" . $j_location . "',
        duration='" . $jobduration . "',
        price='" . $jobprice . "',
        work_mode='" . $jobworkmode . "',
        job_type='" . $job_type . "',
        project_Type='" . $Project_Type . "',
        job_description='" . $jobdescription . "',
        no_of_positions='" . $jobpositions . "',
        qualification='" . $job_qualification . "',
        experience='" . $jobexperience . "',
        availability='" . $Availability . "'
        WHERE id='" . $job_id . "' &&
        status=1";

        $update_job = mysqli_query($con, $query);

        if ($update_job == true) {

            $Message = "Job post < " . $auto_create_jobId . "> edited successfully";
            if ($updateBy === 'Employer') {
                $notiTypeEmp = 'Success';
                $notiTypeAgent = 'info';
            } else {
                $notiTypeEmp = 'info';
                $notiTypeAgent = 'Success';
            }
            // ? code to send notification to employer

            $empAllowNoti = getAllowedNotifications($employer_id);

            if ($empAllowNoti['status']) {

                $empData = $empAllowNoti['data'];

                if ($empData['job_alerts'] == 1) {

                    $query = "INSERT INTO tbl_notification SET notify_to='Employer',employer_id='" . $employer_id . "', activity='Job', notification_type='" . $notiTypeEmp . "',notification_text='" . $Message . "',jobId='" . $job_id . "',job_autoId='" . $auto_create_jobId . "'";

                    $addNotification = mysqli_query($con, $query);
                }
            }

            // ? code to check job agent and check notification status and send notification
            $getJobAgentId = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team T1, employer_agent_relation T2 WHERE T2.auto_create_jobid='" . $auto_create_jobId . "' AND T2.status=1 AND T1.allyted_member_id = T2.agent_id AND T1.status=1"));

            if ($getJobAgentId != null) {
                $agentAllow = getAgentAllowedNotify($getJobAgentId['recruiter_auto_create_id']);
                if ($agentAllow['status']) {
                    $data = $agentAllow['data'];


                    if ($data['job_alerts'] == 1) {
                        $q = "INSERT INTO tbl_notification SET notify_to='Agent',agent_id='" . $data['user_id'] . "',activity='Job', notification_type='" . $notiTypeAgent . "',notification_text='" . $Message . "',jobId='" . $job_id . "',job_autoId='" . $auto_create_jobId . "',status=1";

                        $insertNoti = mysqli_query($con, $q);
                    }
                }
            }

            $stat = ['message' => 'Updated Successfully', 'status' => true];
        } else {
            $stat = ['message' => 'Failed!', 'status' => false];
        }
    } else {
        $stat = ['message' => 'missing params', 'status' => false];
    }
} else {
    $stat = ['message' => 'Something Went Wrong please try again', 'status' => false];
}
echo json_encode($stat);
