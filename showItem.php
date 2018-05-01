<?php
// showItem.php

session_start();

require_once('mylib.php');
require_once('PriceDB.php');


$loginId = checkLoginId();

$asin = getPost('asinNo');
$title = getPost('title');

$mydb = new PriceDB($loginId);
$transData = $mydb->transPrice($asin);

require_once('header.php');
?>
<h1>価格の推移</h1>
<h2>ASIN:<?php echo $asin; ?><br>タイトル:<?php echo $title; ?></h2>

<div class="row-head cssgrid">
    <div class="head">アマゾン価格</div>
    <div class="head">新品価格</div>
    <div class="head">中古価格</div>
    <div class="head">コレクション価格</div>
    <div class="head">登録日</div>
</div><!-- .row -->

<?php foreach ($transData as $row) { ?>
    <?php // var_dump($row); die(); ?>
    <div class="row-item cssgrid">
        <div class="price"><?php echo $row['official_p']; ?></div>
        <div class="price"><?php echo $row['new_p']; ?></div>
        <div class="price"><?php echo $row['used_p']; ?></div>
        <div class="price"><?php echo $row['collectible_p']; ?></div>
        <div class="price"><?php echo $row['date']; ?></div>
    </div><!-- .row-showItem -->
<?php } ?>
<?php require_once('footer.php'); ?>



