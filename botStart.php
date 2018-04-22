<?php
session_start();

if (isset($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];
else
	header('Location: index.php');

require_once('Bot.php');

// echo "アマゾンの現在価格を調べます。\n";
$myobj = new Bot($loginId);
$myobj->checkNow();

header('Location: index.php');













