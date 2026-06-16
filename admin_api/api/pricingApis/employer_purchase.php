<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
include "../../includes/db-connection/db.php";
include "../lib/jwt.php";
// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

$data = json_decode(file_get_contents('php://input'));
date_default_timezone_set('asia/kolkata');
$date = date("Y/m/d");

$folderPath = "../post-apis/uploads/attachment-images/";

if (isset($data) && !empty($data)) {

    $component = $data->component;
    $added = intval($data->added);
    $perCost = intval($data->perCost);
    $totalCost = intval($data->totalCost);
    $empId = $data->employerId;
    $clause = '';

    // images upload code 
    $Attachment = '';
    if (!empty($data->Attachments)) {
        $image_parts = explode(";base64,", $data->Attachments);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $Attachment = uniqid() . '.png';
        $file = $folderPath . $fileProfile;
        $move = file_put_contents($file, $image_base64);
    }

    $sel = mysqli_fetch_assoc(mysqli_query($con, "SELECT Onboardings , Interviews FROM tbl_add_employer WHERE employer_id='" . $empId . "'"));
    $onBoardings = intval($sel['Onboardings']) + $added;
    $interviews = intval($sel['Interviews']) + $added;

    if ($component === 'Onboarding') {
        $clause = "Onboardings = {$onBoardings}";
    }
    if ($component === 'Interview') {
        $clause = "Interviews = {$interviews}";
    }

    $insert = mysqli_query($con, "INSERT INTO employer_purchase_history SET purchase_date='" . $date . "',employer_id='" . $empId . "',component='" . $component . "',Added='" . $added . "',per_cost='" . $perCost . "',total_cost='" . $totalCost . "',attachment='" . @$Attachment . "',status=1");

    if ($insert) {
        $q = "UPDATE tbl_add_employer SET $clause WHERE employer_id='" . $empId . "'";
        $update = mysqli_query($con, $q);
        $stat = ['status' => true, 'message' => 'Updated Successfully'];
    } else {
        $stat = ['status' => false, 'message' => 'Failed to save'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
