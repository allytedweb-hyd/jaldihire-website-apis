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

$ids = [];
$jobIds = [];
$data = [];
$whereClause = '';
$jobsIdClause = '';
$idClause = '';

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

if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
    $agentId = $_GET['agent_id'];
    $idClause = "recruiter_auto_id = '" . $agentId . "'";
} else if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $recruiter_id = $_GET['recruiter_id'];
    $idClause = "jobrecruiterid='" . $recruiter_id . "'";
} else {
    $status = ['status' => true, 'message' => 'Invalid params'];
    echo json_encode($status);
    exit;
}

$interviewIds = [];
$getInterviewCompleted = mysqli_query($con, "SELECT *,T4.round_title as roundName FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3, tbl_interview_rounds T4 WHERE T1.$idClause AND T2.applicant_id = T1.applicant_id && T1.current_round_status=1 && T1.status=0 && T3.auto_create_jobid = T1.job_id AND T4.id = T1.round $limitClause");

$count = mysqli_num_rows(mysqli_query($con, "SELECT *,T4.round_title as roundName FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3, tbl_interview_rounds T4 WHERE T1.$idClause AND T2.applicant_id = T1.applicant_id && T1.current_round_status=1 && T1.status=0 && T3.auto_create_jobid = T1.job_id AND T4.id = T1.round"));


while ($toSchedule = mysqli_fetch_assoc($getInterviewCompleted)) {
    $data[] = $toSchedule;
    $interviewIds[] = $toSchedule['interviewer_id'];
}

foreach ($interviewIds as $key => $id) {
    $interviewerDetails = mysqli_query($con, "SELECT *,name as interviewerName FROM tbl_add_team WHERE auto_create_id='" . $id . "' && status=1");
    while ($getDetails = $interviewerDetails->fetch_assoc()) {
        $data[$key] += $getDetails;
    }
    if (!isset($data['interviewerName']) || !empty($data['interviewerName'])) {
        $interviewerDetails = mysqli_query($con, "SELECT *,company_name as interviewerName FROM tbl_add_employer WHERE employer_auto_create_id='" . $id . "'");
        while ($getDetails = $interviewerDetails->fetch_assoc()) {
            $data[$key] += $getDetails;
        }
    }
}

$getScheduledAppli = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause");

while ($fetch = mysqli_fetch_assoc($getScheduledAppli)) {
    $ids[] = $fetch['applicant_id'];
    $jobIds[] = $fetch['job_id'];
}

if (count($ids) > 0) {
    $idsStr = join("','", $ids);
    $jobIdStr = join("','", $jobIds);
    $whereClause = "assigned_applicant_id NOT IN ('" . $idsStr . "') &&";
    $jobsIdClause = "assigned_job_id IN ('" . $jobIdStr . "') &&";
}

$query = "SELECT * FROM assigned_jobs_list T1, tbl_applicant_add T2, tbl_post_a_job T3  WHERE $whereClause $jobsIdClause T2.applicant_id=T1.assigned_applicant_id && T1.status=1 && T2.status=1 && T1.admin_status=1 && T3.auto_create_jobid = T1.assigned_job_id $limitClause";

$count += mysqli_num_rows(mysqli_query($con, "SELECT * FROM assigned_jobs_list T1, tbl_applicant_add T2, tbl_post_a_job T3  WHERE $whereClause $jobsIdClause T2.applicant_id=T1.assigned_applicant_id && T1.status=1 && T2.status=1 && T1.admin_status=1 && T3.auto_create_jobid = T1.assigned_job_id"));


// echo $query;
// exit;

$getApplicants = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($getApplicants)) {
    $data[] = $row;
}


if ($data != null) {
    $status = ['status' => true, 'message' => 'fetched Successfully', 'data' => $data];
} else {
    $status = ['status' => false, 'message' => 'No data found'];
}

echo json_encode($status);
