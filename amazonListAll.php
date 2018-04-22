<?php
// namespace billiesworks

// amazonListAll.php

require_once('mylib.php');
require_once('PriceDB.php');

$mydb = new PriceDB();
$lastData = $mydb->lastdata();

require_once('header.php');
?>
<h1>ウォッチ一覧</h1>
<a href="amazonfind.php">キーワードで探す</a>
<table>
    <tr>
        <th>ASIN</th>
        <th>タイトル</th>
        <th>アマゾン価格</th>
        <th>新品価格</th>
        <th>中古価格</th>
        <th>コレクション価格</th>
        <th>登録日</th>
    </tr>
    <?php foreach($lastData as $row) { ?>
        <tr>
            <td><?php echo $row['asin']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['official_p']; ?></td>
            <td><?php echo $row['new_p']; ?></td>
            <td><?php echo $row['used_p']; ?></td>
            <td><?php echo $row['collectible_p']; ?></td>
            <td><?php echo $row['date']; ?></td>
        </tr>
    <?php } ?>
</table>
<?php require_once('footer.php'); ?>


