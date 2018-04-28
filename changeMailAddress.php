<?php
session_start();

require_once('mylib.php');
require_once('UserDB.php');

if (!empty($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];
else
	header('Location: index.php');

$myobj = new UserDB();

$nowEmail = $myobj->getMailAddress($loginId);

$flag = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newEmail = trim(getPost('newEmail'));
    if ($myobj->changeMailAddress($loginId, $newEmail)) {
        $msg = "メールアドレスを変更しました。";
        // 必要ならaddress.jsonも変更しなくてはならない。
        $myjson = new ManageUser();
        if ($myjson->changeAdListEmail($loginId, $newEmail)) {
            $msg = $msg . "<br>自動配信リストも変更しておきました。";
        }
        $flag = "OK";
    } else {
        $msg = "メールアドレスは変更できませんでした。";
    }
}

require('header.php');
?>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<?php if ($flag !== "OK") { ?>
    <form action="" method="post">
	    <p>
	        現在のあなたのメールアドレス -- <?php echo $nowEmail; ?>
	    </p>
	    <p>
	        新しいメールアドレスを入力してください。</br>
	        <input type="text" name="newEmail" id="newEmail" required
                   value="<?php if (isset($newEmail)) echo h($newEmail); ?>">
	    </p>
	    <p>
	        <input type="submit" value="変更">
	    </p>
    </form>
<?php } ?>

<?php require_once('footer.php'); ?>
