<?php
// login.php
?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Login - アマゾンサーチ</title>
    </head>
    <body>
        <h1>アマゾンサーチ</h1>
        <h2>ログイン</h2>
        <form action="member.php" method="post">
            <p>
                <label for="id">ID:</label>
                <input type="text" name="id" id="id">
            </p>
            <p>
                <label for="pw">Password:</label>
                <input type="password" name="password" id="pw">
            </p>
            <input type="submit" value="ログイン">
        </form>
        <p>
            <form action="newmember.php" method="post">
                <button type="submit" name="newmember" value="new">
                    新規ログインはこちら
                </button>
            </form>
        </p>
        <?php require_once ('footer.php'); ?>
    </body>
</html>

