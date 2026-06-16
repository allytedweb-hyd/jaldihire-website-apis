<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
include "../vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; 
}

include "../../includes/db-connection/db.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['name']) && isset($data['given_name']) && isset($data['family_name']) && isset($data['picture'])) {

    $email = $data['email'];
    $name = $data['name'];
    $given_name = $data['given_name'];
    $family_name = $data['family_name'];
    $picture = $data['picture'];

    $checkUserQuery = "SELECT * FROM tbl_applicant_add WHERE e_mail = ?";
    $stmt = $con->prepare($checkUserQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fetchUser = $result->fetch_assoc();
        $userId = $fetchUser['applicant_auto_create_id'];

        $key = "J@ldiHire@321";
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
        $payload = array(
            "user_id" => $userId,
            'iat'  => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
            'nbf'  => $issuedAt->getTimestamp(), // Not before
            'exp'  => $expire, // Expire Time
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        echo json_encode([
            "status" => "success",
            "message" => "User already exists.",
            "token" => $jwt
        ]);
    } else {
        $getDbCount = mysqli_query($con, "SELECT * FROM `tbl_applicant_add` ORDER BY applicant_id DESC limit 1");
        $rows = mysqli_fetch_array($getDbCount);
        @$incCount = $rows['applicant_id'] + 1;
        $id = 'JDAPP' . strtoupper(substr(str_replace(' ', '', $name), 0, 4)) . $incCount;
        $status = 1;

        $insertQuery = "INSERT INTO tbl_applicant_add (full_name, e_mail, applicant_auto_create_id, status) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("sssi", $name, $email, $id, $status);

        if ($stmt->execute()) {
            $key = "J@ldiHire@321";
            $issuedAt = new DateTimeImmutable();
            $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
            $payload = array(
                "user_id" => $id,
                'iat'  => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
                'nbf'  => $issuedAt->getTimestamp(), // Not before
                'exp'  => $expire, // Expire Time
            );
            $jwt = JWT::encode($payload, $key, 'HS256');
            echo json_encode([
                "status" => "success",
                "message" => "User registered successfully.",
                "token" => $jwt
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to register user."
            ]);
        }
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid data."
    ]);
}

?>