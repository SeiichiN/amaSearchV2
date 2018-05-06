<?php
// register.php
require_once('UserDB.php');
require_once('lib/mylib.php');
require_once('ManageUser.php');
require_once('conf/mail_conf.php');
require_once('lib/MyValidator.php');

ini_set('session.cookie_httponly', true);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$loginId = getPost('id');
	$name = getPost('name');
	$email = getPost('email');
    // newmember.phpでユーザが入力した値を再表示できるように、覚えておく。
    $_SESSION['tmp_loginId'] = $loginId;
    $_SESSION['tmp_name'] = $name;
    $_SESSION['tmp_email'] = $email;
} else {
	$myurl = getMyURL();
	$referer = getReferer();
	header('Location: ' . $myurl . $referer . '.php');
	exit();
}

$myurl = getMyURL();

// 入力値のチェック
$v = new MyValidator();
$v->lengthCheck($loginId, 'ログイン名', 20);
$v->lengthCheck($name, 'お名前', 30);
$v->lengthCheck($email, 'メールアドレス', 50);
$err = $v();
if ($err) {
	$_SESSION['error'] = $err;
	header ('Location: '.$myurl.'newmember.php');
	exit();
}

// データベースに接続
$mydb = new UserDB();

// ログイン名とメールアドレスに過去の重複がないかを調べる。
$flag = 'OK';
if ($mydb->existLoginId($loginId)) {
	$_SESSION['overlapId'] = 'yes';
    $flag = 'NO';
}
if ($mydb->existEmail($email)) {
    $_SESSION['overlapEmail'] = 'yes';
    $flag = 'NO';
}
if ($flag === 'NO') {
	header('Location: '.$myurl.'newmember.php');
	exit();    
} else {
    // 初期パスワードを生成する
	$passwd = generate_password(8);
	// パスワードをハッシュ化する。
	$passwd_hash = password_hash($passwd, PASSWORD_DEFAULT);
    // メンバー情報をデータベースに登録する。
	$member_hash = [
		'loginId' => $loginId,
		'name'    => $name,
		'email'   => $email,
		'passwd'  => $passwd_hash,
		];
	$mydb->registUser($member_hash);

	$member = [
		'loginId' => $loginId,
		'name'    => $name,
		'email'   => $email,
		'passwd'  => $passwd,
		];
	$myManageUser = new ManageUser();
    // メンバー登録の報告をbillie175@gmail.comに送る。
    if ($myManageUser->inform($member, SITE_MANAGER))
        $_SESSION['msg'] = '新規登録の処理を始めています。';
    else
        $_SESSION['msg'] =  '新規登録がうまくいっていません。';
            
    // セッションIDの再作成
    session_regenerate_id(true);
    // セッション変数loginIdにログインIDを登録する。
    // $_SESSION['loginId'] = $loginId;

    header('Location: '.$myurl.'afterRegist.php');
}