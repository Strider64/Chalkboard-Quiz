<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

use Library\Users;
use Library\Database as DB;

$db = DB::getInstance();
$pdo = $db->getConnection();

/*
 * The below must be used in order for the json to be decoded properly.
 */
$data = json_decode(file_get_contents('php://input'), true);

/*
 * Check to see if username already exists
 * in the MySQL Database Table (members).
 */
function duplicateUsername($username, $pdo) {

    try {
        $query = "SELECT 1 FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            return true; // userName is in database table
        } else {
            return false; // Username isn't in database table
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$username = trim($data['username']);

$status = duplicateUsername($username, $pdo);

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