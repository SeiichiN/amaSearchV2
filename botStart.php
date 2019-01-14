<?php

require_once('lib/mylib.php');
require_once('Bot.php');
require_once('UserDB.php');

ini_set('session.cookie_httponly', true);
session_start();

$loop = React\EventLoop\Factory::create();


$loginId = checkLoginId();

$mydb = new UserDB();
$mailAddress = $mydb->getMailAddress($loginId);

// echo "アマゾンの現在価格を調べます。\n";
$myobj = new Bot();


$loop->futureTick(function() use ($myobj, $loginId, $mailAddress) {
    file_put_contents("temp.txt", "処理の中ですのだ。");
	$msg = $myobj->checkNow($loginId, $mailAddress);
});


$msg = "アマゾンの現在価格を調べます。";
$_SESSION['msg'] = $msg;

$myurl = getMyURL();

ob_end_flush();
require_once 'header.php';
echo "<h1>さいこー</h1>";
require_once 'footer.php';
flush();

$loop->run();

$msg = "現在価格を調べ終わりました。";
file_put_contents("file.txt", $msg);

$_SESSION['msg'] = $msg;

$myurl = getMyURL();
require_once 'header.php';
echo '<a href="index.php">トップへ</a>';
require_once 'footer.php';
flush();
