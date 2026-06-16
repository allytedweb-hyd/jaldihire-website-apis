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

// Accessing the Headers
$headers = apache_request_headers();

// Destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

if (isset($_GET['recruiter_ids']) && !empty($_GET['recruiter_ids'])) {
    // Get the recruiter_ids as a comma-separated string and convert it to an array
    $recruiterIds = explode(',', $_GET['recruiter_ids']);
    $data = [];
    $totalApplicantsCount = 0; // Initialize total applicants count

    // Loop through each recruiter_id and fetch the assigned applicants
    foreach ($recruiterIds as $recruiterId) {
        $recruiterId = mysqli_real_escape_string($con, $recruiterId);
        $jobskills = $_GET['jobskill'];

        // Fetch assigned applicants for the recruiter
        $getApplicants = mysqli_query($con, "SELECT * FROM applicant_recruiter_assigns T1, tbl_applicant_add T2 WHERE T1.recruiter_assigned_id='" . $recruiterId . "' AND T2.applicant_id = T1.applicant_assigned_id AND T2.main_skill='" . $jobskills . "'&& T2.status=1 && T1.status=1");

        $applicants = [];
        while ($row = $getApplicants->fetch_assoc()) {
            $applicants[] = $row;
        }

        // Count applicants for the current recruiter
        $recruiterApplicantsCount = count($applicants);
        $totalApplicantsCount += $recruiterApplicantsCount; // Add to total count

        // Store the applicants data for the current recruiterId
        $data[$recruiterId] = [
            'count' => $recruiterApplicantsCount,
            'applicants' => $applicants
        ];
    }

    // Include total applicant count in the response
    $status = ['status' => true, 'total_applicants' => $totalApplicantsCount, 'data' => $data];
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($status);
