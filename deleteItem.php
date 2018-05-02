<?php
// deleteItem.php

ini_set('session.cookie_httponly', true);
session_start();

require_once('mylib.php');
require_once('PriceDB.php');


$loginId = checkLoginId();

$asin = getPost('delAsinNo');

$mydb = new PriceDB($loginId);
if ($mydb->deleteFromList($asin)) {
	$msg = "ウォッチリストから削除しました。";
} else {
	$msg = "ウォッチリストから削除できませんでした。";
}
if ($mydb->deleteTable($asin)) {
	$msg2 = "アイテムを削除しました。";
} else {
	$msg2 = "......";
}

require_once('header.php');
?>
<p><?php echo $msg; ?></p>
<p><?php echo $msg2; ?></p>
<p><a href="amazonListAll.php">ウォッチ一覧に戻る</a></p>
<?php require_once('footer.php'); ?>
