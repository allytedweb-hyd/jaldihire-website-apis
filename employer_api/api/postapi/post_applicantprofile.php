<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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



include "../../includes/db-connection/db.php";
$json = file_get_contents('php://input');
if (isset($json) && !empty($json)) {
  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $employeeid = $data->applicant_id;
  $employerid = $data->employer_id;
  $biddingprice = $data->bid_price;


  $checkDb = mysqli_query($con, "select * from tbl_bidding  where employee_id='" . $employeeid . "' && bid_status=1");
  $count = mysqli_num_rows($checkDb);
  if ($count >= 1) {
    $stat = ['exists' => 'user already exists'];
  } else {

    $insert_data = mysqli_query($con, "insert into tbl_bidding set employee_id='" . $employeeid . "',employer_id='" . $date . "',bidding_price='" . $biddingprice . "', bid_status=1");

    if ($insert_data == true) {
      $stat = ['successstatus' => 'Success!'];
    } else {

      $stat = ['failurestatus' => 'Failed!'];
    }
  }

  echo json_encode($stat);
}
