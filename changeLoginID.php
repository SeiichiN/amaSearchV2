<?php
session_start();

require_once('mylib.php');
require_once('UserDB.php');

$loginId = checkLoginId();

$myobj = new UserDB();

$flag = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newID = getPost('newLoginId');
    if ($myobj->changeLoginId($loginId, $newID)) {
        $msg = "ログインIDを変更しました。";
        
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

require('header.php');
?>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<?php if ($flag !== "OK") { ?>
    <form action="" method="post">
	    <p>
	        現在のあなたのログインID -- <?php echo $loginId; ?>
	    </p>
	    <p>
	        新しいログインIDを入力してください。</br>
	        <input type="text" name="newLoginId" id="newID" required
                   value="<?php if (isset($newID)) echo h($newID); ?>">
	    </p>
	    <p>
	        <input type="submit" value="変更">
	    </p>
    </form>
<?php } ?>

<?php require_once('footer.php'); ?>
