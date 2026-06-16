<div class="page_outer">
  <?php
  include "includes/header.php";
  include "../includes/db-connection/db.php";

  if (isset($_POST['submit_form'])) {
    // echo '<script>alert("hi from test")</script>';
    // exit();
  
    $company_name = mysqli_real_escape_string($con, $_POST['cmp_name']);
    $primary_mobile = mysqli_real_escape_string($con, $_POST['pri_mobile']);
    $gst = mysqli_real_escape_string($con, $_POST['gst']);
    $e_mail = mysqli_real_escape_string($con, $_POST['e_mail']);

    $address = mysqli_real_escape_string($con, $_POST['Address']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $pincode = mysqli_real_escape_string($con, $_POST['pin_code']);
    $empbank = mysqli_real_escape_string($con, $_POST['bank_name']);
    $account_name = mysqli_real_escape_string($con, $_POST['ac_name']);
    $account_number = mysqli_real_escape_string($con, $_POST['ac_number']);
    $ifsc_pin = mysqli_real_escape_string($con, $_POST['ifsc']);
    $branch_name = mysqli_real_escape_string($con, $_POST['branch_name']);
    $external = '1';


    $profile = $_FILES['emp_img']['name'];
    $targeting = "../api/post-apis/uploads/employer-images/";
    $imageFileType1 = pathinfo($profile, PATHINFO_EXTENSION);
    $imgrename = date('Ymd') . rand(1, 1000000) . '.' . 'png';
    $image1 = move_uploaded_file($_FILES['emp_img']['tmp_name'], $targeting . $imgrename);

    $getDbCount = mysqli_query($con, "SELECT * FROM `tbl_add_employer` ORDER BY employer_id DESC limit 1");
    $rows = mysqli_fetch_array($getDbCount);
    $incCount = $rows['employer_id'] + 1;

    $autoId = 'JDEMP' . strtoupper(substr(str_replace(' ', '', $company_name), 0, 4)) . $incCount;



    if (!empty($e_mail)) {
      $getEmployer = mysqli_query($con, "select * from tbl_add_employer where emp_email='" . $e_mail . "'");
    } else {
      // echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
      echo '<script>alert("Email Should Not Be Empty")</script>';
      echo "<script>
    setTimeout(()=>{
        window.location.href='forms.php'
    },'1500');
    </script>";
      exit();
    }

    if (mysqli_num_rows($getEmployer) > 0) {
      // echo json_encode(['status' => false, "message" => "Email Already Exists"]);
      echo '<script>alert("Email Already Exists")</script>';
      echo "<script>
    setTimeout(()=>{
        window.location.href='forms.php'
    },'1500');
    </script>";
      exit();
    }


    $insert = mysqli_query($con, 'INSERT INTO  tbl_add_employer SET 
  company_name="' . $company_name . '",
    employer_auto_create_id="' . $autoId . '",
  emp_mob="' . $primary_mobile . '",
  external = "' . $external . '",
  emp_email="' . $e_mail . '",
  emp_gst="' . $gst . '",
  emp_address="' . $address . '",
  emp_country="' . $country . '",
  emp_state="' . $state . '",
  emp_city="' . $city . '",
  emp_pincode="' . $pincode . '",
  emp_bank="' . $empbank . '",
  emp_bank_ac_name="' . $account_name . '",
  emp_bank_ac="' . $account_number . '",
  emp_ifsc="' . $ifsc_pin . '",
  emp_branch="' . $branch_name . '",
  emp_img="' . $imgrename . '",
  status=0');

    if ($insert == true) {
      echo '<script>alert("Success")</script>';
      // print_r(['success']);
    } else {
      echo '<script>alert("Fail")</script>';
      echo mysqli_error($con);
    }
  }
  ?>
  <?php

  ?>



  <section class="emp_form_section">
    <div class="sec-title center">
      <h1 class="signup_heading">Employer Signup</h1>
    </div>
    <!-- <div class="fields_outer"> -->
    <div class="row m-0">

      <div class="col-md-8">
        <section class="contact-section">
          <div class="contact-form-area">
            <div class="sec-title ">
              <h2>Company Details</h2>
            </div>
            <div class="form-inner">
              <form method="post" enctype="multipart/form-data">
                <div class="row m-0">
                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <!-- <div class="form-group">
                                      <i class="fas fa-user"></i>
                                      <label class="pos-ot">Full Name</label>
                                      <input type="text" name="username" placeholder=" Full Name" class="form-control required" required="">
                                  </div> -->

                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fas fa-user"></i>

                      <input placeholder="" type="hidden" name="applicant_id" />

                      <input type="text" name="cmp_name" placeholder=" Company Name" class="form-control required"
                        id="employerId" onchange="generateEmployerId()" />
                      <!-- <label>Company Name</label> -->
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fas fa-phone"></i>
                      <input type="number" name="pri_mobile" placeholder="primary Mobile" class="form-control required"
                        onkeypress="return this.value.length < 10;"
                        oninput="if(this.value.length>=10) { this.value = this.value.slice(0,10); }" />
                      <!-- <label> Mobile</label> -->
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fa fa-credit-card" aria-hidden="true"></i>
                      <input type="text" name="gst" placeholder=" GST No" class="form-control required"
                        onkeypress="return this.value.length < 15;"
                        oninput="if(this.value.length>=15) { this.value = this.value.slice(0,15); }" />
                      <!-- <label>Secondary Mobile</label> -->
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fas fa-envelope"></i>
                      <input type="text" name="e_mail" placeholder="Email" class="form-control required"
                        style="text-transform: none !important" />
                      <!-- <label> Email</label> -->
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <div class="form-group form-input-inner  downn position-relative has-float-label">
                      <span class="add_otthg">Add Image</span>
                      <i class="fas fa-user "></i>
                      <!-- <span class="profilee">
                     Profile image
                     </span > -->

                      <input type="file" placeholder=" Profile image" class="form-control file required"
                        name="emp_img" />
                      <!-- <label>Profile image</label> -->
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 column"></div>
                  <!-- <div class="col-lg-12 col-md-12 col-sm-12 column">
                                  <div class="form-group message-btn centred">
                                      <button type="submit" class="theme-btn-two" name="submit-form">Next</button>
                                  </div>
                              </div> -->
                </div>
            </div>
          </div>
        </section>
      </div>
      <div class="col-md-8" *ngIf="activeTab == 'SkillDetails'">
        <section class="contact-section">
          <!-- <div class="row">
          <div class="col-lg-10 col-md-12 col-sm-12 offset-lg-1 big-column"> -->

          <div class="contact-form-area">
            <div class="sec-title ">
              <h2>Address Details</h2>
            </div>
            <div class="form-top">
              <div class="form-inner ">

                <div class="row m-0">
                  <div class="col-lg-12 col-md-12 col-sm-12 column">
                    <!-- <div class="form-group">
                                      <i class="fas fa-user"></i>
                                      <label class="pos-ot">Full Name</label>
                                      <input type="text" name="username" placeholder=" Full Name" class="form-control required" required="">
                                  </div> -->

                    <div class="form-group form-input-inner-group position-relative has-float-label">
                      <i class="fas fa-user"></i>



                      <textarea name="Address" cols="10" rows="2" placeholder="Address">

                      </textarea>
                      <!-- <label>Company Name</label> -->
                    </div>
                  </div>
                </div>

                <div class="row m-0">
                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <div class="form-group form-input-inner   position-relative has-float-label">
                      <i class="fa fa-globe" aria-hidden="true"></i>

                      <!-- <label>Secondary Mobile</label> -->

                      <select class="" style="  appearance: none;" name="country">country

                        <option value="">Select Your country </option>
                        <option value="India">India </option>
                        <option value="US">US</option>
                        <option value="UK">UK</option>
                      </select>
                      <!-- <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  style="fill: rgba(0, 0, 0, 1)"
                >
                  <path
                    d="M11.178 19.569a.998.998 0 0 0 1.644 0l9-13A.999.999 0 0 0 21 5H3a1.002 1.002 0 0 0-.822 1.569l9 13z"
                  ></path>
                </svg> -->
                    </div>



                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fa fa-building" aria-hidden="true"></i>

                      <select id="" name="state" style="  appearance: none;">state
                        <option value="">Select Your state </option>
                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                        <option value="Assam">Assam</option>
                        <option value="Bihar">Bihar</option>
                        <option value="Chhattisgarh">Chhattisgarh</option>
                        <option value="Goa">Goa </option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="Haryana">Haryana</option>
                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                        <option value="Jharkhand">Jharkhand</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Kerala">Kerala</option>
                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Manipur">Manipur</option>
                        <option value="Meghalaya">Meghalaya</option>
                        <option value="Mizoram">Mizoram </option>
                        <option value="Nagaland">Nagaland</option>
                        <option value="Odisha">Odisha</option>
                        <option value="Punjab">Punjab</option>
                        <option value="Rajasthan">Rajasthan </option>
                        <option value="Sikkim">Sikkim</option>
                        <option value="Telangana">Telangana</option>
                        <option value="Tripura">Tripura</option>
                        <option value="Uttar Pradesh"> Uttar Pradesh</option>
                        <option value="Uttarakhand"> Uttarakhand</option>
                        <option value="West Bengal">West Bengal</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 column">
                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fa fa-building" aria-hidden="true"></i>

                      <input type="text" name="city" placeholder="Enter City" class="form-control required" />


                      <!-- <select id="" name="city" style="  appearance: none;">City
                      <option value="">Select Your City </option>
                      </select> -->
                    </div>


                    <div class="form-group form-input-inner position-relative has-float-label">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <input type="number" name="pin_code" placeholder="Pincode" class="form-control required" />
                      <!-- <label> Mobile</label> -->

                    </div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 column"></div>

                </div>

              </div>
            </div>
          </div>
          <!-- </div> -->
          <!-- </div> -->
        </section>
      </div>

      <div class="col-md-8">
        <section class="contact-section mb-4">
          <div class="contact-form-area">
            <div class="sec-title ">
              <h2>Bank Details</h2>
            </div>
            <div class="form-inner">

              <div class="row m-0">
                <div class="col-lg-6 col-md-12 col-sm-12 column">
                  <!-- <div class="form-group">
                                      <i class="fas fa-user"></i>
                                      <label class="pos-ot">Full Name</label>
                                      <input type="text" name="username" placeholder=" Full Name" class="form-control required" required="">
                                  </div> -->

                  <div class="form-group form-input-inner position-relative has-float-label">
                    <i class="fa fa-building" aria-hidden="true"></i>

                    <input placeholder="" type="hidden" formControlName="applicant_id" />

                    <input type="text" name="bank_name" placeholder=" Bank Name" class="form-control required" />
                    <!-- <label>Company Name</label> -->
                  </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 column">
                  <div class="form-group form-input-inner position-relative has-float-label">
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <input type="text" name="ac_name" placeholder="Account Name" class="form-control required" />
                    <!-- <label> Mobile</label> -->
                  </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 column">
                  <div class="form-group form-input-inner position-relative has-float-label">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <input type="number" name="ac_number" placeholder="Account Number" class="form-control required"
                      onkeypress="return this.value.length < 16;"
                      oninput="if(this.value.length>=16) { this.value = this.value.slice(0,16); }" />
                    <!-- <label>Secondary Mobile</label> -->
                  </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 column">
                  <div class="form-group form-input-inner position-relative has-float-label">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <input type="text" name="ifsc" placeholder="IFSC" class="form-control required"
                      onkeypress="return this.value.length < 11;"
                      oninput="if(this.value.length>=11) { this.value = this.value.slice(0,11); }" />
                    <!-- <label> Email</label> -->
                  </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 column">
                  <div class="form-group form-input-inner  downn position-relative has-float-label">
                    <i class="fa fa-building" aria-hidden="true"></i>
                    <!-- <span class="profilee">
                     Profile image
                     </span > -->

                    <input type="text" placeholder=" Branch Name" class="form-control required" name="branch_name" />
                    <!-- <label>Profile image</label> -->

                  </div>

                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 column submitt">
                  <button class=" btn btn-primary " name="submit_form" type="submit">submit</button>
                </div>

              </div>

            </div>
          </div>
        </section>
      </div>

      <!-- </div> -->


    </div>






    </form>
  </section>

</div>


<?php
include "includes/footer.php";
?>