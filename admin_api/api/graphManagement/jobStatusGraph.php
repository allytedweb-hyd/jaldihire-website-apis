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

include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


if ($token) {

    // Validate the Token

    $response = verifyJWT($token);

    if ($response['status']) {
        $idClause = '';

        // Accessing Params
        if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {

            $agentId = $_GET['agent_id'];

            $overDueJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_agent_relation T1,tbl_post_a_job T2 WHERE T1.agent_id='" . $agentId . "' AND T1.auto_create_jobid = T2.auto_create_jobid AND T2.job_status='overdue' AND T2.status=1"));

            $NewJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_agent_relation T1,tbl_post_a_job T2 WHERE T1.agent_id='" . $agentId . "' AND T1.auto_create_jobid = T2.auto_create_jobid AND T2.job_status='New' AND T2.status=1"));

            $InProgressCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_agent_relation T1,tbl_post_a_job T2 WHERE T1.agent_id='" . $agentId . "' AND T1.auto_create_jobid = T2.auto_create_jobid AND T2.job_status='progress'"));

            $closedJobCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_agent_relation T1,tbl_post_a_job T2 WHERE T1.agent_id='" . $agentId . "' AND T1.auto_create_jobid = T2.auto_create_jobid AND T2.job_status='closed'"));
        } else if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
            $recruiter_id = $_GET['recruiter_id'];

            $overDueJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assign_job_recruiter T1,tbl_post_a_job T2 WHERE T1.assigned_reqruiter='" . $recruiter_id . "' AND T1.assigned_job = T2.ID AND T2.job_status='overdue' AND T2.status=1"));

            $NewJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assign_job_recruiter T1,tbl_post_a_job T2 WHERE T1.assigned_reqruiter='" . $recruiter_id . "' AND T1.assigned_job = T2.ID AND T2.job_status='New' AND T2.status=1"));

            $InProgressCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assign_job_recruiter T1,tbl_post_a_job T2 WHERE T1.assigned_reqruiter='" . $recruiter_id . "' AND T1.assigned_job = T2.ID AND T2.job_status='progress'"));

            $closedJobCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assign_job_recruiter T1,tbl_post_a_job T2 WHERE T1.assigned_reqruiter='" . $recruiter_id . "' AND T1.assigned_job = T2.ID AND T2.job_status='closed'"));
        } else {
            $status = ['status' => false, 'Message' => 'Invalid Params'];
            exit(json_encode($status));
        }

        $status = [
            "status" => true,
            "New" => $NewJobsCount,
            "Overdue" => $overDueJobsCount,
            "progress" => $InProgressCount,
            "closed" => $closedJobCount,
        ];

        echo json_encode($status);
    } else {
        $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
        echo json_encode($status);
    }
} else {
    $status = ["status" => false, "message" => "Unauthorized Access"];
    echo json_encode($status);
}
