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

// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

if (isset($_GET['employer']) && !empty($_GET['employer'])) {
    $employer = $_GET['employer'];
    $getJobsCount = 0;
    $getHiredCount = 0;
    $isAssign = 0;

    $get_employer = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_add_employer  WHERE employer_id='" . $employer . "'"));

    if ($get_employer != null) {
        $getPlan = mysqli_fetch_assoc(mysqli_query($con, "SELECT T1.plan,T2.Resource_manager,T2.Panel_members FROM tbl_plans T1, tbl_plan_features T2  WHERE T1.plan_id='" . $get_employer['plan_id'] . "' AND T2.plan_id='" . $get_employer['plan_id'] . "'"));
        if ($getPlan != null) {
            $get_employer += $getPlan;
        }
        $getHiredCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE employer_id='" . $get_employer['employer_auto_create_id'] . "' && status=6"));

        if (isset($_GET['agentId']) && !empty($_GET['agentId'])) {
            $chechAssign = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM assigned_Recruiters_list WHERE assigned_recruiter_id='" . $_GET['agentId'] . "' AND assigned_employer_id='" . $employer . "' AND status=1"));

            if ($chechAssign != null) {
                $isAssign = 1;
            }
        }
    }

    $getJobsCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE employer_id='" . $employer . "' && status=1"));


    $status = ['status' => true, 'data' => $get_employer, 'jobsCount' => $getJobsCount, 'hired' => $getHiredCount, 'isAssign' => $isAssign];
} else {
    $status = ['status' => false, 'message' => 'Invalid params'];
}
echo json_encode($status);
