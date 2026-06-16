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
include '../libs/mails.php';
include "../libs/jwt.php";
include '../notificationApis/notification_functions.php';


// ===== GET TOKEN SAFELY =====
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

$token = '';
if (!empty($authHeader)) {
    $token = str_replace('Bearer ', '', $authHeader);
}

// ===== TOKEN VALIDATION =====
if (empty($token)) {
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "Token missing"
    ]);
    exit;
}

try {
    $decoded = TokenVerify($token);   // make sure TokenVerify returns value and does NOT die()
} catch (Throwable $e) {              // catch all errors (important)
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "Invalid or Expired Token"
    ]);
    exit;
}


date_default_timezone_set('asia/kolkata');
$datetime = date("Y-m-d H:i:s");
$json = file_get_contents('php://input');
$folderPath = "uploads/employer-images/";

if (isset($json) && !empty($json)) {

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $stat = [];

    @$id = $data->id;
    @$job_id = $data->job_id;
    @$role_id = $data->role_id;
    @$jobtitle = $data->job_title;
    @$jobskill = $data->j_skills;
    @$jobrole = $data->job_role;
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
    @$created_by = $data->created_by;
    @$created_by_role = $data->created_by_role;
    @$postedByCompany = $data->postedByCompany;
    @$companyImage = $data->companyImage;
    @$employerMail = $data->employerMail;
    @$companyId = $data->companyID;
    @$employerId = $data->employerId;
    @$skilsImplode = '';
    @$created_at = $data->created_at;
    @$technologyName = implode(',', $data->technologyId);
    @$primarySkill = implode(',', $data->primary_skill);
    @$assignRole = $data->AssignedRole;
    @$assigned_id = $data->Assign_id;
    @$agentName = $data->agentName;
    @$project_name = $data->project_name;
    $agent = '';
    @$joblocation = $data->job_location;
    @$locationImplode = implode(',', $joblocation);
    @$prefix_code = $data->prefixCode;
    @$clientName = $data->clientName;
    @$client_mail = $data->mail;
    @$audio = $data->uploadAudio;
    @$audios = $data->audios;

    if (isset($id) && !empty($id)) {
        mysqli_query($con, "UPDATE tbl_post_a_job_draft SET status=1 where id='" . $id . "'");
    }
    date_default_timezone_set("Asia/Calcutta");
    $cronlastupdate = date("Y-m-d H:i:s");
    $job_status = 'New';
    $jobid = 1;

    $getDbCount = mysqli_query($con, "SELECT MAX(id) as max FROM `tbl_post_a_job` WHERE employer_id='" . @$employerId . "' AND status=1");
    $rows = mysqli_fetch_array($getDbCount);
    $incCount = @$rows == null ? $jobid : intval($rows['max']) + 1;

    $id = 'JH' . strtoupper(substr(str_replace(' ', '', $prefix_code), 0, 5)) . $incCount;

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

    $audioname = '';
    $fileName = '';
    $whereClause = '';
    $folderPath = "uploads/employer-images/";

    // Check for the `audios` field (Base64 format)
    if (!empty($data->audios) && strpos($data->audios, ';base64,') !== false) {
        // Process base64 audio data
        $audio_parts = explode(";base64,", $data->audios);
        if (isset($audio_parts[1])) {
            $audio_base64 = base64_decode($audio_parts[1]);
            $fileName = uniqid() . '.mp3'; // Generate a unique file name with .mp3 extension
            $file = $folderPath . $fileName;
            file_put_contents($file, $audio_base64); // Save the base64 audio as mp3
        }
    }

    // Check for `uploadAudio` field (either Base64 or a file name like "6711f540b30e3.mp3")
    if (!empty($data->uploadAudio)) {
        if (strpos($data->uploadAudio, ';base64,') !== false) {
            // Handle base64-encoded audio
            $audio_parts = explode(";base64,", $data->uploadAudio);
            if (isset($audio_parts[1])) {
                $audio_base64 = base64_decode($audio_parts[1]);
                $fileName = uniqid() . '.mp3'; // Generate a unique file name
                $file = $folderPath . $fileName;
                file_put_contents($file, $audio_base64); // Save the base64 audio as mp3
            }
        } else {
            // Handle a filename like "6711f540b30e3.mp3"
            $fileName = $data->uploadAudio; // Use the provided file name as-is
            $audioname = $fileName; // Set the audioname for database insertion
        }
    }

    // Set $audioname to the final fileName for database insertion
    $audioname = $fileName;

    // Example use of $audioname for database insertion
    if (!empty($audioname)) {
        $whereClause = $audioname;
    }


    if (!empty($jobtitle)) {
        $query = "INSERT INTO tbl_post_a_job SET
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
            cron_last_update='" . $cronlastupdate . "',
            project_name='" . $project_name . "',
            audio='" . $whereClause . "',
            job_status='" . $job_status . "',
            created_by='" . $created_by . "',
            created_by_role='" . $created_by_role . "',
            created_date='" . $datetime . "',
            $agent
            status=1";

        // echo $query;
        // exit();

        $insert_data = mysqli_query($con, $query);
        $last_id = mysqli_insert_id($con);

        $message = '';


        @$agentId = $data->job_agent;

        if (isset($data->job_agent) && !empty($data->job_agent)) {
            $agent = "assign_role=1,";
            $notifyTo = "Employer,Agent";

            $post_agent_data = mysqli_query($con, "INSERT INTO employer_agent_relation SET employer_id='" . $employerId . "',agent_id='" . $data->job_agent . "',assigned_by_id='" . $assigned_id . "',assigned_by_role='" . $assignRole . "',auto_create_jobid='" . $id . "',status=1");
            $body = 'job post successfull';
            if ($post_agent_data) {

                $message .= "Agent Assigned Successfully";
            } else {
                $message .= "Failed to Agent Assign";
            }

            $allowedAgentAlerts = getAgentAllowedNotify($agentId);

            if ($allowedAgentAlerts['status']) {

                $AgentNotData = $allowedAgentAlerts['data'];

                if ($AgentNotData['job_alerts'] == 1) {
                    $Message = "New JOB ID <" . $id . "> assigned successfully";

                    $q = "INSERT INTO tbl_notification SET notify_to='Agent',agent_id='" . $AgentNotData['user_id'] . "',activity='Job', notification_type='info',notification_text='" . $Message . "',jobId='" . $last_id . "',job_autoId='" . $id . "',status=1";

                    $addAgentNotifi = mysqli_query($con, $q);
                }
            }

            $agentData = mysqli_fetch_assoc(mysqli_query($con, "SELECT member_email as email FROM tbl_allyted_team where allyted_member_id ='" . $data->job_agent . "'"));

            $recruiterSubject = "JOB-POST CREATED BY EMPLOYER";
            $mailBody = " Hello,Agent and welcome to the Jaldihire family.
                        the Employer ( $postedByCompany ) posted and assigned to you please check";

            jobPstEmpMail($agentData['email'], $recruiterSubject, $mailBody);
            jobPstwithagent($client_mail, $id, $body);
        }

        $allowedAlerts = getAllowedNotifications($companyId);

        if ($insert_data) {

            if ($allowedAlerts['status']) {
                $notifyData = $allowedAlerts['data'];

                if ($notifyData['job_alerts'] == 1) {
                    $notificationMessage = "New JOB ID <" . $id . "> created successfully ";

                    $query = "INSERT INTO tbl_notification SET notify_to='Employer',employer_id='" . $companyId . "', activity='Job', notification_type='Success',notification_text='" . $notificationMessage . "',jobId='" . $last_id . "',job_autoId='" . $id . "'";

                    $addNotification = mysqli_query($con, $query);
                }
            }
        }
        if ($agentId == '') {
            $body = 'A new job posting has been created, but an advisor has not been assigned yet. An advisor will be assigned to your job posting shortly to assist you with your recruitment needs. Thank you for your patience.';
            jobPstwithoutagent($client_mail, $id, $body);
        }

        $stat = ['message' => 'job post Success!' . $message, 'status' => true, 'interid' => $last_id];
    } else {
        $stat = ['message' => 'missing params', 'status' => false];
    }
} else {
    $stat = ['message' => 'Something went wrong! Try Again', 'status' => false];
}
echo json_encode($stat);
