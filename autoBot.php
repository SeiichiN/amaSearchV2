<?php

require_once('Bot.php');

$arr = ['se-ichi' => 'billie175@gmail.com'];


// echo "アマゾンの現在価格を調べます。\n";

$myobj = new Bot();


foreach($arr as $id => $email) {
	
	$myobj->checkNow($id, $email);
}


header('Location: index.php');






$jsonUrl = "address.json";

if (file_exists($jsonUrl)) {
	$json = file_get_contents($jsonUrl);
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$obj = json_decode($json, true);

	foreach ($obj as $key => $val) {
		echo $key, " : ", $val, "\n";
	}
} else {
	echo 'データがありません。';
}


