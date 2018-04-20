<?php
// require_once ('vendor/autoload.php');
require_once ('mail_conf.php');
use PHPMailer\PHPMailer\PHPMailer;

/**
 * @param: $subject
 *         $body
 *         $to -- send to.
 * @return:
 *         boolean.
 *       
 */
function gmail($subject, $body, $to) {
	$from = GMAIL_ACCOUNT;
	$pass = GMAIL_PASS;
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->CharSet = 'utf-8';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = $from;
	$mail->Password = $pass;
	$mail->setFrom($from, 'Shinichi Nakayama');
	$mail->addReplyTo($from, 'Shinichi Nakayama');
	$mail->addAddress($to);
	$mail->Subject = $subject;
	$mail->Body = $body;
	return $mail->send();
}
