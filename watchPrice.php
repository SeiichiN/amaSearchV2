<?php
// namespace billiesworks

// require_once('mylib.php');
require_once('PriceDB.php');

session_start();

if (isset($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];

if(!empty($_POST['asin'])) {
	$asin = $_POST['asin'];
	$title = $_POST['title'];
//	$price = $_POST['price'];

	// それぞれの値を数値に変換
	// もし空文字列なら、-1 を入れる。
	/* for($i = 0; $i < count($price); $i++) {
	   if ($price[$i] === '')
	   $price[$i] = -1;
	   else
	   $price[$i] = (int)$price[$i];
	   }*/
	
	$newAmazonPrice = [
		'official_p' => (int)$_POST['officialPrice'],
		'new_p' => (int)$_POST['newPrice'],
		'used_p' => (int)$_POST['usedPrice'],
		'collectible_p' => (int)$_POST['collectiblePrice']
		];

	$myobj = new PriceDB($loginId);
	if ($myobj->db_mkitem($asin, $title, $newAmazonPrice))
        ;
    else
        setcookie('msg', "ウォッチの登録に失敗しました。");
} else {
	require_once('hungup.php');
}
header('Location: amazonLookup.php');
?>
