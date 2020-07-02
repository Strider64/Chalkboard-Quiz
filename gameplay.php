<?php

require_once 'assets/config/config.php';

/* Makes it so we don't have to decode the json coming from javascript */
header('Content-type: application/json');

/*
 * The below must be used in order for the json to be decoded properly.
 */
$data = json_decode(file_get_contents('php://input'), true);




function errorOutput($output, $code = 500) {
    http_response_code($code);
    echo json_encode($output);
}

/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */

function output($output) {
    http_response_code(200);
    echo json_encode($output);
}
