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
                <h1>アマゾンサーチ</h1>
            </header>
            <article>



