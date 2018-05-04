<?php
require_once('lib/mylib.php');
require_once('PriceDB.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

$mydb = new PriceDB($loginId);

$data = $mydb->showList();
// var_dump($data[0]['title']);

$selectNo = (int)getPost('sel-no');

// $data[$selectNo]['url'] = getPost('url');


require_once('header.php');
$i = 0;
foreach($data as $row) {
    echo 'NO: ', $i, '<br>';
    echo $row['id'], '<br>';
    echo $row['asin'], '<br>';
    echo $row['title'], '<br>';
    echo $row['url'], '<br>';
    echo '<br>';
    $i++;
} ?>
<form action="" method="post" name="select-no">
    select NO: <input type="text" name="sel-no" value="<?php if(isset($selectNo)) echo $selectNo; ?>">
    <input type="submit" for="select-no">
</form>

    <form action="updateList.php" method="post">
	    <p>
	        <label for="asin">ASIN</label><br>
	        <input type="text" name="asin" id="asin" value="<?php echo $data[$selectNo]['asin']; ?>">
	    </p>
	    <p>
	        <label for="title">タイトル</label><br>
	        <input type="text" name="title" id="title" size="50" value="<?php echo $data[$selectNo]['title']; ?>">
	    </p>
	    <p>
	        <label for="url">URL</label><br>
	        <input type="text" name="url" id="url" size="100" value="<?php echo $data[$selectNo]['url']; ?>">
	    </p>
	    <p>
	        <input type="submit" value="OK">
	    </p>
    </form>
<?php require_once('footer.php'); ?>
