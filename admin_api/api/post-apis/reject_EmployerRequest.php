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

// $folderPath = "uploads/employer-images/";


$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

  $data = json_decode($json);


  @$id = $data->employer_id;

  $autoId = $data->auto_create_jobid;

  $companyname = $data->companyName;

  $assignedBy = $data->assignedBy;

  $email = $data->email;

  $external = $data->external;

  //   $pending = '1';


  $employerRequest = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from tbl_add_employer  where external='" . $external . "' && employer_id='" . $id . "' && employer_auto_create_id='" . $autoId . "' && company_name='" . $companyname . "' && emp_email='" . $email . "' && status=0"));


  if (!empty($email)) {
    if (!empty($id)) {
      $update = mysqli_query($con, "UPDATE tbl_add_employer  SET pending='1', assigned_by='" . $assignedBy . "', status=2 where employer_id='" . $employerRequest['employer_id'] . "' ");
    }
  }


  if ($update == true) {

    $mail = new PHPMailer;


    // $mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'work@mmworkspace.com'; // SMTP username
    $mail->Password = 'Work@321#'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465; // TCP port to connect to

    $mail->setFrom('work@mmworkspace.com', 'JALDHI HIRE');
    $mail->addAddress('makersminds@gmail.com'); // Add a recipient
    $mail->addAddress($email); // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC($cc);
    $mail->addBCC('bcc@example.com');

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true); // Set email format to HTML

    $mail->Subject = 'PLEASE RE-CREATE EMPLOYER LOGIN-JALDHIHIRE';
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
                    Hello there, ' . $companyname . ', Your request for Create account, has been Rejected by Jaldihire Team<br /><br />
                  Regards-<br />
                  Jaldi Hire Team<br />
                </div>
                <a  href="https://mmworkspace.com/test_jaldhihireadmin/main/forms.php" target="_blank" >
                <button style="width: auto;">Click here to create Again</button>
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
      $stat = ['message' => 'Request Rejected successfully', 'added' => 'Request Rejected successfully', 'status' => true, 'err' => mysqli_error($con), 'data' => $data];
      // $stat =  ['Mailer Error: ' . $mail->ErrorInfo];
    } else {
      $stat = ['message' => 'mail failed to sent', 'status' => false];
    }
  } else {

    $stat = ['message' => 'Failed!', 'err' => mysqli_error($con), 'status' => false, 'data' => $data];
  }
}

echo json_encode($stat);
