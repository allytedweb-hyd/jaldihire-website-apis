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
    if (!isset($data->auto_id) || empty($data->auto_id)) {
        $stat = ["message" => "Invalid Request", 'status' => false];
        echo json_encode($stat);
        exit();
    }
    $applicant_auto_create_id = $data->auto_id;
    @$applicant_id = $data->applicant_id;
    @$youtubeUrl = $data->youtube_url;
    @$fullname = $data->full_name;
    @$primarymobile = $data->pri_mobile;
    @$secondarymobile = $data->sec_mobile;
    @$email = $data->e_mail;
    @$city = $data->app_city;
    @$primaryskills = $data->p_skills;
    @$skill_rating = $data->skill_rating;
    @$secondaryskills = $data->s_skills;
    @$awards = $data->awards;
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
    @$inc = implode(',', $interestedcompanies);
    @$exc = implode(',', $excludedcompanies);
    @$expectedsalary = $data->exp_salary;
    @$preferred_location = implode(',', $data->preferred_location);
    @$main_role = $data->main_role;
    @$aadharNumber = $data->aadharNumber;
    @$college = $data->college;
    @$HighestDegree = $data->highest_degree;
    @$percentage = $data->percentage;
    @$education_gap = $data->education_gap;
    @$education_gap_reason = $data->education_gap_reason;
    @$naukari = $data->naukri_url;
    @$instagram = $data->instagram_url;
    @$linkendin = $data->linkendin_url;
    @$oldProfileImg = $data->oldProfileImg;
    @$oldResume = $data->oldResume;
    @$oldpan_card = $data->oldpan_card;
    @$oldAadhar_card = $data->oldAadhar_card;
    @$oldAttachmentAppli = $data->oldAttachmentAppli;
    @$oldAttachments = $data->oldAttachments;


    @$technology = implode(',', $data->technologyId);
    @$primary_skill = implode(',', $data->pri_skill);

    // upload images in applicant submit
    $fileProfile = '';
    if (!empty($data->profile)) {
        $image_parts = explode(";base64,", $data->profile);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileProfile = uniqid() . '.png';
        $file = $folderPath . $fileProfile;
        @$move = file_put_contents($file, $image_base64);
        @unlink($folderPath . $oldProfileImg);
    }

    $fileResume = '';
    if (!empty($data->resume)) {
        $getExt = explode('/', mime_content_type($data->resume))[1];
        $extension = $getExt == 'pdf' ? 'pdf' : 'docx';

        $image_parts = explode(";base64,", $data->resume);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileResume = uniqid() . '.pdf';
        $file = $folderPath . $fileResume;
        @$move = file_put_contents($file, $image_base64);
        @unlink($folderPath . $oldResume);
    }

    $filePancard = '';
    if (!empty($data->pancard)) {
        $image_parts = explode(";base64,", $data->pancard);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $filePancard = uniqid() . '.png';
        $file = $folderPath . $filePancard;
        @$move = file_put_contents($file, $image_base64);
        @unlink($folderPath . $oldpan_card);
    }

    $fileAadharcard = '';
    if (!empty($data->aadharcard)) {
        $image_parts = explode(";base64,", $data->aadharcard);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileAadharcard = uniqid() . '.png';
        $file = $folderPath . $fileAadharcard;
        @$move = file_put_contents($file, $image_base64);
        @unlink($folderPath . $oldAadhar_card);
    }

    $fileAttachment = '';
    if (!empty($data->Attachments)) {
        $image_parts = explode(";base64,", $data->Attachments);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileAttachment = uniqid() . '.png';
        $file = $folderPath . $fileAttachment;
        @$move = file_put_contents($file, $image_base64);
        @unlink($folderPath . $oldAttachmentAppli);
    }

    $fileCurrAttachments = '';
    if (!empty($data->current_attachments)) {
        $image_parts = explode(";base64,", $data->current_attachments);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileAttachment = uniqid() . '.png';
        $file = $folderPath . $fileCurrAttachments;
        @$move = file_put_contents($file, $image_base64);
        @unlink($folderPath . $oldAttachments);
    }

    @$d_profile_image = $data->profile_image;
    @$d_resume_image = $data->resume_image;
    @$d_pan_card = $data->pan_card;
    @$d_aadhar_card = $data->aadhar_card;
    @$d_attachment = $data->Attachments;
    @$d_CurrAttachments = $data->current_attachments;

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
    if (isset($youtubeUrl) && !empty($youtubeUrl)) {
        $whereClause .= "youtube_urls='" . $youtubeUrl . "',";
    }
    if ($fileAttachment != '') {
        $whereClause .= "attachment_appli='" . $fileAttachment . "',";
    } else {
        $whereClause .= "attachment_appli='" . $d_attachment . "',";
    }
    if ($fileCurrAttachments != '') {
        $whereClause .= "attachments='" . $fileCurrAttachments . "',";
    } else {
        $whereClause .= "attachments='" . $d_CurrAttachments . "',";
    }

    // echo $whereClause;
    // exit;

    @$applicantRequest = $data->application_type;
    @$added_by_admin = $applicantRequest == 'user' ? '0' : '1';
    $getApplicant = 0;

    if (!empty($email)) {
        $getApplicant = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE e_mail='" . $email . "' and applicant_auto_create_id != '" . $applicant_auto_create_id . "'");
    } else {
        echo json_encode(["message" => "Email Should Not Be Empty", 'status' => false]);
        exit();
    }

    // if (mysqli_num_rows($getApplicant) > 0) {
    //     $stat = ["message" => "Email Already Exists", 'status' => false];
    //     echo json_encode($stat);
    //     exit();
    // }

    $skillArray = [];

    for ($i = 0; $i < count($primaryskills); $i++) {
        array_push($skillArray, $primaryskills[$i]->p_skill,);
        $skill[$i] = $primaryskills[$i]->p_skill;
        $skill_rating[$i] = $primaryskills[$i]->skill_rating;
    }

    $ListprimarySkills = implode(',', $skillArray);

    $query = "UPDATE tbl_applicant_add SET
$whereClause
primary_skills='" . $ListprimarySkills . "',
full_name='" . $fullname . "',
mobile_number='" . $primarymobile . "',
secondary_mobile='" . $secondarymobile . "',
e_mail='" . $email . "',
aadhar_number='" . $aadharNumber . "',
app_city='" . $city . "',
certifications_images='0',
awards_recognization='" . $awards . "',
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
preferred_location='" . $preferred_location . "',
added_by_admin='" . $added_by_admin . "',
main_role='" . $main_role . "',
technology='" . $technology . "',
main_skill='" . $primary_skill . "' ,
linkendin_url='" . $linkendin . "',
instagram_url='" . $instagram . "',
naukari_url='" . $naukari . "'
WHERE applicant_auto_create_id='" . $applicant_auto_create_id . "'";

    $insert_applicant = mysqli_query($con, $query);

    foreach ($experience_company as $experience) {
        if (isset($experience->exp_company) && !empty($experience->exp_company)) {
            if (isset($experience->experiences_id) && !empty($experience->experiences_id)) {
                mysqli_query(
                    $con,
                    "UPDATE applicant_experiences SET
exp_company='" . $experience->exp_company . "',
experience_role='" . $experience->exp_role . "',
experience_primary_skill='" . $experience->primary_skill . "',
experience_job_type='" . $experience->job_type . "',
starting_date='" . $experience->start_date . "',
ending_date='" . $experience->end_date . "',
starting_salary='" . $experience->starting_slry . "',
ending_salary='" . $experience->ending_slry . "',
reason='" . $experience->reason . "'
WHERE experiences_id='" . $experience->experiences_id . "'
"
                );
            } else {
                mysqli_query(
                    $con,
                    "INSERT INTO applicant_experiences SET
applicant_id='" . $applicant_id . "',
applicant_auto_create_id='" . $applicant_auto_create_id . "',
exp_company='" . $experience->exp_company . "',
experience_role='" . $experience->exp_role . "',
experience_primary_skill='" . $experience->primary_skill . "',
experience_job_type='" . $experience->job_type . "',
starting_date='" . $experience->start_date . "',
ending_date='" . $experience->end_date . "',
starting_salary='" . $experience->starting_slry . "',
ending_salary='" . $experience->ending_slry . "',
reason='" . $experience->reason . "',
status=1
"
                );
            }
        }
    }

    foreach ($primaryskills as $skill) {
        if (isset($skill->id) && !empty($skill->id)) {
            mysqli_query($con, "UPDATE applicant_skill SET
pri_skill='" . $skill->p_skill . "',
skill_rating='" . $skill->skill_rating . "',
interviewer_rating='" . $skill->interviewer_rating . "'
WHERE skill_id='" . $skill->id . "'
");
        } else {
            mysqli_query($con, "INSERT INTO applicant_skill SET
applicant_id='" . $applicant_id . "',
applicant_auto_create_id='" . $applicant_auto_create_id . "',
pri_skill='" . $skill->p_skill . "',
skill_rating='" . $skill->skill_rating . "',
interviewer_rating='" . $skill->interviewer_rating . "',
status=1");
        }
    }

    // insert certifications to certifications table
    $certificates = $data->certificates_array;
    foreach ($certificates as $certificate) {
        $c_name = $certificate->certificateName;
        $c_image = $certificate->certificateImage;
        $image_name = $certificate->c_Image;
        $image = '';
        $fileImage = '';

        if ((isset($c_name) && !empty($c_name)) || (isset($c_image) && !empty($c_image))) {
            if (!empty($c_image)) {
                $mimeType = mime_content_type($c_image);
                $getExt = explode('/', $mimeType)[1];
                if ($getExt == 'pdf') {
                    $extension = 'pdf';
                } elseif (in_array($getExt, ['jpeg', 'jpg', 'png'])) {
                    // Convert any image format to png
                    $extension = 'png'; // Use the original image extension (jpeg, jpg, or png)
                } else {
                    echo "Unsupported file type";
                    return;
                }
                $image_parts = explode(";base64,", $c_image);
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

            if (isset($certificate->certificate_id) && !empty($certificate->certificate_id)) {
                mysqli_query($con, "UPDATE applicant_certificates SET
$image status=1
WHERE certificate_id='" . $certificate->certificate_id . "'
");
            } else {
                mysqli_query($con, "INSERT INTO applicant_certificates SET
applicant_id='" . $applicant_id . "',
applicant_auto_create_id='" . $applicant_auto_create_id . "',$image
status=1
");
            }
        }
    }

    if ($insert_applicant && $applicantRequest == 'user') {
        $stat = ['status' => true, 'message' => 'your application has been submitted successfully...!!our team will get back to you soon'];
        echo json_encode($stat);
        exit();
    }
    if ($insert_applicant == true) {
        $stat = ['message' => 'Updated Successfully', 'status' => true];
    } else {
        $stat = ['message' => 'Failed! to Update', 'status' => false];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something Went Wrong! Please Try Again'];
}
echo json_encode($stat);
