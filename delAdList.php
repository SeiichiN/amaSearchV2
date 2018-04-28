<?php
require_once('ManageUser.php');
require_once('mylib.php');

session_start();

if (isset($_SESSION['loginId'])) {
    $loginId = $_SESSION['loginId'];
} else {
    header('Location: index.php');
    exit();
}

$refererName = getRefere();

$myobj = new ManageUser();

if ($myobj->delAddressList($loginId))
	$msg = "自動配信を解除しました。";
else
	$msg = "自動配信のセットが未完了です。";

setcookie('msg', $msg);
$myurl = getMyURL();
header("Location: {$myurl}{$refererName}.php");
