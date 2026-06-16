<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

$limit = 4;
$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;

$ids = [];
$jobIds = [];
$data = [];
$whereClause = '';
$jobsIdClause = '';
$interviewerIds = [];
if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    $employerId = $_GET['employer_id'];
    $interviewIds = [];

    $q = "SELECT *,T4.round_title as roundName, T3.id as jobId FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3, tbl_interview_rounds T4 WHERE T1.employer_id='" . $employerId . "' AND T2.applicant_id = T1.applicant_id && T1.current_round_status=1 && T1.status=0 && T3.auto_create_jobid = T1.job_id AND T4.id = T1.round LIMIT $limit OFFSET $offset";

    $count = mysqli_num_rows(mysqli_query($con, "SELECT *,T4.round_title as roundName, T3.id as jobId FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3, tbl_interview_rounds T4 WHERE T1.employer_id='" . $employerId . "' AND T2.applicant_id = T1.applicant_id && T1.current_round_status=1 && T1.status=0 && T3.auto_create_jobid = T1.job_id AND T4.id = T1.round"));

    $getInterviewCompleted = mysqli_query($con, $q);

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

    // exit(json_encode($data));

    $getScheduledAppli = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE employer_id='" . $employerId . "'");

    $getEmployerJobs = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE company_Id='" . $employerId . "'");

    while ($jobs = mysqli_fetch_assoc($getEmployerJobs)) {
        $jobIds[] = $jobs['auto_create_jobid'];
    }

    while ($fetch = mysqli_fetch_assoc($getScheduledAppli)) {
        $ids[] = $fetch['applicant_id'];
    }

    if (count($ids) > 0) {
        $idsStr = join("','", $ids);
        $whereClause = "assigned_applicant_id NOT IN ('" . $idsStr . "') &&";
    }

    if (count($jobIds) > 0) {
        $jobIdStr = join("','", $jobIds);
        $jobsIdClause = "assigned_job_id IN ('" . $jobIdStr . "') &&";
    }

    // echo $whereClause;
    // echo $jobsIdClause;
    // exit();

    $query = "SELECT *, T3.id as jobId FROM assigned_jobs_list T1, tbl_applicant_add T2,tbl_post_a_job T3  WHERE $whereClause $jobsIdClause T3.company_Id = '" . $employerId . "' AND T2.applicant_id=T1.assigned_applicant_id && T1.status=1 && T2.status=1 && T1.admin_status=1 && T3.auto_create_jobid = T1.assigned_job_id LIMIT $limit OFFSET $offset";

    $count += mysqli_num_rows(mysqli_query($con, "SELECT *, T3.id as jobId FROM assigned_jobs_list T1, tbl_applicant_add T2,tbl_post_a_job T3  WHERE $whereClause $jobsIdClause T3.company_Id = '" . $employerId . "' AND T2.applicant_id=T1.assigned_applicant_id && T1.status=1 && T2.status=1 && T1.admin_status=1 && T3.auto_create_jobid = T1.assigned_job_id"));

    // echo $query;
    // exit;
    $getApplicants = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($getApplicants)) {
        $data[] = $row;
    }

    if ($data != null) {
        $status = ['status' => true, 'message' => 'fetched Successfully', 'data' => $data, 'count' => $count];
    } else {
        $status = ['status' => false, 'message' => 'No data found'];
    }
} else {
    $status = ['status' => true, 'message' => 'Invalid params'];
}

echo json_encode($status);
