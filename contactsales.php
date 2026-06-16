<?php

$title="Get in Touch with Jaldihire: We're Here to Help!.";
$meta_description = "Connect with our expert team to schedule a personalized demo and explore strategic recruitment solutions tailored to your business objectives.";
$schema = <<<JSON
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact Jaldi Hire",
  "url": "https://www.jaldihire.com/contactsales.php",
  "about": {
    "@type": "Organization",
    "name": "Jaldi Hire"
  },
  "description": "Get in touch with Jaldi Hire for sales inquiries, partnerships, or support. We’re here to help streamline your recruitment journey."
}
JSON;
include 'includes/mianIndexH.php';
include "includes/db-connection/db.php";
include "libs/contact.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['submitrequest'])) {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Set your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; // Your SMTP username
        $mail->Password   = 'your_password'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587; // TCP port to connect to

        // Email content
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($data['email'], $data['yourname']); // Add a recipient

        $mail->isHTML(true);
        $mail->Subject = 'Demo Request from ' . $data['fname'];
        $mail->Body    = '<h1>Demo Request Details</h1>
                          <p>Company Name: ' . $data['fname'] . '</p>
                          <p>Your Name: ' . $data['yourname'] . '</p>
                          <p>Email: ' . $data['email'] . '</p>
                          <p>Role: ' . $data['role'] . '</p>
                          <p>Phone Number: ' . $data['number'] . '</p>
                          <p>Date: ' . $data['date'] . '</p>
                          <p>Time: ' . $data['time'] . '</p>';

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Demo request sent successfully!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
    exit();
}
?>

<section class="contact-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="contact-bgm">
                    <h1>Experience the Future: <br> <span>Book Your Demo Today!</span></h1>
                    <div class="contact-sale">
                        <img src="images/contact-sale.png" alt="contactsaleimage">
                    </div>
                    <h5>Scale Your Business to New Heights with Jaldi Hire</h5>
                </div>
            </div>
            <div class="col-md-5">
                <h2 class="request">Request a demo</h2>
                <form id="contactSales" method="POST">
                    <div class="row">
                        <div class="col-md-12 mbb1">
                            <label for="fname">Company Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" required>
                        </div>
                        <div class="col-md-12 mbb1">
                            <label for="yourname">Your Name</label>
                            <input type="text" class="form-control" id="yourname" name="yourname" required>
                        </div>
                        <div class="col-md-12 mbb1">
                            <label for="email">Work Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-12 mbb1">
                            <label for="role">Role</label>
                            <input type="text" class="form-control" id="role" name="role" required>
                        </div>
                        <div class="col-md-12 mb22">
                            <label for="number">Phone Number</label>
                            <input type="text" class="form-control" id="number" name="number" pattern="\d{10}" 
                              maxlength="10" minlength="10" required oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                            <!-- <input type="number" class="form-control" id="number" name="number" pattern="\d{10}"
                                maxlength="10" minlength="10" required> -->
                        </div>
                        <div class="col-md-12">
                            <label for="date">Request Demo? Select time and date</label>
                            <div class="row dateflex">
                                <div class="col-md-6">
                                    <!-- <input name="date" type="date" class="form-control"  id="dateInput" placeholder="Select Date" required> -->
                                    <input type="date" name="date" class="form-control" id="date" required>
                                    <i class="fa fa-calendar-o cal" aria-hidden="true"></i>
                                </div>
                                <div class="col-md-6 mcm">
                                    <!-- <input name="time" type="time" class="form-control"  id="timeInput" placeholder="Select Time" required> -->
                                    <input type="time" name="time" class="form-control" id="time" required>
                                    <i class="fa fa-clock-o clk" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 cmt">
                            <button class="FMainBtn2" type="submit" name="submitrequest">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#contactSales').on('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission

        // Validate form fields
        let isValid = true;
        let errorMessage = '';

        // Collect form data
        const formData = {
            fname: $('#fname').val(),
            yourname: $('#yourname').val(),
            email: $('#email').val(),
            role: $('#role').val(),
            number: $('#number').val(),
            date: $('#date').val(),
            time: $('#time').val(), // Corrected here
        };

        // Check if any field is empty
        for (const key in formData) {
            if (!formData[key]) {
                isValid = false;
                errorMessage = `${key.charAt(0).toUpperCase() + key.slice(1)} cannot be empty.`;
                break;
            }
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
        document.querySelector('.loader-spin').style.display = 'flex';
        // If validation passes, make the AJAX request
        $.ajax({
            type: 'POST',
            url: 'contactSalesMail.php',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(response) {

                document.querySelector('.loader-spin').style.display = 'none';
                new Noty({
                    type: response.status === 'success' ? 'success' : 'error',
                    layout: 'bottomRight',
                    text: response.message,
                    timeout: 3000,
                }).show();
                if (response.status === 'success') {
                    $('#contactSales')[0].reset(); // Reset form fields
                }
            },
            error: function(xhr, status, error) {
                document.querySelector('.loader-spin').style.display = 'none';
                console.log('AJAX Error:', xhr.responseText);
                new Noty({
                    type: 'error',
                    layout: 'bottomRight',
                    text: 'An error occurred. Please try again later.',
                    timeout: 3000,
                }).show();
            }
        });
    });
});
</script>

<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

    if (isSafari) {
      document.getElementById("dateInput").setAttribute("type", "text");
      document.getElementById("timeInput").setAttribute("type", "text");
      // You can initialize a custom date and time picker here
    }
  });
</script> -->

<!-- <input type="date" id="dateInput" placeholder="Select Date">
<input type="time" id="timeInput" placeholder="Select Time"> -->


<?php
include 'includes/mianIndexF.php';
?>