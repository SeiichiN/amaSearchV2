<?php
// namespace billiesworks;
require_once('mylib.php');
require_once('KwSearch.php');
require_once('category.php');

session_start();

$loginId = checkLoginId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = getPost('category');
    $keyword = getPost('keyword');
    $_SESSION['category'] = $category;
    $_SESSION['keyword'] = $keyword;
	$myobj = new KwSearch();
    $result = $myobj->getData($category, $keyword);
}

if (isset($_SESSION['category'])) $category = $_SESSION['category'];
if (isset($_SESSION['keyword'])) $keyword = $_SESSION['keyword'];

$msg = getSessionMsg();

require_once('header.php');
?>

<h1>キーワード・サーチ</h1>
<div class="notice"><?php if (isset($msg)) echo $msg; ?></div>
<p>探したいものはなんですか？</p>
<form action="amazonFind.php" method="post">
    <label for="category">カテゴリ:</label>
    <select id="category" name="category">
        <?php foreach ($caList as $key => $word) { ?>
            <option value="<?php echo $key; ?>"
                    <?php if(isset($category)) if($key === $category) echo 'selected';?>>
                <?php echo $word; ?></option>
        <?php } ?>
    </select><br>
    <label for="keyword">キーワード:</label>
    <input type="text" name="keyword" id="keyword"
           value="<?php if(isset($keyword)) echo $keyword; ?>"><br>
    <input type="submit" value="OK">
</form>
<section>
    <?php if (!empty($result)) { ?>
        
        <?php foreach($result as $row) { ?>
            <div class="item">
                <div class="title">
                    <a href="<?php echo $row['url']; ?>" target="_blank">
                        タイトル: <?php echo $row['title']; ?>
                    </a>
                </div>
                <div class="image">
                    <a href="<?php echo $row['url']; ?>" target="_blank">
                        <img src="<?php echo $row['image']; ?>" alt="">
                    </a>
                </div>
				<div class="asinId">
                    ASIN:<?php echo $row['id']; ?>
                </div>
				<div class="officialPrice">
                    アマゾン価格: \<?php echo $row['officialPrice']; ?>
                </div>
				<div class="newPrice">
                    新品価格: \<?php echo $row['newPrice']; ?>
                </div>
				<div class="usedPrice">
                    中古品価格: \<?php echo $row['usedPrice']; ?>
                </div>
				<div class="collectiblePrice">
                    コレクション価格: \<?php echo $row['collectiblePrice']; ?>
                </div>
                <div class="watchBtn">
                    <form action="watchPrice.php" method="post">
                        <input type="hidden" name="title" value="<?php echo $row['title']; ?>">
                        <input type="hidden" name="officialPrice" value="<?php echo  $row['officialPrice']; ?>">
                        <input type="hidden" name="newPrice" value="<?php echo  $row['newPrice']; ?>">
                        <input type="hidden" name="usedPrice" value="<?php echo  $row['usedPrice']; ?>">
                        <input type="hidden" name="collectiblePrice"
                               value="<?php echo  $row['collectiblePrice']; ?>">
                        <button type="submit" name="asin"
                                value="<?php echo $row['id']; ?>">
							ウォッチする
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</section>
<?php require_once('footer.php'); ?>

