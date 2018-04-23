<?php
require_once('UserDB.php');
require_once('mylib.php');

session_start();

if (isset($_SESSION['loginId'])) {
    $loginId = $_SESSION['loginId'];
} else {
    header('Location: index.php');
    exit();
}


$myobj = new UserDB();
$address = $myobj->getMailAddress($loginId);

$data[$loginId] = $address;

var_dump($data);
echo "<br>\n";

$jsonUrl = "address.json";

if (file_exists($jsonUrl)) {
	$json = file_get_contents($jsonUrl);
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$arr = json_decode($json, true);
} else {
    echo 'データがありません。';
}

var_dump($arr);
echo "<br>\n";

$arr[$loginId] =  $address;

var_dump($arr);

$arr = json_encode($arr);
file_put_contents("address.json", $arr);
