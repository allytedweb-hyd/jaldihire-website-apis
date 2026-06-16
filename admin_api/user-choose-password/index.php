<?php
include "../includes/db-connection/db.php";
include "./vendor/autoload.php";
include "../libs/alerts.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

@$appId = $_GET['appId'];

$q = mysqli_query($con, "select * from tbl_applicant_add where applicant_auto_create_id='" . $appId . "'");
$f = mysqli_fetch_array($q);

@$name = $f['full_name'];
@$email = $f['e_mail'];

if (isset($_POST['submit'])) {
    $pass = $_POST['pass'];
    $cnf = $_POST['cnfpass'];

    if (!empty($f['password'])) {
        echo '<script>alert("Password already created")</script>';
        exit();
    }

    $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_+=])[a-zA-Z\d!@#$%^&*()-_+=]{8,}$/';

    if (preg_match($regex, $pass)) {
        if ($pass != $cnf) {
            echo '<script>alert("password is wrong")</script>';
        } else {
            $hash = password_hash("$cnf", PASSWORD_DEFAULT);
            $update = mysqli_query($con, "update tbl_applicant_add set password='" . $hash . "',status=1 where applicant_auto_create_id='" . $appId . "'");
            if ($update == true) {
                echo '<script>alert("password is created")</script>';
                $userId = $appId;
                $key = "J@ldiHire@321";
                $issuedAt = new DateTimeImmutable();
                $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
                $payload = array(
                    "user_id" => $userId,
                    'iat' => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
                    'nbf' => $issuedAt->getTimestamp(), // Not before
                    'exp' => $expire, // Expire Time
                );
                $jwt = JWT::encode($payload, $key, 'HS256');
                header("Location: https://applicant.jaldihire.com/?id=$jwt");
            } else {
                echo '<script>alert("password is not created")</script>';
            }
        }
    } else {
        echo '<script>alert("Password must contain at least one digit, one lowercase letter, one uppercase letter, one special character, and be at least 8 characters long.")</script>';
    }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">

pricing-4.html  18:38:51 GMT

Mirrored from wordpressbusiness.top/saas02/pricing-4.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Feb 2023 11:26:37 GMT
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<title>User Login</title>

Fav Icon
<link rel="icon" href="images/favicon.ico" type="image/x-icon">

Google Fonts
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">

Stylesheets
<link href="css/font-awesome-all.css" rel="stylesheet">
<link href="css/flaticon.css" rel="stylesheet">
<link href="css/owl.css" rel="stylesheet">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/jquery.fancybox.min.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/responsive.css" rel="stylesheet">

</head>


page wrapper
<body class="boxed_wrapper">

    preloader
    <div class="preloader"></div>
    preloader

    main header
    <header class="main-header style-four">
        <div class="outer-container">
            <div class="container">
                <div class="main-box clearfix">
                    <div class="logo-box pull-left">
                        <figure class="logo"><a href="index-2.html"><img src="images/logo.png" alt=""></a></figure>
                    </div>
                    <div class="menu-area pull-right clearfix">
                        Mobile Navigation Toggler
                        <div class="mobile-nav-toggler">
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                        </div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li class="current"><a href="#">About</a></li>
                                    <li><a href="#">Features</a></li>
                                    <li><a href="#">Solutions</a></li>
                                    <li><a href="#">FAQ</a></li>
                                    <li><a href="#">Pricing</a></li>
                                    <li><a href="#">Customers</a></li>
                                    <li><a href="#">Partners</a></li>
                                    <li><a href="#">Reviews</a></li>
                                    <li><a href="#">Set Your New Password</a></li>
                                  </ul>
                            </div>
                        </nav>
                       
                    </div>
                </div>
            </div>
        </div>

        sticky Header
        <div class="sticky-header">
            <div class="container clearfix">
                <figure class="logo-box"><a href="index-2.html"><img src="images/logo.png" alt=""></a></figure>
                <div class="menu-area">
                    <nav class="main-menu clearfix">
                        Keep This Empty / Menu will come through Javascript
                    </nav>
                </div>
            </div>
        </div>
    </header>
    main-header end

    Mobile Menu 
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><i class="fas fa-times"></i></div>
        
        <nav class="menu-box">
            <div class="nav-logo"><a href="index-2.html"><img src="images/logo.png" alt="" title=""></a></div>
            <div class="menu-outer">Here Menu Will Come Automatically Via Javascript / Same Menu as in Header</div>
            <div class="contact-info">
                <h4>Contact Info</h4>
                <ul>
                    <li>Hyderabad</li>
                    <li><a href="tel:+8801682648101">+91 9966233450</a></li>
                    <li><a href="mailto:info@example.com">jaldihire@gmail.com</a></li>
                </ul>
            </div>
            <div class="social-links">
                <ul class="clearfix">
                    <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                    <li><a href="#"><span class="fab fa-facebook-square"></span></a></li>
                    <li><a href="#"><span class="fab fa-pinterest-p"></span></a></li>
                    <li><a href="#"><span class="fab fa-instagram"></span></a></li>
                    <li><a href="#"><span class="fab fa-youtube"></span></a></li>
                </ul>
            </div>
        </nav>
    </div>End Mobile Menu

    <section class="page-title style-two">
        <div class="container">
            <div class="content-box clearfix">
                <div class="title-box pull-left">
                    <h1>Set Your New Password</h1>
                    <p>Reach out to the world’s most reliable IT Companies</p>
                </div>
                <ul class="bread-crumb pull-right">
                    <li>Element</li>
                    <li><a href="index-2.html">Home</a></li>
                </ul>
            </div>
        </div>
    </section>


    subscribe-style-five
    <section class="subscribe-style-five">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div id="image_block_38">
                        <div class="image-box">
                            <div class="bg-layer" style="background-image: url(images/icons/user-icon.png);"></div>
                            <figure class="image float-bob-y clearfix"><img src="images/resource/user-16.png" alt=""></figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div id="content_block_41">
                        <div class="content-box">
                            <div class="sec-title"><h2>Applicant Login</h2></div>
                            <div class="text">Lorem ipsum dolor sit amet consectetur adipiscing elit donec tempus pellentesque dui vel tristique purus justo</div>
                            <form  method="post" class="subscribe-form">
                                <div class="form-group">
                                    <input class="alignfgroup" type="text" value="<?php echo $name; ?>" name="email" placeholder="User Name" required="" readonly>
                                    <input class="alignfgroup" type="email" value="<?php echo $email; ?>" name="email" placeholder="User Id " required="" readonly>
                                    <input class="alignfgroup" type="password" name="pass" placeholder="Enter Your New Password" required=""   maxlength="10">
                                    <input class="alignfgroup" type="password" name="cnfpass" placeholder="Conform Your New Password" required=""   maxlength="10">
                                    <button type="submit" class="theme-btn-two" name="submit">Login Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    subscribe-style-five end

    main-footer
    <footer class="main-footer style-five style-six">
        <div class="anim-icons">
            <div class="icon icon-1"><img src="images/icons/pattern-21.png" alt=""></div>
        </div>
        <div class="image-layer" style="background-image: url(images/icons/footer-bg-6.png);"></div>
        <div class="container">
            <div class="footer-top">
                <div class="widget-section">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                            <div class="about-widget footer-widget">
                                <figure class="footer-logo"><a href="index-2.html"><img src="images/logo.png" alt=""></a></figure>
                                <div class="text">Lorem ipsum dolor sit consectetur adipisicing elit, sed do eiusmod tempor .........</div>
                                <div class="apps-download">
                                    <h3>Download the App</h3>
                                    <div class="download-btn">
                                        <a href="#" class="app-store-btn">
                                            <i class="fab fa-apple"></i>
                                            <span>Download on the</span>
                                            App Store
                                        </a>
                                        <a href="#" class="google-play-btn">
                                            <i class="fab fa-android"></i>
                                            <span>Get on it</span>
                                            Google Play
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 footer-column">
                            <div class="links-widget footer-widget">
                                <h4 class="widget-title">Services</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        <li><a href="#">Features</a></li>
                                        <li><a href="#">Pricing</a></li>
                                        <li><a href="#">Solutions</a></li>
                                        <li><a href="#">FAQ</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="contact-widget footer-widget">
                                <h4 class="widget-title">Contact Us</h4>
                                <div class="widget-content">
                                    <ul class="contact-info clearfix">
                                        <li><i class="fas fa-map-marker-alt"></i>Hyderabad</li>
                                        <li><i class="fas fa-phone"></i><a href="tel:0665184575181">+91 9987236353</a></li>
                                        <li><i class="fas fa-envelope"></i><a href="mailto:info@example.com">info@example.com</a></li>
                                    </ul>
                                </div>
                                <ul class="social-links clearfix">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-skype"></i></a></li>
                                    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="links-widget footer-widget">
                                <h4 class="widget-title">About Company</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        <li><a href="#">About</a></li>
                                        <li><a href="#">Our vision</a></li>
                                        <li><a href="#">Carrers</a></li>
                                        <li><a href="#">What We Do</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom clearfix">
                <ul class="footer-nav pull-right">
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Legal</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">&copy; <a href="#">Jaldihire</a>. All rights reserved 2023</div>
    </footer>
    main-footer end


    sidebar cart item
    <div class="xs-sidebar-group info-group info-sidebar">
        <div class="xs-overlay xs-bg-black"></div>
        <div class="xs-sidebar-widget">
            <div class="sidebar-widget-container">
                <div class="widget-heading">
                    <a href="#" class="close-side-widget">
                        X
                    </a>
                </div>
                <div class="sidebar-textwidget">
                    
                    Sidebar Info Content
                <div class="sidebar-info-contents">
                    <div class="content-inner">
                        <div class="logo">
                            <a href="index-2.html"><img src="images/logo.png" alt="" /></a>
                        </div>
                        <div class="content-box">
                            <h4>About Us</h4>
                            <p class="text">Lorem ipsum dolor amet consectetur sed do eiusmod tempor incididunt ut labore. Lorem ipsum dolor amet consectetur adipisicing sed do eiusmod tempor incididunt ut labore.</p>
                            <a href="#" class="theme-btn-two">Explore</a>
                        </div>
                        <div class="contact-info">
                            <h4>Contact Info</h4>
                            <ul>
                                <li>Hyderabad</li>
                                <li><a href="tel:+8801682648101">+88 01682648101</a></li>
                                <li><a href="mailto:info@example.com">Jaldihire@gmail.com</a></li>
                            </ul>
                        </div>
                        Social Box
                        <ul class="social-box">
                            <li class="facebook"><a href="#" class="fab fa-facebook-f"></a></li>
                            <li class="twitter"><a href="#" class="fab fa-twitter"></a></li>
                            <li class="linkedin"><a href="#" class="fab fa-linkedin-in"></a></li>
                            <li class="instagram"><a href="#" class="fab fa-instagram"></a></li>
                            <li class="youtube"><a href="#" class="fab fa-youtube"></a></li>
                        </ul>
                    </div>
                </div>
                    
                </div>
            </div>
        </div>
    </div>
    END sidebar widget item



Scroll to top
<button class="scroll-top scroll-to-target" data-target="html">
    <span class="fa fa-arrow-up"></span>
</button>


jequery plugins
<script src="js/jquery.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.js"></script>
<script src="js/wow.js"></script>
<script src="js/validation.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/appear.js"></script>
<script src="js/circle-progress.js"></script>
<script src="js/jquery.countTo.js"></script>
<script src="js/scrollbar.js"></script>
<script src="js/nav-tool.js"></script>
<script src="js/jquery.paroller.min.js"></script>
<script src="js/tilt.jquery.js"></script>

main-js
<script src="js/script.js"></script>

</body>End of .page_wrapper

pricing-4.html  18:38:51 GMT

Mirrored from wordpressbusiness.top/saas02/pricing-4.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Feb 2023 11:26:37 GMT
</html> -->
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/styles1.css" />
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
                                        placeholder="Enter Your New Password" required maxlength="10"
                                        onpaste="return false" ondrop="return false">
                                    <span toggle="#password-input" class="eye-icon"></span>
                                </div>
                            </div>
                            <div class="col-md-12  col-xs-12">
                                <div class="form-grp password-content">
                                    <input type="password" name="cnfpass" id="password-input" class="form-control"
                                        placeholder="Confirm Your New Password" required maxlength="10"
                                        onpaste="return false" ondrop="return false">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="jal-btn">
                                    <button type="submit" name="submit" id="submit-btn">Sign Up</button>
                                </div>
                            </div>
                            <p class="stepp">Once Step a head to join our family </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const eyeIcon = document.querySelector('.eye-icon');
                const passwordInput = document.querySelector('#password-input');
                eyeIcon.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('hide-password');
                });
            });
        </script>
    </footer>
</body>

</html>