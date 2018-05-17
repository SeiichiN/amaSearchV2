<?php

require_once('lib/mylib.php');
require_once('Bot.php');
require_once('UserDB.php');

ini_set('session.cookie_httponly', true);
session_start();


$loginId = checkLoginId();

$mydb = new UserDB();
$mailAddress = $mydb->getMailAddress($loginId);

// echo "アマゾンの現在価格を調べます。\n";
$myobj = new Bot();
$msg = $myobj->checkNow($loginId, $mailAddress);

$_SESSION['msg'] = $msg;

$myurl = getMyURL();
header('Location: '.$myurl.'index.php');
