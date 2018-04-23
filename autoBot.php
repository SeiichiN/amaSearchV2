<?php

require_once('Bot.php');

$myobj = new Bot();

$jsonUrl = "address.json";

echo "価格を調べています...\n";

if (file_exists($jsonUrl)) {
	$json = file_get_contents($jsonUrl);
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$obj = json_decode($json, true);

	foreach ($obj as $id => $email) {
		$msg = $myobj->checkNow($id, $email);
		echo $msg, "\n";
	}
} else {
	echo 'データがありません。';
}