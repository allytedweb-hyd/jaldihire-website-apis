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
$agentId = '';
$recruiterId = '';

if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {

    $agent_id = $_GET['agent_id'];

    $alljob_id = "recruiter_auto_id='" . $agent_id . "'";

    $jobs = mysqli_query($con, "SELECT * FROM tbl_allyted_team T1, employer_agent_relation T2, tbl_post_a_job T3 WHERE T1.recruiter_auto_create_id='" . $agent_id . "' AND T2.agent_id=T1.allyted_member_id AND  T3.auto_create_jobid = T2.auto_create_jobid AND T3.status=1");

    @$jobsCount = mysqli_num_rows(@$jobs);
    $scheduled = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='1' AND resheduled_status='0'");
} elseif (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {

    $recruiter_id = $_GET['recruiter_id'];

    $alljob_id = "jobrecruiterid='" . $recruiter_id . "'";

    $jobs = mysqli_query($con, "SELECT * FROM tbl_post_a_job a,assign_job_recruiter b WHERE a.id = b.assigned_job and b.assigned_reqruiter='" . $recruiter_id . "'");

    @$jobsCount = mysqli_num_rows(@$jobs);
    $scheduled = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status=0 AND resheduled_status='0'");
} else {
    $status = ['status' => true, 'Message' => 'Invalid Params'];
    echo json_encode($status);
    exit();
}

$onGoing = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='2'");

$hold = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='3'");

$rejected = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='4'");

$hired = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='5'");

$onBoarding = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='6'");

$rescheduled = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id &&resheduled_status='1'");

$scheduledCount = mysqli_num_rows($scheduled);
$onGoingCount = mysqli_num_rows($onGoing);
$holdCount = mysqli_num_rows($hold);
$rejectedCount = mysqli_num_rows($rejected);
$hiredCount = mysqli_num_rows($hired);
$onBoardingCount = mysqli_num_rows($onBoarding);
$rescheduledCount = mysqli_num_rows($rescheduled);

$counts = ['jobs' => @$jobsCount, 'scheduled' => $scheduledCount, 'onGoing' => $onGoingCount, 'hold' => $holdCount, 'rejected' => $rejectedCount, 'hired' => $hiredCount, 'onBoarding' => $onBoardingCount, 'rescheduled' => $rescheduledCount];

$status = ['status' => true, "data" => $counts];

echo json_encode($status);
