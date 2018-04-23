<?php
session_start();

if (isset($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];
else
	header('Location: index.php');

require_once('Bot.php');
require_once('UserDB.php');


function getMailAddress($loginId) {
	$mydb = new UserDB();
	$email = $mydb->getMailAddress($loginId);
	return $email;
}

// echo "アマゾンの現在価格を調べます。\n";
$mailAddress = getMailAddress($loginId);
$myobj = new Bot();
$msg = $myobj->checkNow($loginId, $mailAddress);

setcookie('msg', $msg);
header('Location: index.php');







