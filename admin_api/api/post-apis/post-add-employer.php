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
include "../vendor/autoload.php";


$folderPath = "uploads/employer-images/";


$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

  $data = json_decode($json);


  @$id = $data->id;
  @$companyname = $data->company_name;
  // $employername = $data->emp_name;
  // $employerautoid = $data->employer_id;

  // $employerdesignation =$data->designation; 
  @$employermobile = $data->emp_mob;
  @$email = $data->emp_email;
  @$employerpincode = $data->emp_pincode;
  @$employeraddress = $data->emp_address;
  @$employercity = $data->emp_city;
  @$employerstate = $data->emp_state;
  @$employercountry = $data->emp_country;
  @$employerbank = $data->emp_bank;
  @$bankacname = $data->emp_bank_ac_name;
  @$employeraccount = $data->emp_bank_ac;
  @$employerifsc = $data->emp_ifsc;
  @$emp_url = $data->emp_url;
  @$employerbranch = $data->emp_branch;
  @$createdBy = $data->created_by;
  @$prefix_code = $data->prefix_code;
  @$team_members = $data->team_members;
  @$Onboard_talent = $data->Onboard_talent;
  @$Trusted_customers = $data->Trusted_customers;
  @$Employees_rating = $data->Employees_rating;
  @$Trustpilot = $data->Trustpilot;
  @$glassdoor = $data->glassdoor;
  @$AmbitionBox = $data->AmbitionBox;
  @$About = $data->About;
  @$plan = $data->plan;
  @$plan_validity = $data->plan_validity;

  $current_date_plan = new DateTime();
  $cur_post = $current_date_plan->format("Y-m-d");
  $current_date = new DateTime();
  $days_to_add = $plan_validity * 28;
  $current_date->modify("+$days_to_add days");
  $formatted_date = $current_date->format("Y-m-d");

  $Id = 1;

  // $planIds = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_plans WHERE plan LIKE '$plan%'"));

  // $getplanIds = $planIds['plan_id'];
  $getplanId = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_plan_features WHERE plan_id='" . $plan . "'"));
  $resourceManager = $getplanId['Resource_manager'];
  $panel_members = $getplanId['Panel_members'];
  $applicant_count = $getplanId['applicant_count'];



  // $resourceManager = 1;
  // $interviewer = 1;
  // $panel_members = 1;
  $Onboardings = 10;
  $Interviews = 10;

  $getDbCount = mysqli_query($con, "SELECT MAX(employer_id) as max FROM `tbl_add_employer`");
  $rows = mysqli_fetch_array($getDbCount);
  $incCount = $rows == null ? $Id : intval($rows['max']) + 1;

  @$autoId = 'JDEMP' . strtoupper(substr(str_replace(' ', '', $companyname), 0, 4)) . $incCount;

  @$relation_name = [];
  if (isset($data->relation_name)) {
    $relation_name = $data->relation_name;
  }
  @$certifictificates_array = [];
  if (isset($data->certifictificates_array)) {
    $certifictificates_array = $data->certifictificates_array;
  }
  // @$perk_benfit = [];
  // if (isset($data->perk_benfit)) {
  //   $perk_benfit = $data->perk_benfit;
  // }

  $perk_benfit = [];
  if (isset($data->perk_benfit)) {
    $perk_benfit = $data->perk_benfit;
  }

  @$employergst = $data->emp_gst;

  // $employerimage =$data->profile;
  $fileName = '';
  if (!empty($data->profile)) {
    $image_parts = explode(";base64,", $data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
    $file = $folderPath . $fileName;
    $move = file_put_contents($file, $image_base64);
  }

  @$d_profile_image = $data->emp_img;

  $whereClause = '';

  if ($fileName != '') {
    $whereClause .= 'emp_img="' . $fileName . '",';
  } else {
    $whereClause .= 'emp_img="' . $d_profile_image . '",';
  }


  // echo $whereClause;
  // exit;



  @$employerRequest = $data->employer_type;
  $getEmployer;
  // $binaryData = base64_decode($employerimage);
  // echo json_encode(base64_decode($employerimage));
  // exit();
  // file_put_contents("../../assets", $binaryData);
  // $relation_function =$data->relation_function; 
  // $relation_phn =$data->relation_phn; 
  // $relation_mail =$data->relation_mail; 
  // $assigned =$data->assigned; 
  // $asigned_phn =$data->asigned_phn; 
  // $assigned_mail =$data->assigned_mail; 


  if (!empty($email)) {
    $getEmployer = mysqli_query($con, "select * from tbl_add_employer where emp_email='" . $email . "' && status=1");
  } else {
    echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
    exit();
  }

  if (!empty($employergst)) {
    $getGst = mysqli_query($con, "select * from tbl_add_employer where emp_gst='" . $employergst . "' && status=1");
    if (mysqli_num_rows($getGst) > 0) {
      echo json_encode(['status' => false, "message" => "GST No Already in use"]);
      exit();
    }
  }

  if (mysqli_num_rows($getEmployer) > 0) {
    echo json_encode(['status' => false, "message" => "Email already in use"]);
    exit();
  }



  if (!empty($email)) {
    if (!empty($id)) {
      $EMPLOYERR = mysqli_query($con, "UPDATE tbl_add_employer_draft SET status=1 where employer_id='" . $id . "'");
    }
    $query = 'INSERT INTO tbl_add_employer SET ' . $whereClause . ' company_name="' . $companyname . '",
    employer_auto_create_id="' . $autoId . '",
    prefix_code="' . $prefix_code . '",
    emp_mob="' . $employermobile . '",
    emp_email="' . $email . '",
    emp_pincode="' . $employerpincode . '",
    emp_address="' . $employeraddress . '",
    emp_city="' . $employercity . '",
    emp_state="' . $employerstate . '",
    emp_country="' . $employercountry . '",
    plan_months="' . $plan_validity . '",
    plan_valid_date="' . $formatted_date . '",
    plan_id="' . $plan . '",
    Interviews="' . $Interviews . '",
    Onboardings="' . $Onboardings . '",
    panel_members="' . $panel_members . '",
    emp_url="' . $emp_url . '",
    emp_bank="' . $employerbank . '",
    emp_bank_ac_name="' . $bankacname . '",
    emp_bank_ac="' . $employeraccount . '",
    emp_ifsc="' . $employerifsc . '",
    emp_branch="' . $employerbranch . '",
    emp_gst="' . $employergst . '",
    team_members="' . $team_members . '",
    onboard_talent="' . $Onboard_talent . '",
    trusted_customers	="' . $Trusted_customers . '",
    employees_Ratings="' . $Employees_rating . '",
    glassdoor="' . $glassdoor . '",
    trustpilot="' . $Trustpilot . '",
    ambition_box="' . $AmbitionBox . '",
    about	="' . $About . '",
    resource_managers="' . $resourceManager . '",
    app_profile_count="' . $applicant_count . '",
    created_by="' . $createdBy . '",
    status=0';

    // echo $query;
    // exit();
    $insert_employer = mysqli_query($con, $query);
    $last_id = mysqli_insert_id($con);
  }



  if ($insert_employer == true) {
    $plan_history = mysqli_query($con, query: 'INSERT INTO plans_history SET employer_auto_id="' . $autoId . '",plan_id="' . $plan . '",plan_start_date="' . $cur_post . '",plan_validity_date="' . $formatted_date . '",plan_months="' . $plan_validity . '",creted_by="' . $createdBy . '",status=1');

    $insert_notification = mysqli_query($con, "INSERT INTO allowed_notifications SET user_id='" . $autoId . "',employer_alerts=1,advisor_alerts=1,recruiter_alerts=1,admin_alerts=1,interview_alerts=1,job_alerts=1,status=1");

    for ($i = 0; $i < count($relation_name); $i++) {
      $relation_name1[$i] = $relation_name[$i]->relation_name;
      $relation_function[$i] = $relation_name[$i]->relation_function;
      $relation_phn[$i] = $relation_name[$i]->relation_phn;
      $relation_mail[$i] = $relation_name[$i]->relation_mail;
      $assigned[$i] = $relation_name[$i]->assigned;
      $asigned_phn[$i] = $relation_name[$i]->asigned_phn;
      $assigned_mail[$i] = $relation_name[$i]->assigned_mail;


      $insert_employerrelation = mysqli_query($con, "INSERT INTO tbl_employer_relation SET 
      employer_id='" . $last_id . "',
      employer_auto_create_id='" . $autoId . "',
      relation_name='" . $relation_name1[$i] . "',
      relation_function='" . $relation_function[$i] . "',
      relation_phn='" . $relation_phn[$i] . "',
      relation_mail='" . $relation_mail[$i] . "',
      assigned='" . $assigned[$i] . "',
      asigned_phn='" . $asigned_phn[$i] . "',
      assigned_mail='" . $assigned_mail[$i] . "',
      status=1");
    }
    // for ($i = 0; $i < count($perk_benfit); $i++) {
    //   $select_benfit[$i] = $perk_benfit[$i]->select_benfit;
    //   $content[$i] = $perk_benfit[$i]->content;

    //   $insert_employerrelation = mysqli_query($con, "INSERT INTO tbl_emp_perk_Benfit SET 
    //   employer_id='" . $last_id . "',
    //   employer_auto_create_id='" . $autoId . "',
    //   benfit_type='" . $select_benfit[$i] . "',
    //   benfit_content='" . $content[$i] . "',    
    //   status=1");
    // }

    for ($i = 0; $i < count($perk_benfit); $i++) {
      $select_benfit = $perk_benfit[$i]->select_benfit;
      $content = $perk_benfit[$i]->content;

      $insert_employerrelation = mysqli_query($con, "INSERT INTO tbl_emp_perk_Benfit SET 
        employer_id='" . mysqli_real_escape_string($con, $last_id) . "',
        employer_auto_create_id='" . mysqli_real_escape_string($con, $autoId) . "',
        benfit_type='" . mysqli_real_escape_string($con, $select_benfit) . "',
        benfit_content='" . mysqli_real_escape_string($con, $content) . "',    
        status=1");

      if (!$insert_employerrelation) {
        // Log or display the error for debugging
        echo "Error inserting record for benefit " . $select_benfit . ": " . mysqli_error($con);
      }
    }


    // for ($i = 0; $i < count($perk_benfit); $i++) {
    //   $select_benfit = $perk_benfit[$i]->select_benfit;
    //   $content = $perk_benfit[$i]->content;

    //   $insert_query = "INSERT INTO tbl_emp_perk_Benfit (employer_id, employer_auto_create_id, benfit_type, benfit_content, status) 
    //                    VALUES ('" . $last_id . "', '" . $autoId . "', '" . $select_benfit . "', '" . $content . "', 1)";
    // }

    // for ($i = 0; $i < count($certifictificates_array); $i++) {
    //   $certificateName[$i] = $certifictificates_array[$i]->certificateName;
    //   $certificateImage[$i] = $certifictificates_array[$i]->certificateImage;
    //   $certificateadmin[$i] = $certifictificates_array[$i]->certificateadmin;
    //   $image = '';

    //   if (isset($certificateImage) && !empty($certificateImage)) {
    //     $image_parts = explode(";base64,", $certificateImage[$i]);
    //     $mime_type = explode(":", $image_parts[0])[1]; // Get mime type from base64 string
    //     $getExt = explode('/', $mime_type)[1]; // Get the extension type (e.g., png, jpeg)

    //     // If the file is an image, set the extension to png
    //     $extension = 'png';

    //     // Decode base64 image content
    //     $image_base64 = base64_decode($image_parts[1]);

    //     // Generate unique filename with .png extension
    //     $fileImage = uniqid() . '.' . $extension;
    //     $file = $folderPath . $fileImage;

    //     // Save the image to the server
    //     $move = file_put_contents($file, $image_base64);

    //     if ($fileImage != '') {
    //       $image .= "activity_image='" . $fileImage . "',";
    //     }

    //     $insert_employerActivities = mysqli_query($con, "INSERT INTO tbl_emp_activities SET 
    //     employer_id='" . $last_id . "',
    //     employer_auto_create_id='" . $autoId . "',
    //     activity_title='" . $certificateName[$i] . "',
    //     activity_image='" . $image . "',
    //     activity_created_by='" . $certificateadmin[$i] . "',
    //     status=1");
    //   } else {
    //     $insert_employerActivities = mysqli_query($con, "INSERT INTO tbl_emp_activities SET 
    //     employer_id='" . $last_id . "',
    //     employer_auto_create_id='" . $autoId . "',
    //     activity_title='" . $certificateName[$i] . "',
    //     activity_created_by='" . $certificateadmin[$i] . "',
    //     status=1");
    //   }
    // }




    for ($i = 0; $i < count($certifictificates_array); $i++) {
      $certificateName[$i] = $certifictificates_array[$i]->certificateName;
      $certificateImage[$i] = $certifictificates_array[$i]->certificateImage;
      $certificateadmin[$i] = $certifictificates_array[$i]->certificateadmin;
      $certificateurl[$i] = $certifictificates_array[$i]->certificateurl;
      $image = '';

      if (isset($certificateImage[$i]) && !empty($certificateImage[$i])) {
        $image_parts = explode(";base64,", $certificateImage[$i]);
        $mime_type = explode(":", $image_parts[0])[1]; // Get mime type from base64 string
        $getExt = explode('/', $mime_type)[1]; // Get the extension type (e.g., png, jpeg)

        // Set the extension to png
        $extension = 'png';

        // Decode base64 image content
        $image_base64 = base64_decode($image_parts[1]);

        // Generate unique filename with .png extension
        $fileImage = uniqid() . '.' . $extension;
        $file = $folderPath . $fileImage;

        // Save the image to the server
        $move = file_put_contents($file, $image_base64);

        // Assign the image file name
        $image = $fileImage;
      }

      // Insert the data into the database, with or without an image
      $insert_employerActivities = mysqli_query($con, "INSERT INTO tbl_emp_activities SET 
          employer_id='" . $last_id . "',
          employer_auto_create_id='" . $autoId . "',
          activity_title='" . $certificateName[$i] . "',
          activity_image='" . $image . "',
          activity_created_by='" . $certificateadmin[$i] . "',
          activity_url='" . $certificateurl[$i] . "',
          status=1");
    }


    if ($employerRequest == 'yes') {
      $stat = ['status' => true, 'message' => 'your application has been submitted successfully...!!our team will get back to you soon'];
      echo json_encode($stat);
      exit();
    }




    $mail = new PHPMailer;


    // $mail->SMTPDebug = 3;   // Enable verbose debug output
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'jaldihireteam@mmworkspace.com'; // SMTP username
    $mail->Password = 'Jaldihire@321$#'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465; // TCP port to connect to

    $mail->setFrom('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
    // $mail->addAddress('makersminds@gmail.com'); // Add a recipient
    $mail->addAddress($email); // Name is optional
    $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
    // $mail->addCC($cc);
    $mail->isHTML(true); // Set email format to HTML

    $mail->Subject = 'Please select password for Employer login-Jaldi Hire';
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
                                          src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/Jaldihire_Logo_Name_Logo.png"
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
                                              src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/lock-ani.gif"
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
                                              src="https://mmworkspace.com/test_jaldhihireadmin/assets/images/passwordgif2.gif"
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
                                  Dear ' . $companyname . ',<br /></span> Welcome to Jaldihire!<br> Please click the link below to create your
          password and complete your account setup
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
                                   href=https://jaldihire.com/admin_api/employer-choose-password/index.php?empId=' . $autoId . '  target="_blank"
                                
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
      $stat = ['message' => 'mail sent successfully', 'added' => 'Employer added successfully', 'status' => true, 'err' => mysqli_error($con)];
      // $stat =  ['Mailer Error: ' . $mail->ErrorInfo];
    } else {
      $stat = ['message' => 'mail failed to sent', 'status' => false];
    }
  } else {
    $stat = ['message' => 'Failed!', 'err' => mysqli_error($con), 'status' => false, 'data' => $data];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong! Please Try Again'];
}

echo json_encode($stat);