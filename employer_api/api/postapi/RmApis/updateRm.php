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

require '../../vendor/phpmailer/PHPMailerAutoload.php';
include "../../../includes/db-connection/db.php";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {
    // Ensure all expected properties exist
    if (isset($data->id, $data->name, $data->email, $data->mobile)) {

        // Sanitize your inputs to prevent SQL injection
        $id = mysqli_real_escape_string($con, $data->id);
        $name = mysqli_real_escape_string($con, $data->name);
        $email = mysqli_real_escape_string($con, $data->email);
        $mobile = mysqli_real_escape_string($con, $data->mobile);

        $setProfileClause = ''; // This will hold the SQL for the profile image update
        $folderPath = "../uploads/employer-images/";

        // --- Conditional Image Processing ---
        // This block only runs if a NEW base64 image is sent.
        if (isset($data->profile) && !empty($data->profile) && strpos($data->profile, 'base64') !== false) {

            $image_parts = explode(";base64,", $data->profile);
            $image_base64 = base64_decode($image_parts[1]);

            // Determine file extension from mime type
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $extension = ($image_type == 'jpeg') ? 'jpg' : $image_type;

            $fileProfile = uniqid() . '.' . $extension;
            $file = $folderPath . $fileProfile;

            // Try to save the new file
            if (file_put_contents($file, $image_base64)) {
                // If saved successfully, prepare the SQL to update the filename.
                $setProfileClause = ", profile='" . $fileProfile . "'";

                // Delete the old image file if it exists
                if (isset($data->oldImage) && !empty($data->oldImage) && file_exists($folderPath . $data->oldImage)) {
                    @unlink($folderPath . $data->oldImage);
                }
            }
        }

        // The logic to check for an email before updating
        if (!empty($email)) {
            // Construct the final query. $setProfileClause is empty if no new image was uploaded.
            $update_query = "UPDATE tbl_resource_managers SET rm_name = '" . $name . "', rm_email = '" . $email . "', rm_mobile = '" . $mobile . "'" . $setProfileClause . " WHERE id = '" . $id . "' AND status = '1'";

            if (mysqli_query($con, $update_query)) {
                // --- FETCH UPDATED DATA TO RETURN ---
                // After a successful update, fetch the full user record from the DB
                $select_query = "SELECT id, rm_name, rm_email, rm_mobile, profile FROM tbl_resource_managers WHERE id = '" . $id . "'";
                $result = mysqli_query($con, $select_query);
                $updated_user_data = mysqli_fetch_assoc($result);

                $stat = ['status' => true, 'message' => 'Updated successfully', 'data' => $updated_user_data];
            } else {
                $stat = ['status' => false, 'message' => 'Database update failed!'];
            }
        } else {
            $stat = ['status' => false, 'message' => 'Failed! Email cannot be empty.'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'Incomplete data provided.'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong! No data received.'];
}

echo json_encode($stat);
