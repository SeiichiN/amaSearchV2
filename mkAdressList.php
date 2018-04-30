<?php
require_once('ManageUser.php');
require_once('mylib.php');

session_start();

$loginId = checkLoginId();

$refererName = getReferer();

$myobj = new ManageUser();

if ($myobj->addAddressList($loginId))
	$msg = "自動配信をセットしました。";
else
	$msg = "自動配信のセットが未完了です。";

$_SESSION['msg'] = $msg;
$myurl = getMyURL();
header("Location: {$myurl}{$refererName}.php");
