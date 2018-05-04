<?php
require_once('lib/mylib.php');
require_once('PriceDB.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

$mydb = new PriceDB($loginId);

$url = getPost('url');
$asin = getPost('asin');
// var_dump($asin);
// echo "<br>\n";
// var_dump($url);

$msg = $mydb->updateList($asin, $url);

require('header.php');
?>

<p><?php echo $msg; ?></p>
<p><a href="index.php">top</a></p>
