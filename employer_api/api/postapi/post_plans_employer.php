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
require '../vendor/phpmailer/PHPMailerAutoload.php';

include "../../includes/db-connection/db.php";

$folderPath = "uploads/employer-images/";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

    $data = json_decode($json);

    @$employer_id = $data->employer_id;
    @$total_price = $data->total_price;
    @$onboardingsquantity = $data->onboardingsquantity;
    @$interviewsquantity = $data->interviewsquantity;
    @$Applicant_ProfilesQuantity = $data->Applicant_ProfilesQuantity;
    @$panel_memberQuantity = $data->panel_memberQuantity;
    @$Resounce_manger_quatitY = $data->Resounce_manger_quatitY;

    if (isset($employer_id) && !empty($employer_id)) {
        $getemployerqunatity = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_add_employer WHERE employer_auto_create_id="' . $employer_id . '" AND status=1'));
        @$oldonboarding = $getemployerqunatity['Onboardings'];
        @$oldinterviews = $getemployerqunatity['Interviews'];
        @$oldpanel_members = $getemployerqunatity['panel_members'];
        @$oldapp_profile_count = $getemployerqunatity['app_profile_count'];
        @$oldresource_managers = $getemployerqunatity['resource_managers'];


        $newonboarding = $oldonboarding + $onboardingsquantity;
        $newinterviews = $oldinterviews + $interviewsquantity;
        $newapplicantCount = $oldapp_profile_count + $Applicant_ProfilesQuantity;
        $newpanelmember = $oldpanel_members + $panel_memberQuantity;
        $newresourcemanager = $oldresource_managers + $Resounce_manger_quatitY;

        $insertfeatures = mysqli_query($con, 'UPDATE tbl_add_employer SET Onboardings="' . $newonboarding . '",Interviews="' . $newinterviews . '",panel_members="' . $newpanelmember . '",app_profile_count="' . $newapplicantCount . '",resource_managers="' . $newresourcemanager . '" WHERE employer_auto_create_id="' . $employer_id . '"');

        if ($insertfeatures) {
            $stat = ['status' => true, 'message' => 'Successfully'];
        } else {
            $stat = ['status' => true, 'message' => 'Failed'];
        }
    }
}

echo json_encode($stat);
