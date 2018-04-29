<?php
// header.php
?>
<!doctype html>
<html lang="ja">
	<head>
	    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>アマゾンサーチ</title>
    </head>
    <body>
        <div id="wrap">
            <header>
	            <div class="loginId"><?php if(isset($loginId)) echo $loginId, 'さん'; ?></div>
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
                    <div class="edit-acount">
                        <a href="editAcount.php">アカウント設定</a>
                    </div>
					<div class="new-acount">
                        <a href="newmember.php">新規登録</a>
                    </div>
                </div><!-- .sub-nav -->
                <h1><a href="index.php">アマゾンサーチ</a></h1>
            </header>
            <article>

