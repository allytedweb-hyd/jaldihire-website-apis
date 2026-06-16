<?php

include "footer-mail.php";


if (isset($_POST['submitFooter'])) {

    SendFooterMail();
}
?>

<footer class="main-footer style-five style-six style-seven ftg">
    <div class="container">
        <div class="footer-top">
            <div class="widget-section">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="logo-ig">
                            <img src="images/jaldihire-logo.png" class="j-logo" alt="background verification tool">
                            <img src="images/jal-logo.png" class="jal-cls" alt="interview scheduling platform">
                        </div>
                        <div class="foot-head mt-3">
                            <h4>Unlock Opportunities
                                with Jaldi Hire</h4>
                        </div>
                        <form id="footerForm" method="post">
                            <div class="longformsss">
                                <div class="longform">
                                    <select class="search-clsp" style="color:#fff;" name="type" id="type" required>
                                        <option value="What am I">What am I</option> <!-- Placeholder -->
                                        <option value="Employer">Employer</option>
                                        <option value="Applicant">Job Seeker</option>
                                        <option value="Interviewer">Interviewer</option>
                                    </select>


                                    <input type="email" class="search-clsp2 lineone" name="email" id="email"
                                        placeholder="Email" required>
                                    <i class="fa fa-user us-pos"></i>

                                    <!-- <input type="text" class="search-clsp2" name="number" id="number"  pattern="\d{10}" maxlength="10" minlength="10"  placeholder="+91" required 
                                     pattern="^\+?\d{10,15}$"> -->
                                    <span class="nine">+91</span>
                                    <input type="number" class="search-clsp22" name="number" id="number"
                                        pattern="[6-9]{1}[0-9]{9}" maxlength="10" required
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">

                                    <button type="submit" name="submitFooter" class="try-btn">Connect Now</button>
                                </div>
                            </div>
                        </form>







                        <div class="row pt-5 ftt">
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <li><a href="services.php" class="services_line">Services</a></li>
                                        <ul class="advisory_services">
                                            <li><a href="services.php#advisor">Job Advisory</a></li>
                                            <li><a href="services.php#sourcing">Profile Sourcing</a></li>
                                            <li><a href="services.php#branding">Employer Branding</a></li>
                                            <li><a href="services.php#verfication">BGV Verification</a></li>
                                            <li><a href="services.php#interview">Interview Out Sourcing</a></li>
                                            <li><a href="services.php#integrated">Integrated ATS</a></li>
                                            <li><a href="services.php#candidate">Candidate Engagement</a></li>
                                            <!-- <li>Onboarding</li> -->
                                        </ul>
                                    </div>

                                    <div class="col-md-4 accessto">
                                        <li class="services_line">Access To</li>
                                        <ul class="advisory_services">
                                            <li><a href="employer.php">Employer</a></li>
                                            <li><a href="https://jaldihire.com/applicant/">Job Seeker</a></li>
                                            <li><a href="interviewer.php">Interviewer</a></li>
                                        </ul>
                                    </div>


                                    <div class="col-md-4 ">
                                        <li><a href="emp-priceing.php" class="services_line">Pricing </a></li>
                                        <ul class="advisory_services">
                                            <li><a href="emp-priceing.php">Basic</a></li>
                                            <li><a href="emp-priceing.php">Standard</a></li>
                                            <li><a href="emp-priceing.php">Premium</a></li>
                                        </ul>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-md-3 phonel">
                                        <li class="services_line">Contact Us</li>
                                        <ul class="advisory_services">
                                            <li><a href="tel:+91 7396131155">+91 7396131155</a></li>
                                            <li><a href="tel:+91 8179592757">+91 8179592757</a></li>
                                            <li><a class="services_line">Email</a></li>
                                            <li><a href="mailto:info@jaldihire.com">info@jaldihire.com</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="advisory_services">
                                            <li><a href="index.php" class="services_line2">Home</a></li>
                                            <li><a href="services.php" class="home_features">Services</a></li>
                                            <li><a href="emp-about-us.php" class="home_features">About Us</a></li>
                                            <li><a href="contactsales.php" class="home_features">Contact Sales</a></li>
                                            <li><a href="terms-and-conditions.php"
                                                    class="home_features">Terms&Conditions</a></li>
                                            <li><a href="privacy-policy.php" class="home_features">Privacy Policy</a>
                                            </li>
                                            <li><a href="refund-policy.php" class="home_features">Refund Policy</a>
                                            </li>
                                        </ul>

                                    </div>


                                    <div class="col-md-6">
                                        <ul class="advisory_services" class="services_line">
                                            <div class="lg-flex">
                                                <div class="location">
                                                    <img src="images/location.png" alt="hiring in job">
                                                </div>
                                                <div>
                                                    <li class="hyder"> Office-Location Hyderabad, India</li>
                                                </div>
                                            </div>
                                            <div class="">
                                                <hr>
                                                <p class="village">Plot No 28 28/A, Survey No 40, Financial District
                                                    Road, Raidurgam Khajaguda Village, Serilingampally Mandal Rangareddy
                                                    Dist,Hyderabad,Telangana, India</p>
                                                <hr>
                                            </div>

                                            <div>
                                                <h6 class="follow">Follow us</h6>
                                            </div>


                                            <div>
                                                <ul class="icons_community">
                                                    <li>

                                                        <a href="https://www.instagram.com/jaldihire?igsh=MWVqNGQyNWI0bXd5Ng=="
                                                            target="_blank">
                                                            <!-- <i class="fa fa-instagram"></i> -->
                                                            <i class="ri-instagram-line"></i>
                                                        </a>
                                                    </li>
                                                    <!-- <li>
                                                        <a href="">
                                                           
                                                            <i class="ri-twitter-x-line"></i>

                                                        </a>
                                                    </li>  -->


                                                    <li>
                                                        <a href="https://www.linkedin.com/company/jaldi-hire/ "
                                                            target="_blank">
                                                            <!-- <img src="images/linkedin.png" alt=""> -->
                                                            <i class="ri-linkedin-fill"></i>
                                                        </a>
                                                    </li>
                                                    </a>
                                                </ul>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <img src="images/shadow.png" alt="apply jobs" class="shadow_imggg">
    </div>
    <div class="copyright">© <a href="#">Jaldihire 2024</a>. All rights reserved</div>
</footer>


<!-- jequery plugins -->
<script src="js/jquery.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl3.js"></script>
<script src="js/wow.js"></script>
<script src="js/validation.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/appear.js"></script>
<script src="js/scrollbar.js"></script>
<script src="js/tilt.jquery.js"></script>
<script src="js/pricing-tab.js"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    AOS.init();
</script>

<!-- main-js -->
<script src="//www.amcharts.com/lib/3/amcharts.js"></script>
<script src="//www.amcharts.com/lib/3/serial.js"></script>
<script src="//www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<!-- <script src="https://threejs.org/examples/js/libs/stats.min.js"></script> -->
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 100,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#ffffff"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "grab"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 140,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });

    <
link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css"
        rel="stylesheet" >
        <
    script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js" >
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
            $(document).ready(function() {
                // Form submission handler for footer form
                $('#footerForm').on('submit', function (e) {
                    e.preventDefault(); // Prevent normal form submission

                    // Validate form fields
                    let isValid = true;
                    let errorMessage = '';

                    // Collect form data
                    const formData = {
                        type: $('#type').val(),
                        email: $('#email').val(),
                        number: $('#number').val()
                    };

                    // Check if the dropdown is empty
                    if (!formData.type) {
                        isValid = false;
                        errorMessage = 'Please select what you are.';
                    }
                    // Validate email
                    else if (!formData.email) {
                        isValid = false;
                        errorMessage = 'Email cannot be empty.';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address.';
                    }
                    // Validate phone number (pattern allows + and 10-15 digits)
                    else if (!formData.number) {
                        isValid = false;
                        errorMessage = 'Phone number cannot be empty.';
                    } else if (!/^\+?\d{10,15}$/.test(formData.number)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid phone number.';
                    }

                    if (!isValid) {
                        // Show Noty alert if validation fails
                        new Noty({
                            type: 'error',
                            layout: 'bottomCenter',
                            text: errorMessage,
                            timeout: 3000,
                        }).show();
                        return; // Prevent the form from being submitted
                    }

                    // Show spinner when the form is submitted

                    document.querySelector('.loader-spin').style.display = 'flex';
                    // Send the AJAX request to footer_mail.php
                    $.ajax({
                        type: 'POST',
                        url: 'footer-mail.php',
                        contentType: 'application/json', // Send as JSON
                        data: JSON.stringify(formData), // Convert the data to JSON string
                        dataType: 'json', // Expect JSON response
                        success: function (response) {
                            if (response.status === 'success') {
                                // Now send the second email via user-mail.php
                                $.ajax({
                                    type: 'POST',
                                    url: 'user-mail.php',
                                    contentType: 'application/json',
                                    data: JSON.stringify(
                                        formData), // Reuse formData for the second email
                                    dataType: 'json',
                                    success: function (secondResponse) {
                                        document.querySelector('.loader-spin').style
                                            .display = 'none';
                                        new Noty({
                                            type: 'success',
                                            layout: 'bottomCenter',
                                            text: secondResponse.message ||
                                                'Both emails sent successfully.',
                                            timeout: 3000,
                                            cssClass: 'custom-toast-color'
                                        }).show();

                                        // Reset the form fields
                                        $('#footerForm')[0]
                                            .reset(); // Reset all fields in the form
                                    },
                                    error: function (xhr, status, error) {
                                        document.querySelector('.loader-spin').style
                                            .display = 'none';

                                        console.log('AJAX Error (user-mail):', xhr
                                            .responseText
                                        ); // Log the response for debugging
                                        new Noty({
                                            type: 'error',
                                            layout: 'bottomCenter',
                                            text: 'Second email could not be sent. Please try again later.',
                                            timeout: 3000,
                                        }).show();
                                    }
                                });
                            } else {
                                document.querySelector('.loader-spin').style.display = 'none';
                                new Noty({
                                    type: 'error',
                                    layout: 'bottomCenter',
                                    text: response.message,
                                    timeout: 3000,
                                }).show();
                            }
                        },
                        error: function (xhr, status, error) {
                            document.querySelector('.loader-spin').style.display = 'none';
                            console.log('AJAX Error:', xhr
                                .responseText); // Log the response for debugging
                            new Noty({
                                type: 'error',
                                layout: 'bottomCenter',
                                text: 'An error occurred. Please try again later.',
                                timeout: 3000,
                            }).show();
                        }
                    });
                });
});
</script>








<script>
            var options = {
                series: [{
                data: [600, 500, 400, 300, 200, 100]
    }],
            chart: {
                type: 'bar',
            height: 230
    },
            plotOptions: {
                bar: {
                borderRadius: 0,
            horizontal: true,
        }
    },
            dataLabels: {
                enabled: false
    },
            xaxis: {
                categories: ['Not Interested', 'CE Failed', 'No Response', 'No Req Exp', 'Not Relevant', 'Only FTE'],
    }
};

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
</script>



<script>
            $(document).ready(function() {
                $('.customer-logos').slick({
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 1500,
                    arrows: false,
                    dots: false,
                    pauseOnHover: false,
                    responsive: [{
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 4
                        }
                    }, {
                        breakpoint: 520,
                        settings: {
                            slidesToShow: 3
                        }
                    }]
                });
});
</script>



<script type="text/javascript">
            var Tawk_API = Tawk_API || { },
            Tawk_LoadStart = new Date();
            (function() {
    var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/670b829a4304e3196ad0eec6/1ia2fcbsp';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
})();
</script>

<script>
            document.addEventListener("DOMContentLoaded", function() {
    // Show loader when the page is loading
    const loader = document.querySelector('.loader-spin');
            loader.classList.add('show');

            // Hide loader when the page is fully loaded
            window.onload = function() {
                loader.classList.remove('show');
    };
});
</script>

</body>


</html>