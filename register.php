<?php
// register.php
require_once('PriceDB.php');

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['passwd'])) {
	$loginId = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$passwd = $_POST['passwd'];
	$member = [
		'loginId' => $loginId,
		'name'    => $name,
		'email'   => $email,
		'passwd'  => $passwd,
		];
} else {
	header('Location: login.php');
	exit();
}

// この後、メンバー情報をデータベースに登録する。
$mydb = new PriceDB();
$mydb->registUser($member);

// メイン処理.phpに処理を移す。
header('Location: hungup.php');
