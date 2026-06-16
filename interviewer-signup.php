<?php
$title="Become a Freelance Interviewer | Start Earning with JaldiHire.";
$meta_description = "Apply as a professional interviewer and connect with top talent. Access flexible, compensated opportunities aligned with your expertise.";
include "includes/int-header.php";
include "includes/db-connection/db.php";
include "libs/alerts.php";

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($con, $_POST['full_name']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile_number']);
    $mail = mysqli_real_escape_string($con, $_POST['mail']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cnf_password = mysqli_real_escape_string($con, $_POST['cnf_password']);
    $Id = 1;
    $getDbCount = mysqli_query($con, "SELECT MAX(interviewer_id ) as max FROM `interviewers_login`");
    $rows = mysqli_fetch_array($getDbCount);
    $incCount = $rows == null ? $Id : intval($rows['max']) + 1;

    $autoId = 'JDINT' . strtoupper(substr(str_replace(' ', '', $name), 0, 4)) . $incCount;

    $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_+=])[a-zA-Z\d!@#$%^&*()-_+=]{8,}$/';

    if (!empty($mail)) {
        $getInterviewer = mysqli_query($con, "select * from interviewers_login where mail='" . $mail . "' && status=1");
    } else {

        echo '<script type="text/javascript">
        alert("Email Should Not Be Empty");
        window.location.href = "interviewer-signup.php"; 
      </script>';
        exit();

        // showAlertswall("error", "Error", "Email Should Not Be Empty");
        // exit();
    }

    if (mysqli_num_rows($getInterviewer) > 0) {
        echo '<script type="text/javascript">
        alert("Email Already Existed");
        window.location.href = "interviewer-signup.php"; 
      </script>';
        exit();
        // showAlertswall('Warning', "warning", 'Email already use in');
        // exit();
    }

    if (preg_match($regex, $password)) {
        if ($password != $cnf_password) {
            showAlertswall("error", "Error", "Password did not mached!");
        } else {
            $hash = password_hash("$cnf_password", PASSWORD_DEFAULT);
            $insert = mysqli_query($con, "INSERT INTO interviewers_login SET full_name='" . $name . "',auto_create_id='" . $autoId . "',mobile='" . $mobile . "',mail='" . $mail . "',password='" . $hash . "' ,status='1'");
            if ($insert == true) {
                showAlertswall("success", "Success", "Interviewer Account Created Successfully");
            } else {
                showAlertswall("error", "Error", "Account not created");
            }
        }
    } else {
        showAlertswall("error", "Error", "Password must contain at least one digit, one lowercase letter, one uppercase letter, one special character, and be at least 8 characters long");
    }
}


?>
<!-- 
 <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>Jaldi Hire</title>

    <link rel="icon" href="images/jaldihire-logo.png" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/animate.css">
    <link href="css/font-awesome-all.css" rel="stylesheet" />
    <link href="css/flaticon.css" rel="stylesheet" />
    <link href="css/owl.css" rel="stylesheet" />
    <link href="css/jquery.fancybox.min.css" rel="stylesheet" />
    <link href="css/animate.css" rel="stylesheet" />
    <link href="css/style2.css" rel="stylesheet" />
    <link href="css/responsive2.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">



    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>  -->

<body>
<!-- <div class="loader-spin">
    <div class="loader loading"></div>
</div> -->
     <section class="login-header fixedheader">
        <div class="container-fluid">
            <div class="int-logo">
               <a href="index.php"><img src="images/jaldi-main.png" alt="check job"></a> 
            </div>
        </div>
    </section>
    <section class="login-form2">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/GIF_Interviewer_1.gif" alt="quick it solutions">
                                <div class="log-caru-content">
                                    <h2>
                                        Access Interviews Easily
                                    </h2>
                                    <!-- <p>Analyzing previous trends ensures that businesses always make the right decision.
                                        And
                                        as the scale of the decision and it’s impact magnifies...</p> -->
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="images/GIF_Interviewer_2.gif" alt="hiring new jobs">
                                <div class="log-caru-content">
                                    <h2>Login, Review, Hire</h2>
                                    <!-- <p>Analyzing previous trends ensures that businesses always make the right decision.
                                        And
                                        as the scale of the decision and it’s impact magnifies...</p> -->
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="images/Gif_Interviewer_3.gif" alt="job advisory">
                                <div class="log-caru-content">
                                    <h2>Making Interviews Great</h2>
                                    <!-- <p>Analyzing previous trends ensures that businesses always make the right decision.
                                        And
                                        as the scale of the decision and it’s impact magnifies...</p> -->
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <div class="int-loginForm">

                        <div class="row">
                            <div class="col-md-8">
                                <p>Welcome to <span class="cl-blue-f-w">Jaldi Hire</span> </p>
                                <!-- <h6>Sign in</h6> -->

                            </div>
                            <div class="col-md-4">
                                <!-- <p class="mb-0">No Account?</p>
                                <h6>Sign up</h6> -->

                            </div>
                        </div>



                        <div>
                            <!-- <form method="post">
                                <div>
                                    <label for="">Full Name</label>
                                    <input type="text" name="full_name" placeholder="Enter full name">
                                </div>
                                <div>
                                    <label for="">Mobile Number</label>
                                    <input type="text" name="mobile_number" placeholder="Enter Mobile Number">
                                </div>
                                <div>
                                    <label for="">email address</label>
                                    <input type="text" name="mail" placeholder="Username or email address">
                                </div>
                                <div>
                                    <label for="">Create your Password</label>
                                    <input type="text" name="password" placeholder="Password" class="mb-2">

                                </div>
                                <div>
                                    <label for="">Confirm your Password</label>
                                    <input type="text" name="cnf_password" placeholder="Password" class="mb-2">

                                </div>
                                <div>
                                    <button name="submit" class="sig-btn">Sign up</button>
                                </div>

                            </form> -->

                            <form method="post">
                                <div>
                                    <!-- <label for="full_name">Full Name</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="text" name="full_name" id="full_name"
                                            placeholder="Enter full name">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>

                                </div>
                                <div>
                                    <!-- <label for="mobile_number">Mobile Number</label> -->
                                    <div class="position-relative mb-3"> 
                                        <input type="tel" name="mobile_number"
                                            id="mobile_number" placeholder="Enter Mobile Number" maxlength="10">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>


                                </div>
                                <div>
                                    <!-- <label for="mail">Email Address</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="text" name="mail" id="mail"
                                            placeholder="Email Address">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>

                                </div>
                                <div>
                                    <!-- <label for="password">Create your Password</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="password" name="password" id="password" placeholder="Password"
                                            class="mb-2">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>

                                </div>
                                <div>
                                    <!-- <label for="cnf_password">Confirm your Password</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="password" name="cnf_password" id="cnf_password"
                                            placeholder="Confirm Password" class="mb-2">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>

                                </div>
                                <div>
                                    <button id="addForm" type="button" class="sig-btn">Sign up</button>
                                    <button name="submit" id="submitForm" type="submit" class="sig-btn">Sign up</button>
                                </div>
                            </form>
                        </div>

                        <!-- <div class="midl-text">
                            <p>Or continue with</p>
                        </div> -->

                        <div class="socal_m d-flex align-items-center justify-content-center">
                            <!-- <div class="socal-icon">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M27.44 14.3187C27.44 13.326 27.3509 12.3714 27.1855 11.4551H14V16.8705H21.5345C21.21 18.6205 20.2236 20.1033 18.7409 21.096V24.6087H23.2655C25.9127 22.1714 27.44 18.5824 27.44 14.3187Z"
                                        fill="#4285F4" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.0031 28.0002C17.7831 28.0002 20.9522 26.7465 23.2686 24.6083L18.744 21.0956C17.4904 21.9356 15.8867 22.432 14.0031 22.432C10.3567 22.432 7.27037 19.9692 6.16946 16.6602H1.49219V20.2874C3.79583 24.8629 8.53037 28.0002 14.0031 28.0002Z"
                                        fill="#34A853" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.16637 16.6602C5.88637 15.8202 5.72727 14.9229 5.72727 14.0002C5.72727 13.0774 5.88637 12.1802 6.16637 11.3402V7.71289H1.48909C0.540909 9.60289 0 11.7411 0 14.0002C0 16.2593 0.540909 18.3974 1.48909 20.2874L6.16637 16.6602Z"
                                        fill="#FBBC05" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.0031 5.56818C16.0586 5.56818 17.904 6.27455 19.3549 7.66182L23.3704 3.64637C20.9458 1.38727 17.7767 0 14.0031 0C8.53037 0 3.79582 3.13727 1.49219 7.71273L6.16946 11.34C7.27037 8.03091 10.3567 5.56818 14.0031 5.56818Z"
                                        fill="#EA4335" />
                                </svg>

                            </div> -->
                            <!-- <div class="socal-icon">
                                <svg width="23" height="28" viewBox="0 0 23 28" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.8414 6.46154C13.1172 6.46154 14.7164 5.60712 15.6687 4.4679C16.5312 3.43547 17.1602 1.99364 17.1602 0.551812C17.1602 0.356008 17.1422 0.160203 17.1062 0C15.6867 0.0534012 13.9797 0.943421 12.9555 2.13605C12.1469 3.04387 11.4102 4.4679 11.4102 5.92753C11.4102 6.14113 11.4461 6.35474 11.4641 6.42594C11.5539 6.44374 11.6977 6.46154 11.8414 6.46154ZM7.34922 28C9.09219 28 9.86484 26.843 12.0391 26.843C14.2492 26.843 14.7344 27.9644 16.675 27.9644C18.5797 27.9644 19.8555 26.22 21.0594 24.5111C22.407 22.5531 22.9641 20.6306 23 20.5416C22.8742 20.506 19.2266 19.0286 19.2266 14.8811C19.2266 11.2854 22.1016 9.66561 22.2633 9.541C20.3586 6.83535 17.4656 6.76414 16.675 6.76414C14.5367 6.76414 12.7937 8.04577 11.6977 8.04577C10.5117 8.04577 8.94844 6.83535 7.09766 6.83535C3.57578 6.83535 0 9.71901 0 15.1659C0 18.548 1.32969 22.1259 2.96484 24.4399C4.36641 26.398 5.58828 28 7.34922 28Z"
                                        fill="#000100" />
                                </svg>

                            </div>
                            <div class="socal-icon">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M28.0061 14.0856C28.0061 6.30631 21.7367 0 14.003 0C6.26936 0 0 6.30631 0 14.0856C0 21.1161 5.1207 26.9433 11.8151 28V18.1572H8.2596V14.0856H11.8151V10.9823C11.8151 7.45214 13.9056 5.50217 17.1042 5.50217C18.6363 5.50217 20.2388 5.77728 20.2388 5.77728V9.24365H18.473C16.7335 9.24365 16.191 10.3294 16.191 11.4433V14.0856H20.0747L19.4538 18.1572H16.191V28C22.8853 26.9433 28.0061 21.1161 28.0061 14.0856Z"
                                        fill="#1778F2" />
                                </svg>
                            </div> -->
                        </div>



                    </div>
                </div>
            </div>
        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
    <script src="js/constant.js"></script>
    <script src="js/formValidation.js"></script>
    <script src="js/form.js"></script>
    <script>
        document.querySelector('.loader-spin').style.display = 'block';
        setTimeout(function() {
            document.querySelector('.loader-spin').style.display = 'none';
        }, 1000); // Adjust the time (in milliseconds) as needed
    </script>

</body>