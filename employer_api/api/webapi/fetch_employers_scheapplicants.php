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

@$status = $_GET['status'];
@$limit = $_GET['limit'] ? $_GET['limit'] : 8;
@$skip = $_GET['skip'] ? $_GET['skip'] : 0;

$offset = $limit * $skip;

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    @$employer_id = $_GET['employer_id'];
    $whereClause = "scheduled.employer_id='" . $employer_id . "'";
} elseif (isset($_GET['auto_create_id']) && !empty($_GET['auto_create_id'])) {
    @$team_id = $_GET['auto_create_id'];
    $whereClause = "scheduled.interviewer_id='" . $team_id . "'";
} else {
    $stat = ['status' => false, 'Message' => 'Invalid Params'];
    echo json_encode($stat);
    exit;
}
$data = [];
$sheduleIds = [];
$interViewerIds = [];
$empId = [];

$q = "SELECT *,T3.round_title as roundName, T4.id as jobId, scheduled.employer_id as empId FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3, tbl_post_a_job T4 WHERE applicant.applicant_id=scheduled.applicant_id AND $whereClause AND scheduled.status='" . $status . "' AND T3.id = scheduled.round  AND T4.auto_create_jobid = scheduled.job_id LIMIT $limit OFFSET $offset";


$count = mysqli_num_rows(mysqli_query($con, "SELECT *,T3.round_title as roundName, T4.id as jobId, scheduled.employer_id as empId FROM applicant_scheduled scheduled,tbl_applicant_add applicant, tbl_interview_rounds T3, tbl_post_a_job T4 WHERE applicant.applicant_id=scheduled.applicant_id && $whereClause && scheduled.status='" . $status . "' AND T3.id = scheduled.round AND T4.auto_create_jobid = scheduled.job_id"));

// Execute the query
// $result = mysqli_query($con, $query);
// $count = mysqli_num_rows($result);

// Output both the query and count
// echo "Query: " . $query . "\n";
// echo "Count: " . $count;
// exit();

$get_scheduled_interviews = mysqli_query($con, $q);

while ($fetchschedules = mysqli_fetch_array($get_scheduled_interviews)) {
    $data[] = $fetchschedules;
    $sheduleIds[] = $fetchschedules['schedule_id'];
    $interViewerIds[] = $fetchschedules['interviewer_id'];
    $empId[] = $fetchschedules['empId'];
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

if ($status == 2) {
    foreach ($sheduleIds as $key => $id) {
        $p = [];
        $pending = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE updated_on <= DATE_SUB(DATE_SUB(NOW(), INTERVAL 1 HOUR), INTERVAL 30 MINUTE) AND schedule_id='" . $id . "'"));

        if ($pending != null) {
            $p['pendingstatus'] = '1';
            $data[$key] += $p;
        } else {
            $p['pendingstatus'] = '0';
            $data[$key] += $p;
        }
    }
}
$stat = ['status' => true, 'data' => $data, 'count' => $count];
echo json_encode($stat);
