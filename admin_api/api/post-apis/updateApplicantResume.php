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


include '../../includes/db-connection/db.php';
include '../lib/jwt.php';
$folderPath = "uploads/applicant-images/";
$json = file_get_contents('php://input');
$stat = [];

if (isset($json) && !empty($json)) {
    $data = json_decode($json);

    $applicant_id = $data->applicantId;

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

    if ($fileResume != '') {
        $q = "UPDATE tbl_applicant_add SET resume_image='" . $fileResume . "' WHERE applicant_id='" . $applicant_id . "' && status=1";
        $updateRes = mysqli_query($con, $q);

        if ($updateRes) {
            $stat = ['status' => true, 'message' => 'Resume Updated Successfully'];
        } else {
            $stat = ['status' => false, 'message' => 'Failed To Update! Try Again'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'Please Enter Valid Resume!'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something Went Wrong! Please Try Again'];
}

echo json_encode($stat);
