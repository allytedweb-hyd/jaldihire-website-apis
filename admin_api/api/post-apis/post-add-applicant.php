<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
  'https://jaldihire.com',
  'https://admin.jaldihire.com',
  'https://employer.jaldihire.com',
  'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
  header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit();
}

require '../vendor/phpmailer/PHPMailerAutoload.php';

include "../../includes/db-connection/db.php";

$folderPath = "uploads/applicant-images/";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {


  $data = json_decode($json);


  @$applica_id = $data->id;

  @$fullname = $data->full_name;
  @$primarymobile = $data->pri_mobile;
  @$secondarymobile = $data->sec_mobile;
  @$email = $data->e_mail;
  @$city = $data->app_city;
  @$primaryskills = $data->p_skills;
  @$skill_rating = $data->skill_rating;
  @$createdById = $data->created_by;
  @$createdRole = $data->role;
  @$aadharNumber = $data->aadharNumber;
  @$aadharVerified = $data->aadharVerify;
  @$college = $data->college;
  @$HighestDegree = $data->highest_degree;
  @$percentage = $data->percentage;
  @$education_gap = $data->education_gap;
  @$education_gap_reason = $data->education_gap_reason;

  $getDbCount = mysqli_query($con, "SELECT * FROM `tbl_applicant_add` ORDER BY applicant_id DESC limit 1");
  $rows = mysqli_fetch_array($getDbCount);
  @$incCount = $rows['applicant_id'] + 1;


  $id = 'JDAPP' . strtoupper(substr(str_replace(' ', '', $fullname), 0, 4)) . $incCount;
  // echo json_encode($id);
  // exit();

  @$awards = $data->awardOne;
  @$awardTwo = $data->awardTwo;
  @$awardThree = $data->awardThree;
  @$offeredcompany = $data->latest_company_name;
  @$offerpackage = $data->offer_package;
  @$offerrole = $data->offer_role;
  @$offerlocation = $data->offer_location;
  @$offerjoining = $data->joining_date;
  @$total_expereience = $data->experience_Count;
  @$selectexperience = $data->experiences;
  @$experience_company = $data->exp_company;
  @$releventexperience = $data->r_experience;
  @$curcompany = $data->current_company;
  @$currole = $data->current_role;
  @$curctc = $data->current_Ctc;
  @$clocation = $data->current_company_location;
  @$nperiod = $data->notice_period;
  @$available_from = $data->available_from;
  @$implementaion = $data->implementaion;
  @$support = $data->support;
  @$client_facing = $data->client_facing;
  @$lead_projects = $data->lead_projects;
  @$interestedcompanies = $data->int_companies;
  @$excludedcompanies = $data->exc_companies;
  @$inc = implode(',', @$interestedcompanies);
  @$exc = implode(',', @$excludedcompanies);
  @$expectedsalary = $data->exp_salary;
  @$preferred_location = $data->preferred_location;
  if (is_array($preferred_location)) {
    @$p_location = implode(',', $preferred_location);
  } else {
    @$p_location = $preferred_location;
  }
  @$technology = implode($data->technologyId);
  @$primary_skill = implode($data->pri_skill);
  @$main_role = $data->main_role;
  @$naukari = $data->naukri_url;
  @$instagram = $data->instagram_url;
  @$linkendin = $data->linkendin_url;

  // images upload code 
  $fileProfile = '';
  if (!empty($data->profile)) {
    $image_parts = explode(";base64,", $data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileProfile = uniqid() . '.png';
    $file = $folderPath . $fileProfile;
    $move = file_put_contents($file, $image_base64);
  }

  $fileResume = '';
  if (!empty($data->resume)) {
    $getExt = explode('/', mime_content_type($data->resume))[1];
    $extension = $getExt == 'pdf' ? 'pdf' : 'docx';

    $image_parts = explode(";base64,", $data->resume);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileResume = uniqid() . '.' . $extension;
    $file = $folderPath . $fileResume;
    $move = file_put_contents($file, $image_base64);
  }

  $filePancard = '';
  if (!empty($data->pancard)) {
    $image_parts = explode(";base64,", $data->pancard);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $filePancard = uniqid() . '.png';
    $file = $folderPath . $filePancard;
    $move = file_put_contents($file, $image_base64);
  }

  $fileAadharcard = '';
  if (!empty($data->aadharcard)) {
    $image_parts = explode(";base64,", $data->aadharcard);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileAadharcard = uniqid() . '.png';
    $file = $folderPath . $fileAadharcard;
    $move = file_put_contents($file, $image_base64);
  }

  $fileAttachment = '';
  if (!empty($data->Attachments)) {
    $image_parts = explode(";base64,", $data->Attachments);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileAttachment = uniqid() . '.png';
    $file = $folderPath . $fileAttachment;
    $move = file_put_contents($file, $image_base64);
  }

  $fileCurrAttachments = '';
  if (!empty($data->current_attachments)) {
    $image_parts = explode(";base64,", $data->current_attachments);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileCurrAttachments = uniqid() . '.png';
    $file = $folderPath . $fileCurrAttachments;
    $move = file_put_contents($file, $image_base64);
  }

  @$d_profile_image = $data->profile_image;
  @$d_resume_image = $data->resume_image;
  @$d_pan_card = $data->pan_card;
  @$d_aadhar_card = $data->aadhar_card;
  @$d_attachment = $data->Attachments;
  @$d_curr_attachments = $data->current_attachments;

  $whereClause = "";

  if ($fileProfile != '') {
    $whereClause .= "profile_image='" . $fileProfile . "',";
  } else {
    $whereClause .= "profile_image='" . $d_profile_image . "',";
  }

  if ($fileResume != '') {
    $whereClause .= "resume_image='" . $fileResume . "',";
  } else {
    $whereClause .= "resume_image='" . $d_resume_image . "',";
  }

  if ($filePancard != '') {
    $whereClause .= "pan_card='" . $filePancard . "',";
  } else {
    $whereClause .= "pan_card='" . $d_pan_card . "',";
  }

  if ($fileAadharcard != '') {
    $whereClause .= "aadhar_card='" . $fileAadharcard . "',";
  } else {
    $whereClause .= "aadhar_card='" . $d_aadhar_card . "',";
  }

  if (isset($createdById) && !empty($createdById)) {
    $whereClause .= "created_by_id='" . $createdById . "',";
  }

  if (isset($createdRole) && !empty($createdRole)) {
    $whereClause .= "created_by_role='" . $createdRole . "',";
  }

  if ($fileAttachment != '') {
    $whereClause .= "attachment_appli='" . $fileAttachment . "',";
  } else {
    $whereClause .= "attachment_appli='" . $d_attachment . "',";
  }

  if ($fileCurrAttachments != '') {
    $whereClause .= "attachments='" . $fileCurrAttachments . "',";
  } else {
    $whereClause .= "attachments='" . $d_curr_attachments . "',";
  }
  // echo $whereClause;
  // exit;

  @$applicantRequest = $data->application_type;
  @$added_by_admin = $applicantRequest == 'user' ? '0' : '1';
  $getApplicant;

  // email validation code 
  if (!empty($email)) {
    $getApplicant = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE e_mail='" . $email . "' && status=1");
  } else {
    echo json_encode(["message" => "Email Should Not Be Empty", 'status' => false]);
    exit();
  }
  if (mysqli_num_rows($getApplicant) > 0) {
    $stat = ["message" => "Email Already Exists", 'status' => false];
    echo json_encode($stat);
    exit();
  }

  // implode skills into a variable
  $skillArray = [];
  for ($i = 0; $i < count($primaryskills); $i++) {
    array_push($skillArray, $primaryskills[$i]->p_skill,);
    $skill[$i] = $primaryskills[$i]->p_skill;
    $skill_rating[$i] = $primaryskills[$i]->skill_rating;
  }
  $ListprimarySkills = implode(',', $skillArray);

  // to delete drafts data
  if (isset($id)) {
    $EMPLOYERR = mysqli_query($con, "UPDATE tbl_applicant_add_draft SET status=1 where applicant_id='" . $applica_id . "'");
  }

  // query to insert applicant data
  $query = "INSERT INTO tbl_applicant_add SET 
  $whereClause
  primary_skills='" . $ListprimarySkills . "', 
  full_name='" . $fullname . "',
  applicant_auto_create_id='" . $id . "',
  mobile_number='" . $primarymobile . "',
  secondary_mobile='" . $secondarymobile . "',
  e_mail='" . $email . "',
  aadhar_number='" . $aadharNumber . "',
  aadhar_verification='" . $aadharVerified . "',
  app_city='" . $city . "',
  certifications_images='0',
  awards_recognization='" . $awards . "',
  award_two='" . $awardTwo . "',
  award_three='" . $awardThree . "',
  college='" . $college . "',
  highest_degree='" . $HighestDegree . "',
  percentage='" . $percentage . "',
  education_gap='" . $education_gap . "',
  education_gap_reason='" . $education_gap_reason . "',
  latest_company_name='" . $offeredcompany . "',
  latest_package='" . $offerpackage . "',
  latest_role='" . $offerrole . "',
  latest_location='" . $offerlocation . "',
  latest_joining_date='" . $offerjoining . "',
  experience='" . $selectexperience . "',
  total_expereience='" . $total_expereience . "',
  relevant_exp='" . $releventexperience . "',
  curr_company='" . $curcompany . "',
  role_name='" . $currole . "',
  current_package='" . $curctc . "',
  curr_location='" . $clocation . "',
  notice_period='" . $nperiod . "',
  available_from='" . $available_from . "',
  implementation='" . $implementaion . "',
  support='" . $support . "',
  client_facing='" . $client_facing . "',
  lead_projects='" . $lead_projects . "',
  interested_company='" . $inc . "',
  excluded_company='" . $exc . "',
  expected_package='" . $expectedsalary . "',
  preferred_location='" . $p_location . "',
  added_by_admin='" . $added_by_admin . "',
  main_role='" . $main_role . "',
  technology='" . $technology . "',
  main_skill='" . $primary_skill . "',
  linkendin_url='" . $linkendin . "',
  instagram_url='" . $instagram . "',
  naukari_url='" . $naukari . "',
  status=0";

  $insert_applicant = mysqli_query($con, $query);
  $last_id = mysqli_insert_id($con);

  // insert multiple experience into another table
  for ($i = 0; $i < count($experience_company); $i++) {
    $exp[$i] = $experience_company[$i]->exp_company;
    $experience_role[$i] = $experience_company[$i]->exp_role;
    $start_date[$i] = $experience_company[$i]->start_date;
    $end_date[$i] = $experience_company[$i]->end_date;
    $start_salary[$i] = $experience_company[$i]->starting_slry;
    $ending_salary[$i] = $experience_company[$i]->ending_slry;
    $reason[$i] = $experience_company[$i]->reason;
    $job_type[$i] = $experience_company[$i]->job_type;
    $job_primary_skill[$i] = $experience_company[$i]->primary_skill;

    if (isset($exp[$i]) && !empty($exp[$i])) {
      if ($exp) {
        $insert_app_experience = mysqli_query(
          $con,
          "INSERT INTO applicant_experiences SET 
          applicant_id='" . $last_id . "',
          applicant_auto_create_id='" . $id . "',
          exp_company='" . $exp[$i] . "',
          experience_role='" . $experience_role[$i] . "',
          experience_primary_skill='" . $job_primary_skill[$i] . "',
          experience_job_type='" . $job_type[$i] . "',
          starting_date='" . $start_date[$i] . "',
          ending_date='" . $end_date[$i] . "',
          starting_salary='" . $start_salary[$i] . "',
          ending_salary='" . $ending_salary[$i] . "',
          reason='" . $reason[$i] . "',
          status=1"
        );
      }
    }
  }

  // insert multiple skills into another table
  for ($i = 0; $i < count($primaryskills); $i++) {
    $skill[$i] = $primaryskills[$i]->p_skill;
    $skill_rating[$i] = $primaryskills[$i]->skill_rating;

    if ($skill) {
      $insert_applicant_skill = mysqli_query($con, "INSERT INTO applicant_skill SET 
      applicant_id='" . $last_id . "',
      applicant_auto_create_id='" . $id . "',
      pri_skill='" . $skill[$i] . "',
      skill_rating='" . $skill_rating[$i] . "',
      status=1");
    }
  }

  // insert certifications to certifications table
  $certificates = $data->certifictificates_array;
  for ($i = 0; $i < count($certificates); $i++) {
    $c_name[$i] = $certificates[$i]->certificateName;
    $c_image[$i] = $certificates[$i]->certificateImage;
    $image_name[$i] = $certificates[$i]->c_Image;
    $image = '';
    $fileImage = '';

    if (isset($certificates[$i]->certificate_id) && !empty($certificates[$i]->certificate_id)) {
      mysqli_query($con, "UPDATE applicant_certificates_drafts SET status=1 WHERE certificate_id='" . $certificates[$i]->certificate_id . "'");
    }

    if (isset($c_name[$i]) && !empty($c_name[$i])) {
      // if (!empty($c_image[$i])) {
      //   $getExt = explode('/', mime_content_type($c_image[$i]))[1];
      //   $extension = $getExt == 'pdf' ? 'pdf' : 'docx';
      //   $image_parts = explode(";base64,", $c_image[$i]);
      //   $image_type_aux = explode("image/", $image_parts[0]);
      //   $image_base64 = base64_decode($image_parts[1]);
      //   $fileImage = uniqid() . '.' . $extension;
      //   $file = $folderPath . $fileImage;
      //   $move = file_put_contents($file, $image_base64);
      // }

      if (!empty($c_image[$i])) {
        $getExt = explode('/', mime_content_type($c_image[$i]))[1];
        if ($getExt === 'pdf') {
          $extension = 'pdf';
        } else {
          $extension = 'png';
        }
        $image_parts = explode(";base64,", $c_image[$i]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileImage = uniqid() . '.' . $extension;
        $file = $folderPath . $fileImage;
        $move = file_put_contents($file, $image_base64);
      }


      if ($fileImage != '') {
        $image .= "certificate_image='" . $fileImage . "',";
      } else {
        $image .= "certificate_image='" . $image_name[$i] . "',";
      }

      $insert_applicant_certificates = mysqli_query($con, "INSERT INTO applicant_certificates SET
        applicant_id='" . $last_id . "',
        applicant_auto_create_id='" . $id . "',
        certificate_name='" . $c_name[$i] . "',
        $image
        status=1
      ");
    }
  }

  // api response for user registration
  if ($insert_applicant && $applicantRequest == 'user') {
    $stat = ['status' => true, 'message' => 'your application has been submitted successfully...! our team will get back to you soon'];
    echo json_encode($stat);
    exit();
  }

  // mail sending code 
  if ($insert_applicant == true) {

    $mail = new PHPMailer;
    // $mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'work@mmworkspace.com'; // SMTP username
    $mail->Password = 'Work@321#'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465; // TCP port to connect to

    $mail->setFrom('work@mmworkspace.com', 'JALDHI HIRE');
    $mail->addAddress('makersminds@gmail.com'); // Add a recipient
    $mail->addAddress($email); // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC($cc);
    $mail->addBCC('bcc@example.com');

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true); // Set email format to HTML

    $mail->Subject = 'Please select password for applicant login-jaldihire';
    $mail->Body = '


     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title></title>
    <!--[if !mso]><!-->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <!--<![endif]-->
    <style type="text/css">
      @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif !important;
      }
      @media only screen and (min-width: 620px) {
        .u-row {
          width: 600px !important;
        }

        .u-row .u-col {
          vertical-align: top;
        }

        .u-row .u-col-33p33 {
          width: 199.98px !important;
        }

        .u-row .u-col-66p67 {
          width: 400.02px !important;
        }

        .u-row .u-col-100 {
          width: 600px !important;
        }
      }

      @media (max-width: 620px) {
        .u-row-container {
          max-width: 100% !important;
          padding-left: 0px !important;
          padding-right: 0px !important;
        }

        .u-row .u-col {
          min-width: 320px !important;
          max-width: 100% !important;
          display: block !important;
        }

        .u-row {
          width: 100% !important;
        }

        .u-col {
          width: 100% !important;
        }

        .u-col > div {
          margin: 0 auto;
        }
      }

      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif;
      }

      table,
      tr,
      td {
        vertical-align: top;
        border-collapse: collapse;
      }

      p {
        margin: 0;
      }

      .ie-container table,
      .mso-container table {
        table-layout: fixed;
      }

      * {
        line-height: inherit;
      }

      a[x-apple-data-detectors="true"] {
        color: inherit !important;
        text-decoration: none !important;
      }

      table,
      td {
        color: #000000;
      }

      #u_body a {
        color: #0000ee;
        text-decoration: underline;
      }

      #u_content_heading_49 a {
        color: #1c9ca1;
      }

      #u_content_heading_50 a {
        color: #1c9ca1;
      }
      .trinel::after {
        position: absolute;
        content: "";
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 10px 15px 10px 0;
        border-color: transparent #fefefd transparent transparent;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
        top: 100px;
        rotate: 32deg;
        right: 55px;
      }

      @media (max-width: 480px) {
        #u_content_button_5 .v-size-width {
          width: 76% !important;
        }
        .caleder-img {
          width: 150px !important;
          height: 150px !important;
          padding-right: 10px !important;
          margin-top: 40px !important;
        }
        .logo-img {
          max-width: 140px !important;
          padding-top: 26px !important;
          padding-left: 5px !important;
        }
        .man-img {
          width: 60% !important;
          margin-top: 40px !important;
          margin-left: 37px !important;
        }
      }
      .caleder-img {
        width: 180px;
        height: 180px;
        padding-right: 40px;
        margin-top: 40px;
      }
    </style>
  </head>

  <body
    class="clean-body u_body"
    style="
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      background-color: #f0f0f0;
      color: #000000;
    "
  >
    <!--[if IE]><div class="ie-container"><![endif]-->
    <!--[if mso]><div class="mso-container"><![endif]-->
    <table
      id="u_body"
      style="
        border-collapse: collapse;
        table-layout: fixed;
        border-spacing: 0;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        vertical-align: top;
        min-width: 320px;

        margin: 0 auto;
        background-color: #f0f0f0;
        width: 100%;
      "
      cellpadding="0"
      cellspacing="0"
    >
      <tbody>
        <tr style="vertical-align: top">
          <td
            style="
              word-break: break-word;
              border-collapse: collapse !important;
              vertical-align: top;
            "
          >
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f0f0f0;"><![endif]-->

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ddffe7;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #072c50;
                        height: 100%;
                        width: 100% !important;
                        background-size: cover;
                        background-repeat: no-repeat;
                        border-radius: 20px 20px 0px 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          margin-top: 50px;
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <table
                                  width="100%"
                                  cellpadding="0"
                                  cellspacing="0"
                                  border="0"
                                >
                                  <tr>
                                    <td
                                      style="
                                        padding-right: 0px;
                                        padding-left: 0px;
                                        text-align: start;
                                      "
                                      align="center"
                                    >
                                      <div>
                                        <img
                                          align="center"
                                          border="0"
                                          src="https://jaldihire.com/admin_backend/assets/images/Jaldihire_Logo_Name_Logo.png"
                                          alt="image"
                                          title="image"
                                          class="logo-img"
                                          style="
                                            outline: none;
                                            text-decoration: none;
                                            -ms-interpolation-mode: bicubic;
                                            clear: both;
                                            display: inline-block !important;
                                            border: none;
                                            height: auto;
                                            float: none;
                                            width: 100%;
                                            max-width: 190px;
                                            padding-top: 15px;
                                            padding-left: 35px;
                                            padding-bottom: 20px;
                                          "
                                        />
                                        <div
                                          class="trinel"
                                          style="
                                            display: flex;
                                            background-color: #fff;
                                            padding: 0px 15px 0px 15px;
                                            width: 70%;
                                            margin: auto;
                                            border-radius: 20px;
                                            align-items: center;
                                            margin-bottom: 35px;
                                            margin-top: 20px;
                                            position: relative;
                                          "
                                        >
                                          <div
                                            style="
                                              width: 30%;
                                              text-align: center;
                                            "
                                          >
                                            <img
                                              src="https://jaldihire.com/admin_backend/assets/images/lock-ani.gif"
                                              alt="image"
                                              style="width: 80px; height: 80px"
                                            />
                                          </div>
                                          <div
                                            style="
                                              text-align: center;
                                              width: 70%;
                                            "
                                          >
                                            <img
                                              class="man-img"
                                              src="https://jaldihire.com/admin_backend/assets/images/passwordgif2.gif"
                                              alt="image"
                                              style="
                                                width: 250px;
                                                height: 108px;
                                              "
                                            />
                                          </div>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
               
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #ffffff;
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 30px 10px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                                <h4
                                  style="
                                    margin: 0px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                    font-size: 12px;
                                    font-weight: 700;
                                  "
                                >
                                  <h2
                                    style="
                                      white-space: normal;
                                      background-color: #ffffff;
                                      color: #08374a;
                                      margin: 0px;
                                    "
                                  >
                                   Set Up Your Secure Password Now
                                  </h2>
                                </h4>
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 11px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <div
                                  style="
                                    font-size: 14px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                  "
                                >
                                  <p
                                    style="
                                      color: #00304d;
                                      white-space: normal;
                                      background-color: #ffffff;
                                      line-height: 140%;
                                    "
                                  >
                              Hello there, ' . $fullname . ', Hello and welcome to the Jaldihire family.<br>Please select a password now to gain full access to your applicant dashboard.Have a wonderful day
                                  </p>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 0px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                              
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px 0px 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: #ffffff;
                     margin-bottom: 40px;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 2px 0px 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                     
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          id="u_content_button_5"
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso
                                  ]><style>
                                    .v-button {
                                      background: transparent !important;
                                    }
                                  </style><!
                                [endif]-->
                                <div align="left" style="text-align: center">
                                  <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://unlayer.com" style="height:34px; v-text-anchor:middle; width:140px;" arcsize="12%"  strokecolor="#000000" strokeweight="2px" fillcolor="#000000"><w:anchorlock/><center style="color:#ffffff;"><![endif]-->
                                  <a
                                   href=https://jaldihire.com/admin_backend/user-choose-password/index.php?appId=' . $id . '  target="_blank"
                                
                                    class="v-button v-size-width"
                                    style="
                                      box-sizing: border-box;
                                      display: inline-block;
                                      text-decoration: none;
                                      -webkit-text-size-adjust: none;
                                      text-align: center;
                                      color: #ffffff;
                                      background-color: #0e69c1;
                                      border-radius: 4px;
                                      -webkit-border-radius: 4px;
                                      -moz-border-radius: 4px;
                                      width: auto;
                                      max-width: 100%;
                                      overflow-wrap: break-word;
                                      word-break: break-word;
                                      word-wrap: break-word;
                                      mso-border-alt: none;
                                      border-top-width: 2px;
                                      border-top-style: solid;
                                      border-top-color: #0e69c1;
                                      border-left-width: 2px;
                                      border-left-style: solid;
                                      border-left-color: #0e69c1;
                                      border-right-width: 2px;
                                      border-right-style: solid;
                                      border-right-color: #0e69c1;
                                      border-bottom-width: 2px;
                                      border-bottom-style: solid;
                                      border-bottom-color: #0e69c1;
                                      font-size: 18px;
                                    "
                                  >
                                    <span
                                      style="
                                        display: block;
                                        padding: 10px 30px 8px;
                                        line-height: 90%;
                                      "
                                    
                                      >Click Here
                                    </span>
                                  </a>
                                  <!--[if mso]></center></v:roundrect><![endif]-->
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!--[if mso]></div><![endif]-->
    <!--[if IE]></div><![endif]-->
  </body>
</html>

';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if ($mail->send()) {
      $stat = ['message' => 'mail sent successfully', 'added' => 'applicant added successfully', 'status' => true];
      // $stat =  ['Mailer Error: ' . $mail->ErrorInfo];
    } else {
      $stat = ['message' => 'mail failed to sent', 'err' => mysqli_error($con), 'data' => $data, 'status' => false];
    }
  } else {
    $stat = ['message' => 'Failed!', 'err' => mysqli_error($con), 'data' => $data];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong try again'];
}

echo json_encode($stat);
