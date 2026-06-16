<?php

include "includes/db-connection/db.php";
include "libs/contact.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function SendMail()
{
        global $con;

        $date = date_default_timezone_set('Asia/kolkata');
        $dateFormat = date('Y-m-d H:i:s');

        $email = mysqli_escape_string($con, $_POST['email']);
        if (!empty($email)) {
                // $query = mysqli_query($con, "INSERT INTO user_gamil SET gmail='" . $email . "',created_id='" . $dateFormat . "',updated_on=0,status=1");
                $query = mysqli_query($con, "INSERT INTO user_gamil SET gmail='" . $email . "',created_id='" . $dateFormat . "',updated_id='0',status=1");


                if ($query == true) {

                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        $mail->Host = 'smtp.hostinger.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'jaldihire@mmworkspace.com';
                        $mail->Password = 'Jaldihire@321$#';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        $mail->setFrom('jaldihire@mmworkspace.com', 'JALDHI HIRE');
                        $mail->addAddress($email);
                        $mail->addReplyTo('jaldihire@mmworkspace.com', 'JALDHI HIRE');

                        $mail->isHTML(true);
                        $mail->Subject = 'Jaldi Hire Request Submitted Successfully: Our Team Will Reach Out Shortly
';
                        $content = "Dear $email,<br>
                        Thank you for reaching out to Jaldi Hire.<br>
                        Our customer services team is currently reviewing your request and will get back to you within the next 24 hours. If, for any reason, you do not receive a response within this time, please feel free to contact our supervisor team at support@jaldihire.com.
                        We appreciate your interest and look forward to assisting you!<br>
                        Best regards,<br>
                        Customer Service,<br>
                        Jaldi Hire.";
                        $mail->Body    = '
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
        margin: 12px auto 25px;
      }
      hr {
        border: 1px solid #ed9292;
        width: 50%;
      }
      h1,h2,h3,h4,h5,h6,p{
        color:#000;
      }
    </style>
  </head>
  <body>
    <div class="container" style="background-color:#F2F6FC">
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
                style="margin-bottom: 5px; display: flex; align-items: center; width:100%;"
              >
                <img
                  src="	https://mmworkspace.com/test_jaldhihireadmin/assets/images/logos/new-logo.png"
                  alt="new-logo"
                  style="height: 50px"
                />                
                  <img
                    src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/jaldi-icon.png"
                    style="height: 20px; padding-top:12px;"
                    alt="jaldi-icon"
                  />
              </div>

              <div style = "
              width:100%;">
                <p
                  style="
                    line-height: 19px;
                    font-size: 11px;
                    margin-bottom: 2px;
                    text-align: end;
                    word-spacing: 2px;
                    color:#fff;
                  "
                >
                  Need Help:
                  <span style="color: #F7E54E">Contact Us</span>
                  <br />Customer Number:3456784567
                </p>
              </div>
            </div>

            <div
              class="mail-box1"
              style="
                background: #fff;
                padding: 28px;
                box-shadow:0px 5px 15px #000 !important;
                border-radius: 5px;
                border: 3px solid #f7f7f7;
              "
            >


            

              <div style = "text-align:left;">
                ' . $content . '
                <br /><br />
            </div>


              

             


              <div>
              

                <div
                  style="
                    text-align: start;

                    font-size: 12px;
                  "
                >
                  <p style = "text-align:center">With Regards- <br>Jaldhire Team</p>
                 
                </div>

               
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
  
  ';

                        if ($mail->send()) {
                                sendinfomail();
                                // showAlert("success", "Success", "Mail send  successfully!");

                        }
                } else {
                        showAlert("error", "Error", "Mail send  to Failed!");

                        // echo "<script>alert('Failed')</script>";
                }
        } else {
                showAlert("warning", "Warning", "Please Enter Your Valid Mail!");
        }
}

function sendinfomail()
{
        global $con;
        $email = mysqli_escape_string($con, $_POST['email']);

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jaldihire@mmworkspace.com';
        $mail->Password = 'Jaldihire@321$#';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('jaldihire@mmworkspace.com', 'JALDHI HIRE');
        $mail->addAddress('info@jaldihire.com');
        $mail->addReplyTo('jaldihire@mmworkspace.com', 'JALDHI HIRE');

        $mail->isHTML(true);
        $mail->Subject = ' New Customer /Freelancer/Jobseeker Query Received – Please Respond Within 24 Hours';
        $content = "Dear Support Team,<br>
        We have received a new query from $email through our website. Please review and respond to their request within the next 24 hours to ensure timely communication.
        Lead Details:<br>
        •Email :$email<br>
        Let's ensure we provide them with the best possible experience!<br>
        Thank you,<br>
        Jaldi Hire Team";
        $mail->Body    = '
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
margin: 12px auto 25px;
}
hr {
border: 1px solid #ed9292;
width: 50%;
}
h1,h2,h3,h4,h5,h6,p{
color:#000;
}
</style>
</head>
<body>
<div class="container" style="background-color:#F2F6FC">
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
style="margin-bottom: 5px; display: flex; align-items: center; width:100%;"
>
<img
  src="	https://mmworkspace.com/test_jaldhihireadmin/assets/images/logos/new-logo.png"
  alt="new-logo"
  style="height: 50px"
/>                
  <img
    src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/jaldi-icon.png"
    style="height: 20px; padding-top:12px;"
    alt="jaldi-icon"
  />
</div>

<div style = "
width:100%;">
<p
  style="
    line-height: 19px;
    font-size: 11px;
    margin-bottom: 2px;
    text-align: end;
    word-spacing: 2px;
    color:#fff;
  "
>
  Need Help:
  <span style="color: #F7E54E">Contact Us</span>
  <br />Customer Number:3456784567
</p>
</div>
</div>

<div
class="mail-box1"
style="
background: #fff;
padding: 28px;
box-shadow:0px 5px 15px #000 !important;
border-radius: 5px;
border: 3px solid #f7f7f7;
"
>




<div style = "text-align:left;">
' . $content . '
<br /><br />
</div>







<div>


<div
  style="
    text-align: start;

    font-size: 12px;
  "
>
  <p style = "text-align:center">With Regards- <br>Jaldhire Team</p>
 
</div>


</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>

';
        if ($mail->send()) {
                showAlert("success", "Success", "Mail send  successfully!");
        }
}