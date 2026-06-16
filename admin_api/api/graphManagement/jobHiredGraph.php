<?php
// header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json, charset=utf-8');
// // ini_set('display_errors', 1);
// // ini_set('display_startup_errors', 1);
// // error_reporting(E_ALL);

// include "../../includes/db-connection/db.php";

// include "../lib/jwt.php";

// // Accesing the Headers

// $headers = apache_request_headers();


// // destructing the token
// @$token = str_replace('Bearer ', '', $headers['Authorization']);
// ;


// if ($token) {

//     // Validate the Token

//     $response = verifyJWT($token);

//     if ($response['status']) {

//         // Accessing Params

//         @$empoyerId = $_GET['empoyerId'];
//         @$technology = $_GET['technology'];
//         @$skill = $_GET['skill'];
//         @$type = $_GET['type'];


//         // Checking the Inputs

//         if (empty($empoyerId) || empty($technology) || empty($skill)) {
//             $status = ["status" => false, "message" => "Invalid Params"];
//             exit(json_encode($status));
//         }

//         $array1 = [];
//         $array2 = [];
//         $array3 = [];
//         $array4 = [];
//         $array5 = [];
//         $array6 = [];
//         $array7 = [];


//         // Query for less than 1 year

//         $query1 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='<1'ORDER BY TABLE1.updated_on DESC LIMIT 10";

//         $val1 = mysqli_query($con, $query1);

//         while ($fetch1 = mysqli_fetch_assoc($val1)) {
//             $array1[] = $fetch1;
//         }

//         // Query for than 1 - 3 year

//         $query2 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='1-3'ORDER BY TABLE1.updated_on DESC LIMIT 10";

//         $val2 = mysqli_query($con, $query2);

//         while ($fetch2 = mysqli_fetch_assoc($val2)) {
//             $array2[] = $fetch2;
//         }

//         // Query for than 3 - 5 year

//         $query3 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='3-5'ORDER BY TABLE1.updated_on DESC LIMIT 10";

//         $val3 = mysqli_query($con, $query3);

//         while ($fetch3 = mysqli_fetch_assoc($val3)) {
//             $array3[] = $fetch3;
//         }


//         // Query for than 5 - 8 year

//         $query4 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='5-8'ORDER BY TABLE1.updated_on DESC LIMIT 10";


//         $val4 = mysqli_query($con, $query4);

//         while ($fetch4 = mysqli_fetch_assoc($val4)) {
//             $array4[] = $fetch4;
//         }

//         // Query for than 8 - 11 year

//         $query5 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='8-11'ORDER BY TABLE1.updated_on DESC LIMIT 10";

//         $val5 = mysqli_query($con, $query5);

//         while ($fetch5 = mysqli_fetch_assoc($val5)) {
//             $array5[] = $fetch5;
//         }

//         // Query for than 11 - 13 year

//         $query6 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='11-13'ORDER BY TABLE1.updated_on DESC LIMIT 10";

//         $val6 = mysqli_query($con, $query6);

//         while ($fetch6 = mysqli_fetch_assoc($val6)) {
//             $array6[] = $fetch6;
//         }

//         // Query for than 13+ year

//         $query7 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='13+'ORDER BY TABLE1.updated_on DESC LIMIT 10";

//         $val7 = mysqli_query($con, $query7);

//         while ($fetch7 = mysqli_fetch_assoc($val7)) {
//             $array7[] = $fetch7;
//         }

//         $finalArr = [$array1, $array2, $array3, $array4, $array5, $array6, $array7];

//         $jobPrice = [];
//         $hiredPrice = [];
//         $expPrice = [];
//         $expLevels = [];

//         $levelValues = [
//             "0" => "<1",
//             "1" => "1-3",
//             "2" => "3-5",
//             "3" => "5-8",
//             "4" => "8-11",
//             "5" => "11-13",
//             "6" => "13+"
//         ];

//         foreach ($finalArr as $key => $value) {
//             $arr_count = count($value);
//             $job_pack = 0;
//             $hire_pack = 0;
//             $exp_pack = 0;
//             foreach ($value as $value1) {
//                 $job_pack = $job_pack + $value1['job_price'];
//                 $hire_pack = $hire_pack + $value1['hired_package'];
//                 $exp_pack = $exp_pack + $value1['expected_package'];
//             }
//             array_push($jobPrice, $arr_count > 0 ? intval($job_pack / $arr_count) : 0);
//             array_push($hiredPrice, $arr_count > 0 ? intval($hire_pack / $arr_count) : 0);
//             array_push($expPrice, $arr_count > 0 ? intval($exp_pack / $arr_count) : 0);
//             array_push($expLevels, $levelValues[$key]);
//         }

//         $status = [
//             "status" => true,
//             "jobPrice" => $jobPrice,
//             "hiredPrice" => $hiredPrice,
//             "expPrice" => $expPrice,
//             "expLevels" => $expLevels,
//         ];

//         echo json_encode($status);
//     } else {
//         $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
//         echo json_encode($status);
//     }
// } else {
//     $status = ["status" => false, "message" => "Unauthorized Access"];
//     echo json_encode($status);
// }
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


        // Query for less than 1 year

        $query1 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience,TABLE4.price as market_price FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='<1' AND TABLE4.employer_id = '{$empoyerId}' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '<1' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";
        $val1 = mysqli_query($con, $query1);

        while ($fetch1 = mysqli_fetch_assoc($val1)) {
            $array1[] = $fetch1;
        }

        // exit (json_encode($array1));

        // Query for than 1 - 3 year

        $query2 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience,TABLE4.price as market_price FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='1-3' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '1-3' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val2 = mysqli_query($con, $query2);

        while ($fetch2 = mysqli_fetch_assoc($val2)) {
            $array2[] = $fetch2;
        }

        // Query for than 3 - 5 year

        $query3 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience,TABLE4.price as market_price FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='3-5' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '3-5' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val3 = mysqli_query($con, $query3);

        while ($fetch3 = mysqli_fetch_assoc($val3)) {
            $array3[] = $fetch3;
        }


        // Query for than 5 - 8 year

        $query4 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience,TABLE4.price as market_price FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='5-8' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '5-8' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";


        $val4 = mysqli_query($con, $query4);

        while ($fetch4 = mysqli_fetch_assoc($val4)) {
            $array4[] = $fetch4;
        }

        // Query for than 8 - 11 year

        $query5 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='8-11' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '8-11' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val5 = mysqli_query($con, $query5);

        while ($fetch5 = mysqli_fetch_assoc($val5)) {
            $array5[] = $fetch5;
        }

        // Query for than 11 - 13 year

        $query6 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience,TABLE4.price as market_price FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='11-13' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '11-13' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val6 = mysqli_query($con, $query6);

        while ($fetch6 = mysqli_fetch_assoc($val6)) {
            $array6[] = $fetch6;
        }

        // Query for than 13+ year

        $query7 = "SELECT TABLE1.hired_package, TABLE2.price as job_price,TABLE3.expected_package, TABLE2.experience,TABLE4.price as market_price FROM applicant_scheduled TABLE1 , tbl_post_a_job TABLE2, tbl_applicant_add TABLE3, tbl_market_price TABLE4 WHERE TABLE1.job_id=TABLE2.auto_create_jobid AND TABLE1.applicant_id=TABLE3.applicant_id AND TABLE1.status=6 AND TABLE2.technology = '{$technology}' AND TABLE2.employer_id = '{$empoyerId}' AND TABLE2.primary_skill = '{$skill}' AND TABLE2.experience='13+' AND TABLE4.technology = '{$technology}' AND TABLE4.skill = '{$skill}' AND TABLE4.experience = '13+' AND TABLE4.status=1 ORDER BY TABLE1.updated_on DESC LIMIT 10";

        $val7 = mysqli_query($con, $query7);

        while ($fetch7 = mysqli_fetch_assoc($val7)) {
            $array7[] = $fetch7;
        }

        $finalArr = [$array1, $array2, $array3, $array4, $array5, $array6, $array7];

        $jobPrice = [];
        $hiredPrice = [];
        $expPrice = [];
        $marketPrice = [];
        $expLevels = [];

        $levelValues = [
            "0" => "<1",
            "1" => "1-3",
            "2" => "3-5",
            "3" => "5-8",
            "4" => "8-11",
            "5" => "11-13",
            "6" => "13+"
        ];

        foreach ($finalArr as $key => $value) {
            $arr_count = count($value);
            $job_pack = 0;
            $hire_pack = 0;
            $exp_pack = 0;
            $market_pack = 0;
            foreach ($value as $value1) {
                $job_pack = $job_pack + $value1['job_price'];
                $hire_pack = $hire_pack + $value1['hired_package'];
                $exp_pack = $exp_pack + $value1['expected_package'];
                $market_pack = $market_pack + $value1['market_price'];
            }
            array_push($jobPrice, $arr_count > 0 ? intval($job_pack / $arr_count) : 0);
            array_push($hiredPrice, $arr_count > 0 ? intval($hire_pack / $arr_count) : 0);
            array_push($expPrice, $arr_count > 0 ? intval($exp_pack / $arr_count) : 0);
            array_push($marketPrice, $arr_count > 0 ? intval($market_pack / $arr_count) : 0);
            array_push($expLevels, $levelValues[$key]);
        }

        $status = [
            "status" => true,
            "jobPrice" => $jobPrice,
            "hiredPrice" => $hiredPrice,
            "expPrice" => $expPrice,
            "marketPrice" => $marketPrice,
            "expLevels" => $expLevels,
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
