<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporarily enable for debugging

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
include "../notificationApis/notificationFunctions.php";

$folderPath = "uploads/recruiter-images/";
$json = file_get_contents('php://input');
$status = ['status' => false, 'message' => 'Something went wrong please try again'];

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    
    // Validate required fields
    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(['status' => false, "message" => "ID is required"]);
        exit();
    }
    
    if (!isset($data->email) || empty($data->email)) {
        echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
        exit();
    }
    
    // Sanitize and escape inputs
    $id = mysqli_real_escape_string($con, $data->id);
    $name = isset($data->name) ? mysqli_real_escape_string($con, $data->name) : '';
    $role = isset($data->role) ? mysqli_real_escape_string($con, $data->role) : '';
    $designation = isset($data->designation) ? mysqli_real_escape_string($con, $data->designation) : '';
    $function = isset($data->function) ? mysqli_real_escape_string($con, $data->function) : '';
    $department = isset($data->department) ? mysqli_real_escape_string($con, $data->department) : '';
    $reporting = isset($data->reporting) ? mysqli_real_escape_string($con, $data->reporting) : '';
    $mobile = isset($data->mobile) ? mysqli_real_escape_string($con, $data->mobile) : '';
    $email = mysqli_real_escape_string($con, $data->email);
    $autoId = isset($data->auto_create_id) ? mysqli_real_escape_string($con, $data->auto_create_id) : '';
    
    // Process skills
    $skillsImplode = '';
    if (isset($data->skills) && is_array($data->skills) && count($data->skills) > 0) {
        $skillItems = [];
        foreach ($data->skills as $item) {
            if (isset($item->item_text)) {
                $skillItems[] = mysqli_real_escape_string($con, $item->item_text);
            }
        }
        $skillsImplode = implode(',', $skillItems);
    }
    
    // Process technology
    $techImplode = '';
    if (isset($data->technology) && is_array($data->technology) && count($data->technology) > 0) {
        $techItems = [];
        foreach ($data->technology as $item) {
            if (isset($item->item_text)) {
                $techItems[] = mysqli_real_escape_string($con, $item->item_text);
            }
        }
        $techImplode = implode(',', $techItems);
    }
    
    // Handle profile image
    $fileProfile = '';
    $whereClause = '';
    $oldImage = isset($data->oldImgae) ? $data->oldImgae : ''; // Note: typo in variable name
    
    if (!empty($data->profile)) {
        // Create directory if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        
        $image_parts = explode(";base64,", $data->profile);
        if (count($image_parts) >= 2) {
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_base64 = base64_decode($image_parts[1]);
            if ($image_base64 !== false) {
                $fileProfile = uniqid() . '.png';
                $file = $folderPath . $fileProfile;
                $move = file_put_contents($file, $image_base64);
                if ($move !== false) {
                    // Delete old image if exists
                    if (!empty($oldImage) && file_exists($folderPath . $oldImage)) {
                        @unlink($folderPath . $oldImage);
                    }
                    $whereClause = ", profile_img='" . mysqli_real_escape_string($con, $fileProfile) . "'";
                }
            }
        }
    }
    
    // Check if email exists for other users
    $checkEmailQuery = "SELECT * FROM tbl_allyted_team WHERE member_email='$email' AND allyted_member_id!='$id'";
    $getEmployer = mysqli_query($con, $checkEmailQuery);
    
    if (!$getEmployer) {
        echo json_encode(['status' => false, "message" => "Database error: " . mysqli_error($con)]);
        exit();
    }
    
    // Build update query - make sure all column names are correct
    $q = "UPDATE tbl_allyted_team SET 
        member_name='" . $name . "',
        role='" . $role . "',
        `function`='" . $function . "',
        designation='" . $designation . "',
        department='" . $department . "',
        reportingTo='" . $reporting . "',
        skills='" . $skillsImplode . "',
        member_mobile='" . $mobile . "',
        member_email='" . $email . "',
        technology='" . $techImplode . "'
        $whereClause 
        WHERE allyted_member_id='" . $id . "'";
    
    // Log query for debugging (remove in production)
    error_log("Update Query: " . $q);
    
    $update_team = mysqli_query($con, $q);
    
    if ($update_team) {
        // Handle notifications
        if (!empty($autoId)) {
            $getAllowedNoti = getAgentAllowedNotify($autoId);
            
            if ($getAllowedNoti && isset($getAllowedNoti['status']) && $getAllowedNoti['status']) {
                $Teamdata = $getAllowedNoti['data'] ?? [];
                if (isset($Teamdata['admin_alerts']) && $Teamdata['admin_alerts'] == '1') {
                    
                    $Roleclause = '';
                    if ($role == 'Agent') {
                        $Roleclause = 'notify_to="Agent", agent_id="' . mysqli_real_escape_string($con, $autoId) . '",';
                    }
                    if ($role == 'Recruiter') {
                        $Roleclause = 'notify_to="Recuiter", recruiter_id="' . mysqli_real_escape_string($con, $autoId) . '",';
                    }
                    
                    $text = "Your account details updated successfully by < " . $name . ">";
                    
                    $notificationQuery = "INSERT INTO tbl_notification SET 
                        $Roleclause 
                        notification_type='info',
                        notification_text='" . mysqli_real_escape_string($con, $text) . "',
                        activity='Masters'";
                    
                    mysqli_query($con, $notificationQuery);
                }
            }
        }
        
        $status = ['status' => true, 'message' => 'Updated successfully'];
    } else {
        $status = ['status' => false, 'message' => 'Failed! please try again', 'error' => mysqli_error($con)];
    }
}

echo json_encode($status);
?>