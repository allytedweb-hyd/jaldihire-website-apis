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

$query = "SELECT * FROM job_recruiter_substitute WHERE to_epoch <= $epochTimer && status=2";




$addrecruiter = mysqli_query($con, $query);



$data = [];
while ($fetchData = mysqli_fetch_assoc($addrecruiter)) {
    $data[] = $fetchData;
}

$status = ['data' => $data];
// exit(json_encode($data));

foreach ($data as $recruiterData) {

    $presentRecruiter = $recruiterData['present_recruiter'];
    $subRecruiter = $recruiterData['substitute_recruiter'];
    $jobId = $recruiterData['job_id'];
    $updatedById = $recruiterData['assigned_by_id'];

    $UPDATE_QUERY = "UPDATE assign_job_recruiter TABLE1, job_recruiter_substitute TABLE2 SET TABLE1.assigned_reqruiter = '{$presentRecruiter}', TABLE1.assigned_by_id = '{$updatedById}' , TABLE1.updatedOn = '" . $datetime . "', TABLE1.lastUpdatedBy = '{$updatedById}', TABLE2.status=0, TABLE2.updated_on='{$datetime}' WHERE TABLE1.assigned_job='{$jobId}' AND TABLE2.status=2 AND TABLE2.job_id='{$jobId}'";



    // mysqli_query($con, $UPDATE_QUERY);

    // echo json_encode($UPDATE_QUERY);
    // exit();

    $getSubRecruiterDeatils = getRecruiterDetails($con, $presentRecruiter);
    // $getPresentAgentDeatils = getAgentDetails($con, $presentAgent);


    mysqli_query($con, $UPDATE_QUERY);


    $subject = "Acknwoledgement: Recruiter Job Assign";
    $jaldihireSubject = "Job Assign | $jobId";
    $heading = "Job Acknwoledgement";
    $nameRecepient = $getSubRecruiterDeatils['member_name'];

    $body = "Hi " . $nameRecepient . " (Agent).You are added to $jobId(job) start work and add Recruiter";

    sendEmail($getSubRecruiterDeatils['member_email'], $jaldihireSubject, $heading, $body, $jobId);
}

// echo json_encode($status);
?>
<?php

function getRecruiterDetails($con, $id)
{
    return mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE allyted_member_id ='{$id}'"));
}

?>