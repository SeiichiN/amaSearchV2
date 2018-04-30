<?php
// namespace billiesworks

// amazonListAll.php
session_start();

require_once('mylib.php');
require_once('PriceDB.php');


$loginId = checkLoginId();

$mydb = new PriceDB($loginId);
$lastData = $mydb->lastdata();

require_once('header.php');
?>
<section class="watchList">
    <h1>ウォッチ一覧</h1>


    <?php foreach($lastData as $row) { ?>
        <div class="item">
            <!-- <div class="head">タイトル</div>  -->
            <div class="title"><?php echo $row['title']; ?></div>
            <div class="row">
                <div class="head">ASIN</div>
                <div><?php echo $row['asin']; ?></div>
            </div>
            <div class="row">
                <div class="head">アマゾン価格</div>
                <div class="price">\<?php echo $row['official_p']; ?></div>
            </div>
            <div class="row">
                <div class="head">新品価格</div>
                <div class="price">\<?php echo $row['new_p']; ?></div>
            </div>
            <div class="row">
                <div class="head">中古価格</div>
                <div class="price">\<?php echo $row['used_p']; ?></div>
            </div>
            <div class="row">
                <div class="head">コレクション価格</div>
                <div class="price">\<?php echo $row['collectible_p']; ?></div>
            </div>
            <div class="row">
                <div class="head">登録日</div>
                <div><?php echo $row['date']; ?></div>
            </div>
        </div><!-- .item -->
    <?php } ?>
</section>
<div class="link2find"><a href="amazonFind.php">キーワードで探す</a></div>

<?php require_once('footer.php'); ?>


