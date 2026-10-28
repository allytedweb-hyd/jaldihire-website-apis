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


require '../vendor/phpmailer/PHPMailerAutoload.php';
include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

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

if (isset($json) && !empty($json)) {

  $data = json_decode($json);


  @$id = $data->applicant_id;
  @$autoId = $data->applicant_auto_create_id;
  @$applicantName = $data->applicantName;
  @$email = $data->email;
  @$external = $data->external;
  @$acceptedBy = $data->accepted_by;
  @$assignedBy = $data->assignedBy;

  $applicantRequest = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE external='" . $external . "' && applicant_id='" . $id . "' && applicant_auto_create_id='" . $autoId . "' && full_name='" . $applicantName . "' && e_mail='" . $email . "' && status=0"));

  if (!empty($applicantRequest) && isset($applicantRequest)) {

    $accept = mysqli_query($con, "UPDATE tbl_applicant_add  SET pending='1', accepted_by='" . $acceptedBy . "', assigned_by='" . $assignedBy . "', status=3 WHERE applicant_id='" . $applicantRequest['applicant_id'] . "'");

    if (!empty($acceptedBy) && $acceptedBy == 'Recruiter') {
      $recruiter_assign = mysqli_query($con, "INSERT INTO applicant_recruiter_assigns SET role='" . $role . "',role_id='" . $acceptedBy . "',recruiter_assigned_id='" . $assignedBy . "',applicant_assigned_id='" . $applicantRequest['applicant_id'] . "',status=1");
    }
  }

  if ($accept == true) {

    $mail = new PHPMailer;

    // $mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtpout.secureserver.net'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'info@allyted.com'; // SMTP username
    $mail->Password = 'Allyted@2026'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465; // TCP port to connect to

    $mail->setFrom('info@allyted.com', 'JALDHI HIRE');
    $mail->addAddress('makersminds@gmail.com'); // Add a recipient
    $mail->addAddress($email); // Name is optional
    $mail->addBCC('bcc@example.com');
    $mail->isHTML(true); // Set email format to HTML

    $mail->Subject = 'PLEASE SELECT PASSWORD FOR APPLICANT LOGIN-JALDHIHIRE';
    $mail->Body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <!DOCTYPE html>
    <html>
    <head>
      <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      />
      <script
        src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
      ></script>
      <script
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
      ></script>
      <script
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
      ></script>
      <script
        src="https://kit.fontawesome.com/5f59ca6ad3.js"
        crossorigin="anonymous"
      ></script>
      <style>
        body {
          background: #BFCBCC;
        }
        button:hover{
            color: #063baa;
        }
        button {
            border: navajowhite;
    /* padding: 10px; */
    background-color: transparent;
    border-radius: 10px;
    color: #8a26ff;
    font-weight: 600;
    width: 35%;
    margin: 25px 15px;
        }
        hr {
          border: 1px solid #ED9292;
          width: 50%;
        }
      </style>
    </head>
    <body>
      <div class="container" style="background-color: #aecaf0;">
        <div class="row">
          <div class="col-md-12">
            <div
              class="main-out"
              style="
                margin: auto;
                text-align: center;
                max-width: 70% !important;
                padding: 50px;
              "
            >
              <div class="logo">
                <img src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/logo.png" alt="" />
              </div>
              <div class="title" style="margin-bottom: 20px">
                <h3 style="margin-top:0px;">Jaldihire Team</h3>
                <!-- <span>You have a job update !</span> -->
              </div>
              <div
                class="mail-box1"
                style="
                  background: #fff;
                  padding: 20px;
                  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                  border-radius: 5px;
                "
              >
                <img img src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/image_processing20201104-4118-160qtlp.gif" height="185px" alt="" />
                <div>
                    hello there, ' . $applicantName . ', Hello and welcome to the Jaldihire family.<br />Please select a password now to gain full access to your applicant dashboard.<br />Have a wonderful day<br /><br />
                  Regards-<br />
                  Jaldi Hire Team<br />
                </div>
                <a  href=https://mmworkspace.com/test_jaldhihireadmin/user-choose-password/index.php?appId=' . $autoId . ' target="_blank" >
                <button style="width: auto;">Click here to create password</button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
  </html>
';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    if ($mail->send()) {
      $stat = ['message' => 'Request Accepted Successfully', 'status' => true, 'err' => mysqli_error($con)];
    } else {
      $stat = ['message' => 'mail failed to sent', 'status' => false];
    }
  } else {
    $stat = ['message' => 'Failed!', 'err' => mysqli_error($con), 'status' => false, 'data' => $data];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong please try again'];
}
echo json_encode($stat);
