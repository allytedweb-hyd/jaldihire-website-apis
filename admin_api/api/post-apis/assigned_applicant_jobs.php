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
require "../lib/mails.php";
include "../vendor/autoload.php";
include "../lib/jwt.php";

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
  echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
  exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);

$json = file_get_contents('php://input');
$stat = [];

if (isset($json) && !empty($json)) {
  $data = json_decode($json);
  @$assign = $data->to;
  @$assignedApplicantId = $data->assignedApplicantId;
  @$assignedJobId = $data->assignedJobId;
  @$assignedrecruiter = $data->assigned_recruiter;
  @$assignedAgent = $data->assigned_agent;

  @$finalJobsList = [];
  @$existStatus = [];
  @$nonExistStatus = [];
  @$requestStatus = [];
  @$closedStatus = [];
  @$noWalletJobs = [];

  if ($assign == 'applicant') {
    $query = "SELECT * FROM applicant_recruiter_assigns WHERE applicant_assigned_id='" . @$assignedApplicantId . "' AND recruiter_assigned_id='" . @$assignedrecruiter . "' AND status=1";
    $checkReqAssign = mysqli_num_rows(mysqli_query($con, $query));
    if ($checkReqAssign == 0) {
      $status = ['status' => false, 'message' => 'You Do not have Access for this applicant'];
      exit(json_encode($status));
    }
  }

  if ($assign == 'applicant') {
    $checkAppli = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_id='" . @$assignedApplicantId . "' AND status=1 AND on_boarded=1"));
    if ($checkAppli > 0) {
      $status = ['status' => false, 'message' => 'Applicant Already On-Boarded'];
      exit(json_encode($status));
    }
  }

  if ($assign == 'applicant') {
    foreach ($assignedJobId as $id) {
      $checkEmployer = mysqli_fetch_assoc(mysqli_query($con, "SELECT T1.auto_create_jobid, T2.Onboardings , T2.Interviews FROM tbl_post_a_job T1, tbl_add_employer T2 WHERE T1.auto_create_jobid ='" . $id . "' AND T2.employer_id = T1.employer_id"));
      if (!$checkEmployer['Onboardings'] > 0 || !$checkEmployer['Interviews'] > 0) {
        $noWalletJobs[] = $checkEmployer['auto_create_jobid'];
      }
    }
  }

  if ($assign == 'applicant') {
    foreach ($assignedJobId as $item) {
      $checkJobStatus = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE auto_create_jobid='" . $item . "' && job_status='closed' && close_status=1");
      $closedJobs = mysqli_num_rows($checkJobStatus);
      $closeJobData = mysqli_fetch_assoc($checkJobStatus);
      if ($closedJobs > 0) {
        array_push($closedStatus, $closeJobData['auto_create_jobid']);
      }
    }
  }

  if ($assign == 'applicant') {
    foreach ($assignedJobId as $item) {
      $query = "SELECT * FROM assigned_jobs_list WHERE assigned_applicant_id='" . $assignedApplicantId . "' && assigned_job_id='" . $item . "' && admin_status=0 && status=0";
      $Requestedjobs = mysqli_query($con, $query);
      $fetchJobRequest = mysqli_fetch_assoc($Requestedjobs);
      $RequestedjobsCount = mysqli_num_rows($Requestedjobs);
      $jobDataone = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid= '" . $item . "'"));
      if ($RequestedjobsCount != 0) {
        array_push($requestStatus, $jobDataone['auto_create_jobid']);
      }
    }
  }

  if (!empty($requestStatus) && !empty($fetchJobRequest)) {
    if (count($requestStatus) > 0) {
      foreach ($requestStatus as $item) {
        $acceptQuery = "UPDATE assigned_jobs_list SET admin_status=0,assigned_recruiter='" . $assignedrecruiter . "',status=1 WHERE assigned_job_id='" . $item . "' && assigned_applicant_id='" . $fetchJobRequest['assigned_applicant_id'] . "'";
        $acceptJobRequest = mysqli_query($con, $acceptQuery);
        $status = '';
        if ($acceptJobRequest == true) {
          if (count($requestStatus) > 0) {
            $status .= "Assigned to " . implode(',', $requestStatus);
          }
          $stat = ['status' => true, 'message' => $status];
        } else {
          $stat = ['status' => false, 'message' => 'Failed to Assign'];
        }
      }
      echo json_encode($stat);
      exit();
    }
  }

  if ($assign == 'applicant') {
    foreach ($assignedJobId as $item) {
      $query = "SELECT * FROM assigned_jobs_list WHERE assigned_applicant_id='" . $assignedApplicantId . "' &&assigned_job_id='" . $item . "' && (admin_status=1 OR admin_status=0) && status=1";
      $assignedjobs = mysqli_query($con, $query);
      $assignedjobsCount = mysqli_num_rows($assignedjobs);
      $jobDataone = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid= '" . $item . "'"));
      if ($assignedjobsCount == 0) {
        array_push($finalJobsList, $item);
        array_push($nonExistStatus, $jobDataone['auto_create_jobid']);
      } else {
        array_push($existStatus, $jobDataone['auto_create_jobid']);
      }
    }
  }

  $finalJobsList = array_diff($finalJobsList, $closedStatus, $noWalletJobs);

  if ($assign == 'applicant') {
    if (count($finalJobsList) > 0) {
      foreach ($finalJobsList as $item) {

        @$getAgent = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM employer_agent_relation WHERE auto_create_jobid='" . $item . "' && status=1"));

        @$myJobsQuery = "INSERT INTO assigned_jobs_list SET  assigned_applicant_id='" . @$assignedApplicantId . "' ,assigned_job_id='" . $item . "',assigned_agent='" . @$getAgent['agent_id'] . "',assigned_recruiter='" . $assignedrecruiter . "',admin_status=0,status=1";

        @$applicantmails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE status=1 && applicant_id = '" . $assignedApplicantId . "'"));
        @$agentdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM  tbl_allyted_team WHERE status=1 && allyted_member_id  = '" . @$getAgent['agent_id'] . "'"));
        @$Jobdata = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE status=1 && auto_create_jobid= '" . $item . "'"));

        $inserted = mysqli_query($con, $myJobsQuery);
        if ($myJobsQuery == true) {

          @$subject = "Acknwoledgement: Applicant Job Assign";
          @$jdSubject = "Applicant Job Assign";
          @$heading = "Applicant Job Assign ";
          @$nameAgent = @$agentdata['member_name'];
          @$agentMail = @$agentdata['member_email'];
          @$jobidRecepient = @$Jobdata['auto_create_jobid'];
          @$applicantName = @$applicantmails['full_name'];
          @$applicantmail = @$applicantmails['e_mail'];

          $applicantbody = "Hi $applicantName (Applicant).You are added to '" . @$Jobdata['auto_create_jobid'] . "' get ready for interview process";
          $Agentbody = "Hi  $nameAgent (Agent). Recruiter Added $applicantName('" . @$applicantmails['applicant_auto_create_id'] . "') to this Jobs '" . @$Jobdata['auto_create_jobid'] . "'";

          sendEmail($agentMail, $jdSubject, $heading, $Agentbody);
          applicantMail($applicantmail, $jdSubject, $heading, $applicantbody);
          $status = '';

          if ($existStatus) {
            $status .= "Assigned to job" . implode(',', $existStatus) . "earlier";
          }

          if (count($nonExistStatus) > 0) {
            $status .= "Assigned to " . implode(',', $finalJobsList);
          }

          if (count($closedStatus) > 0) {
            $status .= " Job " . implode(',', $closedStatus) . " Are Closed";
          }

          if (count($noWalletJobs) > 0) {
            $status .= " Job " . implode(',', $noWalletJobs) . " Employer Have No Interviews in Wallet";
          }

          $stat = ['status' => true, 'message' => $status];
        } else {
          $stat = ['status' => false, 'message' => 'Failed to Assign'];
        }
      }
    } else {
      $stat = ['status' => false, 'message' => 'Applicant got assigned to a job earlier'];
    }
  } else {
    $stat = ['status' => false, 'message' => 'Failed to Assign'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong! Please Try Again'];
}
echo json_encode($stat);
