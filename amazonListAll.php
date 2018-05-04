<?php
// namespace billiesworks

// amazonListAll.php
ini_set('session.cookie_httponly', true);
session_start();

require_once('lib/mylib.php');
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
            <div class="title">
                <form action="showItem.php" method="post">
                    <input type="hidden" name="title" value="<?php echo $row['title']; ?>">
					<input type="hidden" name="url" value="<?php echo $row['url']; ?>">
                    <button type="submit" name="asinNo" value="<?php echo $row['asin']; ?>">
                        <?php echo $row['title']; ?>
                    </button>
                </form>
            </div>
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
