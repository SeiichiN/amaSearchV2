<?php
// login.php
require_once('mylib.php');
session_start();

$_SESSION['guestId'] = 'guest';     // . $date("YmdHis");

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
        <label for="loginId">ID:</label>
        <input type="text" name="loginId" id="loginId">
    </p>
    <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </p>
    <input type="submit" value="ログイン">
</form>
<?php require_once ('footer.php'); ?>

