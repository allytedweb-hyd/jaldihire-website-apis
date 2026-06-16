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

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {
    $employerId = $data->employerId;
    $rmId = $data->rmId;
    $applicantId = $data->applicantId;

    // if (empty($employerId) || empty($applicantId) || empty($rmId)) {
    //     exit(json_encode(['status' => false, 'message' => 'Missing required parameters']));
    // }
    if (empty($applicantId) || (empty($employerId) && empty($rmId))) {
        exit(json_encode(['status' => false, 'message' => 'Missing required parameters']));
    }


    // Check if the applicant is already viewed
    $existingApplicant = mysqli_query($con, "SELECT * FROM profile_view_bycount WHERE employer_id='" . $employerId . "' AND applicant_id='" . $applicantId . "'");

    if (mysqli_num_rows($existingApplicant) > 0) {
        // Applicant is already in the database, allow redirection
        exit(json_encode(['status' => true, 'message' => 'Applicant already viewed.']));
    }

    // Get the allowed count for the employer
    $getemployerDetails = mysqli_query($con, "SELECT app_profile_count FROM tbl_add_employer WHERE employer_auto_create_id='" . $employerId . "'");
    $employerDetails = mysqli_fetch_assoc($getemployerDetails);

    if (!$employerDetails) {
        exit(json_encode(['status' => false, 'message' => 'Employer not found']));
    }

    $interviewerLimit = (int)$employerDetails['app_profile_count'];

    // Count already viewed applicants
    $viewedCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM profile_view_bycount WHERE employer_id='" . $employerId . "'"));

    if ($viewedCount < $interviewerLimit) {
        // Insert the new applicant into the table
        $insert = mysqli_query($con, "INSERT INTO profile_view_bycount SET employer_id='" . $employerId . "', applicant_id='" . $applicantId . "', Rm_id='" . $rmId . "'");

        if ($insert) {
            exit(json_encode(['status' => true, 'message' => 'Access granted.']));
        } else {
            exit(json_encode(['status' => false, 'message' => 'Failed to record applicant view.']));
        }
    } else {
        // Deny access as the limit is reached
        exit(json_encode(['status' => false, 'message' => 'You do not have access to open this applicant.']));
    }
} else {
    exit(json_encode(['status' => false, 'message' => 'Invalid request']));
}
