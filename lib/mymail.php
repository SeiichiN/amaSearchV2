<?php
require_once ('vendor/autoload.php');
require_once ('conf/mail_conf.php');
use PHPMailer\PHPMailer\PHPMailer;

/**
 * @params: string $subject
 *          string $body
 *          string $to -- send to.
 *          string $reply -- 返信先。もし引数が指定されなかったらNULL。
 * @return:
 *         boolean TRUE.
 *       
 */
function gmail($subject, $body, $to, $reply = NULL) {
	$from = GMAIL_ACCOUNT;
	$pass = GMAIL_PASS;
	if ($reply == NULL) $reply = $from;
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->CharSet = 'utf-8';
	$mail->Host = SMTP_SERVER;
	$mail->Port = SMTP_PORT;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = $from;
	$mail->Password = $pass;
	$mail->setFrom($from, FROM_NAME);
	$mail->addReplyTo($reply);
	$mail->addAddress($to);
	$mail->Subject = $subject;
	$mail->Body = $body;
	return $mail->send();
}