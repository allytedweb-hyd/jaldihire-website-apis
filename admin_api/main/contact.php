<?php
include "includes/header.php";
include "../includes/db-connection/db.php";

if(isset($_POST['submit-form']))
{
    // echo '<script>alert("hi from test")</script>';
    // exit();

  $name = mysqli_real_escape_string($con,$_POST['username']);
  $email = mysqli_real_escape_string($con,$_POST['email']);
  $mobile = mysqli_real_escape_string($con,$_POST['phone']);
  $subject = mysqli_real_escape_string($con,$_POST['subject']);
  $message = mysqli_real_escape_string($con,$_POST['message']);


  if (!empty($email)) {
  $insert = mysqli_query($con,'INSERT INTO  tbl_jaldihire_contact SET 
  name="'.$name.'",
    email="' . $email . '",
  mobile="'.$mobile.'",
  subject = "'.$subject.'",
  message="'.$message.'",
  status=1');
  }
  if($insert == true)
  {
        echo '<script>alert("Success")</script>';
        echo "<script>
           setTimeout(()=>{
                window.location.href='contact.php'
            },'1500');
            </script>";
  }
  else
  {
    echo '<script>alert("Fail")</script>';
    echo mysqli_error($con);
  }
}
?>
<?php
 
 ?>




<!-- page-title -->
<section class="page-title style-two">
    <div class="container">
        <div class="content-box clearfix">
            <div class="title-box pull-left">
                <h1>Contact Us</h1>
                <p>Reach out to the world’s most reliable Staffing.</p>
            </div>

        </div>
    </div>
</section>
<!-- page-title end -->


<!-- contact-section -->
<section class="contact-section">
    <div class="container">
        <div class="row" style=" justify-content: flex-start;">
            <div class="col-lg-10 col-md-12 col-sm-12 offset-lg-1 big-column">
                <div class="info-content centred">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                            <div class="single-info-box">
                                <figure class="icon-box"><img src="images/icons/info-icon-1.png" alt=""></figure>
                                <h2>Phone</h2>
                                <div class="phone"><a href="tel:0665184575181">12345678</a></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                            <div class="single-info-box">
                                <figure class="icon-box"><img src="images/icons/info-icon-2.png" alt=""></figure>
                                <h2>E-mail</h2>
                                <div class="phone"><a href="mailto:info@example.com">info@example.com</a></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                            <div class="single-info-box">
                                <figure class="icon-box"><img src="images/icons/info-icon-3.png" alt=""></figure>
                                <h2>Address</h2>
                                <div class="text">Do.No 2-152/2, Opposite Toyota Showroom Rayudupalem Junction,
                                    kakinada, Andhra Pradesh, 533005 </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="contact-form-area">
                    <div class="sec-title center">
                        <h2>Contact us</h2>
                    </div>
                    <div class="form-inner">
                        <form method="post" id="contact-form" class="default-form">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 column">
                                    <div class="form-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" name="username" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 column">
                                    <div class="form-group">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" name="email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 column">
                                    <div class="form-group">
                                        <i class="fas fa-file"></i>
                                        <input type="text" name="subject" placeholder="Subject" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 column">
                                    <div class="form-group">
                                        <i class="fas fa-phone"></i>
                                        <input type="number" name="phone" placeholder="Phone" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 column">
                                    <div class="form-group">
                                        <textarea name="message" placeholder="Write here message"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 column">
                                    <div class="form-group message-btn centred">
                                        <button type="submit" class="theme-btn-two" name="submit-form">Submit
                                            Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- contact-section end -->


<!-- map-section -->
<?php
include "includes/footer.php";
?>