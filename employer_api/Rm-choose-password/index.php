<?php
require '../api/vendor/phpmailer/PHPMailerAutoload.php';
require '../api/vendor/autoload.php';
include "../includes/db-connection/db.php";
@$rmId = $_GET['RmId'];

$q = mysqli_query($con, "select * from tbl_resource_managers where RM_auto_create_id='" . $rmId . "'");
$f = mysqli_fetch_array($q);
// }
// echo json_encode($f);


@$name = $f['rm_name'];
@$email = $f['rm_email'];

if (isset($_POST['submit'])) {
  $pass = $_POST['pass'];
  $cnf = $_POST['cnfpass'];

  if (!empty($f['rm_password'])) {
    echo '<script>alert("Password already created")</script>';
    exit();
  }

  $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_+=])[a-zA-Z\d!@#$%^&*()-_+=]{8,}$/';

  if (preg_match($regex, $pass)) {
    if ($pass != $cnf) {
      echo '<script>alert("password is wrong")</script>';
    } else {
      $hash = password_hash("$cnf", PASSWORD_DEFAULT);

      $update = mysqli_query($con, "update tbl_resource_managers set rm_password='" . $hash . "',status=1 where RM_auto_create_id='" . $rmId . "'");
      if ($update == true) {
        echo '<script>alert("password is created")</script>';

        successfullmail($email, $name);

        header("Location:  https://employer.jaldihire.com/");
      } else {
        echo '<script>alert("password is not created")</script>';
      }
    }
  } else {
    echo '<script>alert("Password must contain at least one digit, one lowercase letter, one uppercase letter, one special character, and be at least 8 characters long.")</script>';
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
  <!-- <link rel="stylesheet" href="css/styles1.css" /> -->
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
  <link rel="icon" href="images/j-logo.png" type="image/x-icon" />
</head>

<body>

  <section class="login">
    <div>
      <img src="images/jal.png" class="jal" alt="login-img">
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="logo-ig">
            <img src="images/j-logo.png" class="j-logo" alt="">
            <img src="images/jal-logo.png" class="jal-cls" alt="">
          </div>
        </div>
        <div class="col-md-6" style="margin-top:-25px;">
          <div class="account">
            <h4>Create Account</h4>
            <p>Login into your account</p>
          </div>
          <div class="form-flex">
            <form method="post">
              <div class="col-md-12  col-xs-12">
                <div class="form-grp">
                  <input type="text" name="" id="" class="form-control" placeholder="User Name"
                    value="<?php echo $name; ?>">
                </div>
              </div>
              <div class="col-md-12  col-xs-12">
                <div class="form-grp">
                  <input type="text" name="" id="" class="form-control" placeholder="Email Address"
                    value="<?php echo $email; ?>">
                </div>
              </div>

              <div class="form-group">

                <div class="col-md-12">
                  <input id="password" class="form-control input-md" name="pass" type="password"
                    placeholder="Enter password" required maxlength="10" onpaste="return false"
                    ondrop="return false">
                  <span class="show-pass" onclick="toggle()">
                    <i class="far fa-eye" onclick="myFunction(this)"></i>
                  </span>
                  <input id="password" class="form-control input-md" name="cnfpass" type="password"
                    placeholder="Confirm password">

                  </span>


                  <div id="popover-password">
                    <p><span id="result"></span></p>
                    <div class="progress">
                      <div id="password-strength" class="progress-bar" role="progressbar"
                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                        style="width:0%">
                      </div>
                    </div>
                    <ul class="list-unstyled">
                      <li class="">
                        <span class="low-upper-case">
                          <i class="fas fa-circle" aria-hidden="true"></i>
                          &nbsp;Lowercase &amp; Uppercase
                        </span>
                      </li>
                      <li class="">
                        <span class="one-number">
                          <i class="fas fa-circle" aria-hidden="true"></i>
                          &nbsp;Number (0-9)
                        </span>
                      </li>
                      <li class="">
                        <span class="one-special-char">
                          <i class="fas fa-circle" aria-hidden="true"></i>
                          &nbsp;Special Character (!@#$%^&*)
                        </span>
                      </li>
                      <li class="">
                        <span class="eight-character">
                          <i class="fas fa-circle" aria-hidden="true"></i>
                          &nbsp;Atleast 8 Character
                        </span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-xs-12">
                <div class="jal-btn">
                  <button type="submit" name="submit">Sign Up</button>
                </div>
              </div>
              <p class="stepp">Once Step a head to join our family </p>
            </form>
          </div>
        </div>
      </div>
    </div>

  </section>






  <!-- <section class="login">
        <div>
            <img src="images/jal.png" class="jal" alt="login-img">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="logo-ig">
                        <img src="images/j-logo.png" class="j-logo" alt="">
                        <img src="images/jal-logo.png" class="jal-cls" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="account">
                        <h4>Create Account</h4>
                        <p>Login into your account</p>
                    </div>
                    <div class="form-flex">
                        <form method="post">
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp">
                                    <input type="text" name="" id="" class="form-control" placeholder="User Name"
                                        value="<?php echo $name; ?>">
                                </div>
                            </div>
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp">
                                    <input type="text" name="" id="" class="form-control" placeholder="Email Address"
                                        value="<?php echo $email; ?>">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-grp">
                                    <input type="password" name="pass" id="password-input" class="form-control"
                                        placeholder="Enter Your New Password">
                                    <span toggle="#password-input" class="eye-icon"></span>
                                </div>
                            </div>
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp password-content">
                                    <input type="password" name="cnfpass" id="password-input" class="form-control"
                                        placeholder="Confirm Your New Password">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="jal-btn">
                                    <button type="submit" name="submit">Sign Up</button>
                                </div>
                            </div>
                            <p class="stepp">Once Step a head to join our family </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section> -->


  <footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
      integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
      integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <!-- <script>
            document.addEventListener('DOMContentLoaded', function () {
                const eyeIcon = document.querySelector('.eye-icon');
                const passwordInput = document.querySelector('#password-input');
                eyeIcon.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('hide-password');
                });
            });
        </script> -->

    <script>
      let state = false;
      let password = document.getElementById("password");
      let passwordStrength = document.getElementById("password-strength");
      let lowUpperCase = document.querySelector(".low-upper-case i");
      let number = document.querySelector(".one-number i");
      let specialChar = document.querySelector(".one-special-char i");
      let eightChar = document.querySelector(".eight-character i");

      password.addEventListener("keyup", function() {
        let pass = document.getElementById("password").value;
        checkStrength(pass);
      });

      function toggle() {
        if (state) {
          document.getElementById("password").setAttribute("type", "password");
          state = false;
        } else {
          document.getElementById("password").setAttribute("type", "text")
          state = true;
        }
      }

      function myFunction(show) {
        show.classList.toggle("fa-eye-slash");
      }

      function checkStrength(password) {
        let strength = 0;

        //If password contains both lower and uppercase characters
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
          strength += 1;
          lowUpperCase.classList.remove('fa-circle');
          lowUpperCase.classList.add('fa-check');
        } else {
          lowUpperCase.classList.add('fa-circle');
          lowUpperCase.classList.remove('fa-check');
        }
        //If it has numbers and characters
        if (password.match(/([0-9])/)) {
          strength += 1;
          number.classList.remove('fa-circle');
          number.classList.add('fa-check');
        } else {
          number.classList.add('fa-circle');
          number.classList.remove('fa-check');
        }
        //If it has one special character
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
          strength += 1;
          specialChar.classList.remove('fa-circle');
          specialChar.classList.add('fa-check');
        } else {
          specialChar.classList.add('fa-circle');
          specialChar.classList.remove('fa-check');
        }
        //If password is greater than 7
        if (password.length > 7) {
          strength += 1;
          eightChar.classList.remove('fa-circle');
          eightChar.classList.add('fa-check');
        } else {
          eightChar.classList.add('fa-circle');
          eightChar.classList.remove('fa-check');
        }

        // If value is less than 2
        if (strength < 2) {
          passwordStrength.classList.remove('progress-bar-warning');
          passwordStrength.classList.remove('progress-bar-success');
          passwordStrength.classList.add('progress-bar-danger');
          passwordStrength.style = 'width: 10%';
        } else if (strength == 3) {
          passwordStrength.classList.remove('progress-bar-success');
          passwordStrength.classList.remove('progress-bar-danger');
          passwordStrength.classList.add('progress-bar-warning');
          passwordStrength.style = 'width: 60%';
        } else if (strength == 4) {
          passwordStrength.classList.remove('progress-bar-warning');
          passwordStrength.classList.remove('progress-bar-danger');
          passwordStrength.classList.add('progress-bar-success');
          passwordStrength.style = 'width: 100%';
        }
      }
    </script>








  </footer>
</body>

</html>





<?php



function successfullmail($emails, $name)
{



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
  // foreach ($emails as $email) {
  $mail->addAddress($emails);
  // }
  // Name is optional
  // $mail->addReplyTo('info@example.com', 'Information');
  // $mail->addCC($cc);
  $mail->addBCC('bcc@example.com');

  // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
  $mail->isHTML(true); // Set email format to HTML

  $mail->Subject = 'Thanks for your interest to join jaldihire family';
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
                        background-image: url(https://jaldihire.com/admin_api/employer-choose-password/images/main-b.jpeg);
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
                                          src="https://jaldihire.com/admin_api/images/Jaldihire_Logo_Name_Logo.png"
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
                                              Your Business
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
                                              Account
                                            </h1>
                                            <h1
                                              style="
                                                color: #fff;
                                                font-size: 25px;
                                                font-weight: 800;
                                                margin-left: 30px;
                                                text-transform: uppercase;
                                              "
                                            >
                                              created <br />successfully
                                            </h1>
                                          </div>
                                          <div
                                            style="
                                              text-align: center;
                                              margin-left: 60px;
                                            "
                                          >
                                            <img
                                              class=""
                                              src="https://jaldihire.com/admin_api/employer-choose-password/images/mann.png"
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
                                    Dear ' . $name . ',
                                  </h2>
                                </h4>
                                
          <p>
            <span>Congratulations</span> and I hope this email finds you well. We
            are delighted to inform you that your business account has been
            successfully created. You are now ready to log in to the system and
            activate your account to start utilizing our services.
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
                                  <a
                                    href=https://employer.jaldihire.com/  target="_blank"                                  
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
                                      margin-bottom: 15px;
                                    "
                                  >
                                    <span
                                      style="
                                        display: block;
                                        padding: 10px 30px 8px;
                                        line-height: 90%;
                                      "
                                
                                      >Check Status
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
';
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  if ($mail->send()) {
    $stat = ['message' => 'mail sent successfully', 'added' => 'Employer Account Created', 'status' => true];

    // $stat =  ['Mailer Error: ' . $mail->ErrorInfo];
  } else {
    $stat = ['message' => 'mail failed to sent', 'status' => false];
  }

  return $stat;
}

?>