<?php
// newmember.php
?>
<!doctype html>
<html lang="ja">
	<head>
	    <meta charset="utf-8">
	    <title>NewLogin - アマゾンサーチ</title>
    </head>
    <body>
        <h1>アマゾンサーチへようこそ</h1>
        <p>新規登録をおこないます。</p>
        <form action="register.php" method="post">
	        <p>
                <label for="id">ログイン名を入力してください。</label><br>
                <input type="text" name="id" value="<?php echo $loginId; ?>" id="id" required><br>
                <small>ログイン時に表示される名前です。ニックネームでもいいです。<br>
                半角英数字でもＯＫです。</small>
            </p>
            <p>
                <label for="name">お名前を入力してください。</label><br>
                <input type="text" name="name" value="<?php echo $name; ?>" id="name" required><br>
                <small>実名でおねがいします。画面には表示されません。</small>
            </p>
            <p>
                <label for="email">メールアドレスを入力してください。</label><br>
                <input type="email" name="email" value="<?php echo $email; ?>" id="email" required><br>
                <small>このメールアドレスに価格情報をお届けします。</small>
            </p>
            <p>
                <label for="passwd">パスワードを決めてください。</label><br>
                <input type="password" name="passwd" value="<?php echo $passwd; ?>" id="passwd" required><br>
                <small>このパスワードで次回からログインできます。</small>
            </p>
            <p>
                <input type="submit" value="決定">
            </p>
        </form>
    </body>
</html>

                                                        
                
