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

        @$type = $_GET['type'] ? $_GET['type'] : 'month';

        $query = "SELECT employer_id,company_name FROM tbl_add_employer WHERE status = 1";

        $queryVal = mysqli_query($con, $query);
        $allEmployers = [];

        while ($fetch = mysqli_fetch_assoc($queryVal)) {
            $allEmployers[] = $fetch;
        }

        // [
        //     'dateFrom' => date('Y-m-d', strtotime(" -$count $dateType ")) . " 00:00:00",
        //     'dateTo' => date('Y-m-d') . " 23:59:59"
        // ];

        $labels = [];
        $jobPosts = [];

        foreach ($allEmployers as $key => $value) {
            $jobPostsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE employer_id='" . $value['employer_id'] . "'"));
            array_push($labels, $value['company_name']);
            array_push($jobPosts, $jobPostsCount);
        }

        $status = [
            "status" => true,
            "labels" => $labels,
            "jobPosts" => $jobPosts
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
