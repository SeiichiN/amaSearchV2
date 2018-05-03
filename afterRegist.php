<?php
require_once('lib/mylib.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = getLoginId();

require_once('header.php');
?>
<h1>登録完了</h1>
<p>折り返し、管理人より登録されたメールアドレスに初期パスワードの連絡をお送りいたします。</p>
<p>（初期パスワードは「アカウント設定」で変更できます）</p>
<p>しばらくお待ちください。</p>
<?php require_once('footer.php'); ?>
