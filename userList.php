<?php
require_once('lib/mylib.php');
require_once('UserDB.php');

ini_set('session.cookie_httponly', true);
session_start();

$loginId = checkLoginId();

$myobj = new UserDB();

$msg = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['loginId'])) {
        $delUserList = $_POST['loginId'];
        foreach ($delUserList as $loginName) {
           if ($myobj->deleteUser($loginName))
               array_push($msg,"{$loginName}さんを削除しました。");
        }
    }
}


$userList = $myobj->getUserList();

require_once('header.php');
?>
<div class="notice">
    <?php if(!empty($msg)) {
        foreach ($msg as $str) {
            echo $str, '<br>';
        }
    } ?></div>
<div class="row">
    <div class="head">v</div>
    <div class="head">loginId</div>
    <div class="head">fullName</div>
    <div class="head">email</div>
    <div class="head">activity</div>
</div>
<form action="" method="post">
    <?php foreach ($userList as $row) { ?>
        <div class="row">
            <input type="checkbox" name="loginId[]" value="<?php echo $row['loginId']; ?>">
            <div class="userList"><?php echo $row['loginId']; ?></div>
            <div class="userList"><?php echo $row['fullName']; ?></div>
            <div class="userList"><?php echo $row['email']; ?></div>
            <div class="userList"><?php echo $row['activity']; ?></div>
        </div>
        
    <?php	} ?>
    <input type="submit" value="削除">
</form>


<?php require_once('footer.php'); ?>
