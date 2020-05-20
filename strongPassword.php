<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

use Library\Users;
use Library\Database as DB;

$db = DB::getInstance();
$pdo = $db->getConnection();

function strongPassword($password) {
    /*
     * Validate Strong Password
     */ 
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        return "recommendation at least (8 characters long, 1 uppercasse letter, 1 number, and 1 special character)";
    } else {
        return 'Strong';
    }
}

/*
 * The below must be used in order for the json to be decoded properly.
 */
$data = json_decode(file_get_contents('php://input'), true);

$status = strongPassword($data['password']);

output($status);

/*
 * After encoding data to JSON send back to javascript using
 * these functions.
 */

function errorOutput($output, $code = 500) {
    http_response_code($code);
    echo json_encode($output);
}

function output($output) {
    http_response_code(200);
    echo json_encode($output);
}





