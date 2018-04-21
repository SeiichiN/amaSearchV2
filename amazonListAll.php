<?php
// namespace billiesworks

// amazonListAll.php

require_once('mylib.php');
require_once('PriceDB.php');

$mydb = new PriceDB();
$lastData = $mydb->lastdata();

// $query = "select * from " . INDEX_TABLE;
// $stmt = $db->query($query);
?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ウォッチ一覧</title>
    </head>
    <body>
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
            <?php
            /* while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             *     $query2 = "select * from " . INDEX_TABLE . " inner join {$row['table_name']} on "
             *             . INDEX_TABLE . ".asin = {$row['table_name']}.asin order by id desc limit 1";
             *     $stmt2 = $db->query($query2);
             *     while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {*/
            // print_r($row2);

            // print_r($lastData);
            // die();
            foreach($lastData as $row) {
            ?>
                <tr>
                    <td><?php echo $row['asin']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['official_p']; ?></td>
                    <td><?php echo $row['new_p']; ?></td>
                    <td><?php echo $row['used_p']; ?></td>
                    <td><?php echo $row['collectible_p']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                </tr>
            <?php
            }
            /* }
             * $mydb->closeDB();
             * $db = null;*/
            ?>
        </table>
        <?php
        require_once('footer.php');
        ?>
    </body>
</html>

