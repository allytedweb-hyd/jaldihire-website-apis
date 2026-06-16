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
include '../libs/jwt.php';
include '../notificationApis/notification_functions.php';
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);

@$response = TokenVerify($token);

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {

    if ($response['role'] == 'Agent' || $response['role'] == 'admin' || $response['role'] == 'RM') {
        $jobId = $_GET['job_id'];
        $employerId = $_GET['employer_id'];
        $closedByRole = $response['role'];
        $closedById = $response['userId'];

        $getJobDet = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE id='" . $jobId . "' && employer_id='" . $employerId . "' && close_status=1 && job_status='closed'");

        if (mysqli_num_rows($getJobDet) > 0) {
            $stat = ['status' => false, 'message' => 'Already Closed!'];
            exit(json_encode($stat));
        }

        $closeJob = mysqli_query($con, "UPDATE tbl_post_a_job SET close_status=1,job_status='closed',closed_by_id='" . $closedById . "',closed_by_role='" . $closedByRole . "' WHERE id='" . $jobId . "' && employer_id='" . $employerId . "' && status=1");

        if ($closeJob == true) {
            $getJobData = mysqli_fetch_assoc(mysqli_query($con, "SELECT auto_create_jobid FROM tbl_post_a_job WHERE id='" . $jobId . "'"));

            $Message = "Job Id < " . $getJobData['auto_create_jobid'] . "> closed successfully";

            if ($response['role'] == 'Agent') {
                $notiTypeEmp = 'info';
                $notiTypeAgent = 'Success';
            } else {
                $notiTypeEmp = 'Success';
                $notiTypeAgent = 'info';
            }
            // ? code to send notification to employer

            $empAllowNoti = getAllowedNotifications($closedById);

            if ($empAllowNoti['status']) {


                $empData = $empAllowNoti['data'];

                if ($empData['job_alerts'] == 1) {

                    $query = "INSERT INTO tbl_notification SET notify_to='Employer',employer_id='" . $closedById . "', activity='Job', notification_type='" . $notiTypeEmp . "',notification_text='" . $Message . "',jobId='" . $jobId . "',job_autoId='" . $getJobData['auto_create_jobid'] . "'";

                    $addNotification = mysqli_query($con, $query);
                }
            }

            // ? code to check job agent and check notification status and send notification
            $getJobAgentId = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM  employer_agent_relation  WHERE auto_create_jobid='" . $getJobData['auto_create_jobid'] . "' AND status=1 "));

            if ($getJobAgentId != null) {
                $agentAllow = getAgentAllowedNotify($getJobAgentId['agent_id']);
                if ($agentAllow['status']) {
                    $data = $agentAllow['data'];


                    if ($data['job_alerts'] == 1) {
                        $q = "INSERT INTO tbl_notification SET notify_to='Agent',agent_id='" . $data['user_id'] . "',activity='Job', notification_type='" . $notiTypeAgent . "',notification_text='" . $Message . "',jobId='" . $jobId . "',job_autoId='" . $getJobData['auto_create_jobid'] . "',status=1";

                        $insertNoti = mysqli_query($con, $q);
                    }
                }
            }

            $stat = ['status' => true, 'message' => 'Job Closed!'];
        } else {
            $stat = ['status' => false, 'message' => 'Failed!'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'Invalid Role'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
