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
include "../vendor/autoload.php";
include '../lib/mails.php';
include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);

TokenVerify($token);




$json = file_get_contents('php://input');
$data = json_decode($json);
$stat = [];



if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $assign = $data->to;
    $assignedEmployerId = $data->assignedEmployerId;
    $assignedRecruiterId = $data->assignedRecruiterId;
    $adminId = $data->adminId;
    $agentName = $data->agentName;
    $employerName = $data->employerName;

    if ($assign == 'employer') {

        $query = "SELECT * FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $assignedEmployerId . "' && assigned_recruiter_id='" . $assignedRecruiterId . "' && status=0";
        $assignedEmployers = mysqli_query($con, $query);
        $assignedEmployersCount = mysqli_num_rows($assignedEmployers);
        if ($assignedEmployersCount > 0) {
            $status = ['status' => false, 'message' => 'Requested earlier'];
            echo json_encode($status);
            exit();
        }

        $CheckAssign = "SELECT * FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $assignedEmployerId . "' && assigned_recruiter_id='" . $assignedRecruiterId . "' && status=1";
        $checkEmployerAssigned = mysqli_query($con, $CheckAssign);
        $checkEmployerAssignedCount = mysqli_num_rows($checkEmployerAssigned);
        if ($checkEmployerAssignedCount > 0) {
            $status = ['status' => false, 'message' => 'Employer Already Assigned'];
            echo json_encode($status);
            exit();
        }
    }


    if ($assign == 'employer') {
        $myEmployerQuery = "INSERT INTO assigned_Recruiters_list SET  assigned_employer_id='" . $assignedEmployerId . "' ,assigned_recruiter_id='" . $assignedRecruiterId . "', assigned_by='" . $adminId . "', status=0";
        $inserted = mysqli_query($con, $myEmployerQuery);
        $adminData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_login WHERE login_id='" . $adminId . "' and login_status=1"));
        // echo ($adminData);
        // exit;
        if ($myEmployerQuery == true) {

            $Subject = "AGENT EMPLOYER REQUEST ACCESS TO ADMIN";
            $adminName = $adminData['admin_name'];
            $adminMail = $adminData['login_mail'];

            $mailBody = " Hello, ( $adminName ), and welcome to the Jaldihire family.
            the Agent ( $agentName ) requested the Employer ( $employerName ) for Assigning 
            Thank you, Have a wonderful day";

            requestMailAccess($adminMail, $Subject, $mailBody);

            $stat = ['status' => true, 'message' => 'Requested SuccessFully'];
        } else {
            $stat = ['status' => false, 'message' => 'Failed to Request'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'Something went wrong! Please Try Again'];
    }
} else {
    $stat = ['status' => true, 'message' => 'Something went wrong! Please Try Again'];
}

echo json_encode($stat);
