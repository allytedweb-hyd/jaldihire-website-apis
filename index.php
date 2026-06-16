<?php
header("Content-Security-Policy: default-src 'self';");
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");
    header("Permissions-Policy: geolocation=(), microphone=()");
$title = "Hire Smarter with Jaldi Hire | AI-Powered Recruitment Solutions.";
$meta_description = "Access essential insights tailored for employers, interviewers, and Applicants. Elevate your hiring and career strategy with expert-driven guidance.";
$schema = <<<JSON
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Jaldi Hire",
  "url": "https://www.jaldihire.com",
  "logo": "https://www.jaldihire.com/images/JHGOOGLELOGO.png",
  "sameAs": [
    "https://www.linkedin.com/company/jaldi-hire/",
    "https://www.instagram.com/jaldihire"
  ],
  "description": "Jaldi Hire is an AI-powered recruitment platform for employers, job seekers, and freelance interviewers.",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91 8179592757",
    "contactType": "Customer Support",
    "areaServed": "IN",
    "availableLanguage": "English"
  }
}
JSON;
include 'includes/mianIndexH.php';
include "includes/db-connection/db.php";

include "contact-form.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



if (isset($_POST['submitContact'])) {
    var_dump($_POST);
    // Get form data
    SendContactMail();

}


?>
<!-- banner section  start-->
<!-- <div id="loader">
    <img src="path/to/loader.gif" alt="Loading...">
</div> -->

<!-- <style>
#loader {
    display: none;
 
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
}
</style> -->


<section class="threeBanner pTB-140 servmt">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mainth-baner">
                <!-- <h6 class="">360 Hiring Solution</h6> -->
                <h1 class="">360<sup class="degrees">o</sup><br><span class=""> Hiring </span> <span>Solution</span>
                </h1>
                <p>Jaldi Hire is a SaaS platform that enables employers to create job listings, search a pre-screened
                    talent pool, manage candidate profiles with Integrated ATS, configure interview rounds, and issue
                    offer letters in one place with a <span>360-degree</span> solution
                </p>
                <h2 class="achieve"> “Achieve your hiring targets in a most <span>capital efficient</span> way”</h2>
                <a href="contactsales.php">
                    <button class="main-btn">Contact Sales</button>
                </a>
            </div>


            <div class="col-md-8 d-flex">

                <img class="m41" src="images/m4.png" alt="AI recruitment platform">
                <img class="m42" src="images/m4.png" alt="AI recruitment platform">
                <img class="m43" src="images/m4.png" alt="AI recruitment platform">
                <img class="m44" src="images/m4.png" alt="AI recruitment platform">
                <div class="m45">
                    <!-- <img class="m45" src="images/banner-bur.png" alt="image"> -->
                    <!-- <svg width="800" height="600" viewBox="0 0 1241 1369" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_f_8812_41441)">
                            <rect x="565.758" y="224" width="501.312" height="779.612" rx="60"
                                transform="rotate(26 565.758 224)" fill="#0560B9" />
                        </g>
                        <defs>
                            <filter id="filter0_f_8812_41441" x="0.210938" y="0.214844" width="1239.91" height="1368.04"
                                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                <feGaussianBlur stdDeviation="122" result="effect1_foregroundBlur_8812_41441" />
                            </filter>
                        </defs>
                    </svg> -->
                </div>

                <div class="yel-men ">
                    <p>Interviewer</p>
                    <img src="images/m1.png" alt="job and recruitment">
                </div>
                <div class="gen-men ">

                    <img src="images/m2.png" alt="jobs and hiring">
                    <p>Applicant</p>
                </div>
                <div class="red-men ">

                    <img src="images/m3.png" alt="job descriptions">
                    <p>Employer</p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- banner section  end-->

<!-- service section  start-->
<section class="servicesec">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ser-cent">
                <h2>
                    The Ultimate Fusion of 4 <span>Powerful</span> Forces
                </h2>
                <p>The traditional hiring process can’t solve the new generation's hiring challenges!
                    It requires new practices, strategies, technology, and recruiters with blended skills.

                </p>
            </div>
        </div>
        <div class="row align-items-center mt-4">
            <div class="col-md-3 col-sm-3 col-3 mt-2">
                <ul class="servicList">
                    <li class="sred d-flex">
                        <p>Best Practices</p> <span><img src="images/fi1.png" alt="CHRO recruitment tools"></span>
                    </li>
                    <li class="sgren d-flex">
                        <p>Strategies</p> <span><img src="images/fi2.png" alt="job search"></span>
                    </li>
                    <li class="sblue d-flex">
                        <p>Technology</p> <span><img src="images/fi3.png" alt="application for job search"></span>
                    </li>

                    <li class="svilet d-flex">
                        <p>Expert team</p> <span><img src="images/fi4.png" alt="Remote jobs"></span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 col-sm-6 col-6   d-flex align-items-center">
                <!-- <img src="images/serc.png" alt="image"> -->
                <div class="w-100">
                    <img src="images/for1.png" alt="job seeker" class="frc-1">
                    <img src="images/for2.png" alt="remote hiring tool" class="frc-2">
                    <img src="images/for3.png" alt="new job" class="frc-3">
                    <img src="images/for4.png" alt="recruitment in job" class="frc-4">
                </div>
                <div class="w-15">
                    <img src="images/for5.png" alt="job hire" class="frc-5">
                </div>
            </div>
            <div class="col-md-3 col-sm-3  col-3 srt mt-5">
                <div class="sr-l-out">
                    <div class="sr-l-in">
                        <img src="images/serv-im.png" alt="top hire">
                    </div>
                </div>
                <img src="images/serlogo.png" alt="SAP hiring" class="srlog">
            </div>
        </div>
        <div>
            <img src="images/power.png" alt="candidate screening automation" class="power-img">
        </div>
    </div>
</section>
<!-- service section  end-->

<!-- login section strat -->
<section class="loginsec pTB-100">
    <div class="loginlabl container">
        <div class="loginaf"></div>
        <div class="logtext">
            LOGINS
        </div>
        <div class="loginbf"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 portal text-center hiringg e">
                <h6 class="hiree">Hiring is</h6>
                <h1 class="f-white">Smart Solutions<br>for
                    <span class="spn1">Diverse Requirements <span class="spn2">easy</span>

                </h1>
                <h6 class="nowt">now!</h6>
            </div>
            <img src="images/hiring.jpeg" class="hiring-img" alt="great paying job">
        </div>
    </div>
    <!-- frist section start -->
    <div class="app-portl">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 apl-cont conttt">
                    <h1 class="f-white">Boost Your <span>Hiring Speed by 10x </span> Secure Top Talent Faster
                    </h1>
                    <p>Get access to pre-screened profiles ready to join your team in 30 days.</p>
                    <ul>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>In-Built ATS</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Interview Outsourcing</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>BGV Screening</li>
                        <!-- <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Candidate Engagement</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Payroll</li> -->
                    </ul>

                    <ul class="contt">
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Candidate Engagement</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Payroll</li>
                    </ul>

                    <div class="exp-btn">
                        <a href="employer.php"> <button>
                                Explore more features <span><i class="fa fa-arrow-up " aria-hidden="true"></i></span>
                            </button>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6  po-rl">

                    <img src="images/bgr1.gif" alt="apply now" class="fst-gif">


                    <img src="images/cardwebimg-1.png" alt="jobs recruitment" class="fst-gifb">

                    <div class="dabule-text">
                        Hire Now
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- frist section start -->

    <!-- two section start -->
    <div class="app-portl2">
        <div class="container">
            <div class="row">

                <div class="col-md-6 po-rl">

                    <img src="images/bgr2.gif" alt="tech hiring automation" class="fst-gif_girl">


                    <img src="images/maen1.png" alt="quick jobs" class="fst-gifb">

                    <div class="dabule-text">
                        Earn Now
                    </div>
                </div>

                <div class="col-md-6 apl-cont contttt text-end">
                    <h1 class="">Earn Money as a <br> <span>Freelance Interviewer</span>
                    </h1>
                    <p>Be part of a 500+ freelance interviewer community!
                        Create your profile, conduct interviews in your free time,
                        help companies find top talent, and get instantly paid for your insights.
                    </p>
                    <ul>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Work Remote</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Flexible Schedules</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Instant Payment</li>
                    </ul>
                    <div class="d-flex justify-content-end">
                        <a href="interviewer.php">
                            <button>
                                Get more details <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                            </button>
                        </a>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- two section start -->


    <!-- three section start -->
    <div class="app-portl3">
        <div class="container">
            <div class="row">
                <div class="col-md-6 apl-cont apl-cont2">
                    <h1 class="f-white">Struggling to Find <br>
                        <span>High-Paying Jobs? </span>
                    </h1>
                    <p>Unlock hidden high-paying jobs with personal guidance, profile marketing, and direct access to
                        negotiate with decision-makers.</p>
                    <ul>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Get Higher CTC</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Profile Branding</li>
                        <li><span><i class="fa fa-star" aria-hidden="true"></i></span>Better Work-Life Balance</li>
                    </ul>
                    <a href="https://jaldihire.com/applicant/">
                        <button>
                            Check how <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>

                <div class="col-md-6 po-rl text-center">

                    <img src="images/bgr4.gif" alt="full time jobs" class="fst-gif giff">


                    <img src="images/maen2.png" alt="job posting platform" class="fst-gifb">

                    <div class=" dabule-text dabule-text_text">
                        Apply Now
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- three section start -->

</section>
<!-- login section end -->

<!-- clients section start -->
<section class="clienstsec">
    <div class="loginlabl container lgt">
        <div class="loginaf"></div>
        <div class="logtext">
            OUR CLIENTS
        </div>
        <div class="loginbf"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 clienstse text-center">
                <h1 class="f-white">
                    <span>Trusted</span>
                    by Industry Leaders
                </h1>
                <p>Join our roster of satisfied clients and experience the exceptional results and service that have
                    earned us the trust of industry leaders </p>
            </div>
        </div>
        <div class="allClients text-center">
            <ul class="d-flex justify-content-center ml-4">
                <li><img src="images/r11.png" alt="recruitment and hiring process"></li>
                <li><img src="images/r12.png" alt="recruitment process optimization"></li>
                <li><img src="images/r13.png" alt="we hiring"></li>
                <li><img src="images/r14.png" alt="SAP jobs"></li>
                <li><img src="images/r15.png" alt="job requirements"></li>
            </ul>
            <ul class="d-flex justify-content-center">
                <li><img src="images/r21.png" alt="applicant tracking system (ATS)"></li>
                <li><img src="images/r22.png" alt="Contract to hire"></li>
                <li><img src="images/r23.png" alt="job offerings"></li>
                <li><img src="images/r24.png" alt="job postings"></li>
                <li><img src="images/r25.png" alt="updated job"></li>
                <li><img src="images/r26.png" alt="SaaS hiring solution"></li>
                <li><img src="images/r27.png" alt="high paying jobs "></li>
            </ul>
        </div>
    </div>

    <div class="loginlabl container">
        <div class="loginaf"></div>
        <div class="logtext">
            FEEDBACK
        </div>
        <div class="loginbf"></div>
    </div>

    <div class="container">
        <div class="clt-says">
            <div class="d-flex justify-content-center align-items-end  gap-125">
                <div>
                    <img src="images/says1.png" alt="freelancing jobs" class="saysimg">
                </div>
                <div>
                    <h3>Let’s <span>Hear</span> <br>
                        What They Say</h3>
                </div>
                <div>
                    <img src="images/says2.png" alt="jobs in search" class="saysimg">
                </div>
            </div>
            <div class="carousel-wrap">
                <div class="owl-carousel">
                    <div class="item">
                        <div class="clsayp">
                            Jaldi Hire helped us hire people quickly. The platform is easy to use, so we saved much
                            time. Posting jobs and tracking candidates all in one place made things simple. The
                            interview process went smoothly, and giving feedback was easy. We found great candidates
                            fast.

                            We had a great time using Jaldi Hire. It’s an easy tool that makes hiring faster. We could
                            post jobs quickly and track candidates without any trouble. The feedback system made it easy
                            for us to talk to each other and make decisions.

                            Finding my new job was easy with this platform. The application was simple, and I could
                            check my progress whenever possible. Scheduling interviews was quick, and I got updates
                            right away. This made my job search much simpler.

                            <ul>
                                <li><img src="images/cls-2.png" alt="SAP"></li>
                            </ul>

                            <div class="cltname">
                                <h5>Anjali Devi</h5>
                            </div>

                        </div>
                    </div>
                    <div class="item">
                        <div class="clsayp">
                            Jaldi Hire helped us hire people quickly. The platform is easy to use, so we saved much
                            time. Posting
                            jobs and tracking candidates all in one place made things simple. The interview process went
                            smoothly,
                            and giving feedback was easy. We found great candidates fast.

                            We had a great time using Jaldi Hire. It’s an easy tool that makes hiring faster. We could
                            post jobs
                            quickly and track candidates without any trouble. The feedback system made it easy for us to
                            talk to
                            each other and make decisions.

                            Finding my new job was easy with this platform. The application was simple, and I could
                            check my
                            progress whenever possible. Scheduling interviews was quick, and I got updates right away.
                            This made my
                            job search much simpler.

                            <ul>
                                <li><img src="images/cls-3.png" alt="automated hiring"></li>
                            </ul>

                            <div class="cltname">
                                <h5>Vipin Raj </h5>
                            </div>

                        </div>
                    </div>
                    <div class="item">
                        <div class="clsayp">
                            Jaldi Hire helped us hire people quickly. The platform is easy to use, so we saved much
                            time. Posting
                            jobs and tracking candidates all in one place made things simple. The interview process went
                            smoothly,
                            and giving feedback was easy. We found great candidates fast.

                            We had a great time using Jaldi Hire. It’s an easy tool that makes hiring faster. We could
                            post jobs
                            quickly and track candidates without any trouble. The feedback system made it easy for us to
                            talk to
                            each other and make decisions.

                            Finding my new job was easy with this platform. The application was simple, and I could
                            check my
                            progress whenever possible. Scheduling interviews was quick, and I got updates right away.
                            This made my
                            job search much simpler.

                            <ul>
                                <li><img src="images/cls-4.png" alt="hybrid jobs"></li>

                            </ul>

                            <div class="cltname">
                                <h5>Arjun Gill </h5>
                            </div>

                        </div>
                    </div>

                </div>
            </div>



            <!-- <ul>

                <li><img src="images/cls-2.png" alt="image"></li>
                <li><img src="images/cls-3.png" alt="image"></li>
                <li><img src="images/cls-4.png" alt="image"></li>

            </ul> -->

        </div>
    </div>
</section>

<!-- <div class="carousel-wrap">
    <div class="owl-carousel">
        <div class="item">
            <div class="clsayp">
                Jaldi Hire helped us hire people quickly. The platform is easy to use, so we saved much time. Posting
                jobs and tracking candidates all in one place made things simple. The interview process went smoothly,
                and giving feedback was easy. We found great candidates fast.

                We had a great time using Jaldi Hire. It’s an easy tool that makes hiring faster. We could post jobs
                quickly and track candidates without any trouble. The feedback system made it easy for us to talk to
                each other and make decisions.

                Finding my new job was easy with this platform. The application was simple, and I could check my
                progress whenever possible. Scheduling interviews was quick, and I got updates right away. This made my
                job search much simpler.

            </div>
        </div>
        <div class="item">
            <div class="clsayp">
                Jaldi Hire helped us hire people quickly. The platform is easy to use, so we saved much time. Posting
                jobs and tracking candidates all in one place made things simple. The interview process went smoothly,
                and giving feedback was easy. We found great candidates fast.

                We had a great time using Jaldi Hire. It’s an easy tool that makes hiring faster. We could post jobs
                quickly and track candidates without any trouble. The feedback system made it easy for us to talk to
                each other and make decisions.

                Finding my new job was easy with this platform. The application was simple, and I could check my
                progress whenever possible. Scheduling interviews was quick, and I got updates right away. This made my
                job search much simpler.

            </div>
        </div>
        <div class="item">
            <div class="clsayp">
                Jaldi Hire helped us hire people quickly. The platform is easy to use, so we saved much time. Posting
                jobs and tracking candidates all in one place made things simple. The interview process went smoothly,
                and giving feedback was easy. We found great candidates fast.

                We had a great time using Jaldi Hire. It’s an easy tool that makes hiring faster. We could post jobs
                quickly and track candidates without any trouble. The feedback system made it easy for us to talk to
                each other and make decisions.

                Finding my new job was easy with this platform. The application was simple, and I could check my
                progress whenever possible. Scheduling interviews was quick, and I got updates right away. This made my
                job search much simpler.

            </div>
        </div>
    </div>
</div> -->
<!-- clients section end -->
<!-- case collection start -->
<section class="collectionsec">
    <div class="loginlabl container mb-0 mt-5">
        <div class="loginaf"></div>
        <div class="logtext">
            CASE STUDIES
        </div>
        <div class="loginbf"></div>
    </div>
    <div class="container">
        <div class="brdr-rimg">
            <img src="images/colt-im1.png" alt="employer job dashboard" class="">
        </div>
        <div class="collocont">


            <h1>Case <br>Collection <span>STUDIES</span></h1>
            <img src="images/clhand.png" alt="job seeker advisory" class="">
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="coln-card bg-v-light">
                    <div class="text-center">
                        <img src="images/casestudyone.png" alt="career jobs">
                    </div>
                    <h5>
                        Attracting Top IT Talent in India:Strategies for success
                    </h5>
                    <a href="case-studies.php">
                        <button>
                            Read Case Study <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="coln-card bg-r-light">
                    <div class="text-center">
                        <img src="images/jobdescription.jpg" alt="job profile">
                    </div>
                    <h5>
                        How to Write Job Descriptions That Attract Top IT Talent Quickly
                    </h5>
                    <a href="case-studies-two.php">
                        <button>
                            Read Case Study <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="coln-card bg-g-light">
                    <div class="text-center">
                        <img src="images/downcost.jpg" alt="we are hiring">
                    </div>
                    <h5>
                        How to Cut Down the Cost and Expense in Hiring Practice
                    </h5>
                    <a href="case-studies-three.php">
                        <button>
                            Read Case Study <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>
            </div>

        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="coln-card bg-b-light">
                    <div class="text-center">
                        <img src="images/Recruitment.jpg" alt="digital hiring assistant">
                    </div>
                    <h5>
                        Leveraging AI in IT Recruitment: How to Attract Top Talent Faster
                    </h5>
                    <a href="case-studies-four.php">
                        <button>
                            Read Case Study <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="coln-card bg-p-light">
                    <div class="text-center">
                        <img src="images/recruitment-marketing-strategies.jpg" alt="Recruitment Marketing Strategies for IT Companies">
                    </div>
                    <h5>
                        Recruitment Marketing Strategies for IT Companies: What Works in 2025
                    </h5>
                    <a href="case-studies-five.php">
                        <button>
                            Read Case Study <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="coln-card bg-s-light">
                    <div class="text-center">
                        <img src="images/the-role-of-descison-making.jpg" alt="salary negotiation advice">
                    </div>
                    <h5>
                    The Role of Data-Driven Decision Making in IT Recruitment: Insights for 2024
                    </h5>
                    <a href="case-studies-six.php">
                        <button>
                            Read Case Study <span><i class="fa fa-arrow-up" aria-hidden="true"></i></span>
                        </button>
                    </a>
                </div>
            </div>

        </div>

    </div>

</section>
<!-- case collection end -->

<!-- contact section start -->

<section class="contactussec pTB-100 pt-2" id="contactus">
    <div class="loginlabl container mb-0 mt-5 mb-5  ">
        <div class="loginaf"></div>
        <div class="logtext">
            CONTACT US
        </div>
        <div class="loginbf"></div>
    </div>
    <div class="container">
        <div class="formcard cnt">
            <img src="images/conte-img.png" class="girl_image" alt="job in hire">
            <div class="row">
                <div class="col-md-6">
                    <h1>Let's talk on something great together</h1>
                </div>
                <div class="col-md-6">
                    <!-- <form action="">
                        <h3>I’m interested in: 👋</h3>
                        <ul>
                            <li><button class = "bttnn">Employer</button></li>
                            <li><button class = "bttnn">Applicant </button></li>
                            <li><button class = "bttnn">Interviewer</button></li>
                        </ul>
                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-12">
                                <input type="text" id="name" placeholder="Enter name">
                            </div>
                            <div class="col-md-12 col-xs-12 col-12">
                                <input type="email" placeholder="email@gmail.com">
                            </div>   

                            <div class="col-md-12 col-xs-12 col-12">
                                <label for="">Your message</label>
                                <textarea name="" id="" rows="3" cols=""></textarea>
                            </div>
                        </div>
                        <button class="FMainBtn">
                            Send message
                        </button>
                    </form> -->
                    <form id="indexForm">
                        <h3>I’m interested in: 👋</h3>
                        <ul>
                            <li>
                                <button type="button" class="bttnn" data-value="Employer">Employer</button>
                            </li>
                            <li>
                                <button type="button" class="bttnn" data-value="Applicant">Applicant</button>
                            </li>
                            <li>
                                <button type="button" class="bttnn" data-value="Interviewer">Interviewer</button>
                            </li>
                        </ul>

                        <input type="hidden" name="interest" id="interest" value="" required>

                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-12">
                                <input type="text" name="name" id="name" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-12 col-xs-12 col-12">
                                <input type="email" name="email" placeholder="email@gmail.com" required>
                            </div>
                            <div class="col-md-12 col-xs-12 col-12">
                                <label for="message">Your message</label>
                                <textarea name="message" id="message" rows="3" required></textarea>
                            </div>
                        </div>

                        <button class="FMainBtn msgbtn" name="submitContact" type="submit">Send message</button>
                    </form>



                    <style>
                        .bttnn.selected {
                            background-color: #072c50;
                            ;
                            /* Change to your preferred selected color */
                            color: #fff;
                        }
                    </style>


                </div>
            </div>
        </div>
    </div>

</section>

<link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('#indexForm').on('submit', function (e) {
            e.preventDefault(); // Prevent normal form submission

            // Validate form fields
            let isValid = true;
            let errorMessage = '';

            // Collect form data
            const formData = {
                interest: $('#interest').val(),
                name: $('#name').val(),
                email: $('input[name="email"]').val(),
                message: $('textarea[name="message"]').val()
            };

            // Check if any field is empty
            if (!formData.interest) {
                isValid = false;
                errorMessage = 'Please select your interest.';
            } else if (!formData.name) {
                isValid = false;
                errorMessage = 'Name cannot be empty.';
            } else if (!formData.email) {
                isValid = false;
                errorMessage = 'Email cannot be empty.';
            } else if (!formData.message) {
                isValid = false;
                errorMessage = 'Message cannot be empty.';
            }

            if (!isValid) {
                // Show Noty alert if validation fails
                new Noty({
                    type: 'error',
                    layout: 'bottomCenter',
                    text: errorMessage,
                    timeout: 3000,
                    cssClass: 'custom-toast-margin'
                }).show();
                return; // Prevent the form from being submitted
            }
            document.querySelector('.loader-spin').style.display = 'flex';
            // If validation passes, make the AJAX request
            $.ajax({
                type: 'POST',
                url: 'contact-form.php',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function (response) {
                    hideSpinner();
                    if (response.status === 'success') {
                        document.querySelector('.loader-spin').style.display = 'none';
                        new Noty({
                            type: 'success',
                            layout: 'bottomCenter',
                            text: response.message,
                            timeout: 3000,
                            cssClass: 'custom-toast-margin'
                        }).show();
                        $('#indexForm')[0].reset(); // Reset form fields
                    } else {
                        document.querySelector('.loader-spin').style.display = 'none';
                        new Noty({
                            type: 'error',
                            layout: 'bottomCenter',
                            text: response.message,
                            timeout: 3000,
                            cssClass: 'custom-toast-margin'
                        }).show();
                    }
                },
                error: function (xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    document.querySelector('.loader-spin').style.display = 'none';
                    new Noty({
                        type: 'error',
                        layout: 'bottomCenter',
                        text: 'An error occurred. Please try again later.',
                        timeout: 3000,
                        cssClass: 'custom-toast-margin'
                    }).show();
                }
            });
        });
    });
</script>


<script>
    const buttons = document.querySelectorAll('.bttnn');
    const interestInput = document.getElementById('interest');

    // Add click event listeners to each button
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove 'selected' class from all buttons
            buttons.forEach(btn => btn.classList.remove('selected'));

            // Add 'selected' class to the clicked button
            button.classList.add('selected');

            // Set the value of the hidden input to the selected interest
            interestInput.value = button.getAttribute('data-value');
        });
    });
</script>

<script>
    const form = document.getElementById('contactForm');
    const loader = document.getElementById('loader');

    form.addEventListener('submit', function () {
        loader.style.display = 'block'; // Show loader when form is submitted
    });
</script>

<!-- contact section end -->

<?php
include 'includes/mianIndexF.php';
?>