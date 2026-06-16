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

// $status = $_GET['status'];
@$limit = $_GET['limit'] ? $_GET['limit'] : 8;
@$skip = $_GET['skip'] ? $_GET['skip'] * $limit : 0;

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    $employer_id = $_GET['employer_id'];
    $whereClause = "scheduled.employer_id='" . $employer_id . "'";
} elseif (isset($_GET['auto_create_id']) && !empty($_GET['auto_create_id'])) {
    $team_id = $_GET['auto_create_id'];
    $whereClause = "scheduled.interviewer_id='" . $team_id . "'";
} else {
    $stat = ['status' => false, 'Message' => 'Invalid Params'];
    echo json_encode($stat);
    exit;
}

$countQuery = "SELECT *, T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.resheduled_status=1 AND T3.id = scheduled.round";
$get_scheduled_count = mysqli_query($con, $countQuery);
$totalCount = mysqli_num_rows($get_scheduled_count);

$get_scheduled_interviews = mysqli_query($con, "SELECT *, T3.round_title as roundName FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3 WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.resheduled_status=1 AND T3.id = scheduled.round");
$data = [];
$interViewerIds = [];
$empId = [];


while ($fetchschedules = mysqli_fetch_array($get_scheduled_interviews)) {
    $data[] = $fetchschedules;
    $interViewerIds[] = $fetchschedules['interviewer_id'];
    $empId[] = $fetchschedules['employer_id'];
}

foreach ($interViewerIds as $key => $id) {
    if ($id == $empId[$key]) {
        $query = "SELECT company_name as InterviewerName FROM tbl_add_employer WHERE employer_auto_create_id='" . $id . "' AND status=1";
        $getInterViewerName = mysqli_query($con, $query);
        while ($fet = mysqli_fetch_assoc($getInterViewerName)) {
            $data[$key] += $fet;
        }
    } else {
        $getInterViewerName = mysqli_query($con, "SELECT name as InterviewerName FROM tbl_add_team WHERE auto_create_id='" . $id . "' AND status=1");
        while ($fet = mysqli_fetch_assoc($getInterViewerName)) {
            $data[$key] += $fet;
        }
    }
}



$status = ['data' => $data, 'count' => $totalCount];

echo json_encode($status);
