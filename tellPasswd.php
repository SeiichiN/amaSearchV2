<?php
// tellPasswd.php
require_once('mylib.php');
require_once('UserDB.php');
require_once('mymail.php');

$mydb = new UserDB();

echo "接続できてるよ。\n";

if ($_SERVER['REQUEST_METHOD'] === 'GET')
	$loginId = $_GET['id'];

echo "loginId = {$loginId}\n";

$passwd = $mydb->getPasswd($loginId);

echo "passwd = {$passwd}\n";

$subject = "登録のお知らせ";

$body = "ご登録ありがとうございます。\n"
	. "{$loginId}様の初期パスワードは {$passwd} です。\n"
	. "「アカウント設定」の画面でパスワードの変更は可能です。\n";

$to = $mydb->getMailAddress($loginId);

$reply = "billie175@gmail.com, (Seiichi Nukayama)";


if (gmail($subject, $body, $to, $reply) ) {
	echo "メール送信しました。";
} else {
	echo "メール送信に失敗しました。";
}

?>
