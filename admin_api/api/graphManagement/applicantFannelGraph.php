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

        @$applicantId = $_GET['applicant_id'];

        $getApplicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT main_role as role,total_expereience as experience FROM tbl_applicant_add WHERE applicant_id='" . $applicantId . "' AND status=1"));

        $experience = applicantExp($getApplicant['experience']);

        $query = "SELECT * FROM tbl_post_a_job WHERE role='" . $getApplicant['role'] . "' AND experience = '" . $experience . "'";

        $getJobsCount = mysqli_num_rows(mysqli_query($con, $query));

        $appliedJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assigned_jobs_list WHERE assigned_applicant_id='" . $applicantId . "' AND admin_status=1 AND status=1"));

        $interviewCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled  WHERE applicant_id='" . $applicantId . "'"));

        $offersCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled  WHERE applicant_id='" . $applicantId . "' AND (status=5 OR status=6)"));

        $hiredCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled  WHERE applicant_id='" . $applicantId . "' AND status=6"));

        $status = [
            "status" => true,
            "AvailableJobs" => $getJobsCount,
            "AppliedJobs" => $appliedJobsCount,
            "InterviewsAttended" => $interviewCount,
            "offersReceived" => $offersCount,
            "hiredCount" => $hiredCount
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

function applicantExp($experience)
{
    if ($experience < 1) {
        return '<1';
    } elseif ($experience >= 1 && $experience <= 3) {
        return '1-3';
    } elseif ($experience >= 3 && $experience <= 5) {
        return '3-5';
    } elseif ($experience >= 5 && $experience <= 8) {
        return '5-8';
    } elseif ($experience >= 8 && $experience <= 11) {
        return '8-11';
    } elseif ($experience >= 11 && $experience <= 13) {
        return '11-13';
    } else {
        return '13+';
    }
}
