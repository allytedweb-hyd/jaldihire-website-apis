<?php
$title="JaldiHire Employer Plans | Flexible Pricing for Hiring Needs.";
$meta_description = "Select from flexible, feature-rich pricing plans crafted to support scalable recruitment needs for employers.";
include "includes/MainHeader.php";
include './employer-accountmail.php';

if (isset($_POST['submit'])) {

    employerSignUp();
}
?>

<div class="price-bread servmt3">
    <h1>Pick the price <span>thats right for you</span></h1>
    <p>Would you like to customize the package? No problem, reach out to our sales team!</p>

    <!-- <button class="" onclick="openpopup()">CTA Button </button> -->
    <a href="contactsales.php">
        <button class="main-btn">Contact Sales</button>
    </a>
</div>
<section class="price-out">
    <div class="container">
        <h3>Compare Plans</h3>
        <div class="price-table ">
            <div class="price-col-1">
                <div class="top-hed">
                    <a href="">
                        <button>
                            Features
                        </button>
                    </a>
                    <p>Choose your workspace plan according to your organisational plan</p>
                </div>
                <div class="price-td">
                    Application Cost
                </div>
                <div class="price-td">
                    Hiring Coordinator User ID's
                </div>
                <div class="price-td">
                    Interview Panel User
                </div>
                <div class="price-td">
                    Background Verification
                </div>
                <div class="price-td">
                    Identity Verification
                </div>
                <div class="price-td">
                    Resume Standardization
                </div>
                <div class="price-td">
                    Integrated Interview Platform
                </div>
                <div class="price-td">
                    Applicant Dashboard-Basic graphs
                </div>
                <div class="price-td">
                    Integrations with Outlook / Gmail
                </div>
                <div class="price-td">
                    Employer Performance Analytics
                </div>
                <div class="price-td">
                    Job Advisory Consulting
                </div>
                <div class="price-td">
                    Recruiter Support Service
                </div>
                <div class="price-td">
                  Competitive  Market Analysis
                </div>
                <div class="price-td">
                    Decision Making Analytics
                </div>
                <div class="price-td">
                    Technical Screening Video
                </div>
                <div class="price-td">
                    Coding Platform
                </div>
                <div class="price-td">
                    Technical Ratings
                </div>
                <div class="price-td">
                   Fraud/ Proxy Verification
                </div>
                <div class="price-td border-none">
                    Employer Branding
                </div>
            </div>
            <div class="price-col-2 tab-li ">
                <div class="b-top">
                    <div class="basics">
                        <img src="images/flag.png" alt="my job application">
                        <h5>Basic</h5>
                    </div>

                    <button onclick="openpopup()">Buy Now </button>
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    01
                </div>
                <div class="price-td">
                    01
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td border-none">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>

            </div>
            <div class="price-col-2 tab-li">
                <div class="b-top">
                    <div class="basics">
                        <img src="images/standard.png" alt="talent and recruitment">
                        <h5>Standard</h5>
                    </div>

                    <button onclick="openpopup()">Buy Now </button>
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    01
                </div>
                <div class="price-td">
                    01
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>
                <div class="price-td border-none">
                    <img src="./images/redtick.png" alt="high jobs">
                </div>

            </div>
            <div class="price-col-2 tab-li active">
                <div class="b-top">
                    <div class="basics">
                        <img src="images/basic.png" alt="jobs urgent hiring">
                        <h5>Premium</h5>
                    </div>
                    <h6 class="recomnd">(Recommended)</h6>

                    <button onclick="openpopup()" class="premimum-btn">Buy Now </button>
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    01
                </div>
                <div class="price-td">
                    01
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>
                <div class="price-td border-none">
                    <img src="./images/grentick.png" alt="hiring application">
                </div>

            </div>
        </div>
        <div class="btom-btn mt-4">

            <button class="main-btn2" onclick="openpopup()">Select Plan</button>

        </div>


        <!-- createAccountPopup -->
        <div class="createFormPop">
            <div class="cpopup">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center m-d-none">
                        <img src="./images/cre-3.png" alt="application process" class="cre3">
                        <img src="./images/cre-2.png" alt="post jobs apply" class="cre2 ">
                        <img src="./images/cre-1.png" alt="job tracking" class="cre1 object">


                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 cr-form">
                        <div onclick="closepopup()" class="d-flex justify-content-end p-4 curser-pointer">
                            <svg width="22" height="22" viewBox="0 0 40 41" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="19.9459" cy="20.7038" r="19.8834" fill="black" />
                                <path
                                    d="M23.1695 20.7351L29.3407 14.5639C30.2245 13.6801 30.2245 12.2517 29.3407 11.3678C28.4568 10.484 27.0285 10.484 26.1446 11.3678L19.9773 17.5391L13.6246 11.1863C12.7408 10.3025 11.3124 10.3025 10.4285 11.1863C9.54466 12.0702 9.54466 13.4986 10.4285 14.3824L16.7812 20.7351L10.6455 26.8708C9.76168 27.7547 9.76168 29.1831 10.6455 30.0669C11.0875 30.5049 11.6635 30.7259 12.2436 30.7259C12.8236 30.7259 13.3997 30.5049 13.8416 30.063L19.9773 23.9273L25.9315 29.8815C26.3735 30.3234 26.9495 30.5444 27.5296 30.5444C28.1096 30.5444 28.6857 30.3234 29.1276 29.8815C30.0115 28.9976 30.0115 27.5692 29.1276 26.6854L23.1695 20.7351Z"
                                    fill="white" />
                            </svg>

                        </div>
                        <h3>
                            Get Started with Your Plan
                        </h3>



                        <form id="signUp" class="create-accout">
                            <!-- <div class="row fsrit-input mb-3"> -->

                            <div class="mb-3">
                                <input type="text" name="companyname" id="companyname" placeholder="Company Name">
                                <p id="errorText" class="text-danger"></p>
                            </div>

                            <!-- </div> -->


                            <!-- <label for="emp_mob">Mobile Number</label> -->
                            <div class="mb-3">
                                <input type="tel" name="emp_mob" id="emp_mob" placeholder="Mobile Number"
                                    maxlength="10">
                                <p id="errorText" class="text-danger"></p>
                                <p id="errorText" class="text-danger"></p>
                            </div>



                            <!-- <label for="emp_email">Email Address</label> -->
                            <div class="mb-3">
                                <input type="text" name="emp_email" id="emp_email" placeholder="Email Address">
                                <p id="errorText" class="text-danger"></p>
                            </div>




                            <div class="mb-3">
                                <input type="text" name="emp_gst" id="emp_gst" placeholder="GST Number">
                                <p id="errorText" class="text-danger"></p>
                            </div>






                            <!-- <label for="">Select Your Pack</label> -->
                            <div>
                                <select name="package" class="p-2 mb-3" id="package">
                                    <option value="">Select Your Pack</option>
                                    <option value="1">Basic</option>
                                    <option value="2">Standard</option>
                                    <option value="3">Premium</option>
                                </select>

                                <p id="errorText" class="text-danger"></p>
                            </div>
                            <div>

                                <select name="packmonths" id="packmonths" class="p-2 mb-3">
                                    <option value="">Select Your Pack validity</option>
                                    <option value="1">1 month</option>
                                    <option value="6">6 month</option>
                                    <option value="12">12 month</option>
                                </select>
                                <p id="errorText" class="text-danger"></p>
                            </div>
                            <div class="subtn">

                                <!-- <button name="submit" value="submit" type="button" onclick="submitId()">Submit <span><i
                                            class="fa fa-arrow-right"></i></span></button> -->
                                <button class="main-btn2" id="addForm" type="button">Submit <span><i
                                            class="fa fa-arrow-right"></i></span></button>
                                <button name="submit" id="submitForm" type="submit" class="main-btn2">Sign up</button>
                            </div>

                        </form>



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




<?php
include 'includes/mianIndexF.php';
?>







<link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

<!-- Include Axios from CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
            }).show();
            return; // Prevent the form from being submitted
        }

        // Show loader before AJAX request
        document.querySelector('.loader-spin').style.display = 'flex';

        // If validation passes, make the AJAX request
        $.ajax({
            type: 'POST',
            url: 'employer_accountmail.php',
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
</script>