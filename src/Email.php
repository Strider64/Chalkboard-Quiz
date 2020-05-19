<?php

namespace Library;

/*
 * In order to use swiftmailer the below is needed....
 */

use Swift_SmtpTransport;
use Swift_Message;
use Swift_Mailer;

class Email {

    public $result = \NULL;

    public function __construct(array $data) {
        $this->result = $this->email($data);
    }

    private function email(array $data) {
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

        $name = 'John Pepp';
        $email_from = 'jrpepp@pepster.com';
        $subject = "Activate User Account";
        
        if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
            $comments = 'Here is you confirmation link: http://localhost/Chalkboard-Quiz/register.php?confirmation=' . $data['status'];
        } else {
            $comments = 'Here is you confirmation link: https://chalkboardquiz.com/activate.php?confirmation=' . $data['status'];
        }
        /*
         * Email Address message is going to
         */
        $message->setTo(['jrpepp@pepster.com', 'jrpepp@pepster.com' => 'John Pepp']);

        $message->setSubject($subject); // Subject:
        $message->setBody($comments); // Message:
        $message->setFrom($email_from, $name); // From and Name:

        $mailer = Swift_Mailer::newInstance($transport); // Setting up mailer using transport info that was provided:
        $result = $mailer->send($message, $failedRecipients);

        if ($result) {
            return TRUE;
        } else {
            echo "<pre>" . print_r($failedRecipients, 1) . "</pre>";
            return FALSE;
        }
    }

}
