<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// CONFIG DE ERROS
date_default_timezone_set('America/Araguaina');
$settings          = [];
$settings['root']  = dirname(__DIR__);
$settings['error'] = [
    'display_error_details' => true,
    'log_errors'            => true,
    'log_error_details'     => true,
];

// CORS
ini_set('allow_url_fopen', true);
ini_set('date.timezone', 'America/Araguaina');
date_default_timezone_set('America/Araguaina');
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Max-Age: 3600');
    header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers, Middleware, X-Requested-With');
}
// Access-Control headers are received during OPTIONS requests
if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

return $settings;
