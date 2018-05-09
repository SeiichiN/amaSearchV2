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
        array_push($newP, ["label"=>substr($row['date'], 6,5), "y"=>$row['new_p']]);
        array_push($useP, ["label"=>substr($row['date'], 6,5), "y"=>$row['used_p']]);
        array_push($colP, ["label"=>substr($row['date'], 6,5), "y"=>$row['collectible_p']]);
        ?>
    </div><!-- .row-showItem -->
<?php } ?>
<form action="deleteItem.php" method="post" onSubmit="return kakunin()">
    <button type="submit" name="delAsinNo" value="<?php echo $asin; ?>">このアイテムを削除</button>
</form>
<div id="chartContainer"></div>
<?php
// json文字列に変換してjavascriptにわたす
$offP = json_escape($offP);
$newP = json_encode($newP);
$useP = json_encode($useP);
$colP = json_encode($colP);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
<script>
 // y値を数値に変換する
 function toNum (obj) {
     for (let i = 0; i < obj.length; i++) {
         //obj[i]['y'] = parseInt(obj[i]['y']);
         obj[i].y = parseInt(obj[i].y);
     }
     return obj;
 }
 
 // 文字列をオブジェクトに変換
 var data_offP = JSON.parse('<?php echo $offP; ?>');
 var data_newP = JSON.parse('<?php echo $newP; ?>');
 var data_useP = JSON.parse('<?php echo $useP; ?>');
 var data_colP = JSON.parse('<?php echo $colP; ?>');

 // yの値が文字列なので、数値になおす
 data_offP = toNum(data_offP);
 data_newP = toNum(data_newP);
 data_useP = toNum(data_useP);
 data_colP = toNum(data_colP);

 var chart = new CanvasJS.Chart("chartContainer", {
     title: {
         text: "価格の推移"
     },
     legend: {
         horizontalAlign: "center", // "center", "left", "right"
         verticalAlign: "top", // "top", "bottom", "center"
         fontSize: 16
     },
     data: [
         {
             type: 'line',
             showInLegend: true,
             legendText: 'アマゾン価格',
             dataPoints: data_offP
         },
         {
             type: 'line',
             showInLegend: true,
             legendText: '新品価格',
             dataPoints: data_newP
         },
         {
             type: 'line',
             showInLegend: true,
             legendText: '中古価格',
             dataPoints: data_useP
         }
         /*
          * コレクション価格は値が大きく、他のグラフが
          * 見づらくなるので描画しない。
          * 
          * {
          *     type: 'line',
          *     showInLegend: true,
          *     legendText: 'コレクション価格',
          *     dataPoints: data_colP
          * }*/
     ]
 });
 chart.render();
 
</script>
<?php require_once('footer.php'); ?>
