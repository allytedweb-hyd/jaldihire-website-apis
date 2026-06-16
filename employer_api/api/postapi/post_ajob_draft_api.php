<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
include "../vendor/autoload.php";
include "../../includes/db-connection/db.php";

$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {

  $stat = [];
  // variables to implode data from array
  @$skilsImplode = '';
  @$technologyName = '';
  @$primarySkill = '';
  @$locationImplode = '';

  // job data variables
  $data = json_decode($json);
  // id if draft if updating
  @$draftId = $data->draft_id;

  @$job_id = $data->job_id;
  @$role_id = $data->role_id;
  @$jobtitle = $data->job_title;
  @$jobskill = $data->j_skills;
  @$jobrole = $data->job_role;
  @$joblocation = $data->job_location;
  if (is_array($joblocation)) {
    @$locationImplode = implode(',', @$joblocation);
  }
  @$jobduration = $data->job_duration;
  @$jobprice = $data->job_price;
  @$jobboardingtime = $data->job_bordingtime;
  @$jobworkmode = $data->job_workmode;
  @$job_type = $data->job_type;
  @$Project_Type = $data->Project_Type;
  @$Industry_Type = $data->Industry_Type;
  @$jobdescription = $data->job_description;
  @$jobpositions = $data->job_positions;
  @$jobexperience = $data->job_experience;
  @$job_qualification = $data->job_qualification;
  @$Availability = $data->Availability;
  @$postedByCompany = $data->postedByCompany;
  @$companyImage = $data->companyImage;
  @$companyId = $data->companyID;
  @$employerId = $data->employerId;
  @$created_at = $data->created_at;
  if (is_array($data->technologyId)) {
    @$technologyName = implode(',', $data->technologyId);
  }
  if (is_array($data->primary_skill)) {
    @$primarySkill = implode(',', $data->primary_skill);
  }
  @$assignRole = $data->AssignedRole;
  @$assigned_id = $data->Assign_id;
  @$createdById = $data->created_by;
  @$createdByRole = $data->created_by_role;
  @$project_name = $data->project_name;
  @$agentId = $data->job_agent;
  @$prefix_code = $data->prefixCode;
  @$clientName = $data->clientName;
  @$audios = $data->audios;
  @$jobid = 1;
  @$id = '';
  $folderPath = "uploads/employer-images/";

  @$getDbCount = mysqli_query($con, "SELECT MAX(id) as max  FROM `tbl_post_a_job` WHERE employer_id='" . $employerId . "' AND status=1");
  @$rows = mysqli_fetch_array($getDbCount);
  @$incCount = @$rows == null ? $jobid : intval($rows['max']) + 1;

  if (!empty($prefix_code)) {
    $id = 'JH' . strtoupper(substr(str_replace(' ', '', @$prefix_code), 0, 5)) . @$incCount;
  }

  if (is_array($jobskill)) {
    if (count($jobskill) > 0) {
      for ($i = 0; $i < count($jobskill); $i++) {
        $item = $jobskill[$i];
        if (intval(count($jobskill) - 1) == $i) {
          $skilsImplode .= $item->item_text;
        } else {
          $skilsImplode .= $item->item_text . ',';
        }
      }
    }
  }

  if (isset($draftId) && !empty($draftId)) {
    $audioname = '';
    $fileName = '';
    $folderPath = "uploads/employer-images/";

    if (!empty($data->audios) && strpos($data->audios, ';base64,') !== false) {
      $audio_parts = explode(";base64,", $data->audios);
      if (isset($audio_parts[1])) {
        $audio_base64 = base64_decode($audio_parts[1]);
        $fileName = uniqid() . '.mp3';
        $file = $folderPath . $fileName;
        file_put_contents($file, $audio_base64);
      }
    }

    if (!empty($data->uploadAudio)) {
      if (strpos($data->uploadAudio, ';base64,') !== false) {
        // Handle base64-encoded audio
        $audio_parts = explode(";base64,", $data->uploadAudio);
        if (isset($audio_parts[1])) {
          $audio_base64 = base64_decode($audio_parts[1]);
          $fileName = uniqid() . '.mp3';
          $file = $folderPath . $fileName;
          file_put_contents($file, $audio_base64);
        }
      } else {
        $fileName = $data->uploadAudio;
        $audioname = $fileName;
      }
    }

    $audioname = $fileName;

    if (!empty($audioname)) {
      $whereClause = $audioname;
    }


    // query to update the existing draft 
    $query = "UPDATE tbl_post_a_job_draft SET
    auto_create_jobid='" . $id . "',
    role_id='" . $role_id . "',
    job_title='" . $jobtitle . "',
    skill='" . $skilsImplode . "',
    role='" . $jobrole . "',
    job_location='" . $locationImplode . "',
    duration='" . $jobduration . "',
    price='" . $jobprice . "',
    on_boarding_time='" . $jobboardingtime . "',
    work_mode='" . $jobworkmode . "',
    clientName='" . $clientName . "',
    job_type='" . $job_type . "',
    project_Type='" . $Project_Type . "',
    industry_Type='" . $Industry_Type . "',
    job_description='" . $jobdescription . "',
    no_of_positions='" . $jobpositions . "',
    qualification='" . $job_qualification . "',
    experience='" . $jobexperience . "',
    availability='" . $Availability . "',
    posted_by_company='" . $postedByCompany . "',
    company_image='" . $companyImage . "',
    company_Id='" . $companyId . "',
    employer_id='" . $employerId . "',
    technology='" . $technologyName . "',
    primary_skill='" . $primarySkill . "',
    created_by_id='" . $createdById . "',
    created_by_role='" . $createdByRole . "',
    project_name='" . $project_name . "',
    agent_id='" . $agentId . "',
    audio='" . $whereClause . "'
    WHERE id='" . $draftId . "' && status=0";
  } else {
    $audioname = '';
    $fileName = '';

    if (!empty($audios)) {
      if (strpos($data->audios, ';base64,') !== false) {
        $audio_parts = explode(";base64,", $data->audios);
        if (isset($audio_parts[1])) {
          $audio_base64 = base64_decode($audio_parts[1]);
          $fileName = uniqid() . '.mp3';
          $file = $folderPath . $fileName;
          file_put_contents($file, $audio_base64);
        }
      }
    }

    if (!empty($audio)) {
      if (strpos($data->uploadAudio, ';base64,') !== false) {
        $audio_parts = explode(";base64,", $data->uploadAudio);
        if (isset($audio_parts[1])) {
          $audio_base64 = base64_decode($audio_parts[1]);
          $fileName = uniqid() . '.mp3';
          $file = $folderPath . $fileName;
          file_put_contents($file, $audio_base64);
        }
      }
    }

    $audioname = $fileName;


    if (isset($audio) || isset($audios)) {
      if (!empty($audio)) {
        $whereClause = $audioname;
      } else {
        $whereClause = $audioname;
      }
    }
    // query to Insert the new draft
    $query = "INSERT INTO tbl_post_a_job_draft SET 
    auto_create_jobid='" . $id . "',
    role_id='" . $role_id . "',
    job_title='" . $jobtitle . "',
    skill='" . $skilsImplode . "',
    role='" . $jobrole . "',
    job_location='" . $locationImplode . "',
    duration='" . $jobduration . "',
    price='" . $jobprice . "',
    on_boarding_time='" . $jobboardingtime . "',
    work_mode='" . $jobworkmode . "',
    job_type='" . $job_type . "',
    project_Type='" . $Project_Type . "',
    industry_Type='" . $Industry_Type . "',
    job_description='" . $jobdescription . "',
    no_of_positions='" . $jobpositions . "',
    qualification='" . $job_qualification . "',
    experience='" . $jobexperience . "',
    availability='" . $Availability . "',
    posted_by_company='" . $postedByCompany . "',
    company_image='" . $companyImage . "',
    company_Id='" . $companyId . "',
    employer_id='" . $employerId . "',
    clientName='" . $clientName . "',
    technology='" . $technologyName . "',
    primary_skill='" . $primarySkill . "',
    created_by_id='" . $createdById . "',
    created_by_role='" . $createdByRole . "',
    project_name='" . $project_name . "',
    agent_id='" . $agentId . "',
    audio='" . $whereClause . "',
    status=0";
  }
  // echo $query;
  // exit();

  $insert_data = mysqli_query($con, $query);

  if ($insert_data) {
    $stat = ['message' => 'Job Saved Successfully!', 'status' => true];
  } else {
    $stat = ['message' => 'Failed! To Save', 'status' => false];
  }
} else {
  $stat = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
