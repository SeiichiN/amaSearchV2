<?php
// newmember.php
require_once('mylib.php');

session_start();

$msg1 = '';
$msg2 = '';
$loginId = !empty($_SESSION['loginId']) ? $_SESSION['loginId'] : '';
$name = !empty($_SESSION['name']) ? $_SESSION['name'] : '';
$email = !empty($_SESSION['email']) ? $_SESSION['email'] : '';
$passwd = !empty($_SESSION['passwd']) ? $_SESSION['passwd'] : '';

if (isset($_COOKIE['overlapId'])) {
    if ($_COOKIE['overlapId'] == 'yes') {
	    $msg1 = "そのログイン名はすでに使われています。";
        setcookie('overlapId', '', time() - 3600);
    } else {
        $msg1 = '';
    }
}
if (isset($_COOKIE['overlapEmail'])) {
    if ($_COOKIE['overlapEmail'] == 'yes') {
        $msg2 = "そのE-mailアドレスはすでに使われています。";
        setcookie('overlapEmail', '', time() -3600);
    } else {
        $msg2 = '';
    }
}
?>
<?php require_once('header.php'); ?>
<h1>アマゾンサーチへようこそ</h1>
<p>新規登録をおこないます。</p>
<form action="register.php" method="post">
	<p>
        <label for="id">ログイン名を入力してください。</label><br>
        <input type="text" name="id" value="<?php echo h($loginId); ?>" id="id" required><br>
        <small>ログイン時に表示される名前です。ニックネームでもいいです。<br>
            半角英数字でもＯＫです。</small><br>
        <span class="notice"><?php echo h($msg1); ?></span>
    </p>
    <p>
        <label for="name">お名前を入力してください。</label><br>
        <input type="text" name="name" value="<?php echo h($name); ?>" id="name" required><br>
        <small>実名でおねがいします。</small>
    </p>
    <p>
        <label for="email">メールアドレスを入力してください。</label><br>
        <input type="email" name="email" value="<?php echo h($email); ?>" id="email" required><br>
        <small>このメールアドレスに価格情報をお届けします。</small><br>
        <span class="notice"><?php echo h($msg2); ?></span>
    </p>
    <p>
        <input type="submit" value="決定">
    </p>
</form>
<?php require_once('footer.php'); ?>
