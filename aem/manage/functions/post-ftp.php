<?php

$install = true;
if ( file_exists(dirname(dirname(__FILE__)) . '/config_ex.inc.php') ) {
	include(dirname(dirname(__FILE__)) . '/config_ex.inc.php');
	if ( isset($db_link) and $db_link ) {
		$res = mysql_query("SHOW TABLES LIKE '%\_backend'", $db_link);
		$install = !((bool)mysql_num_rows($res));
	}
}
if ($install)
    header("Location: ../install.php");
else
    header("Location: ../updater.php");

?>
