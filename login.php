<?php
// login.php
require_once('mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$_SESSION['loginId'] = 'guest';     // . $date("YmdHis");

if (isset($_SESSION['auth'])) {
	if ($_SESSION['auth'] == 'no') {
		$msg = "ID か Password が違うようです。";
	}
}

require_once('header.php');
?>

<h1>ログイン</h1>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<form action="member.php" method="post">
    <p>
        <label for="loginId">ID:</label><br>
        <input type="text" name="loginId" id="loginId" class="input-box">
    </p>
    <p>
        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" class="input-box">
    </p>
    <input type="submit" value="ログイン" class="singleBtn">
</form>
<?php require_once ('footer.php'); ?>

