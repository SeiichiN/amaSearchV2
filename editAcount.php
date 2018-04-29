<?php
session_start();

require_once('mylib.php');
require_once('UserDB.php');
require_once('ManageUser.php');

$loginId = checkLoginId();

$msg = getSessionMsg();

// フルネームとメールアドレスを取得
$mydb = new UserDB();
$fullName = $mydb->getFullName($loginId);
$email = $mydb->getMailAddress($loginId);

// 価格を知らせるメールを受け取る設定を確認
$mydb2 = new ManageUser();
if ($mydb2->checkAdList($loginId)){
    $listIn = "受け取る";
    $msgGetPriceMail = "「受け取らない」に変更";
    $linkGetPriceMail = "delAdList.php";
} else {
    $listIn = "受け取らない";
    $msgGetPriceMail = "「受け取る」に変更";
    $linkGetPriceMail = "mkAdressList.php";
}

require_once('header.php');
?>
<h1>アカウント設定</h1>
<section>
    <h2>現在の設定</h2>
    <div class="login-id">ログインID: <?php echo h($loginId); ?></div>
    <div class="full-name">おなまえ: <?php echo h($fullName); ?></div>
    <div class="email">メールアドレス: <?php echo h($email); ?></div>
    <div class="autoMail">価格の変動を知らせるメール: <?php echo $listIn; ?></div>
</section>
<nav>
    <ul>
        <li><a href="changeLoginID.php">ログイン名の変更</a></li>
        <li><a href="changePW.php">パスワードの変更</a></li>
        <li><a href="#">メールアドレスの変更</a></li>
        <li><a href="<?php echo $linkGetPriceMail; ?>">
            価格の変動を知らせるメール -> <?php echo $msgGetPriceMail; ?></a></li>
    </ul>
</nav>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<?php require_once('footer.php'); ?>
