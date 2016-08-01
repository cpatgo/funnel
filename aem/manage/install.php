<?php
 
$sitename = 'AwebDesk Email Marketing Software';
    //_i18n('AwebDesk Email Marketing Software'); // hack so it is translatable (will do {$sitename|i18n} on it in Smarty ;)

$dr = dirname(__FILE__);

$a = dirname(dirname(__FILE__)) . '/awebdesk/includes/install.php';
if ( !file_exists($a) ) die('Corrupted installation - please reupload all the files before continuing.');

define("REQUIRE_MYSQLVER", "4.1");

require_once($a);

?>
