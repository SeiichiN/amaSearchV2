<?php
require_once('Bot.php');

echo "アマゾンの現在価格を調べます。\n";
$myobj = new Bot();
$myobj->checkNow();
?>
