<?php
/**
 * パスワードを生成する
 * @copyright	(c)studio pahoo
 * @author		パパぱふぅ
 * @version		1.0  2004/09/26
*/
/**
 * マイクロ秒により乱数器に種まき
*/
function make_seed() {
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
}

/**
 * パスワードを生成する
 * @param int		$len パスワードの長さ
 * @param string	$str パスワードに使う文字の並び
 * @return string	パスワード
*/
function make_password($len, $str) {
	$l = strlen($str) - 1;

	$psw = "";
	for ($i = 0; $i < $len; $i++) {
		$n = (int)mt_rand(0, $l);
		$psw = $psw . substr($str, $n, 1);			//１文字追加
	}
	return $psw;
}

//メインプログラム ========================================================
print make_password(8, "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
?>
