<?php
// login.php
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$_SESSION['loginId'] = 'guest';     // . $date("YmdHis");

if (isset($_SESSION['auth'])) {
	if ($_SESSION['auth'] == 'no') {
		$msg = "ID か Password が違うようです。";
	}
}
$valiMsg = getSession('error');

require_once('header.php');
?>

<h1>ログイン</h1>

<?php prValidateError($valiMsg); ?>

<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<form action="member.php" method="post">
    <p>
        <label for="loginId">ログイン名:</label><br>
        <input type="text" name="loginId" id="loginId" class="input-box" required>
    </p>
    <p>
        <label for="password">パスワード:</label><br>
        <input type="password" name="password" id="password" class="input-box" required>
    </p>
    <input type="submit" value="ログイン" class="singleBtn">
</form>
<?php require_once ('footer.php'); ?>










