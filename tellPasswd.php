<?php
// tellPasswd.php
require_once('lib/mylib.php');
require_once('UserDB.php');
require_once('lib/mymail.php');
require_once('conf/mail_conf.php');

$mydb = new UserDB();

require_once('header.php');

echo "接続OK ";

if ($_SERVER['REQUEST_METHOD'] === 'GET')
	$loginId = $_GET['id'];

echo "loginId = {$loginId} ";

$passwd = $mydb->getPasswd($loginId);

echo "passwd = {$passwd} ";

$subject = "登録のお知らせ";

$body = "ご登録ありがとうございます。\n"
	. "{$loginId}様の初期パスワードは {$passwd} です。\n"
	. "「アカウント設定」の画面でパスワードの変更が可能です。\n";

$to = $mydb->getMailAddress($loginId);

$reply = REPLY_ADDRESS;


if (gmail($subject, $body, $to, $reply) ) {
	echo "メール送信しました。";
	if ($mydb->setActivity($loginId))
		echo "アクティビティに1をセットしました。";
} else {
	echo "メール送信に失敗しました。";
}

require_once('footer.php');