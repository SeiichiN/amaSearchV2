<?php

ini_set('session.cookie_httponly', true);
session_start();

require_once('lib/mylib.php');
require_once('UserDB.php');
require_once('ManageUser.php');
require_once('lib/MyValidator.php');

$loginId = checkLoginId();

$myobj = new UserDB();

$nowEmail = $myobj->getMailAddress($loginId);

$flag = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newEmail = trim(getPost('newEmail'));

	// 入力値チェック
	$v = new MyValidator();
	$v->lengthCheck($newEmail, '新しいメールアドレス', 50);
	$err = $v();
	if (!$err) {
		// メールアドレスの変更
		if ($myobj->changeMailAddress($loginId, $newEmail)) {
			$msg = "メールアドレスを変更しました。";
			// 必要ならADDRESS_LISTも変更しなくてはならない。
			$myjson = new ManageUser();
			if ($myjson->changeAdListEmail($loginId, $newEmail)) {
				$msg = $msg . "<br>自動配信リストも変更しておきました。";
			}
			$flag = "OK";
		} else {
			$msg = "メールアドレスは変更できませんでした。";
		}
	}
}

require('header.php');
?>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<?php if (!empty($err)) {prValidateError($err); unset($err);} ?>

<?php if ($flag !== "OK") { ?>
    <section class="setting">
        <h2>メールアドレスの変更</h2>
        <form action="" method="post">
	        <div class="head">現在のあなたのメールアドレス</div>
            <div class="address"><?php echo $nowEmail; ?></div>
	        <div class="head">新しいメールアドレスを入力してください。（50文字以内）</div>
	        <input type="email" name="newEmail" id="newEmail" class="input-box" required
                   value="<?php if (isset($newEmail)) echo h($newEmail); ?>">
	        <p>
	            <input type="submit" value="変更" class="singleBtn">
	        </p>
        </form>
    </section>
<?php } ?>
<p><a href="index.php">TOPに戻る</a></p>
<?php require_once('footer.php'); ?>
