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
date_default_timezone_set('asia/kolkata');
$date = date("M j, Y");
$data = [];
$whereClause = '';
$interviewerIds = [];

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
    $agent_id = $_GET['agent_id'];
    $whereClause = "T1.recruiter_auto_id='" . $agent_id . "' &&";
} else if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $recruiter_id = $_GET['recruiter_id'];
    $whereClause = "T1.jobrecruiterid='" . $recruiter_id . "' &&";
} else {
    $status = ['status' => false, 'message' => 'Invalid params'];
    echo json_encode($status);
    exit;
}

// ? removed date condition from query
// ? T1.scheduled_date LIKE '%$date%'  &&


$query = "SELECT *,NULL as password,T4.round_title as roundName, T3.id as jobId FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3, tbl_interview_rounds T4 WHERE  (T1.status=1 OR T1.status=2) &&  $whereClause T2.applicant_id= T1.applicant_id && T2.status=1 && T3.auto_create_jobid = T1.job_id AND T4.id = T1.round ORDER BY scheduled_date ASC $limitClause";

$count = mysqli_num_rows(mysqli_query($con, "SELECT *,NULL as password,T4.round_title as roundName, T3.id as jobId FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3, tbl_interview_rounds T4 WHERE  (T1.status=1 OR T1.status=2) &&  $whereClause T2.applicant_id= T1.applicant_id && T2.status=1 && T3.auto_create_jobid = T1.job_id AND T4.id = T1.round ORDER BY scheduled_date ASC"));

// echo $query;
// exit;
$getTodayInterviews = mysqli_query($con, $query);

while ($fetch = mysqli_fetch_assoc($getTodayInterviews)) {
    $roundNames = [];
    $Assignedrounds = explode(',', $fetch['assigned_rounds']);

    foreach ($Assignedrounds as $round) {
        $q = "SELECT round_title FROM tbl_interview_rounds WHERE id='" . $round . "' AND status=1";
        @$round = mysqli_fetch_assoc(mysqli_query($con, $q));
        if ($round != null) {
            $roundNames[] = $round['round_title'];
        }
    }

    $fetch['AssignedRoundNames'] = implode(',', $roundNames);
    $data[] = $fetch;
    $interviewerIds[] = $fetch['interviewer_id'];
}

foreach ($interviewerIds as $key => $id) {
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

if ($data != null) {
    $status = ['status' => true, 'data' => $data, 'count' => $count];
} else {
    $status = ['status' => false, 'message' => 'No Data Found'];
}
echo json_encode($status);
