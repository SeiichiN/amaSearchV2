<?php
// header.php
?>
<!doctype html>
<html lang="ja">
	<head>
	    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="css/ama.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	    <title>アマゾンサーチ</title>
    </head>
    <body>
        <div id="wrap">
            <header>
	            <div class="loginId"><?php if(isset($loginId)) echo $loginId, 'さん'; ?></div>
                <nav class="hamburger-nav sub-menu">
	                <button type="button" id="nav-toggle" class="nav-toggle-button close"></button>
                    <ul id="nav-list" class="nav-list close">
                        <li><a href="index.php">TOP</a></li>
                        <li><?php if(!empty($loginId)) { ?>
                            <a href="logout.php">ログアウト</a>
                        <?php } else { ?>
                            <a href="login.php">ログイン</a>
                        <?php } ?></li>
                        <li><a href="editAcount.php">アカウント設定</a></li>
                        <li><a href="newmember.php">新規登録</a></li>
                    </ul>
                </nav><!-- .hamburger-nav -->
                <h1><a href="index.php">アマゾンサーチ</a></h1>
            </header>
            <article>

