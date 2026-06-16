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
$json = file_get_contents('php://input');


if (isset($json) && !empty($json)) {
    $data = json_decode($json);

    @$id = $data->id;
    @$autoId = $data->autoId;
    @$avail_from = $data->avail_from;
    @$collegeName = $data->collegeName;
    @$highest_degree = $data->highest_degree;
    @$percentage = $data->percentage;
    @$EducationGap = $data->EducationGap;
    @$EducationGapReason = $data->EducationGapReason;
    @$skillForm = $data->skillsForm;
    @$addedPriSkills = explode(',', $data->AppliSkills);

    // implode skills into a variable
    $skillArray = [];
    for ($i = 0; $i < count($skillForm); $i++) {
        array_push($skillArray, $skillForm[$i]->skill,);
        $skill[$i] = $skillForm[$i]->skill;
        $skill_rating[$i] = $skillForm[$i]->rating;
    }
    $primari_skills = array_merge($addedPriSkills, $skillArray);
    $ListprimarySkills = implode(',', $primari_skills);

    $whereClause = "";
    if (isset($data->prefered_locations) && !empty($data->prefered_locations)) {
        @$prefered_locations = implode(',', $data->prefered_locations);
        $whereClause .= "preferred_location='" . $prefered_locations . "',";
    }
    if (isset($data->IntComapany) && !empty($data->IntComapany)) {
        @$IntComapany = implode(',', $data->IntComapany);
        $whereClause .= "interested_company='" . $IntComapany . "',";
    }
    if (isset($data->ExcCompanies) && !empty($data->ExcCompanies)) {
        @$ExcCompanies = implode(',', $data->ExcCompanies);
        $whereClause .= "excluded_company='" . $ExcCompanies . "',";
    }
    $skillArray = [];
    for ($i = 0; $i < count($skillForm); $i++) {
        $skills[$i] = $skillForm[$i]->skill;
        $rating[$i] = $skillForm[$i]->rating;

        if ($skills) {
            $getSkill = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_skill WHERE pri_skill='" . $skills[$i] . "' AND applicant_id='" . $id . "'"));


            if (!$getSkill > 0) {
                $insert = mysqli_query($con, "INSERT INTO applicant_skill SET  applicant_auto_create_id='" . $autoId . "',pri_skill='" . $skills[$i] . "',skill_rating='" . $rating[$i] . "',applicant_id='" . $id . "', status=1 ");
            }
        }
    }

    $q = "UPDATE tbl_applicant_add SET $whereClause primary_skills='" . $ListprimarySkills . "',available_from='" . $avail_from . "',college='" . $collegeName . "',highest_degree='" . $highest_degree . "',percentage='" . $percentage . "',education_gap='" . $EducationGap . "',education_gap_reason='" . $EducationGapReason . "' WHERE applicant_id='" . $id . "'";


    $query = mysqli_query($con, $q);
    if ($query == true) {
        $stat = ['status' => true, 'message' => "updated successfully"];
    } else {
        $stat = ['status' => true, 'message' => "updated successfully"];
    }
    echo json_encode($stat);
}
