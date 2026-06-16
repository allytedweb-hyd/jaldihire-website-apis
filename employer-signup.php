<?php
$title="Join Jaldi Hire: Your Gateway to Quick Hiring!";
$meta_description = "Register your business to access enterprise-grade hiring tools. Streamline recruitment with secure and efficient onboarding.";
include "includes/MainHeader.php";


?>


    <section class="login-form">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/GIF_Interviewer_1.gif" alt="signup gif1">
                                <div class="log-caru-content">
                                    <h2>
                                        Access Employers Easily
                                    </h2>

                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="images/GIF_Interviewer_2.gif" alt="signup gif2">
                                <div class="log-caru-content">
                                    <h2>Login, Review, Hire , techno</h2>

                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="images/Gif_Interviewer_3.gif" alt="signup gif3">
                                <div class="log-caru-content">
                                    <h2>Making Interviews Great</h2>

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
                                <h1>Welcome to <span class="cl-blue-f-w">Jaldi Hire</span> </h1>


                            </div>
                            <div class="col-md-4">

                            </div>
                        </div>



                        <div>


                            <form id="signUp">
                                <div>
                                    <!-- <label for="companyname">Company Name</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="text" name="companyname" id="companyname"
                                            placeholder="Company Name">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>

                                </div>

                                <div>
                                    <!-- <label for="emp_mob">Mobile Number</label> -->
                                    <div class="position-relative mb-3"> <input type="tel" name="emp_mob" id="emp_mob"
                                            placeholder="Mobile Number" maxlength="10">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>
                                </div>

                                <div>
                                    <!-- <label for="emp_email">Email Address</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="text" name="emp_email" id="emp_email" placeholder="Email Address">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>

                                </div>


                                <div>
                                    <!-- <label for="emp_gst">GST</label> -->
                                    <div class="position-relative mb-3">
                                        <input type="text" name="emp_gst" id="emp_gst" placeholder="GST Number">
                                        <p id="errorText" class="text-danger"></p>
                                    </div>
                                </div>




                                <div>
                                    <!-- <label for="">Select Your Pack</label> -->
                                    <div class="package_design mb-3">
                                        <select name="package" class="package_form" id="package">
                                            <option value="">Select Your Pack</option>
                                            <option value="1">Basic</option>
                                            <option value="2">Standard</option>
                                            <option value="3">Premium</option>
                                        </select>
                                        <p id="errorText" class="text-danger"></p>
                                    </div>
                                </div>
                                <div>
                                    <!-- <label for="">Select Your pack validity</label> -->
                                    <div class="package_design mb-3">
                                        <select name="packmonths" class="package_form" id="packmonths">
                                            <option value="">Select Your Pack validity</option>
                                            <option value="1">1 month</option>
                                            <option value="6">6 month</option>
                                            <option value="12">12 month</option>
                                        </select>
                                        <p id="errorText" class="text-danger"></p>
                                    </div>
                                </div>



                                <div class="mt-2">
                                    <button id="addForm" type="button" class="sig-btn">Sign up</button>
                                    <button name="submit" id="submitForm" type="submit" class="sig-btn">Sign up</button>
                                </div>

                            </form>
                        </div>



                        <div class="socal_m d-flex align-items-center justify-content-center">

                        </div>



                    </div>
                </div>
            </div>
        </div>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script src="js/employer-signup.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#signUp').on('submit', function(e) {
            e.preventDefault(); // Prevent normal form submission

            // Validate form fields
            let isValid = true;
            let errorMessage = '';

            // Collect form data
            const formData = {
                companyname: $('#companyname').val(),
                emp_mob: $('#emp_mob').val(),
                emp_email: $('#emp_email').val(),
                emp_gst: $('#emp_gst').val(),
                package: $('#package').val(),
                packmonths: $('#packmonths').val(),
            };

            // Check if any field is empty
            if (!formData.companyname) {
                isValid = false;
                errorMessage = 'Please select your interest.';
            } else if (!formData.emp_mob) {
                isValid = false;
                errorMessage = 'Mobile cannot be empty.';
            } else if (!formData.emp_email) {
                isValid = false;
                errorMessage = 'Email cannot be empty.';
            } else if (!formData.emp_gst) {
                isValid = false;
                errorMessage = 'GST cannot be empty.';
            } else if (!formData.package) {
                isValid = false;
                errorMessage = 'Package cannot be empty.';
            } else if (!formData.packmonths) {
                isValid = false;
                errorMessage = 'Duration cannot be empty.';
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

            // Show loader before AJAX request
            document.querySelector('.loader-spin').style.display = 'flex';

            // If validation passes, make the AJAX request
            $.ajax({
                type: 'POST',
                url: 'employer-accountmail.php',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response); // Debugging purpose

                    // Hide loader after response
                    document.querySelector('.loader-spin').style.display = 'none';

                    if (response.status === 'success') {
                        new Noty({
                            type: 'success',
                            layout: 'bottomCenter',
                            text: response.message,
                            timeout: 3000,
                            cssClass: 'custom-toast-margin'
                        }).show();
                        $('#signUp')[0].reset(); // Reset form fields
                    } else {
                        new Noty({
                            type: 'error',
                            layout: 'bottomCenter',
                            text: response.message,
                            timeout: 3000,
                            cssClass: 'custom-toast-margin'
                        }).show();
                    }
                },
                error: function(xhr, status, error) {
                    // Hide loader on error
                    document.querySelector('.loader-spin').style.display = 'none';

                    console.log('AJAX Error:', xhr.responseText);
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







    </body>

    <?php
include 'includes/mianIndexF.php';
?>