<?php

$dr = dirname(__FILE__);

$a = dirname(dirname(__FILE__)) . '/awebdesk/includes/updater.php';
if ( !file_exists($a) ) die('Corrupted installation - please reupload all the files before continuing.');

define("REQUIRE_MYSQLVER", "4.1");

require_once($a);

?>
