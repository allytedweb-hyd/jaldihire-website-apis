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
$json = file_get_contents('php://input');
$data = json_decode($json);
$stat = [];

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $assign = $data->to;
    $assignedEmployerId = $data->assignedemployerId;
    $assignedRecruiterId = $data->assignedRecruiterId;

    if ($assign == 'requestUpdate') {
        $query = "SELECT * FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $assignedEmployerId . "' && assigned_recruiter_id='" . $assignedRecruiterId . "' && status=1";
        $assignedEmployers = mysqli_query($con, $query);
        $assignedEmployersCount = mysqli_num_rows($assignedEmployers);
        if ($assignedEmployersCount > 0) {
            $status = ['status' => false, 'message' => 'Employer Already Assigned'];
            echo json_encode($status);
            exit();
        }
    }

    if ($assign == 'requestUpdate') {
        $myEmployerQuery = "UPDATE  assigned_Recruiters_list set  status=1 WHERE assigned_employer_id='" . $assignedEmployerId . "' && assigned_recruiter_id='" . $assignedRecruiterId . "'";
        $inserted = mysqli_query($con, $myEmployerQuery);
        if ($myEmployerQuery == true) {
            $stat = ['status' => true, 'message' => 'Access granted'];
        } else {
            $stat = ['status' => false, 'message' => 'Failed to Accept'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'Please Try Again!'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Somthing went wrong Please Try Again'];
}

echo json_encode($stat);
