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
require "../lib/mails.php";
include "../lib/jwt.php";

$json = file_get_contents('php://input');
$data = json_decode($json);
$stat = [];

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    @$assign = $data->to;
    @$assignedApplicantId = $data->assignedApplicantId;
    @$assignedJobId = $data->assignedJobId;
    @$applicantmail = $data->applicant_mail;
    @$applicantname = $data->applicant_name;
    @$recruiter_id = $data->agent_id;

    // ? to check onboardings and interviews in emp wallet
    if ($assign == 'applicant') {
        $checkEmployer = mysqli_fetch_assoc(mysqli_query($con, "SELECT T1.auto_create_jobid, T2.Onboardings , T2.Interviews FROM tbl_post_a_job T1, tbl_add_employer T2 WHERE T1.auto_create_jobid ='" . $assignedJobId . "' AND T2.employer_id = T1.employer_id"));
        if (!$checkEmployer['Onboardings'] > 0 || !$checkEmployer['Interviews'] > 0) {
            exit(json_encode(['status' => false, 'message' => 'Employer Have No Interviews in Wallet']));
        }
    }

    // to check job access of agent     
    if ($assign == 'applicant') {
        $q = "SELECT * FROM employer_agent_relation  WHERE  auto_create_jobid = '" . $assignedJobId . "' AND status=1";
        $checkJobAccess = mysqli_num_rows(mysqli_query($con, $q));
        if ($checkJobAccess == 0) {
            $status = ['status' => false, 'message' => 'You Do not Have Access For This Job'];
            exit(json_encode($status));
        }
    }
    // to check job status 
    if ($assign == 'applicant') {
        $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $assignedJobId . "' && job_status='closed' && close_status=1");
        if (mysqli_num_rows($checkJobStatus) > 0) {
            $status = ['status' => false, 'message' => 'This Job is Closed'];
            exit(json_encode($status));
        }
    }

    // MULTIPLE JOBS FOR SINGLE USER
    if ($assign == 'applicant') {
        //  $job = explode(",",$assignedJobId);
        // for ($i = 0; $i < count($assignedJobId); $i++) {
        $query = "select * from assigned_jobs_list where assigned_applicant_id='" . $assignedApplicantId . "' &&assigned_job_id='" . $assignedJobId . "' && admin_status=1";
        $assignedjobs = mysqli_query($con, $query);
        $assignedjobsCount = mysqli_num_rows($assignedjobs);
        if ($assignedjobsCount > 0) {
            $status = ['status' => false, 'message' => 'Already Assigned To Job'];
            echo json_encode($status);
            exit();
        }
        // }
    }

    if ($assign == 'applicant') {

        $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT a.emp_email as email, a.company_name FROM tbl_add_employer a,assigned_Recruiters_list b WHERE a.employer_id = b.assigned_employer_id and b.assigned_recruiter_id='" . $recruiter_id . "'"));
        $id = $getDetails['email'];
        $empName = $getDetails['company_name'];

        //  $job = explode(",",$assignedJobId);
        // for ($i = 0; $i < count($assignedJobId); $i++) {
        $myApplicantQuery = "UPDATE assigned_jobs_list SET admin_status=1 WHERE  assigned_applicant_id='" . $assignedApplicantId . "' && assigned_job_id='" . $assignedJobId . "'";
        $inserted = mysqli_query($con, $myApplicantQuery);
        if ($myApplicantQuery == true) {

            $applicantSubject = "APPLICANT SUBMIT TO EMPLOYER";
            $employerSubject = "APPLICANT SUBMIT TO EMPLOYER FOR INTERVIEW PROCESS";

            $applicantBody = " Hello, { $applicantname  }, Hello and welcome to the Jaldihire family.
            you are successfully submitted to the Employer { $empName }
            Have a wonderful day";
            $employerBody = " Hello, { $empName  }, Hello and welcome to the Jaldihire family.
            the Applicant { $applicantname } successefully assigned to you, You may start the interview Schedule process
            Have a wonderful day";

            applicant_Email($applicantmail, $applicantSubject, $applicantBody);
            jobAssignAgentEmailtoEmployer($id, $employerSubject, $employerBody);

            $stat = ['status' => true, 'message' => 'successfully added to this job'];
        } else {

            $stat = ['status' => false, 'message' => 'Failed to Add'];
        }
        // }
    }
    // MULTIPLE USERS FOR SINGLE JOB
    else if ($assign == 'job') {
        // $applicant = explode(",",$assignedApplicantId);
        // for ($i = 0; $i < count($assignedApplicantId); $i++) {
        $myJobQuery = "UPDATE assigned_jobs_list SET status=1 WHERE  assigned_applicant_id='" . $assignedApplicantId . "' && assigned_job_id='" . $assignedJobId . "'";
        $inserted = mysqli_query($con, $myJobQuery);

        if ($myJobQuery == true) {
            $stat = ['status' => true, 'message' => 'successfully Added'];
        } else {

            $stat = ['status' => false, 'message' => 'Failed to Add'];
        }
        // }
    } else {
        $stat = ['successstatus' => 'Success!'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something Went Wrong please try again'];
}

echo json_encode($stat);
