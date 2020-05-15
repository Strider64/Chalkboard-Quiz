<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

use Library\Users;

$register = new Users();

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'enter') {
    $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $register->register($data);
    
}
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <title>The Chalkboard Quiz</title>
        <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    </head>
    <body>
        <div id="registrationPage">
            <form class="registerForm" action="" method="post" autocomplete="on">

                <h1>Register</h1>
                <p>Please fill in this form to create an account.</p>
                <hr>

                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="data[username]" autofocus required>
                
                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Enter Email" name="data[email]" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="data[password]" required>

                <label for="psw-repeat"><b>Repeat Password</b></label>
                <input type="password" placeholder="Repeat Password" name="data[repeatPassword]" required>
                <hr>

                <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
                <input type="submit" name="submit" value="enter" class="registerbtn">


                <div class="signin">
                    <p>Already have an account? <a href="index.php">Sign in</a>.</p>
                </div>
            </form>
        </div>
    </body>
</html>
