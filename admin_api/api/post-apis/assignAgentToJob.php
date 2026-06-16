<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$status = [];
$message = '';


if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $assign = $data->to;
    $assignedJobId = $data->assignedJobId;
    $assignedAgentId = $data->assignedAgentId;
    @$adminId = $data->adminId;
    @$assignId = $data->Assign_id;
    @$AssignedRole = $data->AssignedRole;
    @$employerId = $data->employerId;

    $finalAgentsList = [];
    $existStatus = [];
    $nonExistStatus = [];

    if ($assign == 'jobs') {
        $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $assignedJobId . "' && job_status='closed' && close_status=1");
        if (mysqli_num_rows($checkJobStatus) > 0) {
            $status = ['status' => false, 'message' => 'This Job is Closed'];
            exit(json_encode($status));
        }
    }

    if ($assign == 'jobs') {
        $checkEmployerAssign = mysqli_query($con, "SELECT * FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $employerId . "' && assigned_recruiter_id='" . $assignedAgentId . "' && status=1");
    }

    if (mysqli_num_rows($checkEmployerAssign) > 0) {
        if ($assign == 'jobs') {
            $query = "SELECT * FROM employer_agent_relation WHERE auto_create_jobid='" . $assignedJobId . "' && status=1";
            $assignedAgent = mysqli_query($con, $query);
            $assignedAgentCount = mysqli_num_rows($assignedAgent);
            if ($assignedAgentCount > 0) {
                $assignAgentData = mysqli_fetch_assoc($assignedAgent);
                $agentData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $assignAgentData['agent_id'] . "'"));
                if ($assignedAgentCount == 0) {
                    array_push($finalAgentsList, $assignedAgentId);
                    array_push($nonExistStatus, $agentData['member_name']);
                } else {
                    array_push($existStatus, $agentData['member_name']);
                }
            }
        }

        if ($assign == 'jobs') {

            if ($existStatus) {
                $message .= " Already Assigned to " . implode(',', $existStatus);
                $status = ['status' => false, 'message' => $message];
            } else {
                $assignAgent = "INSERT INTO employer_agent_relation SET  auto_create_jobid='" . $assignedJobId . "' ,employer_id='" . $employerId . "',agent_id='" . $assignedAgentId . "',admin_id='" . $adminId . "',assigned_by_role='" . $AssignedRole . "',assigned_by_id='" . $assignId . "', status=1";

                $Agentsmails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $assignedAgentId . "'"));

                $employermails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE status=1 && employer_id= '" . $employerId . "'"));

                $Jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && id= '" . $assignedJobId . "'"));

                $inserted = mysqli_query($con, $assignAgent);

                if ($inserted == true) {

                    // if (count($finalAgentsList) > 0) {

                    $agentsubject = "Jaldihire: Agent Assign to job";
                    $empsubject = "Jaldihire: Agent Assign to job";

                    $agentMail = $Agentsmails['member_email'];
                    $employer_mails = $employermails['emp_email'];

                    $agentbody = "Here is the mail to inform Agent";
                    $employerbody = "Here is the mail to inform Employer";

                    jobAssignAgentEmailtoAgent($Agentsmails['member_email'], $agentsubject, $agentbody);
                    jobAssignAgentEmailtoEmployer($employermails['emp_email'], $empsubject, $employerbody);

                    $message = " Assigned to " . $Agentsmails['member_name'];
                    // }
                    $status = ['status' => true, 'message' => $message];
                } else {
                    $status = ['status' => false, 'message' => 'Failed to Assign'];
                }
            }
        } else {
            $status = ['status' => false, 'message' => 'Failed to Assign'];
        }
    } else {
        $status = ['status' => false, 'message' => 'Please Assign to Employer'];
    }
} else {
    $status = ['status' => false, 'message' => 'Something went wrong please try again'];
}
echo json_encode($status);
