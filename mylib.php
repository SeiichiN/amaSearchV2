<?php
// namespace billiesworks

function exception_handler($exception) {
	echo "捕捉できない例外: " , $exception->getMessage();
	echo "(File: ", $exception->getFile(), ")";
 	echo "(Line: ", $exception->getLine(), ")\n";
	echo "処理が集中しています。再度おねがいします。";
	sleep(3);
//	header('Location: hungup.php');
	exit();
}

set_exception_handler('exception_handler');

set_error_handler(
	function ($errno, $errstr, $file, $line, $context) {
		throw new ErrorException($errstr, 0, $errno, $file, $line);
	}
);

function h ($str) {
	return htmlspecialchars($str, ENT_QUOTE, "UTF-8");
}

?>
