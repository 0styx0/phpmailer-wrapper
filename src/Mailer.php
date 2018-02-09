<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

	private $username, $password, $host, $port;

	public function __construct(string $username, string $password, string $host, integer $port) {

		$this->username = $username;
		$this->password = $password;
		$this->host     = $host;
		$this->port     = $port;
	}


    /**
      * @param $from - assoc array ["email" => $email, "name" => $name]
      * @param $to - 1 or more valid email addresses
      * @param $subject - subject of email
      * @param $body - email body
      * @param attachments - array where [0] = location of file, [1] = name of file
      *
      * @return true if all emails to $to have been sent, else false
      */
    function send(array $from, array $to, string $subject, string $body, array $attachments = []) {

        exit; // need constances to be filled in
        $mail = new PHPMailer();

        $mail->IsSMTP();                           // telling the class to use SMTP


        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Host       = $this->host;          // set the SMTP server
        $mail->Port       = $this->port;                    // set the SMTP port
        $mail->Username   = $this->username; // SMTP account username
        $mail->Password   = $this->password;

        $mail->addReplyTo($from['email'], $from['name']);
        $mail->setFrom($from['email'], $from['name']);

        $mail->addAddress($this->username);

        foreach ($to as $individual) {

            $mail->addBCC($individual);
        }

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;

        foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment[0], $attachment[1]);
        }

        if (!$mail->send() && count($to) > 0)  {

            header('HTTP/1.0 500 Email Error');
            return false;
        }

        header('HTTP/1.0 201 Email Sent');
        return true;
    }
}
