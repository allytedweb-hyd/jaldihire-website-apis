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
$headers = apache_request_headers();
date_default_timezone_set("Asia/Calcutta");

// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

@$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
@$skip = intval($_GET['skip']);

@$offset = $limit * $skip;
$limitClause = '';
$data = [];

if (isset($_GET['limit']) && isset($_GET['skip'])) {
    $limitClause = "LIMIT $limit OFFSET $offset";
}

if (isset($_GET['appId']) && !empty($_GET['appId'])) {

    $getSavedJobs = mysqli_query($con, "SELECT * FROM tbl_saved_jobs T1,tbl_post_a_job T2 WHERE T1.applicant_id='" . $_GET['appId'] . "' AND T2.id = T1.job_id AND T1.status=1 AND T2.status=1 $limitClause");
    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_saved_jobs T1,tbl_post_a_job T2 WHERE T1.applicant_id='" . $_GET['appId'] . "' AND T2.id = T1.job_id AND T1.status=1 AND T2.status=1"));

    while ($fet = mysqli_fetch_assoc($getSavedJobs)) {
        $data[] = $fet;
    }
    if ($data != null) {
        $stat = ['status' => true, 'data' => $data, 'count' => $count];
    } else {
        $stat = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($stat);
