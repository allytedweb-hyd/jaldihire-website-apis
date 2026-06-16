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


include "../../includes/db-connection/db.php";

include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


if ($token) {

    // Validate the Token

    $response = verifyJWT($token);

    if ($response['status']) {

        // Accessing Params

        @$empoyerId = $_GET['empoyerId'];
        @$technology = $_GET['technology'];
        @$skill = $_GET['skill'];
        @$type = $_GET['type'];


        // Checking the Inputs

        if (empty($empoyerId) || empty($technology) || empty($skill)) {
            $status = ["status" => false, "message" => "Invalid Params"];
            exit(json_encode($status));
        }

        $array1 = [];
        $array2 = [];
        $array3 = [];
        $array4 = [];
        $array5 = [];
        $array6 = [];
        $array7 = [];

        $days = [];
        $exp = [];


        // Query for less than 1 year

        $query1 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience < 1 ORDER BY TABLE1.updated_on DESC LIMIT 10";



        $val1 = mysqli_query($con, $query1);

        while ($fetch1 = mysqli_fetch_assoc($val1)) {
            $array1[] = $fetch1['diff'];
        }

        $diffVal1 = 0;

        foreach ($array1 as $key => $value) {
            $diffVal1 = $diffVal1 + $value;
        }

        if (count($array1) > 0) {
            array_push($days, intval($diffVal1 / count($array1)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '<1');




        // echo json_encode($status);
        // exit;

        // Query for than 1 - 3 year

        $query2 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience > 1 AND TABLE3.total_expereience >= 3 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val2 = mysqli_query($con, $query2);

        while ($fetch2 = mysqli_fetch_assoc($val2)) {
            $array2[] = $fetch2['diff'];
        }

        $diffVal2 = 0;

        foreach ($array2 as $key => $value) {
            $diffVal2 = $diffVal2 + $value;
        }

        if (count($array2) > 0) {
            array_push($days, intval($diffVal2 / count($array2)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '1-3');

        // Query for than 3 - 5 year

        $query3 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience > 3 AND TABLE3.total_expereience >= 5 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val3 = mysqli_query($con, $query3);

        while ($fetch3 = mysqli_fetch_assoc($val3)) {
            $array3[] = $fetch3['diff'];
        }

        $diffVal3 = 0;

        foreach ($array3 as $key => $value) {
            $diffVal3 = $diffVal3 + $value;
        }

        if (count($array3) > 0) {
            array_push($days, intval($diffVal3 / count($array3)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '3-5');


        // Query for than 5 - 8 year

        $query4 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience > 5 AND TABLE3.total_expereience >= 8 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val4 = mysqli_query($con, $query4);

        while ($fetch4 = mysqli_fetch_assoc($val4)) {
            $array4[] = $fetch4['diff'];
        }

        $diffVal4 = 0;

        foreach ($array4 as $key => $value) {
            $diffVal4 = $diffVal4 + $value;
        }

        if (count($array4) > 0) {
            array_push($days, intval($diffVal4 / count($array4)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '5-8');

        // Query for than 8 - 11 year

        $query5 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience > 8 AND TABLE3.total_expereience >= 11 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val5 = mysqli_query($con, $query5);

        while ($fetch5 = mysqli_fetch_assoc($val5)) {
            $array5[] = $fetch5['diff'];
        }

        $diffVal5 = 0;

        foreach ($array5 as $key => $value) {
            $diffVal5 = $diffVal5 + $value;
        }

        if (count($array5) > 0) {
            array_push($days, intval($diffVal5 / count($array5)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '8-11');

        // Query for than 11 - 13 year

        $query6 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience > 11 AND TABLE3.total_expereience >= 13 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val6 = mysqli_query($con, $query6);

        while ($fetch6 = mysqli_fetch_assoc($val6)) {
            $array6[] = $fetch6['diff'];
        }

        $diffVal6 = 0;

        foreach ($array6 as $key => $value) {
            $diffVal6 = $diffVal6 + $value;
        }

        if (count($array6) > 0) {
            array_push($days, intval($diffVal6 / count($array6)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '11-13');

        // Query for than 13+ year

        $query7 = "SELECT ABS(TIMESTAMPDIFF(day,TABLE1.updated_on,TABLE2.created_date)) as diff FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE3.total_expereience > 13 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val7 = mysqli_query($con, $query7);

        while ($fetch7 = mysqli_fetch_assoc($val7)) {
            $array7[] = $fetch7['diff'];
        }

        $diffVal7 = 0;

        foreach ($array7 as $key => $value) {
            $diffVal7 = $diffVal7 + $value;
        }

        if (count($array7) > 0) {
            array_push($days, intval($diffVal7 / count($array7)));
        } else {
            array_push($days, 0);
        }
        array_push($exp, '13+');



        $status = [
            "status" => true,
            "days" => $days,
            "exp" => $exp
        ];

        echo json_encode($status);
    } else {
        $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
        echo json_encode($status);
    }
} else {
    $status = ["status" => false, "message" => "Unauthorized Access"];
    echo json_encode($status);
}
