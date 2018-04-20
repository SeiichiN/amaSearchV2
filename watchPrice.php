<?php
// namespace billiesworks

// require_once('mylib.php');
require_once('PriceDB.php');

if(!empty($_POST['asin'])) {
	$asin = $_POST['asin'];
	$title = $_POST['title'];
	$price = $_POST['price'];

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
