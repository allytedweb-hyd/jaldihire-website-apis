<?php
include '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

function createJWT($userId, $role)
{
    $key = "J@ldiHire@321";
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
    $payload = array(
        "userId" => $userId,
        'iat' => $issuedAt->getTimestamp(),
        'nbf' => $issuedAt->getTimestamp(),
        'exp' => $expire,
        "role" => $role,
    );
    $jwt = JWT::encode($payload, $key, 'HS256');
    $status = ["status" => true, "token" => $jwt];
    return $status;
}
function verifyJWT($token)
{
    $key = "J@ldiHire@321";
    try {
        $jwt = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $jwt->user_id;
        $role = $jwt->role;
        $status = ["status" => true, "message" => "Authorized", 'userId' => $userId, 'role' => $role];
    } catch (\Exception $e) {
        $status = ["status" => false, "error" => $e, "message" => "Token has expired"];
    }
    return $status;
}

function TokenVerify($token)
{
    $response = verifyJWT($token);
    $checkToken = checkTokenExpiration($token);
    if (!$response['status']) {
        $status = ["status" => false, "message" => "Un Authorized Acess"];
        echo json_encode($status);
        exit;
    }
    return $response;
}

function checkTokenExpiration($jwt)
{
    $key = "J@ldiHire@321";
    global $con;
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return true; // Token is valid
    } catch (ExpiredException $e) {
        // Token expired, log the user out
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $userId = $decoded->user_id;
        $role = $decoded->role;

        if ($role === 'admin') {
            $updateQuery = "UPDATE tbl_add_employer SET is_logged_in = 0 WHERE employer_auto_create_id = ?";
        } else if ($role === 'team') {
            $updateQuery = "UPDATE tbl_add_team SET is_logged_in = 0 WHERE auto_create_id = ?";
        } else {
            return false;
        }

        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param('s', $userId);
        $stmt->execute();

        return false; // Token is expired
    } catch (Exception $e) {
        // Invalid token, log the user out
        return false;
    }

}