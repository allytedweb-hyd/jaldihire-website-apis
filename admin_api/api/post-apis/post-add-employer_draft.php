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
$folderPath = "uploads/employer-images/";

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {

  $data = json_decode($json);
  @$draftId = $data->draft_id;
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
  @$createdBy = $data->created_by;
  @$employergst = $data->emp_gst;
  @$id = 1;
  @$oldImage = $data->oldImage;

  // to generate employer Id for new draft employer
  if (!isset($draft) && empty($draft)) {
    $getDbCount = mysqli_query($con, "SELECT MAX(employer_id) as max FROM `tbl_add_employer`");
    $rows = mysqli_fetch_array($getDbCount);
    $incCount = $rows == null ? $id : intval($rows['max']) + 1;
    $autoId = 'JDAPP' . strtoupper(substr(str_replace(' ', '', $companyname), 0, 4)) . $incCount;
  }

  // image upload code
  $fileName = '';
  if (!empty(@$data->profile)) {
    $image_parts = explode(";base64,", $data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
    $file = $folderPath . $fileName;
    @$move = file_put_contents($file, $image_base64);
    @unlink($folderPath . $oldImage);
  }

  $query = '';

  if (isset($draftId) && !empty($draftId)) {
    // query to save the existing draft
    $query = "UPDATE tbl_add_employer_draft SET 
    company_name='" . $companyname . "',
    emp_mob='" . $employermobile . "',
    emp_email='" . $email . "',
    emp_img='" . $fileName . "',
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
    emp_gst='" . $employergst . "',
    status=0 WHERE employer_id='" . $draftId . "' AND status=0";;
  } else {
    // query to create new draft
    $query = "INSERT INTO tbl_add_employer_draft SET 
    company_name='" . $companyname . "',
    employer_auto_create_id='" . $autoId . "',
    emp_mob='" . $employermobile . "',
    emp_email='" . $email . "',
    emp_img='" . $fileName . "',
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
    emp_gst='" . $employergst . "',
    status=0";
  }

  $insert_employer = mysqli_query($con, $query);
  $last_id = mysqli_insert_id($con);

  // employer relations code 
  @$relation_name = [];
  if (isset($data->relation_name)) {
    $relation_name = $data->relation_name;
  }
  if ($insert_employer) {
    for ($i = 0; $i < count($relation_name); $i++) {
      @$relation_id[$i] = $relation_name[$i]->relation_id;
      @$relation_name1[$i] = $relation_name[$i]->relation_name;
      @$relation_function[$i] = $relation_name[$i]->relation_function;
      @$relation_phn[$i] = $relation_name[$i]->relation_phn;
      @$relation_mail[$i] = $relation_name[$i]->relation_mail;
      @$assigned[$i] = $relation_name[$i]->assigned;
      @$asigned_phn[$i] = $relation_name[$i]->asigned_phn;
      @$assigned_mail[$i] = $relation_name[$i]->assigned_mail;

      $empId = $last_id == 0 ? $draftId : $last_id;

      if (isset($relation_id[$i]) && !empty($relation_id[$i])) {
        // query to update existing relations
        $q = "UPDATE tbl_employer_relation_draft SET
        relation_name='" . $relation_name1[$i] . "',
        relation_function='" . $relation_function[$i] . "',
        relation_phn='" . $relation_phn[$i] . "',
        relation_mail='" . $relation_mail[$i] . "',
        assigned='" . $assigned[$i] . "',
        asigned_phn='" . $asigned_phn[$i] . "',
        assigned_mail='" . $assigned_mail[$i] . "',
        status=0 WHERE relation_id='" . $relation_id[$i] . "'";
      } else {
        // query to insert new relations
        $q = "INSERT INTO tbl_employer_relation_draft SET 
        employer_id='" . $empId . "',
        employer_auto_create_id='" . $autoId . "',
        relation_name='" . $relation_name1[$i] . "',
        relation_function='" . $relation_function[$i] . "',
        relation_phn='" . $relation_phn[$i] . "',
        relation_mail='" . $relation_mail[$i] . "',
        assigned='" . $assigned[$i] . "',
        asigned_phn='" . $asigned_phn[$i] . "',
        assigned_mail='" . $assigned_mail[$i] . "',
        status=0";
      }
      mysqli_query($con, $q);
    }
    $stat = ['message' => 'Saved to Draft successfully', 'status' => true];
  } else {
    $stat = ['message' => ' Failed to Draft', 'status' => false];
  }
} else {
  $stat = ['status' => true, 'message' => 'Something Went wrong! please try Again'];
}
echo json_encode($stat);
