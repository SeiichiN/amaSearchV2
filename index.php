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
<div id="aboutMenu">メニューの説明</div>
<section id="overlay-aboutMenu">
    <h1>操作メニュー</h1>
    <dt>キーワードで検索</dt>
    <dd>アマゾンの商品をキーワードで検索できます。提示された中から「ウォッチ」を選択できます。</dd>
    <dt>商品番号ASINで検索</dt>
    <dd>キーワード検索でうまく探し当てられなかった場合、ASIN番号がわかっているなら、ここから目当ての商品を表示できます。</dd>
    <dt>ウオッチ一覧</dt>
    <dd>現在ウォッチに登録している商品を一覧できます。また、個別の商品についての価格の推移を一覧することもできます。
        また、ウォッチの取消しもできます。</dd>
    <dt>価格の変動を今テェック</dt>
    <dd>現在の価格の状況を調べて、もし変動があるなら、登録してあるメールアドレスにメールが届きます。
        また、同時に価格情報データベースにその変動を記録します。</dd>
    <dt>価格の変動を知らせるメールを受け取る</dt>
    <dd>毎日朝8時時点で、ウォッチに登録してある商品について、自動的に価格の変化を調べます。その結果は、登録してある
        メールアドレスに届きます。また、この設定は「アカウント設定」で変更できます。</dd>
</section>
<script src="js/aboutMenu.js"></script>
<?php require_once('footer.php'); ?>
