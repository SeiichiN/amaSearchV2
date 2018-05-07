<?php
// showItem.php

ini_set('session.cookie_httponly', true);
session_start();

require_once('lib/mylib.php');
require_once('PriceDB.php');

function json_escape(array $array)
{
    return json_encode(
        $array,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    );
}


$loginId = checkLoginId();

$asin = getPost('asinNo');
$title = getPost('title');
$url = getPost('url');

$mydb = new PriceDB($loginId);
$transData = $mydb->transPrice($asin);

// $json_data = json_encode($transData, JSON_PRETTY_PRINT);
// print_r($json_data);

$offP = [];
$newP = [];
$useP = [];
$colP = [];

require_once('header.php');
?>
<div class="showItem-header">
    <h1>価格の推移</h1>
    <div class="asinNo"><a href="<?php echo $url; ?>" target="_blank">ASIN:<?php echo $asin; ?></a></div>
    <h2><a href="<?php echo $url; ?>" target="_blank"><?php echo $title; ?></a></h2>
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
        <?php
        // json用データ作成  
        array_push($offP, ["label"=>substr($row['date'], 6,5), "y"=>$row['official_p']]);
        array_push($newP, ["label"=>substr($row['date'], 6,5), "y"=>$row['official_p']]);
        array_push($useP, ["label"=>substr($row['date'], 6,5), "y"=>$row['official_p']]);
        array_push($colP, ["label"=>substr($row['date'], 6,5), "y"=>$row['official_p']]);
        ?>
    </div><!-- .row-showItem -->
<?php } ?>
<form action="deleteItem.php" method="post" onSubmit="return kakunin()">
    <button type="submit" name="delAsinNo" value="<?php echo $asin; ?>">このアイテムを削除</button>
</form>
<?php
/* $json_offP = json_encode($offP, JSON_PRETTY_PRINT);
 * $json_newP = json_encode($newP, JSON_PRETTY_PRINT);
 * $json_useP = json_encode($useP, JSON_PRETTY_PRINT);
 * $json_colP = json_encode($colP, JSON_PRETTY_PRINT);*/

$offP = json_escape($offP);
/* $newP = json_encode($newP);
 * $useP = json_encode($useP);
 * $colP = json_encode($colP);*/

// $offP = 'wahaha';
?>
<!-- <script src="js/graph.js"></script> -->
<?php // require_once('footer.php'); ?>
<script>
    var data = '<?php echo $offP; ?>';
    console.log(data);
</script>
</body>
</html>

