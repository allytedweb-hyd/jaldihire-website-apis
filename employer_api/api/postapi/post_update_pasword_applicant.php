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


$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  $otp = $data->otp;
  $email = $data->e_mail;
  $newPswd = $data->new_pswd;
  $confPswd = $data->conf_pswd;

  if (empty($otp)) {
    echo json_encode(['status' => false, "message" => "OTP Not Valid"]);
    exit();
  }

  $getOtp = mysqli_fetch_assoc(mysqli_query($con, "select * from tbl_applicant_add where forgot_otp='" . $otp . "' && e_mail='" . $email . "'"));

  if ($getOtp == null) {
    echo json_encode(['status' => false, "message" => "OTP Dosn't Matched, Please Check"]);
    exit();
  } else {
    if ($newPswd == $confPswd) {
      $hash = password_hash("$confPswd", PASSWORD_DEFAULT);
      $insert_employer = mysqli_query($con, "UPDATE tbl_applicant_add  SET
        password ='" . $hash . "',forgot_otp='' WHERE e_mail='" . $email . "' ");
    } else {
      echo json_encode(['status' => false, "message" => "Password Mismatched , Re-Enter Confirm Password"]);
      exit();
    }
  }

  if ($insert_employer == true) {

    $mail = new PHPMailer;

    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'jaldihireteam@mmworkspace.com'; // SMTP username
    $mail->Password = 'Jaldihire@321$#'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465; // TCP port to connect to

    $mail->setFrom('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
    $mail->addAddress($email); // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC($cc);

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true); // Set email format to HTML

    $mail->Subject = 'OTP FOR CREATE PASSWORD FOR APPLICANT LOGIN-JALDHIHIRE';
    $mail->Body =
      '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <!DOCTYPE html>
    <!DOCTYPE html>
    <html>
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
          href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet"
        />
        <link
          rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        />
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script
          src="https://kit.fontawesome.com/5f59ca6ad3.js"
          crossorigin="anonymous"
        ></script>
        <style>
          body {
            background: #bfcbcc;
            font-family: "Poppins", sans-serif;
          }
          button {
            border: navajowhite;
            padding: 10px;
            background-color: #2281ff;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            width: 35%;
            margin: 25px 15px;
          }
          hr {
            border: 1px solid #ed9292;
            width: 50%;
          }
          h1,
          h2,
          h3,
          h4,
          h5,
          h6,
          p {
            color: #000;
          }
        </style>
      </head>
      <body>
        <div class="container" style="background-color: #f2f6fc">
          <div class="row">
            <div class="col-md-12">
              <div
                class="main-out"
                style="
                  margin: auto;
                  text-align: center;
                  max-width: 50% !important;
                  padding: 50px;
                "
              >
                <div
                  style="
                    background: black;
                    display: flex;
                    align-items: center;
                    padding: 7px 10px 0px;
                    color: #fff;
                    border-radius: 5px 5px 0px 0px;
                  "
                >
                  <div
                    class="title"
                    style="
                      margin-bottom: 5px;
                      display: flex;
                      align-items: center;
                      width: 100%;
                    "
                  >
                    <img
                      src="	https://mmworkspace.com/test_jaldhihireadmin/assets/images/logos/new-logo.png"
                      alt=""
                      style="height: 50px"
                    />
    
                    <img
                      src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/jaldi-icon.png"
                      style="height: 20px; padding-top: 12px"
                    />
                  </div>
    
                  <div style="width: 100%">
                    <p
                      style="
                        line-height: 19px;
                        font-size: 11px;
                        margin-bottom: 2px;
                        text-align: end;
                        word-spacing: 2px;
                        color: #fff;
                      "
                    >
                      Need Help:
                      <span style="color: #f7e54e">Contact Us</span>
                      <br />Customer Number:3456784567
                    </p>
                  </div>
                </div>
       
                <div
               
                  
                 class="mail-box1" style="
                  background: #fff;
                  padding: 20px;
                  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                  border-radius: 5px;
                ">
                <img style="height:90px" src="https://mmworkspace.com/test_jaldhihireemployer/assets/images/Animation.gif" alt="">
                <div>
    
              
                  <div>
                    <h2
                      style="
                        font-size: 23px;
                        text-align: start;
                        margin-top: 0px;
                        font-weight: 700;
                      " >
                        
                    </h2>
                  </div>
    
                  <div
                
                >
                <div class="logo">
                  <img src="https://mmworkspace.com/test_jaldhihireemployer/assets/images/Animation.gif"alt="" />
                </div>
                  <div>
                   Enter OTP is Successfully sent to the given mail for the given data.
                  </div>
                  <h2 style="color:#12b912;">Password Updated Successfully</h2>
               
    
                  <div>
                      <hr />
                  </div>
                </div>
    
                  <div>
                    <div
                      style="
                        text-align: start;
    
                        font-size: 12px;
                      "
                    >
                    <div class="bttun" style="
                    text-align: center;
                ">
                      <a href="https://mmworkspace.com/test_jaldhihireadmin/applicant/" target="_blank" >
                      <button style="color: #fff; background-color: black; border-radius: 6px" type="submit">Get Started</button>
                     </a>
                    </div>
    
                    </div>
                  </div>
                </div>  
              </>
            </div>
          </div>
        </div>
      </body>
    </html>
    
';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


    if ($mail->send()) {
      $stat = ['message' => 'Password Updated Successfully', 'status' => true];
      // $stat =  ['Mailer Error: ' . $mail->ErrorInfo];
    } else {
      $stat = ['message' => 'mail failed to sent', 'status' => false];
    }
  } else {

    $stat = ['message' => 'Failed!', 'err' => mysqli_error($con), 'status' => false];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something Went Wrong! Please Try Again'];
}

echo json_encode($stat);
