<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

use Library\Users;
use Library\Database as DB;

$db = DB::getInstance();
$pdo = $db->getConnection();

$register = new Users();

function confirmationNumber() {
    $status = bin2hex(random_bytes(32));
    return $status;
}

/*
 * Send Activation number to activate.php page
 */

function send_email(array $data, $status) {

    if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
        $comments = 'Here is you confirmation link: http://localhost/Chalkboard-Quiz/activate.php?confirmation=' . $status;
    } else {
        $comments = 'Here is you confirmation link: https://chalkboardquiz.com/activate.php?confirmation=' . $status;
    }

    /* Setup swiftmailer using your email server information */
    $transport = (new Swift_SmtpTransport('smtp.gmail.com', EMAIL_PORT, 'tls'))
            ->setUsername(EMAIL_USERNAME)
            ->setPassword(EMAIL_PASSWORD);


    $mailer = new Swift_Mailer($transport); // Create the Mailer using your created Transport

    /* create message */
    $message = (new Swift_Message('Confirmation Number'))
            ->setFrom(['jrpepp@pepster' => 'John Pepp'])
            ->setTo([$data['email'] => $data['username']])
            ->setBody($comments)
    ;

    /* Send the message */
    $result = $mailer->send($message);

    return $result;
}

function duplicateUsername($username, $pdo) {
    $query = "SELECT 1 FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        return true; // userName is in database table
    }
}

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (isset($submit) && $submit === 'enter') {

    $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $username = trim($data['username']);

    $statusUsername = duplicateUsername($username, $pdo);

    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $emailStatus = true;
    } else {
        $emailStatus = false;
        $errEmail = "Email Address is not a valid email address";
    }

    if (!$statusUsername && $emailStatus) {
        $status = confirmationNumber();


        if ($data['password'] === $data['repeatPassword']) {
            $result = $register->register($data, $status);

            if ($result) {
                $sentResult = send_email($data, $status);
                unset($data); // Delete User's Data:
                header("Location: success.php");
                exit;
            } else {
                $message = "Invalid Email Entry";
            }
        } else {
            $errPassword = "Passwords did not match, please re-enter";
        }
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
        <div id="registrationPage">
            <form class="registerForm" action="" method="post" autocomplete="on">

                <h1><?php echo (isset($message)) ? $message : 'Register'; ?></h1>
                <p><?php echo (isset($errPassword)) ? $errPassword : "Please fill in this form to create an account."; ?></p>
                <hr>

                <label for="username"><b>Username <span class="unavailable"> - Not Available, please choose a different one.</span></b></label>
                <input id="username" type="text" placeholder="<?php echo (isset($statusUsername) && $statusUsername) ? "Username is not available, please re-enter!" : "Enter Username"; ?>" name="data[username]" value="<?php echo (isset($data['username'])) ? $data['username'] : null; ?>" autofocus required>

                <label for="email"><?php echo (isset($errEmail)) ? $errEmail : "<b>Email</b>"; ?></label>
                <input type="email" placeholder="Enter Email" name="data[email]" value="<?php echo (isset($data['email'])) ? $data['email'] : null; ?>" required>

                <label for="psw"><b>Password <span class="recommendation">recommendation at least (8 characters long, 1 uppercasse letter, 1 number, and 1 special character)</span></b></label>
                <input id="password" type="password" placeholder="Enter Password" name="data[password]" required>

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
        <script src="assets/js/register.js"></script>
    </body>
</html>
