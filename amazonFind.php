<?php
// namespace billiesworks;
require_once('mylib.php');
require_once('KwSearch.php');

session_start();

if (isset($_SESSION['loginid'])) {
    $loginId = $_SESSION['loginid'];
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST['category']) && !empty($_POST['keyword'])) {
    $category = $_POST['category'];
    $keyword = $_POST['keyword'];
    setcookie('category', $category);
    setcookie('keyword', $keyword);
	$myobj = new KwSearch();
    $result = $myobj->getData($category, $keyword);
}
if (isset($_COOKIE['category'])) $category = $_COOKIE['category'];
if (isset($_COOKIE['keyword'])) $keyword = $_COOKIE['keyword'];

require_once('header.php');
?>

<div class="err"><?php if (isset($errmsg)) echo $errmsg; ?></div>
<h1>キーワード・サーチ</h1>
<p>探したいものはなんですか？</p>
<form action="amazonFind.php" method="post">
    <label for="category">カテゴリ:</label>
    <select id="category" name="category">
        <option value="All">全て</option>
        <option value="Apparel">アパレル</option>
        <option value="Automotive">Automotive</option>
        <option value="Baby">ベビー&マタニティ</option>
        <option value="Beauty">コスメ</option>
        <option value="Blended">全て</option>
        <option value="Books">本（和書）</option>
        <option value="Classical">クラシック音楽</option>
        <option value="DVD">ＤＶＤ</option>
        <option value="Electronics">エレクトロニクス</option>
        <option value="ForeignBooks">洋書</option>
        <option value="Grocery">食品</option>
        <option value="HealthPersonalCare">ヘルスケア</option>
        <option value="Hobbies">ホビー</option>
        <option value="HomeImprovement">HomeImprovement</option>
        <option value="Industrial">Industrial</option>
        <option value="Jewelry">ジュエリー</option>
        <option value="Kitchen">ホーム&キッチン</option>
        <option value="Music">音楽</option>
        <option value="MusicTracks">曲名</option>
        <option value="OfficeProducts">OfficeProducts</option>
        <option value="Shoes">靴</option>
        <option value="Software">ソフトウェア</option>
        <option value="SportingGoods">スポーツ&アウトドア</option>
        <option value="Toys">おもちゃ</option>
        <option value="VHS">ＶＨＳ</option>
        <option value="Video">ビデオ</option>
        <option value="VideoGames">ゲーム</option>
        <option value="Watches">時計</option>
    </select><br>
    <label for="keyword">キーワード:</label>
    <input type="text" name="keyword" id="keyword"
           value="<?php if(isset($keyword)) echo $keyword; ?>"><br>
    <input type="submit" value="OK">
</form>
<section>
    <?php if (!empty($result)) { ?>
        <h1>検索結果</h1>
        
        <?php foreach($result as $row) { ?>
            <div class="item">
				<div class="asinId">
                    ASIN:
                    <form action="amazonLookup.php" method="post">
                        <button type="submit" name="asin"
                                value="<?php echo $row['id']; ?>">
                            <?php echo $row['id']; ?>
                        </button>
                    </form>
                </div>
                <div class="title">
                    <a href="<?php echo $row['url']; ?>">
                        タイトル: <?php echo $row['title']; ?>
                    </a>
                </div>
                <div class="image">
                    <a href="<?php echo $row['url']; ?>">
                        <img src="<?php echo $row['image']; ?>" alt="">
                    </a>
                </div>
				<div class="officialPrice">
                    アマゾン価格: <?php echo $row['officialPrice']; ?>
                </div>
				<div class="newPrice">
                    新品価格: <?php echo $row['newPrice']; ?>
                </div>
				<div class="usedPrice">
                    中古品価格: <?php echo $row['usedPrice']; ?>
                </div>
				<div class="collectiblePrice">
                    コレクション価格: <?php echo $row['collectiblePrice']; ?>
                </div>

            </div>
        <?php } ?>
    <?php } ?>
</section>
<?php require_once('footer.php'); ?>

