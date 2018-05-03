<?php
ini_set('session.cookie_httponly', true);
session_start();

require_once('lib/mylib.php');
require_once('UserDB.php');
require_once('lib/MyValidator.php');

$loginId = checkLoginId();

$myobj = new UserDB();

$flag = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newID = getPost('newLoginId');

	// 入力値のチェック
	$v = new MyValidator();
	$v->lengthCheck($newID, 'ログイン名', 20);
	$err = $v();
	if (!$err) {
		if ($myobj->changeLoginId($loginId, $newID)) {
			$msg = "ログイン名を変更しました。";
        
			// データベース名も変更しなければならない。
			$oldFileName = 'db/' . $loginId . '.db';
			$newFileName = 'db/' . $newID . '.db';
			if (rename($oldFileName, $newFileName)) {
				$msg= $msg . "データベース名も変更しました。";
			}

			// ログイン名を変更したので、
			// 現在の表示を変更する。
			$_SESSION['loginId'] = $newID;
			$loginId = $newID;
			
			$flag = "OK";
		} else {
			$msg = "ログインIDは変更できませんでした。";
		}
	}
}

require('header.php');
?>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>

<?php if (!empty($err)) {prValidateError($err); unset($err);} ?>

<?php if ($flag !== "OK") { ?>
    <section class="setting">
	    <h2>ログイン名の変更</h2>
        <form action="" method="post">
	        <p>
	            現在のあなたのログイン名 -- <?php echo $loginId; ?>
	        </p>
	        <p>
	            新しいログイン名を入力してください。（20文字以内）</br>
	            <input type="text" name="newLoginId" id="newID" class="input-box" required
                       value="<?php if (isset($newID)) echo h($newID); ?>">
	        </p>
	        <p>
	            <input type="submit" value="変更" class="singleBtn">
	        </p>
        </form>
    </section>
<?php } ?>
<p><a href="index.php">TOPに戻る</a></p>

<?php require_once('footer.php'); ?>