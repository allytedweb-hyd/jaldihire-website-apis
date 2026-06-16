<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

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
    <!-- <link href="css/responsive.css" rel="stylesheet" /> -->
    <link href="css/responsive2.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <section class="logo fixedheader empad">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-expand-l navbar-light">
                    <a class="navbar-brand d-flex" href="index.php"><img src="./images/jaldi-hire-logo.png"
                            alt="hire faster with AI"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse nav-bar " id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="emp-about-us.php">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="emp-priceing.php">Pricing</a>
                            </li>

                        </ul>
                        <div class="form-inline my-2 my-lg-0 mlft">
                            <a href="contactsales.php">
                                <button class="main-btn my-2 my-sm-0 md-6" type="submit">Request a Demo</button>
                            </a>
                        </div>
                        <div class="form-inline my-2 my-lg-0 mlft">
                            <a href="https://jaldihire.com/employer/">
                                <button class="main-btn my-2 my-sm-0 md-6" type="">Login</button>
                            </a>
                            <!-- <a href="#"><button class="btn my-2 my-sm-0 md-6" type="">Sign
                                    up</button></a> -->

                        </div>
                        <div class="form-inline my-2 my-lg-0 mlft">
                            <a href="employer-signup.php">
                                <button class="main-btn my-2 my-sm-0 md-6" type="">Signup</button>
                            </a>
                            <!-- <a href="#"><button class="btn my-2 my-sm-0 md-6" type="">Sign
                                    up</button></a> -->

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