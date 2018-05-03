<?php
// member.php
require_once('UserDB.php');
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$myurl = getMyURL();

// if (isset($_COOKIE['overlapId']))
//     setcookie('overlapId', '', time() - 3600);
// if (isset($_COOKIE['overlapEmail']))
//     setcookie('overlapEmail', '', time() - 3600);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$loginId = getPost('loginId');
	$passwd = getPost('password');
} else {
	header('Location: '. $myurl . 'login.php');
	exit();
}

// データベースに接続
$mydb = new UserDB();

// ログイン名とパスワードを調べる。
//    ['loginId', 'name', 'passwd', 'email']
$flag = 'NO';
if ($mydb->existLoginId($loginId)) {
	if ($passwd === $mydb->getPasswd($loginId)) {
		$flag = 'OK';
	}
}

if ($flag === 'NO') {
	$_SESSION['auth'] = 'no';
	header('Location: login.php');
	exit();    
}
if ($flag === 'OK') {
	if (isset($_SESSION['auth']))
		$_SESSION['auth'] === '';

    // セッションIDの再作成
    session_regenerate_id(true);

    $_SESSION['loginId'] = $loginId;
    $_SESSION['passwd'] = $passwd;
    $_SESSION['guestId'] = '';

    header('Location: '.$myurl.'index.php');
}














