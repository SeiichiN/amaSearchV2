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
        <form action="" method="post">
            <p>
                <label for="name">お名前を入力してください。</label>
                <input type="text" name="name" value="<?php echo $name; ?>" id="name">
            </p>
            <p>
                <label for="email">メールアドレスを入力してください。</label>
                <input type="email" name="email" value="<?php echo $email; ?>" id="email"><br>
                <small>このメールアドレスに価格情報をお届けします。</small>
            </p>
            <p>
                <label for="passwd">パスワードを決めてください。</label>
                <input type="password" name="passwd" value="<?php echo $passwd; ?>" id="passwd"><br>
                <small>このパスワードで次回からログインできます。</small>
            </p>
            <p>
                <input type="submit" value="決定">
            </p>
        </form>
    </body>
</html>

                                                        
                
