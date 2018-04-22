<?php
// register.php
require_once('UserDB.php');
require_once('mylib.php');

session_start();

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['passwd'])) {
	$loginId = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$passwd = $_POST['passwd'];
    $_SESSION['loginId'] = $loginId;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['passwd'] = $passwd;
} else {
//	header('Location: login.php');
//	exit();
}

// データベースに接続
$mydb = new UserDB();

// ログイン名とメールアドレスに過去の重複がないかを調べる。
//    ['loginId', 'name', 'passwd', 'email']
$flag = 'OK';
if ($mydb->findUser('loginId', $loginId)) {
	setcookie('overlapId', 'yes');
    $flag = 'retry';
} else {
    if (isset($_COOKIE['overlapId']))
        setcookie('overlapId', '', time() - 3600);
}
if ($mydb->findUser('email', $email)) {
	setcookie('overlapEmail', 'yes');
    $flag = 'retry';
} else {
    if (isset($_COOKIE['overlapEmail']))
        setcookie('overlapEmail', '', time() - 3600);
}
if ($flag === 'retry') {
	header('Location: newmember.php');
	exit();    
} else {
    // メンバー情報をデータベースに登録する。
	$member = [
		'loginId' => $loginId,
		'name'    => $name,
		'email'   => $email,
		'passwd'  => $passwd,
		];
	$mydb->registUser($member);
    // セッションIDの再作成
    session_regenerate_id(true);
	$msg = "登録しました。";
}
// メイン処理.phpに処理を移す。
// header('Location: XXXX.php');
