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

$json = file_get_contents('php://input');
$data = json_decode($json);
$stat = [];

if (isset($json) && !empty($json)) {

    @$id = $data->id;
    $agentId = $data->agent_id;
    $job_id = $data->job_id;
    $status = $data->status;

    $assignAgent = mysqli_query($con, "UPDATE employer_agent_relation SET status='" . $status . "' WHERE agent_id='" . $agentId . "' && auto_create_jobid='" . $job_id . "'");

    if ($assignAgent) {
        if ($status == 1) {
            $updateJob = mysqli_query($con, "UPDATE tbl_post_a_job SET status='1' WHERE auto_create_jobid='" . $job_id . "'");
        }
        $status = ['status' => true, 'Message' => 'Updated Successfully'];
    } else {
        $status = ['status' => false, 'Message' => 'failed please try Again'];
    }
} else {
    $status = ['status' => false, 'Message' => 'Something went wrong! please try again'];
}
echo json_encode($status);
