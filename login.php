<?php
// login.php
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$_SESSION['loginId'] = 'guest';     // . $date("YmdHis");

if (isset($_SESSION['auth'])) {
	if ($_SESSION['auth'] == 'no') {
		$msg = "ID か Password が違うようです。";
	}
}
$valiMsg = getSession('error');

require_once('header.php');
?>

<h1>ログイン</h1>

<?php prValidateError($valiMsg); ?>

<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<form action="member.php" method="post" class="clearfix">
    <p>
        <label for="loginId">ログイン名:</label><br>
        <input type="text" name="loginId" id="loginId" class="input-box" required>
    </p>
    <p>
        <label for="password">パスワード:</label><br>
        <input type="password" name="password" id="password" class="input-box" required>
    </p>
    <input type="submit" value="ログイン" class="singleBtn">
</form>
<p id="aboutThis">この「アマゾンサーチ」について</p>
<section id="overlay-aboutThis">
    <h1>このアプリについて</h1>
    <p>このアプリは、アマゾンの商品の価格の推移をウォッチするのが目的です。<br>
        アマゾンの価格、特に中古品価格については、商品によっては毎日といっていいほど変動しています。<br>
        その価格の変動をウォッチするのがこのアプリの目的です。<br>
        <br>
        まず、どの商品をウォッチするのか、決めます。商品は、キーワードで検索することができます。<br>
        もし、アマゾンの商品番号ASINがわかっていたら、それをもとに検索することもできます。<br>
        <br>
        もし、その商品をウォッチすることに決めると、価格の変化を閲覧することができます。また、メールで受け取ることもできます。<br>
        また、毎朝8時に価格情報メールを自動的に受け取るようにすることもできます。<br>
        このアプリのおおまかな説明は以上です。<br>
        <br>
        まず、右上のハンバーガーボタンから、「新規登録」をクリックし、新規登録してください。<br>
        僕のところに新規登録の連絡がくるので、折り返し「初期パスワード」の連絡を差し上げます。<br>
        初期パスワードは、ハンバーガーボタンの「アカウント設定」で変更できます。<br>
        <br>
        何か質問等ございましたら、billie175@gmail.com までご連絡ください。</p>
</section>

<script src="js/aboutThis.js"></script>
<?php require_once ('footer.php'); ?>

