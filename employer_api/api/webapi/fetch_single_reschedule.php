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

@$employer_id = $_GET['employer_id'];
@$scheduled_id = $_GET['scheduled_id'];
@$recruiter_id = $_GET['recruiter_id'];
@$role = $_GET['role'];


if ($role == 'employer') {
    if (isset($employer_id) && !empty($employer_id)) {

        $countQuery = "SELECT *,T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && scheduled.employer_id='" . $employer_id . "' && scheduled.resheduled_status=1";

        $get_scheduled_count = mysqli_query($con, $countQuery);
        $totalCount = mysqli_num_rows($get_scheduled_count);

        $get_scheduled_interviews = mysqli_query($con, "SELECT *, T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && scheduled.employer_id='" . $employer_id . "'AND T3.id = scheduled.round && (scheduled.emp_resch_status=1 OR scheduled.resheduled_status=1) ");

        $fetchschedules = mysqli_fetch_assoc($get_scheduled_interviews);

        if ($fetchschedules != null) {
            $status = ['status' => true, 'message' => "success", 'data' => $fetchschedules];
        } else {
            $status = ['status' => false, 'message' => "No Data Found"];
        }
    } else {
        $status = ['error' => "Missing Parameters"];
    }
}
if ($role == 'recruiter') {

    if (isset($recruiter_id) && !empty($recruiter_id)) {

        $get_scheduled_interviews = mysqli_query($con, "SELECT *, T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && scheduled.jobrecruiterid='" . $recruiter_id . "'AND T3.id = scheduled.round && (scheduled.emp_resch_status=1 OR scheduled.resheduled_status=1)");

        $data = [];
        while ($fetchschedules = mysqli_fetch_assoc($get_scheduled_interviews)) {
            $data[] = $fetchschedules;
        }
        $status = ['status' => true, 'message' => "success", 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => "failed", 'err' => mysqli_error($con)];
    }
}

if (isset($scheduled_id) && !empty($scheduled_id)) {

    $get_scheduled_interviews = mysqli_query($con, "SELECT *, T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && scheduled.schedule_id='" . $scheduled_id . "'AND T3.id = scheduled.round && (scheduled.emp_resch_status=1 OR scheduled.resheduled_status=1) ");

    $fetchschedules = mysqli_fetch_assoc($get_scheduled_interviews);

    if ($fetchschedules != null) {
        $status = ['status' => true, 'message' => "success", 'data' => $fetchschedules];
    } else {
        $status = ['status' => false, 'message' => "No Data Found"];
    }
}
echo json_encode($status);
