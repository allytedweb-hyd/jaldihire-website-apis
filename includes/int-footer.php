<?php
include './sent-mail.php';

if (isset($_POST['submit'])) {
    SendMail();
}
?>

<footer class="main-footer style-five style-six style-seven">
    <div class="container">
        <div class="footer-top">
            <div class="widget-section">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="logo-ig">
                            <img src="images/jaldihire-logo.png" class="j-logo" alt="">
                            <img src="images/jal-logo.png" class="jal-cls" alt="">
                        </div>
                        <div class="foot-head mt-3">
                            <h4>Unlock Opportunities
                                with Jaldi Hire</h4>
                        </div>
                        <form action="#" method="post">
                            <div class="fot-flex">

                                <input type="text" class="search-clsp" name="email" placeholder="Enter your email">
                                <i class="fa fa-user us-pos"></i>

                                <button type="submit" name="submit" class="try-btn">Try Now</button>
                            </div>
                        </form>
                        <div>
                            <ul class="footer-ul">
                                <li><a href="#featureSec">Features</a></li>
                                <!-- <li><a href="">Plans & Pricing</a></li> -->

                                <!-- <li><a href="">Careers</a></li> -->
                                <li><a href="emp-about-us.php">About Us</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">© <a href="#">Jaldihire 2024</a>. All rights reserved</div>
</footer>


<!-- jequery plugins -->
<script src="js/jquery.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.js"></script>
<script src="js/wow.js"></script>
<script src="js/validation.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/appear.js"></script>
<script src="js/scrollbar.js"></script>
<script src="js/tilt.jquery.js"></script>
<script src="js/pricing-tab.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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


/* ---- stats.js config ---- */

var count_particles, stats, update;
stats = new Stats;
stats.setMode(0);
stats.domElement.style.position = 'absolute';
stats.domElement.style.left = '0px';
stats.domElement.style.top = '0px';
document.body.appendChild(stats.domElement);
count_particles = document.querySelector('.js-count-particles');
update = function() {
    stats.begin();
    stats.end();
    if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
        count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
    }
    requestAnimationFrame(update);
};
requestAnimationFrame(update);
</script>

<script>
var options = {
    series: [{
        //   data: [100, 50, 100, 150, 200, 250, 300]
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

<!-- <script>
$(".jaldi-carousel").owlCarousel({
    loop: true,
    autoplay: true,
    items: 1,
    nav: false,
    autoplayspeed: 2000,
    // autoplayTimeout:2000,
    autoplayHoverPause: false,
    mouseDrag: false,
    animateOut: 'slideOutUp',
    animateIn: 'slideInUp'
});
  </script> -->

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



<!--Start of Tawk.to Script-->
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API = Tawk_API || {},
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
<!--End of Tawk.to Script-->
<!--End of Tawk.to Script-->

<script>
document.querySelector('.loader-spin').style.display = 'block';
setTimeout(function() {
    document.querySelector('.loader-spin').style.display = 'none';
}, 1000); // Adjust the time (in milliseconds) as needed
</script>



</body>


<!-- End of .page_wrapper -->

</html>