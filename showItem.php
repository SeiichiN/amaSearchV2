<?php
// showItem.php

ini_set('session.cookie_httponly', true);
session_start();

require_once('lib/mylib.php');
require_once('PriceDB.php');


$loginId = checkLoginId();

$asin = getPost('asinNo');
$title = getPost('title');

$mydb = new PriceDB($loginId);
$transData = $mydb->transPrice($asin);

require_once('header.php');
?>
<div class="showItem-header">
    <h1>価格の推移</h1>
    <div class="asinNo">ASIN:<?php echo $asin; ?></div>
    <h2><?php echo $title; ?></h2>
</div><!-- .showItem-header -->

<div class="row-head cssgrid">
    <div class="head">アマゾン価格<span class="yen">(円)</span></div>
    <div class="head">新品価格<span class="yen">(円)</span></div>
    <div class="head">中古価格<span class="yen">(円)</span></div>
    <div class="head">コレクション価格<span class="yen">(円)</span></div>
    <div class="head">登録日</div>
</div><!-- .row -->

<?php foreach ($transData as $row) { ?>
    <?php // var_dump($row); die(); ?>
    <div class="row-item cssgrid">
        <div class="price"><?php echo $row['official_p']; ?></div>
        <div class="price"><?php echo $row['new_p']; ?></div>
        <div class="price"><?php echo $row['used_p']; ?></div>
        <div class="price"><?php echo $row['collectible_p']; ?></div>
        <div class="date"><?php echo $row['date']; ?></div>
    </div><!-- .row-showItem -->
<?php } ?>
<form action="deleteItem.php" method="post" onSubmit="return kakunin()">
    <button type="submit" name="delAsinNo" value="<?php echo $asin; ?>">このアイテムを削除</button>
</form>
<?php require_once('footer.php'); ?>



