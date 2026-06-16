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

require "../libs/mails.php";
include "../../includes/db-connection/db.php";
include '../libs/jwt.php';
include '../notificationApis/notification_functions.php';

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {

    $data = json_decode($json);
    $epoch_date = $data->epoch_date;
    $scheduled_date = $data->scheduledDateTime;
    $employer_id = $data->employer_id;
    $jobid = $data->jobid;
    $interviewType = $data->interviewType;

    $matchedObjects = $data->MatchedObjects;

    // Get Employer Details
    $getemployer = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_add_employer WHERE employer_auto_create_id="' . $employer_id . '" && status=1'));
    $getemployername = $getemployer['company_name'];
    $getemployermail = $getemployer['emp_email'];

    // Get Employer Rounds
    $getemployerrounds = mysqli_query($con, 'SELECT * FROM tbl_interview_rounds WHERE emp_id="' . $employer_id . '" && status=1');
    $first_round_id = '';
    if ($getemployerrounds) {
        $round = mysqli_fetch_assoc($getemployerrounds);
        $first_round_id = $round['id'];
    }

    $response = []; // To store details for each applicant

    foreach ($matchedObjects as $applicant) {
        $applicantId = $applicant->applicantId;
        $interviewerId = $applicant->interviewerId;

        // Check if the applicant is already scheduled for the same job
        $check_sheduletable = mysqli_query($con, 'SELECT * FROM applicant_scheduled WHERE applicant_id="' . $applicantId . '" AND job_id="' . $jobid . '"');
        if (mysqli_num_rows($check_sheduletable) > 0) {

            $App_Id = $applicantId;
            $whereClause = [];

            $getScheduledInterviewDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE applicant_id ='{$App_Id}'"));
            $assignedround = $getScheduledInterviewDetails['round'];
            $AppId = $getScheduledInterviewDetails['applicant_id'];
            if (isset($data->round) && !empty($data->round)) {
                $updatedRounds = $getScheduledInterviewDetails['assigned_rounds'] . $data->round . ',';
                array_push($whereClause, "round='{$data->round}'");
                array_push($whereClause, "interviewer_id='" . $interviewerId . "'");
                array_push($whereClause, "assigned_rounds='{$updatedRounds}'");
                array_push($whereClause, "current_round_status=0");
            }

            if (isset($data->current_round_status) && !empty($data->current_round_status)) {
                array_push($whereClause, "current_round_status='{$data->current_round_status}'");
            }

            if (isset($data->epoch_date) && !empty($data->epoch_date)) {
                array_push($whereClause, "epoch_date='{$data->epoch_date}'");
            }

            if (isset($data->scheduledDateTime) && !empty($data->scheduledDateTime)) {
                array_push($whereClause, "scheduled_date='{$data->scheduledDateTime}'");
            }

            // if (isset($data->status) && !empty($data->status)) {
            //     array_push($whereClause, "status='{$data->status}'");
            // }

            $arrayClause = '';


            if (count($whereClause) > 0) {
                $arrayClause = implode(",", $whereClause);
            } else {
                $status = ['status' => false, 'message' => 'please submit any data to update'];
                echo json_encode($status);
                exit();
            }

            $query = "UPDATE applicant_scheduled SET status = 1, $arrayClause WHERE applicant_id = '{$App_Id}' AND job_id = '{$jobid}'";

            $scheduledapplicant = mysqli_query($con, $query);


            if ($scheduledapplicant == true) {
                $response[] = [
                    'applicantId' => $applicantId,
                    'status' => true,
                    'message' => 'Applicant scheduled Succesfully.'
                ];
            }



            continue;
        }

        if (!empty($applicantId)) {
            $getapprecruiters = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM applicant_recruiter_assigns WHERE applicant_assigned_id="' . $applicantId . '" && status=1'));
            $getrecruiter = $getapprecruiters['recruiter_assigned_id'];

            $apprecruiter = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_allyted_team WHERE allyted_member_id="' . $getrecruiter . '" && status=1'));
            $recruiterautoid = $apprecruiter['recruiter_auto_create_id'];
            $recruitername = $apprecruiter['member_name'];
            $recruitermail = $apprecruiter['member_email'];
        }

        $scheduledapplicant = mysqli_query($con, "INSERT INTO applicant_scheduled SET 
            epoch_date='" . $epoch_date . "', 
            applicant_id='" . $applicantId . "', 
            employer_id='" . $employer_id . "', 
            round='" . $data->round . "', 
            interviewer_id='" . $interviewerId . "', 
            current_round_status=0, 
            job_id='" . $jobid . "', 
            assigned_rounds='" . $data->round . ",', 
            scheduled_date='" . $scheduled_date . "', 
            recruiter_auto_id='" . $recruiterautoid . "', 
            recruiter_mail='" . $recruitermail . "', 
            recruiter_name='" . $recruitername . "', 
            employer_name='" . $getemployername . "', 
            employer_mail='" . $getemployermail . "', 
            jobrecruitermail='" . $recruitermail . "', 
            job_recruitername='" . $recruitername . "', 
            jobrecruiterid='" . $getrecruiter . "', 
            interviewType='" . $interviewType . "', 
            resheduled_status=0, 
            status=1");

        if ($scheduledapplicant) {
            $last_id = mysqli_insert_id($con);
            mysqli_query($con, "INSERT INTO interview_record SET 
                schedule_id='" . $last_id . "', 
                applicant_id='" . $applicantId . "', 
                employer_id='" . $employer_id . "', 
                interviewer_id='" . $interviewerId . "', 
                job_id='" . $jobid . "', 
                agent_id='" . $recruiterautoid . "', 
                agent_name='" . $recruitername . "', 
                agent_mail='" . $recruitermail . "', 
                recruiter_id='" . $getrecruiter . "', 
                recruiter_name='" . $recruitername . "', 
                recruiter_mail='" . $recruitermail . "', 
                scheduled_date='" . $scheduled_date . "', 
                employer_name='" . $getemployername . "', 
                employer_mail='" . $getemployermail . "', 
                round='" . $first_round_id . "', 
                interviewType='" . $interviewType . "', 
                interview_status=0");

            $response[] = [
                'applicantId' => $applicantId,
                'status' => true,
                'message' => 'Applicant scheduled successfully.'
            ];
        } else {
            $response[] = [
                'applicantId' => $applicantId,
                'status' => false,
                'message' => 'Failed to schedule applicant due to a database error.'
            ];
        }
    }

    $stat = ['status' => true, 'message' => 'Processing completed.', 'details' => $response];
} else {
    $stat = ['status' => false, 'message' => 'Invalid or empty payload.'];
}

echo json_encode($stat);
