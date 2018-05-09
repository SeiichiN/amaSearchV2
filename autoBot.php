<?php

require_once('Bot.php');
require_once('conf/mail_conf.php');

$myobj = new Bot();

$jsonUrl = ADDRESS_LIST;

$msg = "価格を調べています...\n";

if (file_exists($jsonUrl)) {
	$json = file_get_contents($jsonUrl);
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$obj = json_decode($json, true);

	foreach ($obj as $id => $email) {
		$msg = $msg . $myobj->checkNow($id, $email);
		$msg = $msg . "\n";
	}
} else {
	$msg = $msg .  'データがありません。';
}

// ログ出力
$logtime = date("Y-m-d_His");
$log = 'log/report_' . $logtime . '.log';
error_log($msg, 3, $log);
