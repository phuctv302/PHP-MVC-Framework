<?php

namespace core;

use models\User;

class Email extends Controller {
    public string $to = '';
    public string $from = '';

    // pass to view
    public string $firstname = '';
    public string $url = '';


    public function __construct(User $user, string $url){
        $this->to = $user->email;
        $this->from = $_ENV['EMAIL_FROM'];
        $this->firstname = $user->firstname;
        $this->url = $url;
    }

    // send actual email with nice UI
    public function send($view, $subject){
        // get html
        $message = $this->render($view, [
            'firstname' => $this->firstname,
            'url' => $this->url,
            'subject' => $subject
        ]);

        // email options
        // It is mandatory to set the content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers. From is required, rest other headers are optional
        $headers .= 'From: <phuctanki323232@gmail.com>' . "\r\n";
        $headers .= 'Cc: sales@example.com' . "\r\n";

        // send mail
        return mail($this->to, $subject, $message, $headers);
    }

    public function sendPasswordReset(){
        return $this->send('reset.email', 'Your password reset token');
    }
}