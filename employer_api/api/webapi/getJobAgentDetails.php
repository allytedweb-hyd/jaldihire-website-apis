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

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $jobId = $_GET['job_id'];

    $get_agent_data = mysqli_query($con, "SELECT * FROM tbl_allyted_team a,employer_agent_relation b WHERE b.auto_create_jobid ='" . $jobId . "' && a.allyted_member_id = b.agent_id && a.status=1 && b.status=1");
    $data = [];

    while ($fetchAgents = mysqli_fetch_array($get_agent_data)) {
        $data[] = $fetchAgents;
    }

    $status = ['status' => true, 'Message' => 'fetched Successfully', 'data' => $data];
} else {
    $status = ['status' => false, 'Message' => "Missing Parameters"];
}

echo json_encode($status);
