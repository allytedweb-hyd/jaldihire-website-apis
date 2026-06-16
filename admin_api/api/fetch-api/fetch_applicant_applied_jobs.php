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

$data = [];

if (isset($_GET['applicant_id']) && !empty($_GET['applicant_id'])) {
    $applicant_id = $_GET['applicant_id'];
    $jobIds = [];

    // $getApplicantJobs = mysqli_query($con, "SELECT *,T4.company_name ,T2.role as jobRole,T3.status as interViewStatus,NULL as password FROM assigned_jobs_list T1,tbl_post_a_job T2, applicant_scheduled T3, tbl_add_employer T4 WHERE T1.assigned_applicant_id = '" . $applicant_id . "' AND T2.auto_create_jobid = T1.assigned_job_id AND T3.applicant_id = T1.assigned_applicant_id AND T1.admin_status=1 AND T1.status=1 AND T2.status=1 AND T4.employer_id = T2.employer_id AND T4.status=1");

    $getApplicantJobs = mysqli_query($con, "SELECT *,T3.company_name ,T2.role as jobRole,NULL as password FROM assigned_jobs_list T1,tbl_post_a_job T2, tbl_add_employer T3  WHERE T1.assigned_applicant_id = '" . $applicant_id . "' AND T2.auto_create_jobid = T1.assigned_job_id AND T1.admin_status=1 AND T1.status=1 AND T2.status=1 AND T3.employer_id = T2.employer_id AND T3.status=1");

    while ($fetchJobs = mysqli_fetch_assoc($getApplicantJobs)) {
        $data[] = $fetchJobs;
        $jobIds[] = $fetchJobs['auto_create_jobid'];
    }

    // exit(json_encode($jobIds));
    foreach ($jobIds as $key => $jobId) {
        $get_Interview_details = mysqli_query($con, "SELECT *,status as InterviewStatus FROM applicant_scheduled  WHERE applicant_id='" . $applicant_id . "' AND job_id='" . $jobId . "'");

        while ($fetchInterviews = mysqli_fetch_assoc($get_Interview_details)) {
            $data[$key] += $fetchInterviews;
        }
    }

    // exit(json_encode($data));

    if ($data != null) {
        $status = ['status' => true, 'message' => 'Fetched Successfully', 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid params'];
}
echo json_encode($status);
