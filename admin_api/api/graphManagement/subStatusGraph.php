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
        // @$dateType = $_GET['dateType'];


        // Checking the Inputs

        if (empty($empoyerId) || empty($technology) || empty($skill)) {
            $status = ["status" => false, "message" => "Invalid Params"];
            exit(json_encode($status));
        }

        $query = "SELECT TABLE2.subStatus FROM tbl_post_a_job TABLE1, applicant_scheduled TABLE2, tbl_applicant_add TABLE3 WHERE TABLE2.job_id=TABLE1.auto_create_jobid AND TABLE2.applicant_id = TABLE3.applicant_id AND TABLE1.technology = '{$technology}' AND TABLE1.employer_id = '{$empoyerId}' AND TABLE1.primary_skill = '{$skill}' AND TABLE2.subStatus != '' GROUP BY TABLE2.subStatus";

        $getQuery = mysqli_query($con, $query);

        $allReasons = [];

        while ($fetch = mysqli_fetch_assoc($getQuery)) {
            $allReasons[] = $fetch['subStatus'];
        }

        $reasons = [];
        $hold = [];
        $rejected = [];

        foreach ($allReasons as $key => $value) {
            $holdCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE subStatus='" . $value . "' AND status=3"));
            $rejectedCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE subStatus='" . $value . "' AND status=4"));
            if ($holdCount > 0 || $rejectedCount > 0) {
                array_push($reasons, $value);
                array_push($hold, $holdCount);
                array_push($rejected, $rejectedCount);
            }
        }


        $status = [
            "status" => true,
            "hold" => $hold,
            "reasons" => $reasons,
            "rejected" => $rejected
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
