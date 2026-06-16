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

if (isset($json) && !empty($json)) {

  // variables 
  $p_location = '';

  $data = json_decode($json);
  @$applica_id = $data->id;

  // personal details fields 
  @$fullname = $data->full_name;
  @$primarymobile = $data->pri_mobile;
  @$secondarymobile = $data->sec_mobile;
  @$email = $data->e_mail;
  @$city = $data->app_city;
  @$main_role = $data->main_role;
  @$aadharNumber = $data->aadharNumber;
  @$createdRole = $data->role;

  // education details fields
  @$HighestDegree = $data->highest_degree;
  @$college = $data->college;
  @$percentage = $data->percentage;
  @$education_gap = $data->education_gap;
  @$education_gap_reason = $data->education_gap_reason;

  // professional skills fields
  @$technology = $data->technologyId;
  @$primary_skill = $data->pri_skill;
  @$primaryskills = $data->p_skills;
  @$skill_rating = $data->skill_rating;

  // certifications fields 
  @$awards = $data->awards;
  @$awardTwo = $data->awardTwo;
  @$awardThree = $data->awardThree;

  // experience fields 
  @$selectexperience = $data->experiences;
  @$implementaion = $data->implementaion;
  @$support = $data->support;
  @$client_facing = $data->client_facing;
  @$lead_projects = $data->lead_projects;
  @$releventexperience = $data->r_experience;

  // current company fields
  @$curcompany = $data->current_company;
  @$currole = $data->current_role;
  @$curctc = $data->current_Ctc;
  @$clocation = @$data->current_company_location;
  @$nperiod = $data->notice_period;
  @$available_from = @$data->available_from;

  // latest offer fields
  @$offeredcompany = @$data->latest_company_name;
  @$offerpackage = $data->offer_package;
  @$offerrole = $data->offer_role;
  @$offerlocation = $data->offer_location;
  @$offerjoining = $data->joining_date;
  @$attachments = $data->current_attachments;

  // totoal experience and experience array 
  @$total_expereience = @$data->experience_Count;
  @$experience_company = $data->exp_company;

  // candidate preferences fields
  @$interestedcompanies = $data->int_companies;
  @$excludedcompanies = $data->exc_companies;
  @$inc = implode(',', $interestedcompanies);
  @$exc = implode(',', $excludedcompanies);
  @$expectedsalary = $data->exp_salary;
  @$preferred_location = @$data->preferred_location;
  @$p_location = implode(',', $preferred_location);

  // social media url links fields
  @$addUrl = $data->others_profile;
  @$naukari = $data->naukri_url;
  @$instagram = $data->instagram_url;
  @$linkendin = $data->linkendin_url;

  // recruiter id if the applicant selected a recruiter 
  @$request = $data->recruiter_name;
  // variables to differentiaite that request is from applicant panel 
  @$external = '1';
  @$applicantRequest = $data->application_type;
  @$added_by_admin = $applicantRequest == 'user' ? '0' : '1';

  // variables to store the id and role of the applicant added by
  @$createdById = $data->created_by;
  @$createdRole = $data->role;


  // to generate appplicant auto create id 
  $autoId = 1;
  $getDbCount = mysqli_query($con, "SELECT MAX(applicant_id) as max FROM `tbl_applicant_add`");
  @$rows = mysqli_fetch_array($getDbCount);
  $incCount = $rows == null ? $autoId : intval($rows['max']) + 1;
  $id = 'JDAPP' . strtoupper(substr(str_replace(' ', '', $fullname), 0, 4)) . $incCount;


  // upload images functions for each image

  // profile
  $fileProfile = '';
  if (!empty($data->profile)) {
    $image_parts = explode(";base64,", $data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileProfile = uniqid() . '.png';
    $file = $folderPath . $fileProfile;
    $move = file_put_contents($file, $image_base64);
  }

  // resume
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

  // pancard
  $filePancard = '';
  if (!empty($data->pancard)) {
    $image_parts = explode(";base64,", $data->pancard);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $filePancard = uniqid() . '.png';
    $file = $folderPath . $filePancard;
    $move = file_put_contents($file, $image_base64);
  }

  // aadhar
  $fileAadharcard = '';
  if (!empty($data->aadharcard)) {
    $image_parts = explode(";base64,", $data->aadharcard);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileAadharcard = uniqid() . '.png';
    $file = $folderPath . $fileAadharcard;
    $move = file_put_contents($file, $image_base64);
  }

  // attachments 
  $fileAttachment = '';
  if (!empty($data->Attachments)) {
    $image_parts = explode(";base64,", $data->Attachments);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileAttachment = uniqid() . '.png';
    $file = $folderPath . $fileAttachment;
    $move = file_put_contents($file, $image_base64);
  }

  // Applicant image names 
  @$d_profile_image = $data->profile_image;
  @$d_resume_image = $data->resume_image;
  @$d_pan_card = $data->pan_card;
  @$d_aadhar_card = $data->aadhar_card;
  @$d_attachment = $data->Attachments;

  // where clause to add the stored image name to the query
  $whereClause = "";

  // profile
  if ($fileProfile != '') {
    $whereClause .= "profile_image='" . $fileProfile . "',";
  } else {
    $whereClause .= "profile_image='" . $d_profile_image . "',";
  }

  // resume
  if ($fileResume != '') {
    $whereClause .= "resume_image='" . $fileResume . "',";
  } else {
    $whereClause .= "resume_image='" . $d_resume_image . "',";
  }

  // pancard
  if ($filePancard != '') {
    $whereClause .= "pan_card='" . $filePancard . "',";
  } else {
    $whereClause .= "pan_card='" . $d_pan_card . "',";
  }

  // aadhar card
  if ($fileAadharcard != '') {
    $whereClause .= "aadhar_card='" . $fileAadharcard . "',";
  } else {
    $whereClause .= "aadhar_card='" . $d_aadhar_card . "',";
  }

  // attachments
  if ($fileAttachment != '') {
    $whereClause .= "attachment_appli='" . $fileAttachment . "',";
  } else {
    $whereClause .= "attachment_appli='" . $d_attachment . "',";
  }

  if (isset($createdById) && !empty($createdById)) {
    $whereClause .= "created_by_id='" . $createdById . "',";
  }

  if (isset($createdRole) && !empty($createdRole)) {
    $whereClause .= "created_by_role='" . $createdRole . "',";
  }

  // echo $whereClause;
  // exit;

  // email validation to restrict duplicant emails 
  $getApplicant;
  if (empty($email)) {
    echo json_encode(["message" => "Email Should Not Be Empty", 'status' => false]);
    exit();
  }

  $getApplicant = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE e_mail='" . $email . "' && status=1");

  if (mysqli_num_rows($getApplicant) > 0) {
    $stat = ["message" => "Email Already Exists", 'status' => false];
    echo json_encode($stat);
    exit();
  }

  // To store all the primary skills into one variable from array
  $skillArray = [];

  for ($i = 0; $i < count($primaryskills); $i++) {
    array_push($skillArray, $primaryskills[$i]->p_skill,);
    $skill[$i] = $primaryskills[$i]->p_skill;
    $skill_rating[$i] = $primaryskills[$i]->skill_rating;
  }

  $ListprimarySkills = implode(',', $skillArray);


  // To store all the others profile urls in one variable from array
  $urlArray = [];
  for ($i = 0; $i < count($addUrl); $i++) {
    array_push($urlArray, $addUrl[$i]->source,);
    $addUrls[$i] = $addUrl[$i]->source;
  }
  $impUrls = implode(',', $addUrls);

  // query to insert data in tbl_applicant_add 

  $q = "INSERT INTO tbl_applicant_add SET 
  $whereClause
  applicant_auto_create_id='" . $id . "',
  full_name='" . $fullname . "',
  mobile_number='" . $primarymobile . "',
  secondary_mobile='" . $secondarymobile . "',
  e_mail='" . $email . "',
  aadhar_number='" . $aadharNumber . "',
  primary_skills='" . $ListprimarySkills . "', 
  external = '" . $external . "',
  request_to='" . $request . "',
  app_city='" . $city . "',
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
  attachments='" . $attachments . "',
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
  addUrls='" . $impUrls . "',
  status=0";

  // echo $q;
  // exit();

  $insert_applicant = mysqli_query($con, $q);
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
      $query = "INSERT INTO applicant_experiences SET 
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
      status=1";
      mysqli_query($con, $query);
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

  for ($i = 0; $i < count($addUrl); $i++) {
    $addUrls[$i] = $addUrl[$i]->source;
    $urlName[$i] = $addUrl[$i]->name;

    if ($addUrls) {
      $insert_applicant_urls = mysqli_query($con, "INSERT INTO applicant_urls SET 
      applicant_id='" . $last_id . "',
      applicant_auto_create_id='" . $id . "',
      url_link='" . $addUrls[$i] . "',
      url_name='" . $urlName[$i] . "',
      status=1");
    }
  }

  // insert certifications to certifications table
  $certificates = $data->certificates_array;
  for ($i = 0; $i < count($certificates); $i++) {
    $c_name[$i] = $certificates[$i]->certificateName;
    $c_image[$i] = $certificates[$i]->certificateImage;
    $image = '';
    $fileImage = '';

    if (isset($c_name[$i]) && !empty($c_name[$i])) {
      if (!empty($c_image[$i])) {
        $getExt = explode('/', mime_content_type($c_image[$i]))[1];
        $extension = $getExt == 'pdf' ? 'pdf' : 'docx';
        $image_parts = explode(";base64,", $c_image[$i]);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileImage = uniqid() . '.' . $extension;
        $file = $folderPath . $fileImage;
        $move = file_put_contents($file, $image_base64);
      }

      if ($fileImage != '') {
        $image .= "certificate_image='" . $fileImage . "',";
      }

      $q = "INSERT INTO applicant_certificates SET
      applicant_id='" . $last_id . "',
      applicant_auto_create_id='" . $id . "',
      certificate_name='" . $c_name[$i] . "',
      $image
      status=1
    ";

      mysqli_query($con, $q);
    }
  }

  if ($insert_applicant && $applicantRequest == 'user') {
    $stat = ['status' => true, 'message' => 'your application has been submitted successfully...! our team will get back to you soon'];
    echo json_encode($stat);
    exit();
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong try again'];
}

echo json_encode($stat);
