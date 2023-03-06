<?php

function curlRequest($URL)
{

    $request = curl_init();
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($request, CURLOPT_URL, $URL);
    curl_setopt($request, CURLOPT_TIMEOUT, 80);

    $response = curl_exec($request);
    curl_close($request);

    return json_decode($response, true);
}

function googleCaptcha($userIdentifier)
{
    return (curlRequest($_ENV['GOOGLE_CAPTCHA_ENDPOINT'] . $userIdentifier))['success'];
}

?>