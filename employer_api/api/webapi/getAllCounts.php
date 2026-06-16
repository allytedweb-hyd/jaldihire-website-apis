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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../includes/db-connection/db.php";

// $alljob_id = $_GET['employer_id'];

// $jobs = mysqli_query($con, "select * from tbl_post_a_job where company_Id='" . $alljob_id . "' && status=1");
// $scheduled = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& status=1");
// $onGoing = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& status=2");
// $hold = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& status=3");
// $rejected = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& status=4");
// $hired = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& status=5");
// $onBoarding = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& status=6");
// $rescheduled = mysqli_query($con, "select * from applicant_scheduled where employer_id='" . $alljob_id . "'&& resheduled_status=1");

// $jobsCount = mysqli_num_rows($jobs);
// $scheduledCount = mysqli_num_rows($scheduled);
// $onGoingCount = mysqli_num_rows($onGoing);
// $holdCount = mysqli_num_rows($hold);
// $rejectedCount = mysqli_num_rows($rejected);
// $hiredCount = mysqli_num_rows($hired);
// $onBoardingCount = mysqli_num_rows($onBoarding);
// $rescheduledCount = mysqli_num_rows($rescheduled);

// $counts = ['jobs' => $jobsCount, 'scheduled' => $scheduledCount, 'onGoing' => $onGoingCount, 'hold' => $holdCount, 'rejected' => $rejectedCount, 'hired' => $hiredCount, 'onBoarding' => $onBoardingCount, 'rescheduled' => $rescheduledCount];

// $status = ['status' => true, "data" => $counts];

// echo json_encode($status);


$companyId = '';
$alljob_id = '';

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {

    $allid = $_GET['employer_id'];

    $companyId = "company_Id='" . $allid . "'";

    $alljob_id = "employer_id='" . $allid . "'";

    $jobs = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE $companyId && status=1");
} elseif (isset($_GET['auto_create_id']) && !empty($_GET['auto_create_id'])) {

    $team_id = $_GET['auto_create_id'];

    $alljob_id = "interviewer_id = '" . $team_id . "'";

    $jobs = mysqli_query($con, "SELECT * FROM applicant_scheduled T1,tbl_add_team T2,     tbl_post_a_job T3, tbl_applicant_add T4 WHERE T1.interviewer_id = '" . $team_id . "' AND T2.auto_create_id = T1.interviewer_id AND BINARY T3.auto_create_jobid = T1.job_id AND T1.status=0 AND T4.applicant_id = T1.applicant_id");
} else {
    $status = ['status' => true, 'Message' => 'Invalid Params'];
    echo json_encode($status);
    exit();
}


$scheduled = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='1'");

$onGoing = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='2'");

$hold = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='3'");

$rejected = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='4'");

$hired = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='5'");

$onBoarding = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id && status='6'");

$rescheduled = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $alljob_id &&resheduled_status='1'");

$jobsCount = mysqli_num_rows($jobs);
$scheduledCount = mysqli_num_rows($scheduled);
$onGoingCount = mysqli_num_rows($onGoing);
$holdCount = mysqli_num_rows($hold);
$rejectedCount = mysqli_num_rows($rejected);
$hiredCount = mysqli_num_rows($hired);
$onBoardingCount = mysqli_num_rows($onBoarding);
$rescheduledCount = mysqli_num_rows($rescheduled);

$counts = ['jobs' => $jobsCount, 'scheduled' => $scheduledCount, 'onGoing' => $onGoingCount, 'hold' => $holdCount, 'rejected' => $rejectedCount, 'hired' => $hiredCount, 'onBoarding' => $onBoardingCount, 'rescheduled' => $rescheduledCount];

$status = ['status' => true, "data" => $counts];

echo json_encode($status);
