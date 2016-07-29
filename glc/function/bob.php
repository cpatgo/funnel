<?php
require_once("../config.php");
include("setting.php");
$type = 3;
$levels = $type;
$reenter = $board_join[$type];
echo "$reenter";
echo "<br>";
$reenter = $reenter - $board_join[$levels - 1];
echo $reenter." - ".$board_join[$ll];
echo "<br>";
echo $reenter;
?>