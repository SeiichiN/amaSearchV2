<?php
// namespace billiesworks

function exception_handler($exception) {
	$logtime = date('Y-m-d_His');
	$log = 'log/error_' . $logtime . '.log';
	
	$msg = "捕捉できない例外: " . $exception->getMessage();
	$msg = $msg . "(File: " . $exception->getFile() . ")";
 	$msg = $msg .  "(Line: " . $exception->getLine() . ")\n";
	sleep(1);
	return error_log($msg, 3, $log);
	// exit();
}

set_exception_handler('exception_handler');

set_error_handler(
	function ($errno, $errstr, $file, $line, $context) {
		throw new ErrorException($errstr, 0, $errno, $file, $line);
	}
);

function putErrorLog($e) {
	$logtime = date('Y-m-d_His');
	$log = 'log/error_' . $logtime . '.log';
	$msg = 'エラー:' . $e->getMessage();
	$msg = $msg . '(File:' . $e->getFile() . ')';
	$msg = $msg . '(Line:' . $e->getLine() . ")\n";
	return error_log($msg, 3, $log);
}

function h ($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

/**
 * Summary: それぞれの値を数値に変換
 *          もし空文字列なら、-1 を入れる。
 * 
 * @params: element of array,  $strNum.
 * @return: int,  $intNum
 *
 * Usage: $newArray = array_map("arrChange", $oldArray)
 */
function arrChange ($strNum) {
	if ($strNum == '')
		$intNum = -1;
	else
		$intNum = (int)$strNum;
	return $intNum;
}

/**
 * http://espresso3389.hatenablog.com/entry/2014/04/15/024056
 * PHPで本気で安全なパスワードを生成する
 */

// パスワードに使っても良い文字集合
$password_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

// $sizeに指定された長さのパスワードを生成
function generate_password($size) {
    global $password_chars;
    $password_chars_count = strlen($password_chars);
    // mcrypt_create_iv は、php7.0以降廃止。
    //  $data = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
    // random_bytesは、mcrypt_create_ivの代わりに導入されたが、php7.0以降しか対応していない。
    //  $data = random_bytes($size);
    // openssl_random_pseudo_bytesは、ランダムなバイト文字列を生成
    // バイト文字列をそのまま使っていいいのかな？
    $data = openssl_random_pseudo_bytes($size);
    $pin = '';
    for ($n = 0; $n < $size; $n ++) {
        $pin .= substr($password_chars, ord(substr($data, $n, 1)) % $password_chars_count, 1);
    }
    return $pin;
}

// SESSION変数からloginIdを知る
function getLoginId() {
	if (isset($_SESSION['loginId'])) {
		$loginId = $_SESSION['loginId'];
	} else {
		$loginId = NULL;
	}
	return $loginId;
}

// loginIdがNULLならindex.phpへもどす
function checkLoginId() {
	$myurl = getMyURL();
	$loginId = getLoginId();
	if ($loginId  === NULL || $loginId === 'guest') {
		header('Location: '. $myurl . 'login.php');
		exit();
	} else {
		return $loginId;
	}
}

// POSTを受け取る
function getPost($key) {
	if (!empty($_POST[$key])) {
		$val = trim($_POST[$key]);
		return $val;
	}
}

// GETを受け取る
function getGet($key) {
	if (!empty($_GET[$key])) {
		$val = trim(h($_GET[$key]));
		return $val;
	}
}

// COOKIEのmsgを受け取る
function getCookieMsg() {
	if (!empty($_COOKIE['msg'])) {
		$msg = trim($_COOKIE['msg']);
		setcookie('msg', '', time() - 3600);
		return $msg;
	} else {
		return NULL;
	}
}
// SESSIONのmsgを受け取る
function getSessionMsg() {
	if (!empty($_SESSION['msg'])) {
		$msg = trim($_SESSION['msg']);
		$_SESSION['msg'] = '';
		return $msg;
	} else {
		return NULL;
	}
}

// refererを知る
// 拡張子を除いたファイル名を返す
function getReferer() {
	$myPath = $_SERVER['HTTP_REFERER'];
	return (pathinfo($myPath, PATHINFO_FILENAME));
}

/**
 *  Summary: URLを知る
 *           HTTP_HOST -- ホスト名
 *           PHP_SELF  -- 実行中のスクリプト
 *           dirname   -- 親フォルダのパスを取り出す
 */
function getMyURL() {
	$myurl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
	return $myurl;
}


// COOKIEを受け取る
function getCookie($key) {
	if (!empty($_COOKIE[$key])) {
		$val = trim($_COOKIE[$key]);
		setcookie($key, '', time() - 3600);
		return $val;
	} else {
		return NULL;
	}
}

// SESSIONを受け取る
function getSession($key) {
	if (!empty($_SESSION[$key])) {
		$val = $_SESSION[$key];
		return $val;
	} else {
		return NULL;
	}
}

// バリデートでのエラーを表示する
function prValidateError($valiMsg) {
	if (isset($valiMsg)) {
        print '<ul class="errorMsg" style="color:Red">';
        foreach ($valiMsg as $errmsg) {
            print "<li>{$errmsg}</li>";
        }
        print '</ul>';
        $_SESSION['error'] = NULL;
    }
}

