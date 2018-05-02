<?php
ini_set('session.cookie_httponly', true);
session_start();


$_SESSION = [];
if (isset($_COOKIE[session_name()])) {
	$cparam = session_get_cookie_params();
	setcookie(session_name(), '', time() - 3600,
			  $cparam['path'], $cparam['domain'],
			  $cparam['secure'], $cparam['httponly']);
}

setcookie('category', '', time() - 3600);
setcookie('asin', '', time() - 3600);
setcookie('keyword', '', time() - 3600);


header('Location: index.php');

