<?php

$endpoint = 'your/endpoint'; // relative to what setted in $host and $version in MyApi Class

require_once 'api.php';
$my_api = new MyApi('testuser', 'testpassword');

$response = $my_api->http($endpoint, 'GET');

if ($response === FALSE) {
    $profile = array('status' => false, 'error' => 'CURL_ERR');
    exit(print_r($profile));
}

// Use $response ...