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

$limit = 5;
$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;

if (isset($_GET['jobId']) && !empty($_GET['jobId'])) {
    $jobId = $_GET['jobId'];
    $interViewerIds = [];
    $empId = [];
    $data = [];
    $getInterview = mysqli_query($con, "SELECT *, T3.round_title as roundName FROM interview_record T1, tbl_applicant_add T2 ,tbl_interview_rounds T3  WHERE T1.job_id='" . $jobId . "' AND T2.applicant_id = T1.applicant_id && T2.status = 1 AND T3.id = T1.round LIMIT $limit OFFSET $offset");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT *, T3.round_title as roundName FROM interview_record T1, tbl_applicant_add T2, tbl_interview_rounds T3 WHERE T1.job_id='" . $jobId . "' AND T2.applicant_id = T1.applicant_id && T2.status = 1 AND T3.id = T1.round"));

    while ($fetch = mysqli_fetch_assoc($getInterview)) {
        $data[] = $fetch;
        $interViewerIds[] = $fetch['interviewer_id'];
        $empId[] = $fetch['employer_id'];
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

    if ($data == null) {
        $status = ['status' => false, 'message' => 'No Data Found'];
    } else {
        $status = ['status' => true, 'data' => $data, 'count' => $count];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid params'];
}

echo json_encode($status);
