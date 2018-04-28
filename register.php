<?php
// register.php
require_once('UserDB.php');
require_once('mylib.php');
require_once ('ManageUser.php');

session_start();

    
if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['id'])) {
	$loginId = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
    // newmember.phpでユーザが入力した値を再表示できるように、覚えておく。
    $_SESSION['loginId'] = $loginId;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
} else {
//	header('Location: login.php');
//	exit();
}

// データベースに接続
$mydb = new UserDB();

// ログイン名とメールアドレスに過去の重複がないかを調べる。
$flag = 'OK';
if ($mydb->existLoginId($loginId)) {
	setcookie('overlapId', 'yes');
    $flag = 'NO';
}
if ($mydb->existEmail($email)) {
    setcookie('overlapEmail', 'yes');
    $flag = 'NO';
}
if ($flag === 'NO') {
	header('Location: newmember.php');
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
    if ($myManageUser->inform($member, 'billie175@gmail.com'))
        echo 'メール送信しました。';
    else
        echo 'メール送信に失敗しました。';
            
    // セッションIDの再作成
    session_regenerate_id(true);
    // セッション変数loginIdにログインIDを登録する。
    $_SESSION['loginId'] = $loginId;

    header('Location: afterRegist.php');
}

