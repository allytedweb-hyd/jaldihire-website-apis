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


$firstEmployerId = '';
$data = [];
$status = '';

if (isset($_GET['agentId']) && !empty($_GET['agentId'])) {
    $recruiter_id = $_GET['agentId'];

    $query = mysqli_query($con, "SELECT * FROM employer_agent_relation WHERE agent_id='" . $recruiter_id . "' && status='1' ORDER BY id DESC $limitClause");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_agent_relation WHERE agent_id='" . $recruiter_id . "' && status='1' ORDER BY id DESC"));

    $ids = [];
    $jobIds = [];

    if (mysqli_num_rows($query) > 0) {

        while ($row = mysqli_fetch_assoc($query)) {
            $ids[] = $row['employer_id'];
            $jobIds[] = $row['auto_create_jobid'];
        }

        $data = [];

        for ($i = 0; $i < count($ids); $i++) {

            $getJobDetails = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE  auto_create_jobid='" . $jobIds[$i] . "' && status=1");

            while ($fetchJobs = mysqli_fetch_assoc($getJobDetails)) {
                $data[] = $fetchJobs;
            }
            $hiredCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $jobIds[$i] . "' && status=6"));

            $holdCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $jobIds[$i] . "' && status=3"));

            $rejCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $jobIds[$i] . "' && status=4"));

            $counts = [
                'hired' => $hiredCount,
                'hold' => $holdCount,
                'rejected' => $rejCount
            ];
            $data[$i] += $counts;
        }
        $status = ['status' => true, 'data' => $data, 'count' => $count];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid params'];
}
echo json_encode($status);





// if (isset($_GET['agentId']) && !empty($_GET['agentId'])) {
//     $recruiter_id = $_GET['agentId'];

//     $query = mysqli_query($con, "SELECT * FROM employer_agent_relation WHERE agent_id='" . $recruiter_id . "' && status='1'");

//     $ids = [];
//     $jobIds = [];

//     if (mysqli_num_rows($query) > 0) {

//         while ($row = mysqli_fetch_assoc($query)) {
//             $ids[] = $row['employer_id'];
//             $jobIds[] = $row['auto_create_jobid'];
//         }

//         $data = [];
//         for ($i = 0; $i < count($ids); $i++) {
//             $getJobDetails = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE  auto_create_jobid='" . $jobIds[$i] . "' && status=1");
//             while ($fetchJobs = mysqli_fetch_assoc($getJobDetails)) {
//                 $data[] = $fetchJobs;
//             }
//         }
//         $status = ['data' => $data];
//     }

//     $getDetails = mysqli_query($con, "SELECT * FROM assigned_Recruiters_list WHERE assigned_recruiter_id='" . $recruiter_id . "' && status=1");

//     $employerIds = [];
//     while ($row = mysqli_fetch_assoc($getDetails)) {
//         $employerIds[] = $row['assigned_employer_id'];
//     }

//     if ($getDetails != null) {


//         for ($i = 0; $i < count($employerIds); $i++) {
//             $get_recruiters_data = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE employer_id ='" . $employerIds[$i] . "' && status=1");
//             while ($fetchJobs = mysqli_fetch_array($get_recruiters_data)) {
//                 $data[] = $fetchJobs;
//             }
//         }
//         $status = ['data' => $data];
//     }
// } else {
//     $status = ['error' => "Missing Parameters"];
// }