<?php

namespace services;

class Email {
    public $to = '';
    public $from = '';

    public function __construct($user){
        $this->to = $user->email;
        $this->from = $_ENV['EMAIL_FROM'];
    }

    // send actual email with nice UI
    public function send($message, $subject){
        // email options
        // It is mandatory to set the content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers. From is required, rest other headers are optional
        $headers .= 'From: <' . $_ENV['EMAIL_FROM'] . '>' . "\r\n";
        //        $headers .= 'Cc: sales@example.com' . "\r\n";

        // send mails
        return mail($this->to, $subject, $message, $headers);
    }
}
