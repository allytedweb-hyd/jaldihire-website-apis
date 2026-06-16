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
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

$limitClause = '';

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = 0;
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = 0;
    $skip = intval($_GET['skip']);
}
@$offset = $limit * $skip;

if (isset($_GET['limit']) && isset($_GET['skip'])) {
    $limitClause = "LIMIT $limit OFFSET $offset";
}


@$status = $_GET['status'];


if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
    @$agent_id = $_GET['agent_id'];
    $whereClause = "scheduled.recruiter_auto_id='" . $agent_id . "'";
} elseif (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    @$recruiter_id = $_GET['recruiter_id'];
    $whereClause = "scheduled.jobrecruiterid='" . $recruiter_id . "'";
} else {
    $stat = ['status' => false, 'Message' => 'Invalid Params'];
    echo json_encode($stat);
    exit;
}

$q = "SELECT * , T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.status='" . $status . "' AND T3.id = scheduled.round AND resheduled_status !=1 $limitClause";

$totalCount = mysqli_num_rows(mysqli_query($con, "SELECT * , T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.status='" . $status . "' AND T3.id = scheduled.round AND resheduled_status !=1"));

$get_scheduled_interviews = mysqli_query($con, $q);

$data = [];
while ($fetchschedules = mysqli_fetch_array($get_scheduled_interviews)) {
    $data[] = $fetchschedules;
}

$stat = ['status' => true, 'data' => $data, 'count' => $totalCount];
echo json_encode($stat);
