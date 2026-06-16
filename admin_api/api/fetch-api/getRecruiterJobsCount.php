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

if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    $recruiterId = $_GET['recruiter_id'];
    $jobData = [];
    $data = [];

    $getAllJobs = mysqli_query($con, "SELECT *,no_of_positions as positions FROM tbl_post_a_job T1, assign_job_recruiter T2 WHERE T2.assigned_reqruiter='" . $recruiterId . "' AND T2.status=1 AND T1.id= T2.assigned_job AND T1.status=1");

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

    $status = ['status' => true, 'data' => $jobData];
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($status);
