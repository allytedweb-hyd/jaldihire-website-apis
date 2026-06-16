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
            $idClause = "recruiter_auto_id='" . $_GET['agent_id'] . "' &&";
        } else if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
            $idClause = "jobrecruiterid='" . $_GET['recruiter_id'] . "' &&";
        } else {
            $status = ['status' => false, 'Message' => 'Invalid Params'];
            exit(json_encode($status));
        }

        $hiredCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause status=6"));

        $SelectedCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause status=5"));

        $rejectedCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause status=4"));

        $holdCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause status=3"));

        $ongoingCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause status=2"));

        $scheduledCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE $idClause status=1"));

        $status = [
            "status" => true,
            "hired" => $hiredCount,
            "selected" => $SelectedCount,
            "rejected" => $rejectedCount,
            "hold" => $holdCount,
            "scheduled" => $scheduledCount,
            "onGoing" => $ongoingCount,
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
