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

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

$folderPath = "uploads/employer-images/";


$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

  $data = json_decode($json);

  if (!isset($data->id) || empty($data->id)) {
    $stat = ["message" => "Invalid Request", 'status' => false];
    echo json_encode($stat);
    exit();
  }


  @$employer_auto_create_id = $data->id;
  @$employerId = $data->empId;
  @$companyname = $data->company_name;
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
  @$employerbranch = $data->emp_branch;
  @$oldImage = $data->oldImage;


  // $getDbCount = mysqli_query($con, "SELECT * FROM `tbl_add_employer` ORDER BY employer_id DESC limit 1");
  // $rows = mysqli_fetch_array($getDbCount);
  // $incCount = $rows['employer_id'] + 1;

  // $autoId = 'JDAPP' . strtoupper($companyname) . $incCount;

  @$relation_name = [];
  if (isset($data->relation_name)) {
    $relation_name = $data->relation_name;
  }
  @$perkBenfit = [];
  if (isset($data->perkBenfit)) {
    $perkBenfit = $data->perkBenfit;
  }
  $employergst = $data->emp_gst;

  // $employerimage =$data->profile;
  $fileName = '';
  if (!empty($data->profile)) {
    $image_parts = explode(";base64,", $data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
    $file = $folderPath . $fileName;
    @unlink($folderPath . $oldImage);
    $move = file_put_contents($file, $image_base64);
  }

  @$d_profile_image = $data->emp_img;

  $whereClause = "";

  if ($fileName != '') {
    $whereClause .= "emp_img='" . $fileName . "',";
  } else {
    $whereClause .= "emp_img='" . $d_profile_image . "',";
  }

  // echo $whereClause;
  // exit;

  @$employerRequest = $data->employer_type;
  $getEmployer;

  if (!empty($email)) {
    $getEmployer = mysqli_query($con, "select * from tbl_add_employer where emp_email='" . $email . "' and employer_auto_create_id != '" . $employer_auto_create_id . "'");
  } else {
    echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
    exit();
  }

  if (mysqli_num_rows($getEmployer) > 0) {
    echo json_encode(['status' => false, "message" => "Email Already Exists"]);
    exit();
  }

  // employer_auto_create_id='" . $autoId . "',
  if (!empty($email)) {

    $insert_employer = mysqli_query($con, "UPDATE tbl_add_employer set 
    $whereClause
    company_name='" . $companyname . "',
    emp_mob='" . $employermobile . "',
    emp_email='" . $email . "',
    emp_pincode='" . $employerpincode . "',
    emp_address='" . $employeraddress . "',
    emp_city='" . $employercity . "',
    emp_state='" . $employerstate . "',
    emp_country='" . $employercountry . "',
    emp_bank='" . $employerbank . "',
    emp_bank_ac_name='" . $bankacname . "',
    emp_bank_ac='" . $employeraccount . "',
    emp_ifsc='" . $employerifsc . "',
    emp_branch='" . $employerbranch . "',
    emp_gst='" . $employergst . "'
    WHERE employer_auto_create_id='" . $employer_auto_create_id . "'");
    $last_id = mysqli_insert_id($con);
  }


  if ($insert_employer == true) {

    // for ($i = 0; $i < count($relation_name); $i++) {
    //   $relation_name1[$i] = $relation_name[$i]->relation_name;
    //   $relation_function[$i] = $relation_name[$i]->relation_function;
    //   $relation_phn[$i] = $relation_name[$i]->relation_phn;
    //   $relation_mail[$i] = $relation_name[$i]->relation_mail;
    //   $assigned[$i] = $relation_name[$i]->assigned;
    //   $asigned_phn[$i] = $relation_name[$i]->asigned_phn;
    //   $assigned_mail[$i] = $relation_name[$i]->assigned_mail;


    // $insert_employerrelation = mysqli_query($con, "INSERT INTO tbl_employer_relation SET 
    // employer_id='" . $last_id . "',
    // employer_auto_create_id='" . $autoId . "',
    // relation_name='" . $relation_name1[$i] . "',
    // relation_function='" . $relation_function[$i] . "',
    // relation_phn='" . $relation_phn[$i] . "',
    // relation_mail='" . $relation_mail[$i] . "',
    // assigned='" . $assigned[$i] . "',
    // asigned_phn='" . $asigned_phn[$i] . "',
    // assigned_mail='" . $assigned_mail[$i] . "',
    // WHERE relation_id='" . $experience->relation_id . "'");



    // foreach ($relation_name as $relation) {
    //   $query = "UPDATE tbl_employer_relation SET 
    //   relation_name='" . $relation->relation_name . "',
    //   relation_function='" . $relation->relation_function . "',
    //   relation_phn='" . $relation->relation_phn . "',
    //   relation_mail='" . $relation->relation_mail . "',
    //   assigned='" . $relation->assigned . "',
    //   asigned_phn='" . $relation->asigned_phn . "',
    //   assigned_mail='" . $relation->assigned_mail . "'
    //    WHERE relation_id='" . $relation->relation_id . "'
    //    ";

    //   //  echo $query;
    //   //  exit();
    //   mysqli_query($con, $query);
    // }


    foreach ($relation_name as $relation) {
      if (isset($relation->relation_id) && !empty($relation->relation_id)) {
        $query = "UPDATE tbl_employer_relation SET 
                    relation_name='" . $relation->relation_name . "',
                    relation_function='" . $relation->relation_function . "',
                    relation_phn='" . $relation->relation_phn . "',
                    relation_mail='" . $relation->relation_mail . "',
                    assigned='" . $relation->assigned . "',
                    asigned_phn='" . $relation->asigned_phn . "',
                    assigned_mail='" . $relation->assigned_mail . "'
                    WHERE relation_id='" . $relation->relation_id . "'";
        mysqli_query($con, $query);
      } else {
        if (!empty($relation->relation_name)) {
          $query = "INSERT INTO tbl_employer_relation SET 
                        employer_id='" . $employerId . "',
                        employer_auto_create_id='" . $employer_auto_create_id . "',
                        relation_name='" . $relation->relation_name . "',
                        relation_function='" . $relation->relation_function . "',
                        relation_phn='" . $relation->relation_phn . "',
                        relation_mail='" . $relation->relation_mail . "',
                        assigned='" . $relation->assigned . "',
                        asigned_phn='" . $relation->asigned_phn . "',
                        assigned_mail='" . $relation->assigned_mail . "',
                        status=1";
          mysqli_query($con, $query);
        }
      }
    }
    foreach ($perkBenfit as $perk_Benfit) {
      if (isset($perk_Benfit->perk_benfit_id) && !empty($perk_Benfit->perk_benfit_id)) {
        $query = "UPDATE tbl_emp_perk_Benfit SET benfit_type='" . $perk_Benfit->select_benfit . "',benfit_content='" . $perk_Benfit->benfit_content . "' WHERE perk_benfit_id='" . $perk_Benfit->perk_benfit_id . "'";
        // echo $query;
        // exit();
        mysqli_query($con, query: $query);
      } else {
        $query = "INSERT INTO tbl_emp_perk_Benfit SET employer_id='" . $employerId . "',employer_auto_create_id='" . $employer_auto_create_id . "',benfit_type='" . $perk_Benfit->select_benfit . "',benfit_content='" . $perk_Benfit->benfit_content . "',status=1";
        mysqli_query($con, $query);
      }
    }

    $certificates = $data->certifictificates_array;
    foreach ($certificates as $certificate) {
      $certificateName = $certificate->certificateName;
      $certificateImage = $certificate->certificateImage;
      $certificateadmin = $certificate->certificateadmin;
      $image = '';
      $fileImage = '';

      if (isset($certificate->activity_id) && !empty($certificate->activity_id)) {
        if (!empty($certificateImage)) {
          $image_parts = explode(";base64,", $certificateImage);
          $mime_type = explode(":", $image_parts[0])[1]; // Get mime type from base64 string
          $getExt = explode('/', $mime_type)[1]; // Get the extension type (e.g., png, jpeg)
          $extension = 'png';
          $image_base64 = base64_decode($image_parts[1]);
          $fileImage = uniqid() . '.' . $extension;
          $file = $folderPath . $fileImage;
          $move = file_put_contents($file, $image_base64);
          $image = $fileImage;
        }
        $insert_employerActivities = mysqli_query($con, "UPDATE tbl_emp_activities SET 
        activity_title='" . $certificateName . "',
        activity_image='" . $image . "',
        activity_created_by='" . $certificateadmin . "'
        WHERE activity_id='" . $certificate->activity_id . "'
        ");
      } else {
        if (!empty($certificateImage)) {
          $image_parts = explode(";base64,", $certificateImage);
          $image_type_aux = explode("image/", $image_parts[0]);
          $image_base64 = base64_decode($image_parts[1]);
          $fileName = uniqid() . '.png';
          $file = $folderPath . $fileName;
          @unlink($folderPath . $oldImage);
          $move = file_put_contents($file, $image_base64);
        }
        $insert_employerActivities = mysqli_query($con, "INSERT INTO tbl_emp_activities SET 
          employer_id='" . $employerId . "',
          employer_auto_create_id='" . $employer_auto_create_id . "',
          activity_title='" . $certificateName . "',
          activity_image='" . $fileName . "',
          activity_created_by='" . $certificateadmin . "',
          status=1");
      }
    }





    $stat = ['message' => 'Employer Updated successfully',  'status' => true];
  } else {

    $stat = ['message' => ' Failed to Update', 'err' => mysqli_error($con), 'status' => false];
  }
}

echo json_encode($stat);
