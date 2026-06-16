<?php

$request = new HttpRequest();
$request->setUrl('https://aadhaar-number-verification.p.rapidapi.com/Uidverifywebsvcv1/Uidverify');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders([
    'content-type' => 'application/x-www-form-urlencoded',
    'X-RapidAPI-Key' => '58aa325038msh665166d4d26c151p181ce0jsnde5bd93ee194',
    'X-RapidAPI-Host' => 'aadhaar-number-verification.p.rapidapi.com'
]);

$request->setContentType('application/x-www-form-urlencoded');
$request->setPostFields([
    'captchaValue' => 'TK6HXq',
    'captchaTxnId' => '58p5MxkQrNFp',
    'method' => 'uidvalidate',
    'clientid' => '111',
    'txn_id' => '4545533',
    'consent' => 'Y',
    'uidnumber' => '719767270242'
]);

try {
    $response = $request->send();

    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}