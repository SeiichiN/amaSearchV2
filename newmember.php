<?php
// newmember.php
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$msg1 = '';
$msg2 = '';
$loginId = !empty($_SESSION['tmp_loginId']) ? $_SESSION['tmp_loginId'] : NULL;
$name = !empty($_SESSION['tmp_name']) ? $_SESSION['tmp_name'] : NULL;
$email = !empty($_SESSION['tmp_email']) ? $_SESSION['tmp_email'] : NULL;
$passwd = !empty($_SESSION['tmp_passwd']) ? $_SESSION['tmp_passwd'] : NULL;

if (isset($_SESSION['overlapId'])) {
    if ($_SESSION['overlapId'] === 'yes') {
	    $msg1 = "そのログイン名はすでに使われています。";
		$_SESSION['overlapId'] = '';
    } else {
        $msg1 = '';
    }
}
if (isset($_SESSION['overlapEmail'])) {
    if ($_SESSION['overlapEmail'] === 'yes') {
        $msg2 = "そのE-mailアドレスはすでに使われています。";
		$_SESSION['overlapEmail'] = '';
    } else {
        $msg2 = '';
    }
}

$valiMsg = getSession('error');

?>
<?php require_once('header.php'); ?>

<h1>アマゾンサーチへようこそ</h1>

<?php prValidateError($valiMsg); ?>

<p>新規登録をおこないます。</p>
<form action="register.php" method="post">
	<p>
        <label for="id">ログイン名を入力してください。（20文字以内）</label><br>
        <input type="text" name="id" value="<?php echo h($loginId); ?>" id="id" class="input-box" required><br>
        <small>ログイン時に表示される名前です。ニックネームでもいいです。<br>
            半角英数字でもＯＫです。<br>
        全角・半角とも20文字以内でおねがいします。</small><br>
        <span class="notice"><?php echo h($msg1); ?></span>
    </p>
    <p>
        <label for="name">お名前を入力してください。（30文字以内）</label><br>
        <input type="text" name="name" value="<?php echo h($name); ?>" id="name" class="input-box" required><br>
        <small>実名でおねがいします。全角半角とも30文字以内でおねがいします。</small>
    </p>
    <p>
        <label for="email">メールアドレスを入力してください。</label><br>
        <input type="email" name="email" value="<?php echo h($email); ?>" id="email" class="input-box" required><br>
        <small>このメールアドレスに価格情報をお届けします。<br>
        半角50文字以内でおねがいします。</small><br>
        <span class="notice"><?php echo h($msg2); ?></span>
    </p>
    <p>
        <input type="submit" value="決定" class="singleBtn">
    </p>
</form>
<?php require_once('footer.php'); ?>
