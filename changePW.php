<?php
session_start();

require_once('mylib.php');
require_once('UserDB.php');

if (!empty($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];
else
	header('Location: index.php');

$myobj = new UserDB();

$flag = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$oldPW = getPost('oldPassword');
	$newPW = getPost('newPassword');
	$renewPW = getPost('renewPassword');
	if (($oldPW === $myobj->getPasswd($loginId)) && ($newPW === $renewPW)) {
		if ($myobj->changePasswd($loginId, $newPW)) {
            $msg = "パスワードを変更しました。";
            $flag = "OK";
        } else {
            $msg = "パスワードは変更できませんでした。";
        }
	} else {
		$msg = "入力が正しくありません。";
    }
}

require('header.php');
?>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<?php if ($flag !== "OK") { ?>
    <form action="" method="post">
	    <p>
	        現在のパスワードを入力してください。<br>
	        <input type="password" name="oldPassword" id="oldPW" required
	               value="<?php if (isset($oldPW)) echo $oldPW; ?>">
	    </p>
	    <p>
	        新しいパスワードを入力してください。</br>
	        <input type="password" name="newPassword" id="newPW" required
                   value="<?php if (isset($newPW)) echo $newPW; ?>">
	    </p>
        <p>
            確認のため、<br>
	        もう一度新しいパスワードを入力してください。</br>
	        <input type="password" name="renewPassword" id="renewPW" required
                   value="<?php if (isset($renewPW)) echo $renewPW; ?>">
	    </p>
	    <p>
	        <input type="submit" value="変更">
	    </p>
    </form>
<?php } ?>

<?php require_once('footer.php'); ?>
