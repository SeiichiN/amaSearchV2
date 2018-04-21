<?php
// namespace billiesworks;

require_once('IdLookup.php');

if (!empty($_POST['asin'])) {
    $asin = $_POST['asin'];
    setcookie('asin', $asin);
    // var_dump($asin);
    $myobj = new IdLookup();
	$result = $myobj->getData($asin);
}

if (isset($_COOKIE['asin'])) $asin = $_COOKIE['asin'];

?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>アマゾンで探す</title>
    </head>
    <body>
        <div class="err"><?php if (isset($errmsg)) echo $errmsg; ?></div>
        <h1>アマゾン・サーチ</h1>
        <p>ASINを入力してください。</p>
        <form action="amazonLookup.php" method="post">
            <label for="asin">ASIN:</label>
            <input type="text" name="asin" id="asin"
                   value="<?php if (isset($asin)) echo $asin; ?>"><br>
            <input type="submit" value="OK">
        </form>
        <section>
            <?php
            if (!empty($result)) {
            ?>
                <h1>検索結果</h1>
 
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
                    <form action="watchPrice.php" method="post">
                        <div class="asinId">
                            ASIN: <?php echo $id; ?>
                        </div>
                        <div class="title">
                            <a href="<?php echo $url; ?>">
                                タイトル: <?php echo $title; ?>
                            </a>
                        </div>
                        <div class="image">
                            <a href="<?php echo $url; ?>">
                                <img src="<?php echo $image; ?>" alt="">
                            </a>
                        </div>
				        <div class="officialPrice">
                            アマゾン価格: \<?php echo $officialPrice; ?>
                            <input type="checkbox" name="price[]" id="officialPrice"
                                   value="<?php echo $officialPrice; ?>">
                            <label for="officialPrice">ウォッチ</label>
                        </div>
				        <div class="newPrice">
                            新品: \<?php echo $newPrice; ?>
                            <input type="checkbox" name="price[]" id="newPrice"
                                   value="<?php echo $newPrice; ?>">
                            <label for="newPrice">ウォッチ</label>
                        </div>
                        <div class="usedPrice">
                            中古品: \<?php echo $usedPrice; ?>
                            <input type="checkbox" name="price[]" id="usedPrice"
                                   value="<?php echo $usedPrice; ?>">
                            <label for="usedPrice">ウォッチ</label>
                        </div>
                        <div class="collectiblePrice">
                            コレクター商品: \<?php echo $collectiblePrice; ?>
                            <input type="checkbox" name="price[]" id="collectiblePrice"
                                   value="<?php echo $collectiblePrice; ?>">
                            <label for="collectiblePrice">ウォッチ</label>
                        </div>
                        <input type="hidden" name="asin" value="<?php echo $id; ?>">
                        <input type="hidden" name="title" value="<?php echo $title; ?>">
                        <input type="submit" value="ウォッチする">
                    </form>
                <?php
                }
            }
            ?>
        </section>
        <p><a href="amazonFind.php">アマゾンで探す</a></p>
        <?php require_once('footer.php'); ?>
    </body>
</html>
