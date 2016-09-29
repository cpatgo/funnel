<?php
define('AWEBP_DB_HOST', 'localhost');
define('AWEBP_DB_USER', 'wwwglchu_glcuser');
define('AWEBP_DB_PASS', '7*hHqFw{+RJh');
define('AWEBP_DB_NAME', 'wwwglchu_glc_aem');


$GLOBALS["db_link"] = mysql_connect(AWEBP_DB_HOST, AWEBP_DB_USER, AWEBP_DB_PASS, true);
$db_linkdb = mysql_select_db(AWEBP_DB_NAME, $GLOBALS["db_link"]);
?>