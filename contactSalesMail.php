<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable error reporting for debugging

include "includes/db-connection/db.php";
include "libs/contact.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function SalesContactMail()
{
    global $con;

    // Read the JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    // Sanitize input
     $fname = mysqli_escape_string($con, $input['fname']);
    $yourname = mysqli_escape_string($con, $input['yourname']);
    $role = mysqli_escape_string($con, $input['role']);
    $email = mysqli_escape_string($con, $input['email']);
    $number = mysqli_escape_string($con, $input['number']);
    $date = mysqli_escape_string($con, $input['date']);
    $time = mysqli_escape_string($con, $input['time']);

    // Validate email field
    if (!empty($email)) {
       $query = mysqli_query($con, "INSERT INTO requestdemo SET comapany_name='" . $fname . "',name='" . $yourname . "',role='" . $role . "',number='" . $number . "',date='" . $date . "',time='" . $time . "',work_mail='" . $email . "',status=1");

        if ($query) {
            // Set up PHPMailer
            $mail = new PHPMailer(true); // Enable exceptions
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jaldihire@mmworkspace.com';
                $mail->Password = 'Jaldihire@321$#'; // Consider using environment variables for sensitive data
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Determine the type
         

                // Set email parameters
                $mail->setFrom('jaldihire@mmworkspace.com', 'JALDHI HIRE');
                $mail->addAddress('sales@jaldihire.com'); // Recipient
                $mail->addReplyTo('jaldihire@mmworkspace.com', 'JALDHI HIRE');
                $mail->isHTML(true); // Set email format to HTML

                // Set the subject
                $mail->Subject = "New Customer Demo Request on $date. Please respond Immediately";

                // Construct the HTML body of the email
                $mail->Body = '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title></title>
    <!--[if !mso]><!-->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <!--<![endif]-->
    <style type="text/css">
      @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif !important;
      }
      @media only screen and (min-width: 620px) {
        .u-row {
          width: 600px !important;
        }

        .u-row .u-col {
          vertical-align: top;
        }

        .u-row .u-col-33p33 {
          width: 199.98px !important;
        }

        .u-row .u-col-66p67 {
          width: 400.02px !important;
        }

        .u-row .u-col-100 {
          width: 600px !important;
        }
      }

      @media (max-width: 620px) {
        .u-row-container {
          max-width: 100% !important;
          padding-left: 0px !important;
          padding-right: 0px !important;
        }

        .u-row .u-col {
          min-width: 320px !important;
          max-width: 100% !important;
          display: block !important;
        }

        .u-row {
          width: 100% !important;
        }

        .u-col {
          width: 100% !important;
        }

        .u-col > div {
          margin: 0 auto;
        }
      }

      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif;
      }

      table,
      tr,
      td {
        vertical-align: top;
        border-collapse: collapse;
      }

      p {
        margin: 0;
      }

      .ie-container table,
      .mso-container table {
        table-layout: fixed;
      }

      * {
        line-height: inherit;
      }

      a[x-apple-data-detectors="true"] {
        color: inherit !important;
        text-decoration: none !important;
      }

      table,
      td {
        color: #000000;
      }

      #u_body a {
        color: #0000ee;
        text-decoration: underline;
      }

      #u_content_heading_49 a {
        color: #1c9ca1;
      }

      #u_content_heading_50 a {
        color: #1c9ca1;
      }
      .trinel::after {
        position: absolute;
        content: "";
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 10px 15px 10px 0;
        border-color: transparent #fefefd transparent transparent;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
        top: 100px;
        rotate: 32deg;
        right: 55px;
      }

      @media (max-width: 480px) {
        #u_content_button_5 .v-size-width {
          width: 76% !important;
        }
        .caleder-img {
          width: 150px !important;
          height: 150px !important;
          padding-right: 10px !important;
          margin-top: 40px !important;
        }
        .logo-img {
          max-width: 140px !important;
          padding-top: 26px !important;
          padding-left: 5px !important;
        }
        .man-img {
          width: 60% !important;
          margin-top: 40px !important;
          margin-left: 37px !important;
        }
      }
      .caleder-img {
        width: 180px;
        height: 180px;
        padding-right: 40px;
        margin-top: 40px;
      }
    </style>
  </head>

  <body
    class="clean-body u_body"
    style="
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      background-color: #f0f0f0;
      color: #000000;
    "
  >
    <!--[if IE]><div class="ie-container"><![endif]-->
    <!--[if mso]><div class="mso-container"><![endif]-->
    <table
      id="u_body"
      style="
        border-collapse: collapse;
        table-layout: fixed;
        border-spacing: 0;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        vertical-align: top;
        min-width: 320px;

        margin: 0 auto;
        background-color: #f0f0f0;
        width: 100%;
      "
      cellpadding="0"
      cellspacing="0"
    >
      <tbody>
        <tr style="vertical-align: top">
          <td
            style="
              word-break: break-word;
              border-collapse: collapse !important;
              vertical-align: top;
            "
          >
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f0f0f0;"><![endif]-->

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ddffe7;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-image: url(https://mmworkspace.com/test_jaldhihireadmin/employer-choose-password/images/main-b.jpeg);
                        height: 100%;
                        width: 100% !important;
                        background-size: cover;
                        background-repeat: no-repeat;
                        border-radius: 20px 20px 0px 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          margin-top: 50px;
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px 10px 10px 3px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <table
                                  width="100%"
                                  cellpadding="0"
                                  cellspacing="0"
                                  border="0"
                                >
                                  <tr>
                                    <td
                                      style="
                                        padding-right: 0px;
                                        padding-left: 0px;
                                        text-align: start;
                                      "
                                      align="center"
                                    >
                                      <div>
                                        <img
                                          align="center"
                                          border="0"
                                          src="https://mmworkspace.com/test_jaldhihireadmin/employer-choose-password/images/Jaldihire_Logo_Name_Logo.png"
                                          alt="image"
                                          title="image"
                                          class="logo-img"
                                          style="
                                            outline: none;
                                            text-decoration: none;
                                            -ms-interpolation-mode: bicubic;
                                            clear: both;
                                            display: inline-block !important;
                                            border: none;
                                            height: auto;
                                            float: none;
                                            width: 100%;
                                            max-width: 190px;
                                            padding-top: 15px;
                                            padding-left: 35px;
                                            padding-bottom: 20px;
                                          "
                                        />
                                        <div class="" style="display: flex">
                                          <div
                                            class="content"
                                            style="width: 50%"
                                          >
                                            <h1
                                              style="
                                                color: #fff;
                                                font-size: 25px;
                                                font-weight: 800;
                                                margin-left: 30px;
                                                text-transform: uppercase;
                                              "
                                            >
                                              New
                                            </h1>
                                            <h1
                                              class="acct"
                                              style="
                                                color: #0a233f;
                                                font-size: 25px;
                                                font-weight: 800;
                                                padding-left: 30px;
                                                text-transform: uppercase;
                                                background-color: #f6b543;
                                              "
                                            >
                                              Query
                                            </h1>
                                            <h1
                                              style="
                                                color: #fff;
                                                font-size: 25px;
                                                font-weight: 800;
                                                margin-left: 30px;
                                                text-transform: uppercase;
                                              "
                                            >Recevied</h1>
                                          </div>
                                          <div
                                            style="
                                              text-align: center;
                                              margin-left: 60px;
                                            "
                                          >
                                            <img
                                              class=""
                                              src="https://mmworkspace.com/test_jaldhihireadmin/employer-choose-password/images/mann.png"
                                              alt="image"
                                              style="
                                                width: 60%;
                                                text-align: center;
                                              "
                                            />
                                          </div>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #f4faff;
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          margin-left: 30px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 30px 10px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                                <h4
                                  style="
                                    margin: 0px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                    font-size: 12px;
                                    font-weight: 700;
                                  "
                                >
                                  <h2
                                    style="
                                      white-space: normal;

                                      color: #08374a;
                                      margin: 0px;
                                    "
                                  >
                                    Dear Sales Team
                                  </h2>
                                </h4>
                                
          <p>
           We have received a request for a demo from  '.$fname.' through our website.
For '. $date .' and slot('. $time .') 
Lead Details:<br/>Name:'. $fname.' <br>Email :'.$email.'<br/>Demo date: '. $date .'<br/>Time: '. $time .'
          </p>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 11px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                    
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 0px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                          
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px 0px 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: #f4faff;
                  margin-bottom: 40px;
                  border-radius: 0px 0px 20px 20px;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 2px 0px 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          margin-left: 30px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                    
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          id="u_content_button_5"
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso
                                  ]><style>
                                    .v-button {
                                      background: transparent !important;
                                    }
                                  </style><!
                                [endif]-->
                                <div align="left" style="text-align: center">
                                  <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://unlayer.com" style="height:34px; v-text-anchor:middle; width:140px;" arcsize="12%"  strokecolor="#000000" strokeweight="2px" fillcolor="#000000"><w:anchorlock/><center style="color:#ffffff;"><![endif]-->
                                   <h6>With Regards</h6>
                               <h6>Jaldihire Team</h6>
                                  <!--[if mso]></center></v:roundrect><![endif]-->
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!--[if mso]></div><![endif]-->
    <!--[if IE]></div><![endif]-->
  </body>
</html> ';
                $mail->AltBody = "Name: {$fname}\nEmail: {$email}\nInterest: {$date}\nMessage: {$time}";

                // Send the email
                if ($mail->send()) {
                    echo json_encode(['status' => 'success', 'message' => 'Request submitted successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to send email!']);
                }
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit form data!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email!']);
    }
}

// header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    SalesContactMail();
}
?>