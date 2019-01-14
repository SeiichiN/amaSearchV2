<?php
// namespace billiesworks

// require_once('mylib.php');
require_once('PriceDB.php');
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

$asin = getPost('asin');
$title = getPost('title');
$url = getPost('url');

// $asin = $_POST['asin'];
// $title = $_POST['title'];
// $url = $_POST['url'];

if ($asin !== '') {
	// それぞれの値を数値に変換
	// もし空文字列なら、-1 を入れる。
	/* for($i = 0; $i < count($price); $i++) {
	   if ($price[$i] === '')
	   $price[$i] = -1;
	   else
	   $price[$i] = (int)$price[$i];
	   }*/
	if ($_POST['officialPrice'] === '')
		$officialPrice = -1;
	else
		$officialPrice = (int)$_POST['officialPrice'];
	
	if ($_POST['newPrice'] === '')
		$newPrice = -1;
	else
		$newPrice = (int)$_POST['newPrice'];
	
	if ($_POST['usedPrice'] === '')
		$usedPrice = -1;
	else
		$usedPrice = (int)$_POST['usedPrice'];
	
	if ($_POST['collectiblePrice'] === '')
		$collectiblePrice = -1;
	else
		$collectiblePrice = (int)$_POST['collectiblePrice'];
	
	$newAmazonPrice = [
		'official_p' => $officialPrice,
		'new_p' => $newPrice,
		'used_p' => $usedPrice,
		'collectible_p' => $collectiblePrice
		];

	$myobj = new PriceDB($loginId);
	// ウォッチの登録
	//   -- ウォッチリスト(list)への登録
	//   -- テーブルの作成(db_XXXXXXXX)
	if ($myobj->db_mkitem($asin, $title, $newAmazonPrice, $url))
//        $_SESSION['msg'] = "ウォッチに登録しました。";
        $msg = "ウォッチに登録しました。";
    else
//        $_SESSION['msg'] =  "ウォッチの登録に失敗しました。";
        $msg =  "ウォッチの登録に失敗しました。";
}

echo $msg;

/* $referer = getReferer();
 * $myurl = getMyURL();
 * 
 * header('Location: ' . $myurl . $referer .'.php');*/
?>













