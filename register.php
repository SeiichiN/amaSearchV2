<?php
// register.php
require_once('UserDB.php');
require_once('mylib.php');
require_once('mkpasswd.php');
require_once('mymail.php');

session_start();

function inform($member) {
	$to = 'billie175@gmail.com';
	$reply = $member['email'];
	$subject = '新規登録のお知らせ';
	$body = "AmazonSeeach にて、{$member['loginId']}さま"
		  . "（実名：{$member['name']}さま）が登録されました。>\n"
		  . "初期パスワードは「{$member['passwd']} 」です。>\n"
		. 'http://192.168.11.64/amaSearchV2/tellPasswd.php 返事を送る';
	return gmail($subject, $body, $to, $reply);
}
    
if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['id'])) {
	$loginId = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
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

    // メンバー登録の報告をbillie175@gmail.comに送る。
    if (inform($member))
        echo 'メール送信しました。';
    else
        echo 'メール送信に失敗しました。';
            
    // セッションIDの再作成
    session_regenerate_id(true);
	$msg = "登録しました";
}

require_once('header.php');
?>
<h1><?php if (isset($msg)) echo $msg; ?></h1>
<p>折り返し、管理人より登録されたメールアドレスに初期パスワードの連絡をお送りいたします。</p>
<p>しばらくお待ちください。</p>
<?php require_once('footer.php'); ?>
