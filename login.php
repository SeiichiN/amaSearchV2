<?php
// login.php
require_once('mylib.php');
session_start();

$_SESSION['guestId'] = 'guest';     // . $date("YmdHis");

if (isset($_COOKIE['auth'])) {
	if ($_COOKIE['auth'] == 'no') {
		$msg = "ID か Password が違うようです。";
		setcookie('auth', '', time() - 3600);
	}
}

require_once('header.php');
?>

<h1>ログイン</h1>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<form action="member.php" method="post">
    <p>
        <label for="id">ID:</label>
        <input type="text" name="id" id="id">
    </p>
    <p>
        <label for="pw">Password:</label>
        <input type="password" name="password" id="pw">
    </p>
    <input type="submit" value="ログイン">
</form>
<?php require_once ('footer.php'); ?>


