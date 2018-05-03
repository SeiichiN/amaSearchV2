<?php
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

$msg = getSessionMsg();

require_once('header.php');

?>
<h1>メニュー</h1>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<nav class="main-menu">
    <ul>
        <li><a href="amazonFind.php">キーワードで検索</a></li>
        <li><a href="amazonLookup.php">商品番号ASINで検索</a></li>
        <li><a href="amazonListAll.php">ウォッチ一覧</a></li>
        <li><a href="botStart.php">価格の変動を今チェック</a></li>
	    <li><a href="mkAdressList.php">価格の変動を知らせるメールを受け取る</a></li>
    </ul>
</nav>
<?php require_once('footer.php'); ?>