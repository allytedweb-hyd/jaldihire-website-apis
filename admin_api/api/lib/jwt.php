<?php
include "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createJWT($userId, $role)
{
    $key = "J@ldiHire@321";
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();

    $payload = [
        "userId" => $userId,
        "role"   => $role,
        "iat"    => $issuedAt->getTimestamp(),
        "nbf"    => $issuedAt->getTimestamp(),
        "exp"    => $expire
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');

    return [
        "status" => true,
        "token"  => $jwt
    ];
}

function verifyJWT($token)
{
    $key = "J@ldiHire@321";

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        return [
            "status" => true,
            "userId" => $decoded->userId ?? $decoded->id ?? null,
            "role"   => $decoded->role ?? null
        ];

    } catch (\Throwable $e) {

        return [
            "status" => false,
            "message" => $e->getMessage()
        ];
    }
}


function TokenVerify($token)
{
    $response = verifyJWT($token);

    if (!$response['status']) {
        throw new Exception("Unauthorized Access");
    }

    return $response;
}
