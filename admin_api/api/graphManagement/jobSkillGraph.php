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

        // Accessing Params

        @$empoyerId = $_GET['empoyerId'];
        @$technology = $_GET['technology'];
        @$skill = $_GET['skill'];


        // Checking the Inputs

        if (empty($empoyerId) || empty($technology) || empty($skill)) {
            $status = ["status" => false, "message" => "Invalid Params"];
            exit(json_encode($status));
        }

        $jobId = "SELECT auto_create_jobid as jobId FROM tbl_post_a_job WHERE employer_id='{$empoyerId}' AND technology='{$technology}' AND primary_skill='{$skill}'";

        $query = mysqli_query($con, $jobId);
        $allJobs = [];

        while ($fetch = mysqli_fetch_assoc($query)) {
            $allJobs[] = $fetch['jobId'];
        }
        $hiredCount = 0;
        $holdCount = 0;
        $selectedCount = 0;
        $rejectedCount = 0;
        $onGoingCount = 0;
        foreach ($allJobs as $job) {
            $countQuery = "SELECT SUM(status=2) as onGoing, SUM(status=3) as hold, SUM(status=4) as rejected, SUM(status=5) as selected, SUM(status=6) as hired FROM applicant_scheduled WHERE job_id='{$job}'";


            // getting all the counts

            $cntQue = mysqli_fetch_assoc(mysqli_query($con, $countQuery));
            $hiredCount = $hiredCount + intval($cntQue['hired']);
            $onGoingCount = $onGoingCount + intval($cntQue['onGoing']);
            $holdCount = $holdCount + intval($cntQue['hold']);
            $rejectedCount = $rejectedCount + intval($cntQue['rejected']);
            $selectedCount = $selectedCount + intval($cntQue['selected']);
        }

        $totalCounts = [
            "hired" => $hiredCount,
            "onGoing" => $onGoingCount,
            "hold" => $holdCount,
            "rejected" => $rejectedCount,
            "selected" => $selectedCount,
            "totalJobs" => count($allJobs)
        ];
        $status = ["status" => true, "jobSkillStatus" => $totalCounts];
        echo json_encode($status);
    } else {
        $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
        echo json_encode($status);
    }
} else {
    $status = ["status" => false, "message" => "Unauthorized Access"];
    echo json_encode($status);
}
