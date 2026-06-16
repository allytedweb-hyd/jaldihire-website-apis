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


include '../notificationApis/notificationFunctions.php';
include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";
include '../lib/mails.php';
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
$data = json_decode($json);
$stat = [];




if (isset($json) && !empty($json)) {
  $data = json_decode($json);
  $assign = $data->to;
  $assignedEmployerId = $data->assignedEmployerId;
  $assignedAgentId = $data->assignedAgentsId;
  $assignedBy = $data->assigned_id;


  $finalRecuitersList = [];
  $existStatus = [];
  $nonExistStatus = [];
  $message = '';

  if ($assign == 'employer') {
    foreach ($assignedAgentId as $item) {
      $query = mysqli_query($con, "SELECT * FROM assigned_Recruiters_list WHERE assigned_employer_id='" . $assignedEmployerId . "' && assigned_recruiter_id='" . $item . "' && status=1");
      $assignedRecruiterCount = mysqli_num_rows($query);
      $recruiterData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $item . "'"));
      if ($assignedRecruiterCount == 0) {
        array_push($finalRecuitersList, $item);
        array_push($nonExistStatus, $recruiterData['member_name']);
      } else {
        array_push($existStatus, $recruiterData['member_name']);
      }
    }
  }


  if ($assign == 'employer') {
    if (count($finalRecuitersList) > 0) {
      foreach ($finalRecuitersList as $item) {
        $myEmployerQuery = "INSERT INTO assigned_Recruiters_list SET  assigned_employer_id='" . $assignedEmployerId . "' ,assigned_recruiter_id='" . $item . "',assigned_by='" . $assignedBy . "',status=1";
        $inserted = mysqli_query($con, $myEmployerQuery);
        mysqli_query($con, "UPDATE employer_agent_relation SET status=0 WHERE (employer_id='" . $assignedEmployerId . "' AND agent_id='" . $item . "')");


        $agentData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && allyted_member_id= '" . $item . "'"));
        $employerData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE status=1 && employer_id= '" . $assignedEmployerId . "'"));

        $getAllowedNoti = getAgentAllowedNotify($agentData['recruiter_auto_create_id']);

        if ($getAllowedNoti['status']) {
          $d = $getAllowedNoti['data'];

          if ($d['admin_alerts'] == '1') {

            $EmployertName = $employerData['company_name'];
            $agentId = $agentData['recruiter_auto_create_id'];
            $text = "New Employer <" . $EmployertName . "> added to your account";

            $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Agent',notification_text='" . $text . "',activity='Masters',agent_id='" . $agentId . "'");
          }
        }


        $getAllowedNoti = getAllowedNotifications($employerData['employer_auto_create_id']);
        if ($getAllowedNoti['status']) {
          $employerid = $getAllowedNoti['data'];
          if ($employerid['employer_alerts'] == '1') {
            $agentName = $agentData['member_name'];
            $employerautoid = $employerData['employer_auto_create_id'];
            $text = "New Advisor <" . $agentName . "> added to your account";

            $InsertNotification = mysqli_query($con, "INSERT tbl_notification SET notify_to='Employer',notification_text='" . $text . "',activity='Masters',employer_id='" . $employerautoid . "' ");
          }
        }
      }
      if ($inserted == true) {

        if (count($nonExistStatus) > 0) {
          $subject = "Jaldihire: Employer Assign";
          $empsubject = "Introducing Agent - Your Dedicated Support for Efficient Jobseeker Recruitment";
          // $JaldihireSubject = "Job Assign | $assignedJobId";

          $agentMail = $agentData['member_email'];
          $agentName = $agentData['member_name'];
          $greeting = "Hello $agentName";
          $employerMail = $employerData['emp_email'];
          $employerName = $employerData['company_name'];
          $empGreeting = "Hello $employerName";


          $body = "Your dedication and support in assisting employer ($employerName) will play a crucial role in helping us reach our goals and work effectively for our clients. Your assistance in reaching the goals set by our employer is crucial for the efficient development and successful operation of our company.

          Through your dedicated efforts, we aim to provide jobseekers with a platform where they can build their skills proficiently and ultimately help them reach their career aspirations.
          
          If you have any questions or require any support during your time with us, please do not hesitate to reach out. We are here to ensure your success as an integral part of our team.";

          $employerbody = "I am delighted to introduce Agent ($agentName, JH ID No) as your dedicated support assisting you in finding the best jobseekers for your job descriptions quickly and efficiently.

          With extensive experience in talent acquisition and a strong understanding of the industry, Agent is committed to providing you with top-notch service. They will work closely with you to understand your detailed job requirements, enabling them to efficiently and swiftly source suitable profiles that match your needs.
          
          Rest assured that our team is committed to assisting you every step of the way. Should you have any questions or require further assistance, please do not hesitate to reach out.
          
          Thank you for choosing our services. We look forward to working closely with you and helping you find the perfect candidates for your organization.";

          AgentEmail($agentData['member_email'], $subject, $greeting, $body);
          employerEmail($employerData['emp_email'], $empsubject, $empGreeting, $employerbody);

          $message .= " Assigned to " . implode(',', $nonExistStatus);
        }
        if ($existStatus) {
          $message .= " Already Assigned to " . implode(',', $existStatus);
        }

        $stat = ['status' => true, 'message' => $message];
      } else {
        $stat = ['status' => false, 'message' => 'Failed to Assign Try Again'];
      }
    } else {
      $stat = ['status' => false, 'message' => 'Agents Already Assigned'];
    }
  } else {
    $stat = ['status' => false, 'Message' => 'Invalid Assign value!'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong! try again'];
}

echo json_encode($stat);
