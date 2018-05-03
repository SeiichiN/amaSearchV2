<?php
ini_set('session.cookie_httponly', true);
session_start();

require_once('lib/mylib.php');


$_SESSION = [];
if (isset($_COOKIE[session_name()])) {
	$cparam = session_get_cookie_params();
	setcookie(session_name(), '', time() - 3600,
			  $cparam['path'], $cparam['domain'],
			  $cparam['secure'], $cparam['httponly']);
}

$myurl = getMyURL();

header('Location: '.$myurl.'index.php');

