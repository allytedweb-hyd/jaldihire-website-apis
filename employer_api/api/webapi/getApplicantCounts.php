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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../includes/db-connection/db.php";

$interviewProgress = 0;

if (isset($_GET['applicant_id']) && !empty($_GET['applicant_id'])) {
    $applicantId = $_GET['applicant_id'];

    $onBoarding = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE applicant_id='" . $applicantId . "' && status='6'");

    $noOFIntierviews = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE applicant_id='" . $applicantId . "'");

    $noOfshortListed = mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE applicant_id='" . $applicantId . "' && status='5'");

    $applicantDetails = mysqli_query($con, "SELECT *,T2.round_title as roundName FROM applicant_scheduled T1, tbl_interview_rounds T2 WHERE applicant_id='" . $applicantId . "' AND T2.id = T1.round");

    $applicantfetch = mysqli_fetch_assoc($applicantDetails);

    if ($applicantfetch != null) {

        $query = "SELECT * FROM tbl_interview_rounds WHERE emp_id='" . $applicantfetch['employer_id'] . "' && status=1";
        $interviewRoundsCount = mysqli_num_rows(mysqli_query($con, $query));

        $q = "SELECT round_number as round FROM tbl_interview_rounds WHERE id='" . $applicantfetch['round'] . "' ";

        $appliIntRound = mysqli_fetch_assoc(mysqli_query($con, $q));
        $roundNumber = $appliIntRound['round'];
        $interviewProgress = ($roundNumber / $interviewRoundsCount) * 100;
        // echo $interviewProgress;
        // exit();
    }

    $onBoardingCount = mysqli_num_rows($onBoarding);
    $intreviewersCount = mysqli_num_rows($noOFIntierviews);
    $shortListedCount = mysqli_num_rows($noOfshortListed);
    $status = ['onboarding' => $onBoardingCount, 'clients' => $intreviewersCount, 'shortListed' => $shortListedCount, 'data' => $applicantfetch, 'progress' => $interviewProgress];
} else {
    $status = ['status' => true, 'Message' => 'Invalid Params'];
}
echo json_encode($status);
