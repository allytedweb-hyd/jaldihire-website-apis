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

include "../../includes/db-connection/db.php";
$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  $applicant_id = $data->applicant_id;
  $fullname = $data->full_name;
  $primarymobile = $data->pri_mobile;
  $secondarymobile = $data->sec_mobile;
  $email = $data->e_mail;
  $primaryskills = $data->p_skills;
  $secondaryskills = $data->s_skills;
  $awards = $data->awards;
  $offeredcompany = $data->latest_company_name;
  $offerpackage = $data->offer_package;
  $offerrole = $data->offer_role;
  $offerlocation = $data->offer_location;
  $offerjoining = $data->joining_date;
  $selectexperience = $data->experiences;
  $totalexperience = $data->total_experience;
  $releventexperience = $data->r_experience;
  $curcompany = $data->current_company;
  $currole = $data->current_role;
  $curctc = $data->current_Ctc;
  $clocation = $data->current_company_location;
  $nperiod = $data->notice_period;
  $interestedcompanies = $data->int_companies;
  $excludedcompanies = $data->exc_companies;
  $inc = implode(',', $interestedcompanies);
  $exc = implode(',', $excludedcompanies);
  $expectedsalary = $data->exp_salary;




  $insert_applicant = mysqli_query($con, "INSERT INTO tbl_applicant_add SET full_name='" . $fullname . "',applicant_auto_create_id='" . $applicant_id . "',mobile_number='" . $primarymobile . "',secondary_mobile='" . $secondarymobile . "',e_mail='" . $email . "',profile_image='0',resume_image='0',pan_card='0',aadhar_card='0',primary_skills='" . $primaryskills . "',secondary_skills='" . $secondaryskills . "',certifications_images='0',awards_recognization='" . $awards . "',latest_company_name='" . $offeredcompany . "',latest_package='" . $offerpackage . "',latest_role='" . $offerrole . "',latest_location='" . $offerlocation . "',latest_joining_date='" . $offerjoining . "',experience='" . $selectexperience . "',total_exp='" . $totalexperience . "',relevant_exp='" . $releventexperience . "',curr_company='" . $curcompany . "',role_name='" . $currole . "',current_package='" . $curctc . "',curr_location='" . $clocation . "',notice_period='" . $nperiod . "',interested_company='" . $inc . "',excluded_company='" . $exc . "',expected_package='" . $expectedsalary . "',status=1");

  if ($insert_applicant == true) {

    $cc = $data->enter_cc;
    $sub = $data->enter_subject;
    $msg = $data->enter_message;

    $mail = new PHPMailer;


    // $mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtpout.secureserver.net';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'info@allyted.com';                 // SMTP username
    $mail->Password = 'Allyted@2026';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('info@allyted.com', 'Mailer');
    $mail->addAddress('makersminds@gmail.com');     // Add a recipient
    $mail->addAddress($email);               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC($cc);
    $mail->addBCC('bcc@example.com');

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $sub;
    $mail->Body    = 'heyyy';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
      $stat =  ['failed' => 'mail failed to sent'];
      // $stat =  ['Mailer Error: ' . $mail->ErrorInfo];
    } else {
      $stat = ['success' => 'mail sent successfully', 'added' => 'applicant added successfully'];
    }
  } else {

    $stat = ['failurestatus' => 'Failed!', 'err' => mysqli_error($con), 'data' => $data];
  }
}

echo json_encode($stat);
