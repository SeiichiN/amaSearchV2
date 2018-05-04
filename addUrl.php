<?php
require_once('lib/mylib.php');
require_once('PriceDB.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

$mydb = new PriceDB($loginId);

require_once('header.php');

if  ($msg = $mydb->addColumn('url'))
	echo '<p>', $msg, '</p>';
else
	echo 'うまくいきませんでした。';

require_once('footer.php');

