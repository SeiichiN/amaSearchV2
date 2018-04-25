<?php
// tellPasswd.php

require_once('UserDB.php');

$mydb = new UserDB();

echo "接続できてるよ。\n";

if ($_SERVER['REQUEST_METHOD'] === 'GET')
	$loginId = $_GET['id'];

echo "loginId = {$loginId}";

?>
