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
include '../lib/mails.php';
include "../lib/jwt.php";

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
  echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
  exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);

$folderPath = "uploads/recruiter-images/";
$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  // $recruiter_id = $data->recruiter_id;
  @$name = $data->name;
  @$function = $data->function;
  @$department = $data->department;
  @$role = $data->role;
  @$designation = $data->designation;
  @$reportingTo = $data->reporting;
  @$mobile = $data->mobile;
  @$email = $data->email;
  @$skill = $data->skills;
  @$validity = $data->validity;
  @$createdBy = $data->created_by;
  @$skillsImplode = '';
  @$technology = $data->technology;
  @$technologyImplode = '';

  // @$technology = implode(',', $data->technology);

  $fileProfile = '';
  if (!empty($data->profile)) {
    $image_parts = explode(";base64,", $data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileProfile = uniqid() . '.png';
    $file = $folderPath . $fileProfile;
    $move = file_put_contents($file, $image_base64);
  }

  if (!empty($email)) {
    $getEmployer = mysqli_query($con, "SELECT * FROM tbl_allyted_team WHERE status=1 && member_email='" . $email . "' ");
  } else {
    echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
    exit();
  }
  if (mysqli_num_rows($getEmployer) > 0) {
    echo json_encode(['status' => false, "message" => "Email Already Exists"]);
    exit();
  }

  if (count($skill) > 0) {
    for ($i = 0; $i < count($skill); $i++) {
      $item = $skill[$i];
      if (intval(count($skill) - 1) == $i) {
        $skillsImplode .= $item->item_text;
      } else {
        $skillsImplode .= $item->item_text . ',';
      }
    }
  }
  if (count($technology) > 0) {
    for ($i = 0; $i < count($technology); $i++) {
      $item = $technology[$i];
      if (intval(count($technology) - 1) == $i) {
        $technologyImplode .= $item->item_text;
      } else {
        $technologyImplode .= $item->item_text . ',';
      }
    }
  }

  $id = 1;

  $getAddteam = mysqli_query($con, "SELECT MAX(allyted_member_id) as max FROM `tbl_allyted_team`");
  $rows = mysqli_fetch_array($getAddteam);
  $incCount = $rows == null ? $id : intval($rows['max']) + 1;

  $auto_create_id = 'TEAM' . strtoupper(substr(str_replace(' ', '', $name), 0, 5)) . $incCount;

  if (!empty($email)) {
    $insert_team = mysqli_query($con, "INSERT INTO tbl_allyted_team SET 
    recruiter_auto_create_id='" . $auto_create_id . "',
    member_name='" . $name . "',
    profile_img='" . $fileProfile . "',
    `function`='" . $function . "',
    department='" . $department . "',
    designation='" . $designation . "',
    reportingTo='" . $reportingTo . "',
    role='" . $role . "',
    validity='" . $validity . "',
    technology='" . $technologyImplode . "',
    skills='" . $skillsImplode . "',
    member_mobile='" . $mobile . "',
    member_email='" . $email . "',
    created_by='" . $createdBy . "',
    status=0");
  }


  if ($insert_team == true) {

    $insert_notification = mysqli_query($con, "INSERT INTO allowed_notifications SET user_id='" . $auto_create_id . "',employer_alerts=1,advisor_alerts=1,recruiter_alerts=1,admin_alerts=1,interview_alerts=1,job_alerts=1,status=1");


    $subject = "Please select password for $role login-Jaldi Hire";
    $Jaldihire = "Create new account | Agent";
    $heading = "Create new account";

    $body = "Hello , $name, Hello and welcome to the Jaldihire family.Please select a password now to gain full access to your dashboard.Have a wonderful day
    Regards";

    add_allyted($email, $subject, $heading, $body, $auto_create_id);

    $stat = ['status' => true, 'message' => 'Mail Sent Successfully', 'added' => 'Added successfully'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Failed!', 'err' => mysqli_error($con)];
}


echo json_encode($stat);
