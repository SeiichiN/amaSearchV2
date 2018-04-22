<?php
// member.php
require_once('PriceDB.php');
require_once('mylib.php');

session_start();

if (!empty($_POST['id']) && !empty($_POST['password'])) {
	$loginId = $_POST['id'];
	$passwd = $_POST['password'];
} else {
	header('Location: login.php');
	exit();
}
echo "ここまではきてるよ\n";
// データベースに接続
$mydb = new PriceDB();

// ログイン名とパスワードを調べる。
//    ['loginId', 'name', 'passwd', 'email']
$flag_id = '';
$flag_pw = '';
if ($mydb->findUser('loginId', $loginId)) {
    $flag_id = 'OK';
} else {
	$flag_id = 'NO';
}
if ($mydb->findUser('password', $passwd)) {
    $flag_pw = 'OK';
} else {
	$flag_pw = 'NO';
}
if ($flag_id == 'NO' || $flag_pw == 'NO') {
	setcookie('auth', 'no');
	header('Location: login.php');
	exit();    
}
if ($flag_id === 'OK' && $flag_pw === 'OK') {
	if (isset($_COOKIE['auth'])) {
		setcookie('auth', '', time() - 3600);
	}
    // セッションIDの再作成
    session_regenerate_id(true);

    $_SESSION['loginid'] = $loginId;
    $_SESSION['passwd'] = $passwd;
    $_SESSION['guestid'] = '';

    header('Location: amazonFind.php');
}
