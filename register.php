<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

use Library\Users;
use Library\Database as DB;

$db = DB::getInstance();
$pdo = $db->getConnection();

function send_email(array $data) {

    /* Setup swiftmailer using your email server information */
    if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
        $transport = Swift_SmtpTransport::newInstance(EMAIL_HOST, EMAIL_PORT); // 25 for remote server 587 for localhost:
    } else {
        $transport = Swift_SmtpTransport::newInstance(EMAIL_HOST, 25);
    }

    $transport->setUsername(EMAIL_USERNAME);
    $transport->setPassword(EMAIL_PASSWORD);

    /* Setup To, From, Subject and Message */
    $message = Swift_Message::newInstance();

    $email_from = 'jrpepp2014@jrpepp.com';
    if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
        $comments = 'Here is you confirmation link: http://localhost/Chalkboard-Quiz/register.php?confirmation=' . $data['confirmation'];
    } else {
        $comments = 'Here is you confirmation link: https://chalkboardquiz.com/activate.php?confirmation=' . $data['confirmation'];
    }
    $message->setTo([
        $data['email'] => $data['name']
    ]);

    $subject = "Chalkboard Email Verification";

    $message->setSubject($subject); // Subject:
    $message->setBody($comments); // Message:
    $message->setFrom($email_from, 'Chalkboarde Quiz'); // From and Name:

    $mailer = Swift_Mailer::newInstance($transport); // Setting up mailer using transport info that was provided:
    $result = $mailer->send($message, $failedRecipients);

    if ($result) {
        return TRUE;
    } else {
        echo "<pre>" . print_r($failedRecipients, 1) . "</pre>";
        return FALSE;
    }
}

$register = new Users();

function duplicateUsername($username, $pdo) {

    try {
        $query = "SELECT 1 FROM members WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            return true; // userName is in database table
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function duplicateEmail($email, $pdo) {

    try {
        $query = "SELECT 1 FROM members WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            return true; // email is in database table
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (isset($submit) && $submit === 'enter') {

    $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $statusUsername = duplicateUsername($data['username'], $pdo);
    $statusEmail = duplicateEmail($data['email'], $pdo);

    if ($statusUsername && !$statusEmail) {
        $errUsername = "";
        $errEmail = $data['email'];
    } elseif ($statusEmail && !$statusUsername) {
        $errUsername = $data['username'];
        $errEmail = "";
    } elseif ($statusUsername && $statusEmail) {
        $errUsername = "";
        $errEmail = "";
    } else {

        $result = $register->register($data);
        if ($result) {
            $message = "Thank You";
        } else {
            $errUsername = $data['username'];
            $errEmail = $data['email'];
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
        <div id="registrationPage">
            <form class="registerForm" action="" method="post" autocomplete="on">

                <h1><?php echo (isset($message)) ? $message : 'Register'; ?></h1>
                <p><?php echo (isset($errPassword)) ? $errPassword : "Please fill in this form to create an account."; ?></p>
                <hr>

                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="<?php echo (isset($statusUsername)) ? "Username is not available, please re-enter!" : "Enter Username"; ?>" name="data[username]" value="<?php echo (!empty($errUsername)) ? $errUsername : Null;   ?>" autofocus required>

                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="<?php echo (isset($statusEmail)) ? "Email is not available, please re-enter!" : "Enter Email"; ?>" name="data[email]" value="<?php echo (!empty($errEmail)) ? $errEmail : null; ?>" required>

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
