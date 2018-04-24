<?php
// header.php
// $myPath = __FILE__;
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
switch ($filename) {
    case 'login' :
	    $titletxt = 'ログイン - ';
        break;
    case 'newmember' :
        $titletxt = '新規登録 - ';
        break;
    case 'amazonFind' :
        $titletxt = 'キーワード検索 - ';
        break;
    case 'amazonListAll' :
        $titletxt = 'ウォッチ一覧 - ';
        break;
    case 'amazonLookup' :
        $titletxt = 'ASINで検索';
        break;
    default :
        $titletxt = '';
        break;
}
?>
<!doctype html>
<html lang="ja">
	<head>
	    <meta charset="utf-8">
	<title><?php echo $titletxt; ?>アマゾンサーチ</title>
    </head>
    <body>
        <div id="wrap">
            <header>
                <div class="loginId"><?php if(isset($loginId)) echo $loginId; ?></div>
                <div class="sub-nav">
                    <div class="top">
                        <a href="index.php">TOP</a>
                    </div>
                    <div class="login-logout">
                        <?php if(!empty($loginId)) { ?>
                            <a href="logout.php">ログアウト</a>
                        <?php } else { ?>
                            <a href="login.php">ログイン</a>
                        <?php } ?>
                    </div>
					<div class="new-acount">
                        <!--
                        <form action="newmember.php" method="post">
                            <button type="submit" name="newmember" value="new">
                                新規ログインはこちら
                            </button>
                        </form>
                        -->
                        <a href="newmember.php">新規登録</a>
                    </div>
                </div><!-- .sub-nav -->
                <h1><a href="index.php">アマゾンサーチ</a></h1>
            </header>
            <article>



