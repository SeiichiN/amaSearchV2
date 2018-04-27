<?php


require('header.php');
?>

<form action="" method="post">
	<p>
	現在のパスワードを入力してください。<br>
	<input type="password" name="oldPassword" id="oldPW">
	</p>
	<p>
	新しいパスワードを入力してください。</br>
	<input type="password" name="newPassword" id="newPW">
	</p>
    <p>
    確認のため、<br>
	もう一度新しいパスワードを入力してください。</br>
	<input type="password" name="renewPassword" id="renewPW">
	</p>
	<p>
	<input type="submit" value="決定">
	</p>
</form>

<?php require_once('footer.php'); ?>
