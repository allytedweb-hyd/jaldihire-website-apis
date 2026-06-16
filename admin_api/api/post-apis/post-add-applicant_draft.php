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
$folderPath = "uploads/applicant-images/";

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {

  // VARIABLES TO STORE ARRAY DATA AS STRING 
  $inc = '';
  $exc = '';
  $technology = '';
  $primary_skill = '';


  $data = json_decode($json);
  // draft id for updating existing draft 
  @$draftId = $data->draft_id;

  @$applicant_id = $data->applicant_id;
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

  // to generate applicant auto create id
  $getDbCount = mysqli_query($con, "SELECT * FROM `tbl_applicant_add` ORDER BY applicant_id DESC limit 1");
  @$rows = mysqli_fetch_array($getDbCount);
  @$incCount = $rows['applicant_id'] + 1;

  @$id = 'JDAPP' . strtoupper(substr(str_replace(' ', '', $fullname), 0, 4)) . $incCount;

  @$secondaryskills = $data->s_skills;
  @$awards = $data->awards;
  @$awards = $data->awardOne;
  @$awardTwo = $data->awardTwo;
  @$awardThree = $data->awardThree;
  @$offeredcompany = $data->latest_company_name;
  @$offerpackage = $data->offer_package;
  @$offerrole = $data->offer_role;
  @$offerlocation = $data->offer_location;
  @$offerjoining = $data->joining_date;
  @$attachments = $data->current_attachments;
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
  if (is_array($interestedcompanies)) {
    @$inc = implode(',', $interestedcompanies);
  }
  if (is_array($excludedcompanies)) {
    @$exc = implode(',', $excludedcompanies);
  }
  @$expectedsalary = $data->exp_salary;
  @$preferred_location = $data->preferred_location;
  if (is_array($preferred_location)) {
    @$p_location = implode(',', $preferred_location);
  } else {
    @$p_location = $preferred_location;
  }
  if (is_array($data->technologyId)) {
    @$technology = implode(',', $data->technologyId);
  }
  if (is_array($data->pri_skill)) {
    @$primary_skill = implode($data->pri_skill);
  }
  @$main_role = $data->main_role;
  @$naukari = $data->naukri_url;
  @$instagram = $data->instagram_url;
  @$linkendin = $data->linkendin_url;


  // images upload code 
  $fileProfile = '';
  if (!empty(@$data->profile)) {
    $image_parts = explode(";base64,", @$data->profile);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileProfile = uniqid() . '.png';
    $file = $folderPath . $fileProfile;
    $move = file_put_contents($file, $image_base64);
  }

  $fileResume = '';
  if (!empty(@$data->resume)) {
    $getExt = explode('/', mime_content_type(@$data->resume))[1];
    $extension = $getExt == 'pdf' ? 'pdf' : 'docx';

    $image_parts = explode(";base64,", @$data->resume);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileResume = uniqid() . '.' . $extension;
    $file = $folderPath . $fileResume;
    $move = file_put_contents($file, $image_base64);
  }

  $filePancard = '';
  if (!empty(@$data->pancard)) {
    $image_parts = explode(";base64,", @$data->pancard);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $filePancard = uniqid() . '.png';
    $file = $folderPath . $filePancard;
    $move = file_put_contents($file, $image_base64);
  }

  $fileAadharcard = '';
  if (!empty(@$data->aadharcard)) {
    $image_parts = explode(";base64,", @$data->aadharcard);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileAadharcard = uniqid() . '.png';
    $file = $folderPath . $fileAadharcard;
    $move = file_put_contents($file, $image_base64);
  }

  $fileAttachment = '';
  if (!empty(@$data->Attachments)) {
    $image_parts = explode(";base64,", @$data->Attachments);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_base64 = base64_decode($image_parts[1]);
    $fileAttachment = uniqid() . '.png';
    $file = $folderPath . $fileAttachment;
    $move = file_put_contents($file, $image_base64);
  }

  @$d_profile_image = $data->profile_image;
  @$d_resume_image = $data->resume_image;
  @$d_pan_card = $data->pan_card;
  @$d_aadhar_card = $data->aadhar_card;
  @$d_attachment = $data->Attachments;

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

  @$added_by_admin = $applicantRequest == 'user' ? '0' : '1';

  // email validation code
  // $getApplicant;
  // if (!empty($email)) {
  //   $getApplicant = mysqli_query($con, "select * from tbl_applicant_add_draft where e_mail='" . $email . "'");
  // } else {
  //   echo json_encode(["message" => "Email Should Not Be Empty", 'status' => false]);
  //   exit();
  // }
  // if (mysqli_num_rows($getApplicant) > 0) {
  //   $stat = ["message" => "Email Already Exists", 'status' => false];
  //   echo json_encode($stat);
  //   exit();
  // }


  // implode skills and storing in a variable
  $skillArray = [];
  for ($i = 0; $i < count($primaryskills); $i++) {
    array_push($skillArray, $primaryskills[$i]->p_skill,);
    $skill[$i] = $primaryskills[$i]->p_skill;
    $skill_rating[$i] = $primaryskills[$i]->skill_rating;
  }
  $ListprimarySkills = implode(',', $skillArray);

  if (isset($draftId) && !empty($draftId)) {

    $query = "UPDATE tbl_applicant_add_draft SET
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
      status=0 WHERE 
      applicant_id='" . $draftId . "'
      AND status=0";
  } else {
    // applicant data insert query
    $query = "INSERT INTO tbl_applicant_add_draft SET 
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
      status=0";
  }


  // echo $query;
  // exit();

  $insert_applicant = mysqli_query($con, $query);
  $last_id = mysqli_insert_id($con);

  // experience insertion to experience table
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
    @$exp_id[$i] = $experience_company[$i]->experiences_id;

    if (isset($exp[$i]) && !empty($exp[$i])) {
      if ($exp) {
        if (isset($exp[$i]) && !empty($exp[$i])) {
          mysqli_query($con, "UPDATE applicant_experiences_draft SET 
          exp_company='" . $exp[$i] . "',
          experience_role='" . $experience_role[$i] . "',
          experience_primary_skill='" . $job_primary_skill[$i] . "',
          experience_job_type='" . $job_type[$i] . "',
          starting_date='" . $start_date[$i] . "',
          ending_date='" . $end_date[$i] . "',
          starting_salary='" . $start_salary[$i] . "',
          ending_salary='" . $ending_salary[$i] . "',
          reason='" . $reason[$i] . "',
          status=0 WHERE experiences_id='" . $exp_id[$i] . "' AND status=0
          ");
        } else {
          mysqli_query(
            $con,
            "INSERT INTO applicant_experiences_draft SET 
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
            status=0"
          );
        }
      }
    }
  }

  // skills insertion to skills table
  for ($i = 0; $i < count($primaryskills); $i++) {
    $skill[$i] = $primaryskills[$i]->p_skill;
    $skill_rating[$i] = $primaryskills[$i]->skill_rating;
    @$skill_id[$i] = $primaryskills[$i]->skill_id;

    if (!empty($skill[$i]) && !empty($skill_rating[$i])) {
      if (isset($skill_id[$i]) && !empty($skill_id[$i])) {
        mysqli_query($con, "UPDATE applicant_skill_draft SET 
          pri_skill='" . $skill[$i] . "',
          skill_rating='" . $skill_rating[$i] . "',
          status=0 WHERE skill_id='" . $skill_id[$i] . "' AND status=0
          ");
      } else {
        mysqli_query($con, "INSERT INTO applicant_skill_draft SET 
          applicant_id='" . $last_id . "',
          applicant_auto_create_id='" . $id . "',
          pri_skill='" . $skill[$i] . "',
          skill_rating='" . $skill_rating[$i] . "',
          status=0");
      }
    }
  }

  // certificates insertion into another table 
  $certificates = $data->certifictificates_array;
  if (isset($certificates) && !empty($certificates)) {
    for ($i = 0; $i < count($certificates); $i++) {
      if (!empty($certificates[$i]->certificateName) && !empty($certificates[$i]->certificateImage)) {
        $c_name[$i] = $certificates[$i]->certificateName;
        $c_image[$i] = $certificates[$i]->certificateImage;
        $image_name = $certificates[$i]->c_Image;
        @$certificat_id[$i] = $certificates[$i]->certificate_id;
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
          } else {
            $image .= "certificate_image='" . $image_name . "',";
          }

          if (isset($certificat_id[$i]) && !empty($certificat_id[$i])) {
            mysqli_query($con, "UPDATE applicant_certificates_drafts SET 
            certificate_name='" . $c_name[$i] . "',
            $image
            status=0 WHERE certificate_id='" . $certificat_id[$i] . "' AND status=0
            ");
          } else {
            mysqli_query($con, "INSERT INTO applicant_certificates_drafts SET
              applicant_id='" . $last_id . "',
              applicant_auto_create_id='" . $id . "',
              certificate_name='" . $c_name[$i] . "',
              $image
              status=0
            ");
          }
        }
      }
    }
  }

  //API response code
  if ($insert_applicant == true) {
    $stat = ['message' => 'Saved to Draft successfully', 'status' => true];
  } else {
    $stat = ['message' => 'Failed! to Draft', 'status' => false];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong try again'];
}

echo json_encode($stat);
