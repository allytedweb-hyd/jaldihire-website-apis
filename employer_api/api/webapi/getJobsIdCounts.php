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

$limit = 4;
$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    $employerId = $_GET['employer_id'];
    $jobData = [];
    $data = [];

    $getAllJobs = mysqli_query($con, "SELECT *,no_of_positions as positions FROM tbl_post_a_job WHERE employer_id='" . $employerId . "' && status=1 && (job_status='New' OR job_status='Progress') LIMIT $limit OFFSET $offset");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT *,no_of_positions as positions FROM tbl_post_a_job WHERE employer_id='" . $employerId . "' && status=1 && (job_status='New' OR job_status='Progress')"));

    while ($getJobIds = mysqli_fetch_assoc($getAllJobs)) {
        $jobData[] = $getJobIds;
        $jobIds[] = $getJobIds['auto_create_jobid'];
    }

    // exit(json_encode($jobData));

    foreach ($jobIds as $key => $job) {
        $gethold = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $job . "' && status=3"));
        $holdCount = ['holdCount' => $gethold];

        $getRejected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $job . "' && status=4"));
        $rejectedCount = ['RejectedCount' => $getRejected];

        $getSelected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $job . "' && status=5"));
        $selectedCount = ['selectedCount' => $getSelected];

        $getOnBoarded = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $job . "' && status=6"));
        $OnBoardedCount = ['OnBoardedCount' => $getOnBoarded];

        $getAssigned = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assigned_jobs_list WHERE assigned_job_id='" . $job . "' && admin_status=1 &&  status=1"));
        $assignedCount = ['assignedCount' => $getAssigned];

        $jobData[$key] += $holdCount;
        $jobData[$key] += $rejectedCount;
        $jobData[$key] += $selectedCount;
        $jobData[$key] += $OnBoardedCount;
        $jobData[$key] += $assignedCount;
    }

    $status = ['status' => true, 'data' => $jobData, 'count' => $count];
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($status);
