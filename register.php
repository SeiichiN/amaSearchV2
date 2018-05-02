<?php
// register.php
require_once('UserDB.php');
require_once('mylib.php');
require_once('ManageUser.php');
require_once('conf/mail_conf.php');

ini_set('session.cookie_httponly', true);
session_start();

    
if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['id'])) {
	$loginId = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
    // newmember.phpでユーザが入力した値を再表示できるように、覚えておく。
    $_SESSION['tmp_loginId'] = $loginId;
    $_SESSION['tmp_name'] = $name;
    $_SESSION['tmp_email'] = $email;
} else {
//	header('Location: login.php');
//	exit();
}

$myurl = getMyURL();

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
    // メンバー情報をデータベースに登録する。
	$member = [
		'loginId' => $loginId,
		'name'    => $name,
		'email'   => $email,
		'passwd'  => $passwd,
		];
	$mydb->registUser($member);

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

