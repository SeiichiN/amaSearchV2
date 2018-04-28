<?php
session_start();

if (!empty($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];

if (!empty($_COOKIE['msg'])) {
    $msg = $_COOKIE['msg'];
    setcookie('msg', '', time() - 3600);
}

require_once('header.php');

?>
<h1>メニュー</h1>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<nav>
    <ul>
        <li><a href="amazonFind.php">キーワードで検索</a></li>
        <li><a href="amazonLookup.php">商品番号ASINで検索</a></li>
        <li><a href="amazonListAll.php">ウォッチ一覧</a></li>
        <li><a href="botStart.php">価格の変動を今チェック</a></li>
	    <li><a href="mkAdressList.php">価格の変動を知らせるメールを受け取る</a></li>
    </ul>
</nav>
<?php require_once('footer.php'); ?>