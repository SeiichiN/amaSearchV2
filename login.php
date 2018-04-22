<?php
// login.php
require_once('mylib.php');
session_start();
$_SESSION['guestid'] = 'guest';     // . $date("YmdHis");

require_once('header.php');
?>

<h1>ログイン</h1>
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


