<?php
// namespace billiesworks;
require_once('lib/mylib.php');
require_once('IdLookup.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asin = getPost('asin');
    $_SESSION['asin'] = $asin;
    // var_dump($asin);
    $myobj = new IdLookup();
	$result = $myobj->getData($asin);
}

$msg = getSessionMsg();

require_once('header.php');
?>
<div class="err"><?php if (isset($errmsg)) echo $errmsg; ?></div>
<div class="msg"><?php if (isset($msg)) echo $msg; ?></div>
<h1>商品番号サーチ</h1>
<p>ASINを入力してください。</p>
<form action="amazonLookup.php" method="post">
	<div class="row">
	    <div class="asin-box">
	        <label for="asin">ASIN:</label><br>
            <input type="text" name="asin" id="asin"
                   value="<?php if (isset($asin)) echo h($asin); ?>">
	    </div><!-- .asin-box -->
	    
        <input type="submit" value="検索">
	</div><!-- .row -->
</form>
<section>
    <?php if (!empty($result)) { ?>
         
        <?php
        foreach($result as $row) {
            $id = $row['id'];
            $url = $row['url'];
            $title = $row['title'];
            $image = $row['image'];
            $newPrice = $row['newPrice'];
            $usedPrice = $row['usedPrice'];
            $collectiblePrice = $row['collectiblePrice'];
            $officialPrice = $row['officialPrice'];
        ?>
            <div class="item">
                <div class="asinId">
                    ASIN: <?php echo $id; ?>
                </div>
                <div class="title">
                    <a href="<?php echo $url; ?>" target="_blank">
                        タイトル: <?php echo $title; ?>
                    </a>
                </div>
                <div class="image">
                    <a href="<?php echo $url; ?>" target="_blank">
                        <img src="<?php echo $image; ?>" alt="">
                    </a>
                </div>
                <form action="watchPrice.php" method="post">
                    <div class="row">
                        <div class="price">
				            <div class="officialPrice">
                                アマゾン価格: \<?php echo $officialPrice; ?>
                                <input type="hidden" name="officialPrice" id="officialPrice"
                                       value="<?php echo $officialPrice; ?>">
                            </div>
				            <div class="newPrice">
                                新品: \<?php echo $newPrice; ?>
                                <input type="hidden" name="newPrice" id="newPrice"
                                       value="<?php echo $newPrice; ?>">
                            </div>
                            <div class="usedPrice">
                                中古品: \<?php echo $usedPrice; ?>
                                <input type="hidden" name="usedPrice" id="usedPrice"
                                       value="<?php echo $usedPrice; ?>">
                            </div>
                            <div class="collectiblePrice">
                                コレクター商品: \<?php echo $collectiblePrice; ?>
                                <input type="hidden" name="collectiblePrice" id="collectiblePrice"
                                       value="<?php echo $collectiblePrice; ?>">
                            </div>
                        </div><!-- .price -->
                        <div class="watchBtn">
                            <input type="hidden" name="asin" value="<?php echo $id; ?>">
                            <input type="hidden" name="title" value="<?php echo $title; ?>">
                            <input type="hidden" name="url" value="<?php echo $url; ?>">
                            <input type="submit" value="ウォッチする">
                        </div><!-- .watchBtn -->
                    </div><!-- .row -->
                </form>
            </div><!-- .item -->
        <?php } ?>
    <?php } ?>
</section>
<p><a href="amazonFind.php">キーワードで探す</a></p>
<?php require_once('footer.php'); ?>





