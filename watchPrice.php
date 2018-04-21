<?php
// namespace billiesworks

// require_once('mylib.php');
require_once('PriceDB.php');

if(!empty($_POST['asin'])) {
	$asin = $_POST['asin'];
	$title = $_POST['title'];
	$price = $_POST['price'];

	// それぞれの値を数値に変換
	// もし空文字列なら、-1 を入れる。
	for($i = 0; $i < count($price); $i++) {
		if ($price[$i] === '')
			$price[$i] = -1;
		else
			$price[$i] = (int)$price[$i];
	}
	
	$newAmazonPrice = [
		'official_p' => $price[0],
		'new_p' => $price[1],
		'used_p' => $price[2],
		'collectible_p' => $price[3]
		];
	// echo $asin, "<br>\n";
	// foreach ($price as $amount) {
	// 	echo $amount, "<br>\n";
	// }
	$myobj = new PriceDB();
	$myobj->db_mkitem($asin, $title, $newAmazonPrice);

} else {
	require_once('hungup.php');
}
// header('Location: amazonListAll.php');
?>
