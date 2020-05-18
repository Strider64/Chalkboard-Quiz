<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

use Library\Users;
use Library\Database as DB;

$trigger = false;

$login = new Users();
$confirmation = filter_input(INPUT_GET, 'confirmation', FILTER_SANITIZE_SPECIAL_CHARS);
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'enter') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    
    
    $result = $login->activate($username, $password, $status);
    if ($result) {
        $trigger = true;
    }
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
        <noscript>
        <h1>Sorry, but you need Javascript enabled to use this website.</h1>
        </noscript>

        <section class="contentStyle">
            <p class="banner2">The Chalkboard Quiz by <a class="website" href="https://www.miniaturephotographer.com/">The Miniature Photographer</a></p>
            <?php if (!$trigger) { ?>
            <form id="activationForm" class="login" action="activate.php" method="post">
                <input type="hidden" name="status" value="<?= $confirmation ?>">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="" tabindex="1" autofocus="">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" tabindex="2">
                <input id="submit" type="submit" name="submit" value="enter" tabindex="3">
            </form>
            <?php } ?>
            <div class="textMessageBox">
                <?php if ($trigger) { ?>
                    <h1>Thank You for Registering and Activating!</h1>
                    <p>By activating your account with enable Top Score Boards, difficulty levels, playing options and many more features!</p>
                    <a class="btn3" title="Home Page" href="index.php">Home</a>
                <?php } else { ?>
                    <h1>Please Login to activate your account!</h1>
                    <p>In order to fully enjoy The Chalkboard Quiz please login in to activate your account. Just a reminder that I will never sell you email address to any 3rd party.</p>
                <?php } ?>
            </div>
        </section>

        <footer>
            &copy; The Miniature Photographer
            <div class="content">
                <a class="menuExit" title="The Miniature Photographer" href="https://www.miniaturephotographer.com/">The Miniature Photographer</a>
                <!--        <a title="Terms of Service" href="#">Terms of Service</a>-->
            </div>
            Dedicated to Mildred I. Pepp (my Mom) 10-29-1928 / 02-26-2017
        </footer>


    </body>
</html>