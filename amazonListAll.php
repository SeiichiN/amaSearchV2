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
<h1>ウォッチ一覧</h1>
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
<div class="link2find"><a href="amazonFind.php">キーワードで探す</a></div>

<?php require_once('footer.php'); ?>


