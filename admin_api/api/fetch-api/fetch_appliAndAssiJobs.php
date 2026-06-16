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

$data = [];
$clause = '';
if (isset($_GET['appli_id']) && isset($_GET['appli_id'])) {

    $id = $_GET['appli_id'];

    if (isset($_GET['applied']) && !empty($_GET['applied'])) {
        $clause = "T1.requested_id='" . $id . "' AND T1.requested_role='Applicant' &&";
    }

    $q = "SELECT * FROM assigned_jobs_list T1, tbl_post_a_job T2 WHERE $clause T1.assigned_applicant_id='" . $id . "' AND T2.auto_create_jobid = T1.assigned_job_id $limitClause";

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assigned_jobs_list T1, tbl_post_a_job T2 WHERE $clause T1.assigned_applicant_id='" . $id . "' AND T2.auto_create_jobid = T1.assigned_job_id"));

    $getJobs = mysqli_query($con, $q);

    while ($fet = mysqli_fetch_assoc($getJobs)) {
        $data[] = $fet;
    }

    if ($data != null) {
        $status = ['status' => true, 'data' => $data, 'count' => $count];
    } else {
        $status = ['status' => false, 'message' => 'No Jobs Found', 'count' => $count];
    }
} else {
    $status = ['status' => true, 'message' => 'Invalid Params'];
}

echo json_encode($status);
