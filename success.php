<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';


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
            <div class="textMessageBox">
                <h1>Thank You for Registering!</h1>
                <p>An email has been sent to you to activate your account, please check your spam or junk folder if you haven't received the activation code. </p>
                 <a class="btn3" title="Home Page" href="index.php">Home</a>
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

        <script type="text/javascript" src="assets/js/login.js"></script>
    </body>
</html>