<?php
// namespace billiesworks;

require_once('IdLookup.php');

session_start();

if (isset($_SESSION['loginId']))
    $loginId = $_SESSION['loginId'];
else
    header('Location: index.php');

if (!empty($_POST['asin'])) {
    $asin = $_POST['asin'];
    setcookie('asin', $asin);
    // var_dump($asin);
    $myobj = new IdLookup();
	$result = $myobj->getData($asin);
}

if (isset($_COOKIE['asin'])) {
    $asin = $_COOKIE['asin'];
    setcookie('asin', '', time() - 3600);
}

if (isset($_COOKIE['msg'])) {
    $msg = $_COOKIE['msg'];
    setcookie('msg', '', time() - 3600);
}

require_once('header.php');
?>
<div class="err"><?php if (isset($errmsg)) echo $errmsg; ?></div>
<div class="msg"><?php if (isset($msg)) echo $msg; ?></div>
<h1>商品番号サーチ</h1>
<p>ASINを入力してください。</p>
<form action="amazonLookup.php" method="post">
    <label for="asin">ASIN:</label>
    <input type="text" name="asin" id="asin"
           value="<?php if (isset($asin)) echo $asin; ?>"><br>
    <input type="submit" value="OK">
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
            <form action="watchPrice.php" method="post">
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
                <input type="hidden" name="asin" value="<?php echo $id; ?>">
                <input type="hidden" name="title" value="<?php echo $title; ?>">
                <input type="submit" value="ウォッチする">
            </form>
        <?php } ?>
    <?php } ?>
</section>
<p><a href="amazonFind.php">アマゾンで探す</a></p>
<?php require_once('footer.php'); ?>

