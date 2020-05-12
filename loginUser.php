<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

//use Library\Email;
use Library\Database as DB;
use Library\Users as Login;

/* Makes it so we don't have to decode the json coming from JQuery */
header('Content-type: application/json');

/*
 * The below must be used in order for the json to be decoded properly.
 */
$data = json_decode(file_get_contents('php://input'), true);

$result = false;

$login = new Login();

//$login->delete();


if (isset($data['username']) && $data['password']) {

    $username = $data['username'];
    $password = $data['password'];
    $result = $login->read($username, $password);
    
    if ($result) {
        $_SESSION['username'] = $data['username'];
        $data['userId'] = $result;
        unset($data['password']);
        output($data);
    } else {
        output(false);
    }
    
    
}



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