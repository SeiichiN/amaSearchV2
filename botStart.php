<?php
require_once('lib/mylib.php');

exec("nohup php -c '' 'start.php' > /dev/null &");

$msg = "価格を調べています。結果はメールでお知らせします。";

$_SESSION['msg'] = $msg;

$myurl = getMyURL();
header('Location: '.$myurl.'index.php');
