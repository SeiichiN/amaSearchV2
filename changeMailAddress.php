<?php

ini_set('session.cookie_httponly', true);
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
    <section class="setting">
        <h2>メールアドレスの変更</h2>
        <form action="" method="post">
	        <div class="head">現在のあなたのメールアドレス</div>
            <div class="address"><?php echo $nowEmail; ?></div>
	        <div class="head">新しいメールアドレスを入力してください。</div>
	        <input type="email" name="newEmail" id="newEmail" class="input-box" required
                   value="<?php if (isset($newEmail)) echo h($newEmail); ?>">
	        <p>
	            <input type="submit" value="変更" class="singleBtn">
	        </p>
        </form>
    </section>
<?php } ?>

<?php require_once('footer.php'); ?>
