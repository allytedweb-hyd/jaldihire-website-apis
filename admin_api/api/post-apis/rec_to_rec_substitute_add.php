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
include '../lib/mails.php';

date_default_timezone_set("Asia/Calcutta");
$datetime = date("Y-m-d H:i:s");
$epochTimer = intval(strtotime($datetime) . '000');
// echo json_encode($epochTimer);
// exit();

$addgent = mysqli_query($con, "SELECT * FROM substitute_recruiter  WHERE from_epoch <= '" . $epochTimer . "' && status=1");

$data = [];
while ($fetchData = mysqli_fetch_assoc($addgent)) {
    $data[] = $fetchData;
}
$status = ['data' => $data];

foreach ($data as $recData) {

    // echo json_encode($agentData);
    // exit();
    $presentRecruiter = $recData['present_recruiter'];
    $subRecruiter = $recData['substitute_recruiter'];
    // $jobId = $agentData['job_id'];
    $updatedById = $recData['assigned_by_id'];

    // to change assigned applicants 
    $UPDATE_QUERY = "UPDATE applicant_recruiter_assigns TABLE1, substitute_recruiter TABLE2 SET TABLE1.recruiter_assigned_id = '{$subRecruiter}', TABLE1.role_id = '{$updatedById}' , TABLE1.updated_on = '" . $datetime . "', TABLE1.lastUpdatedBy = '{$updatedById}', TABLE2.status=2, TABLE2.updated_on='{$datetime}' WHERE TABLE1.recruiter_assigned_id='{$presentRecruiter}' AND TABLE2.status=1 AND TABLE2.present_recruiter='{$presentRecruiter}'";

    // to change Recruiter Jobs
    $UPDATE_JOBS = "UPDATE assign_job_recruiter TABLE1 SET assigned_reqruiter = '{$subRecruiter}', updated_by = '{$updatedById}' , updatedOn = '" . $datetime . "', lastUpdatedBy = '{$updatedById}' WHERE assigned_reqruiter='{$presentRecruiter}' AND status=1";

    $getSubRecDeatils = getRecDetails($con, $subRecruiter);
    // $getPresentAgentDeatils = getAgentDetails($con, $presentAgent);

    // echo json_encode($getSubAgentDeatils);
    // exit();
    mysqli_query($con, $UPDATE_QUERY);
    mysqli_query($con, $UPDATE_JOBS);

    $subject = "Acknwoledgement: Recruiter Job Assign";
    $lambdaSubject = "Agent Substitute";
    $heading = "Job Acknwoledgement";
    $nameRecepient = $getSubRecDeatils['member_name'];

    $body = "Hi " . $nameRecepient . " (Agent).You are added as a Substitute Agent start work and add Recruiter";

    sendEmail($getSubRecDeatils['member_email'], $subject, $heading, $body);
}
?>
<?php

function getRecDetails($con, $id)
{
    return mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id ='{$id}'"));
}

?>