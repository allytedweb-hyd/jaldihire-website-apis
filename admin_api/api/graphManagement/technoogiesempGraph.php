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

        @$employerName = $_GET['employerName'];

        $query = "SELECT employer_id FROM tbl_add_employer WHERE company_name='" . $employerName . "' AND status = 1";

        $getEmployer = mysqli_fetch_assoc(mysqli_query($con, $query));

        $technologies = [];
        $jobPosts = [];

        $getTechnologies = mysqli_query($con, "SELECT technology FROM tbl_post_a_job WHERE employer_id='" . $getEmployer['employer_id'] . "' GROUP BY technology");
        while ($fetch = mysqli_fetch_assoc($getTechnologies)) {
            array_push($technologies, $fetch['technology']);
        }

        foreach ($technologies as $key => $value) {
            $jobPostsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE employer_id='" . $getEmployer['employer_id'] . "' AND technology='" . $value . "'"));
            array_push($jobPosts, $jobPostsCount);
        }

        $status = [
            "status" => true,
            "technologies" => $technologies,
            "jobsCount" => $jobPosts,
            "employerId" => $getEmployer['employer_id']
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
