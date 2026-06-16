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
include '../lib/mails.php';

date_default_timezone_set("Asia/Calcutta");
$datetime = date("Y-m-d H:i:s");
$epochTimer = intval(strtotime($datetime) . '000');
// echo json_encode($epochTimer);
// exit();

$addgent = mysqli_query($con, "SELECT * FROM substitute_agent  WHERE from_epoch <= '" . $epochTimer . "' && status=1");

$data = [];
while ($fetchData = mysqli_fetch_assoc($addgent)) {
    $data[] = $fetchData;
}
$status = ['data' => $data];

foreach ($data as $agentData) {

    // echo json_encode($agentData);
    // exit();
    $presentAgent = $agentData['present_agent'];
    $subAgent = $agentData['substitute_agent'];
    // $jobId = $agentData['job_id'];
    $updatedById = $agentData['assigned_by_id'];

    $UPDATE_QUERY = "UPDATE employer_agent_relation TABLE1, substitute_agent TABLE2 SET TABLE1.agent_id = '{$subAgent}', TABLE1.assigned_by_id = '{$updatedById}' , TABLE1.updatedOn = '" . $datetime . "', TABLE1.lastUpdatedBy = '{$updatedById}', TABLE2.status=2, TABLE2.updated_on='{$datetime}' WHERE TABLE1.agent_id='{$presentAgent}' AND TABLE2.status=1 AND TABLE2.present_agent='{$presentAgent}'";

    $UPDATE_EMPLOYER = "UPDATE assigned_Recruiters_list SET assigned_recruiter_id='{$subAgent}',assigned_by='{$updatedById}',updated_date='" . $datetime . "',lastUpdatedBy='{$updatedById}' WHERE assigned_recruiter_id='{$presentAgent}' AND status=1";


    $getSubAgentDeatils = getAgentDetails($con, $subAgent);
    // $getPresentAgentDeatils = getAgentDetails($con, $presentAgent);

    // echo json_encode($getSubAgentDeatils);
    // exit();
    mysqli_query($con, $UPDATE_QUERY);
    mysqli_query($con, $UPDATE_EMPLOYER);


    $subject = "Acknwoledgement: Recruiter Job Assign";
    $lambdaSubject = "Agent Substitute";
    $heading = "Job Acknwoledgement";
    $nameRecepient = $getSubAgentDeatils['member_name'];

    $body = "Hi " . $nameRecepient . " (Agent).You are added as a Substitute Agent start work and add Recruiter";

    sendEmail($getSubAgentDeatils['member_email'], $subject, $heading, $body);
}
?>
<?php

function getAgentDetails($con, $id)
{
    return mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id ='{$id}'"));
}

?>