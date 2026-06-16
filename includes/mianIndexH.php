<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="google-site-verification" content="dlKqKHeMgvX7txOkbsfV-02D3LYoj6YmzW_UWr7G5sE" />

    <!-- <title>Jaldi Hire</title> -->
    <title><?php echo isset($title) ? htmlspecialchars($title) : "Default Title"; ?></title>
    <meta name="msvalidate.01" content="A818BD64095D4DC1A863F23ED27307BB" />
    <?php
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  $uri = strtok($_SERVER["REQUEST_URI"], '?'); // remove query string if needed
  $canonicalUrl = $protocol . $_SERVER['HTTP_HOST'] . $uri;
  ?>
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>" />
    <meta name="description"
        content="<?php echo isset($meta_description) ? htmlspecialchars($meta_description) : 'Default site description'; ?>">
    <!-- Fav Icon -->
    <link rel="icon" href="images/circle-google-logo.png" style="width: 26px; height: 26px" type="image/x-icon" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/animate.css">
    <!-- Stylesheets -->
    <link href="css/font-awesome-all.css" rel="stylesheet" />
    <link href="css/flaticon.css" rel="stylesheet" />
    <link href="css/owl.css" rel="stylesheet" />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/jquery.fancybox.min.css" rel="stylesheet" />
    <link href="css/animate.css" rel="stylesheet" />
    <link href="css/style2.css" rel="stylesheet" />
    <!-- <link href="css/style3.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <!-- <link href="css/responsive.css" rel="stylesheet" /> -->
    <link href="css/responsive2.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/appear.js"></script>
    <!-- Include Toastr CSS and JavaScript from CDN -->
    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Noty CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" rel="stylesheet" />

    <!-- Noty JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <script src="https://analytics.ahrefs.com/analytics.js" data-key="PCGNCCtJNJ6ICxNu3rwr9A" async></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-794L8BN5ER"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-794L8BN5ER');
    </script>
    <?php if (!empty($schema)): ?>
    <script type="application/ld+json">
    <?php echo $schema; ?>
    </script>
    <?php endif; ?>
    <script>
    (function(c, l, a, r, i, t, y) {
        c[a] = c[a] || function() {
            (c[a].q = c[a].q || []).push(arguments)
        };
        t = l.createElement(r);
        t.async = 1;
        t.src = "https://www.clarity.ms/tag/" + i + "?ref=bwt";
        y = l.getElementsByTagName(r)[0];
        y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "s3tkpxgv7s");
    </script>


</head>

<!-- page wrapper -->

<body class="boxed_wrapper">
    <!-- preloader -->
    <!-- <div class="preloader"></div> -->
    <!-- preloader -->
    <div class="loader-spin">
        <div class="loader loading"></div>
    </div>
    <!-- main header -->
    <header>
        <section class="logo mainhome fixedheader">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-expand-l navbar-light">
                    <a class="navbar-brand d-flex finalpage" href="index.php"><img src="./images/jaldi-hire-logo.png"
                            alt="hire faster with AI"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse nav-bar" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.php">Home</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link active" href="emp-about-us.php">About Us</a>
                            </li>

                            <li class="nav-item serv-act">
                                <a class="nav-link active" href="services.php">Services</a>
                                <section class="serv-hov">
                                    <div class="container">
                                        <div class="row">
                                            <div class="services">
                                                <div class="col-md-6">
                                                    <div>
                                                        <img src="images/triangle.png" class="services_mg"
                                                            alt="recruitment software India">
                                                        <h5 class="hea_ser">
                                                            Services
                                                        </h5>
                                                    </div>

                                                    <div class="job pt-2">
                                                        <div class="job_icon">
                                                            <img src="images/v1.png" alt="freelance interviewers India">
                                                        </div>


                                                        <div class="job_advisory">
                                                            <a href="services.php#advisor">
                                                                <h5 class="advisory">
                                                                    Job Advisory
                                                                </h5>
                                                            </a>

                                                            <p class="guidance">
                                                                Expert guidance to help you navigate your career path
                                                                and achieve your professional
                                                                goals
                                                            </p>
                                                        </div>
                                                    </div>




                                                    <div class="job pt-3">
                                                        <div class="job_icon">
                                                            <img src="images/v2.png" alt="my job">
                                                        </div>

                                                        <div class="job_advisory">
                                                            <a href="services.php#branding">
                                                                <h5 class="advisory">
                                                                    Employer Branding
                                                                </h5>
                                                            </a>
                                                            <p class="guidance">
                                                                Build a strong employer brand that attracts top talent
                                                                and sets you apart from
                                                                competitors.
                                                            </p>
                                                        </div>
                                                    </div>




                                                    <div class="job pt-3">
                                                        <div class="job_icon">
                                                            <img src="images/v3.png" alt="job posting">
                                                        </div>

                                                        <div class="job_advisory">
                                                            <a href="services.php#interview">
                                                                <h5 class="advisory">
                                                                    Interview Out Sourcing
                                                                </h5>
                                                            </a>
                                                            <p class="guidance">
                                                                Streamline your interview process by outsourcing to our
                                                                experienced team, ensuring a
                                                                smooth candidate experience.
                                                            </p>
                                                        </div>
                                                    </div>




                                                    <div class="job pt-3">
                                                        <div class="job_icon">
                                                            <img src="images/v4.png" alt="Best Paying jobs">
                                                        </div>

                                                        <div class="job_advisory">
                                                            <a href="services.php#candidate">
                                                                <h5 class="advisory">
                                                                    Candidate Engagement
                                                                </h5>
                                                            </a>
                                                            <p class="guidance">
                                                                Keep candidates engaged throughout the hiring journey,
                                                                enhancing their experience and
                                                                your reputation.
                                                            </p>
                                                        </div>
                                                    </div>




                                                </div>



                                                <div class="col-md-6">


                                                    <div class="job pt-5">
                                                        <div class="job_icon">
                                                            <img src="images/v5.png" alt="job opportunities">
                                                        </div>

                                                        <div class="job_advisory">
                                                            <a href="services.php#sourcing">
                                                                <h5 class="advisory">
                                                                    Profile Sourcing
                                                                </h5>
                                                            </a>
                                                            <p class="guidance">
                                                                We find top talent that aligns with your needs, saving
                                                                you time in the recruitment
                                                                process
                                                            </p>
                                                        </div>
                                                    </div>



                                                    <div class="job pt-3">
                                                        <div class="job_icon">
                                                            <img src="images/v6.png" alt="AI hiring solution">
                                                        </div>

                                                        <div class="job_advisory">

                                                            <a href="services.php#verfication">
                                                                <h5 class="advisory">
                                                                    BGV Verificaion
                                                                </h5>
                                                            </a>
                                                            <p class="guidance">
                                                                Reliable background verification services to ensure the
                                                                integrity and safety of your
                                                                hires.
                                                            </p>
                                                        </div>
                                                    </div>




                                                    <div class="job pt-3">
                                                        <div class="job_icon">
                                                            <img src="images/v7.png" alt="job listings">
                                                        </div>

                                                        <div class="job_advisory">
                                                            <a href="services.php#integrated">
                                                                <h5 class="advisory">
                                                                    Integrated ATS
                                                                </h5>
                                                            </a>
                                                            <p class="guidance">
                                                                Our advanced Applicant Tracking System helps you manage
                                                                candidates efficiently, from
                                                                application to hiring.
                                                            </p>
                                                        </div>
                                                    </div>


                                                    <!-- <div class="job pt-3">
                                                        <div class="job_icon">
                                                            <img src="images/vector-7.png" alt="">
                                                        </div>

                                                        <div class="job_advisory">
                                                        <a href="services.php#boarding">
                                                            <h5 class="advisory">
                                                                Onboarding
                                                            </h5>
                                                        </a>

                                                            <p class="guidance">
                                                                Smooth and efficient onboarding processes to help new
                                                                hires settle in quickly and
                                                                effectively.
                                                            </p>
                                                        </div>
                                                    </div> -->

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </section>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " href="employer.php">Employer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="https://jaldihire.com/applicant/">Job Seeker</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="interviewer.php">Interviewer</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link " href="contactsales.php">Contact Us</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link " href="https://jaldihire.com/applicant/search/">Browse Jobs <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision"
                                            text-rendering="geometricPrecision" image-rendering="optimizeQuality"
                                            fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 509 511.54" width="13"
                                            height="13" fill="#fff">
                                            <path fill-rule="nonzero"
                                                d="M447.19 347.03c0-17.06 13.85-30.91 30.91-30.91 17.05 0 30.9 13.85 30.9 30.91v87.82c0 21.08-8.63 40.29-22.51 54.18-13.88 13.88-33.09 22.51-54.18 22.51H76.69c-21.09 0-40.3-8.63-54.18-22.51C8.63 475.14 0 455.93 0 434.85V76.69c0-21.09 8.63-40.3 22.51-54.18C36.39 8.63 55.6 0 76.69 0h86.98c17.06 0 30.9 13.85 30.9 30.9 0 17.06-13.84 30.91-30.9 30.91H76.69c-4.07 0-7.82 1.69-10.51 4.37-2.68 2.69-4.37 6.44-4.37 10.51v358.16c0 4.06 1.69 7.82 4.37 10.5 2.69 2.68 6.44 4.38 10.51 4.38h355.62c4.07 0 7.82-1.7 10.51-4.38 2.68-2.68 4.37-6.44 4.37-10.5v-87.82zm0-243.56L308.15 244.28c-11.91 12.12-31.45 12.28-43.56.37-12.11-11.91-12.28-31.45-.37-43.56L401.77 61.81H309.7c-17.06 0-30.9-13.85-30.9-30.91 0-17.05 13.84-30.9 30.9-30.9h168.4C495.15 0 509 13.85 509 30.9v165.04c0 17.06-13.85 30.9-30.9 30.9-17.06 0-30.91-13.84-30.91-30.9v-92.47z" />
                                        </svg>
                                    </span></a>
                            </li>

                        </ul>
                        <div class="form-inline my-2 my-lg-0">
                            <a href="contactsales.php">
                                <button class="main-btn mb-2 ml" type="button">Contact Sales<span>
                                        <!-- <i class="fa fa-paper-plane" aria-hidden="true"></i> -->
                                    </span></button>
                            </a>


                        </div>
                </nav>
            </div>
        </section>
    </header>
    <!-- main-header end -->

    <!-- Mobile Menu  -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><i class="fas fa-times"></i></div>

        <nav class="menu-box">
            <div class="nav-logo">
                <a href="#"><img src="images/jaldihire-logo.png" alt="background verification tool" /></a>
            </div>
            <div class="menu-outer">
                <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
            </div>
            <div class="contact-info">
                <h4>Contact Info</h4>
                <ul>
                    <li>Hyderabad</li>
                    <li><a href="tel:+8801682648101">+91 9876543210</a></li>
                    <li><a href="mailto:info@example.com">info@example.com</a></li>
                </ul>
            </div>
            <div class="social-links">
                <ul class="clearfix">
                    <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="15" fill="#fff" width="15"
                                viewBox="0 0 512 512">
                                <path
                                    d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z">
                                </path>
                            </svg></a></li>
                    <li>
                        <a href="#"><span class="fab fa-facebook-square"></span></a>
                    </li>
                    <li>
                        <a href="#"><span class="fab fa-instagram"></span></a>
                    </li>
                    <li>
                        <a href="#"><span class="fab fa-youtube"></span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- End Mobile Menu -->

    <!-- banner-section -->