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
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);

TokenVerify($token);

$limit = 10;
$skip = 0;

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = intval($_GET['skip']);
}
$offset = $limit * $skip;
$data = [];
if (!empty($_GET['empId'])) {
    $empId = $_GET['empId'];
    // $get_job = mysqli_query($con, "SELECT *,T2.id as row_id FROM tbl_post_a_job T1, employer_agent_relation T2 ,tbl_allyted_team T3 WHERE BINARY T1.employer_id='" . $empId . "' AND T2.employer_id = BINARY T1.employer_id and T2.auto_create_jobid = T1.auto_create_jobid and T3.allyted_member_id = BINARY T2.agent_id && T1.status=1");

    $job_ids = [];
    $get_job = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE employer_id='" . $empId . "' AND status=1 LIMIT $limit OFFSET $offset");
    $countQuery = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE employer_id='" . $empId . "' AND status=1 ");
    $count = mysqli_num_rows($countQuery);
    while ($fetch = mysqli_fetch_assoc($get_job)) {
        $data[] = $fetch;
        $job_ids[] = $fetch['auto_create_jobid'];
    }

    // for loop to get job assign agent details
    foreach ($job_ids as $key => $jobId) {
        $get_agent = mysqli_query($con, "SELECT * FROM employer_agent_relation T1,tbl_allyted_team T2 WHERE BINARY T1.employer_id='" . $empId . "' AND T1.auto_create_jobid ='" . $jobId . "'  AND T2.allyted_member_id = BINARY T1.agent_id && T1.status=1 AND T2.status=1");

        while ($fetchAgent = mysqli_fetch_assoc($get_agent)) {
            $data[$key] += $fetchAgent;
        }
    }

    // for loop to get agent substitute details
    foreach ($job_ids as $key => $jobId) {
        $getAssignedDetails = mysqli_query($con, "SELECT * FROM job_agent_substitute WHERE job_id='" . $jobId . "' && (status=2 OR status=1)");
        while ($fet = $getAssignedDetails->fetch_assoc()) {
            $data[$key] += $fet;
        }
    }
    if ($data != null) {
        $status = ['status' => true, 'data' => $data, 'count' => $count];
    } else {
        $status = ['status' => false, 'message' => 'No Jobs Found'];
    }
} else {
    $status = ['status' => false, 'message' => "Missing Parameters"];
}
echo json_encode($status);
