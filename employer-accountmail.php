<?php

error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable error reporting for debugging

include "includes/db-connection/db.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function employerSignUp()
{
  global $con;

  // Read the JSON input
  $input = json_decode(file_get_contents(filename: "php://input"), true);

  // Sanitize input
  $companyname = mysqli_real_escape_string($con, $input['companyname']);
  $emp_mob = mysqli_real_escape_string($con, $input['emp_mob']);
  $emp_email = mysqli_real_escape_string($con, $input['emp_email']);
  $emp_gst = mysqli_real_escape_string($con, $input['emp_gst']);
  $package = mysqli_real_escape_string($con, $input['package']);
  $packmonths = mysqli_real_escape_string($con, $input['packmonths']);
  $external = 'self';

  $Id = 1;
  $getDbCount = mysqli_query($con, "SELECT MAX(employer_id) as max FROM `tbl_add_employer`");
  $rows = mysqli_fetch_array($getDbCount);
  $incCount = $rows == null ? $Id : intval($rows['max']) + 1;

  $autoId = 'JDEMP' . strtoupper(substr(str_replace(' ', '', $companyname), 0, 4)) . $incCount;

  // Validate email
  if (empty($emp_email)) {
    echo json_encode(['status' => 'error', 'message' => 'Email should not be empty']);
    exit();
  }

  // Check if email already exists
  $getEmployer = mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE emp_email='$emp_email' AND status=1");
  if (mysqli_num_rows($getEmployer) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
    exit();
  }

  // Check if GST number already exists
  if (!empty($emp_gst)) {
    $getGst = mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE emp_gst='$emp_gst' AND status=1");
    if (mysqli_num_rows($getGst) > 0) {
      echo json_encode(['status' => 'error', 'message' => 'GST number already exists']);
      exit();
    }
  }

  // Insert new employer
  $query = "INSERT INTO tbl_add_employer SET company_name='$companyname', employer_auto_create_id='$autoId', 
              emp_mob='$emp_mob', emp_email='$emp_email', emp_gst='$emp_gst', plan_id='$package', 
              plan_months='$packmonths', external='$external', resource_managers=1, interviewers=1, status=0";

  $insert_employer = mysqli_query($con, $query);
  if ($insert_employer) {
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtpout.secureserver.net';
      $mail->SMTPAuth = true;
      $mail->Username = 'jaldihire@mmworkspace.com';
      $mail->Password = 'Jaldihire@321$#';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;

      $mail->setFrom('jaldihire@mmworkspace.com', 'JALDHI HIRE');
      $mail->addAddress($emp_email);
      $mail->addReplyTo('jaldihire@mmworkspace.com', 'JALDHI HIRE');
      $mail->isHTML(true);
      $mail->Subject = 'Please select password for Employer login - Jaldi Hire';
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
                                background-color: #072c50;
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
                                          padding: 10px;
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
                                                  src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/Jaldihire_Logo_Name_Logo.png"
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
                                                <div
                                                  class="trinel"
                                                  style="
                                                    display: flex;
                                                    background-color: #fff;
                                                    padding: 0px 15px 0px 15px;
                                                    width: 70%;
                                                    margin: auto;
                                                    border-radius: 20px;
                                                    align-items: center;
                                                    margin-bottom: 35px;
                                                    margin-top: 20px;
                                                    position: relative;
                                                  "
                                                >
                                                  <div
                                                    style="
                                                      width: 30%;
                                                      text-align: center;
                                                    "
                                                  >
                                                    <img
                                                      src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/lock-ani.gif"
                                                      alt="image"
                                                      style="width: 80px; height: 80px"
                                                    />
                                                  </div>
                                                  <div
                                                    style="
                                                      text-align: center;
                                                      width: 70%;
                                                    "
                                                  >
                                                    <img
                                                      class="man-img"
                                                      src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/passwordgif2.gif"
                                                      alt="image"
                                                      style="
                                                        width: 250px;
                                                        height: 108px;
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
                                background-color: #ffffff;
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
                                              background-color: #ffffff;
                                              color: #08374a;
                                              margin: 0px;
                                            "
                                          >
                                           Set Up Your Secure Password Now
                                          </h2>
                                        </h4>
                                        <!--[if mso]></td></tr></table><![endif]-->
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
                                        <div
                                          style="
                                            font-size: 14px;
                                            line-height: 140%;
                                            text-align: left;
                                            word-wrap: break-word;
                                          "
                                        >
                                          <p
                                            style="
                                              color: #00304d;
                                              white-space: normal;
                                              background-color: #ffffff;
                                              line-height: 140%;
                                            "
                                          >
                                          Dear ' . $companyname . ',<br /></span> Welcome to Jaldihire!<br> Please click the link below to create your
                  password and complete your account setup
                                          </p>
                                        </div>
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
                          background-color: #ffffff;
                             margin-bottom: 40px;
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
                                          <a
                                           href=https://jaldihire.com/admin_backend/employer-choose-password/index.php?empId=' . $autoId . '  target="_blank"
                                        
                                            class="v-button v-size-width"
                                            style="
                                              box-sizing: border-box;
                                              display: inline-block;
                                              text-decoration: none;
                                              -webkit-text-size-adjust: none;
                                              text-align: center;
                                              color: #ffffff;
                                              background-color: #0e69c1;
                                              border-radius: 4px;
                                              -webkit-border-radius: 4px;
                                              -moz-border-radius: 4px;
                                              width: auto;
                                              max-width: 100%;
                                              overflow-wrap: break-word;
                                              word-break: break-word;
                                              word-wrap: break-word;
                                              mso-border-alt: none;
                                              border-top-width: 2px;
                                              border-top-style: solid;
                                              border-top-color: #0e69c1;
                                              border-left-width: 2px;
                                              border-left-style: solid;
                                              border-left-color: #0e69c1;
                                              border-right-width: 2px;
                                              border-right-style: solid;
                                              border-right-color: #0e69c1;
                                              border-bottom-width: 2px;
                                              border-bottom-style: solid;
                                              border-bottom-color: #0e69c1;
                                              font-size: 18px;
                                            "
                                          >
                                            <span
                                              style="
                                                display: block;
                                                padding: 10px 30px 8px;
                                                line-height: 90%;
                                              "
                                            
                                              >Click Here
                                            </span>
                                          </a>
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
        </html>
        '; // You can customize the email body
      $mail->AltBody = 'This is the plain text version for non-HTML clients';

      if ($mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful. Email sent!']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
      }
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to register employer']);
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  employerSignUp();
}
