<?php
define('AWEBP_DB_HOST', 'localhost');
define('AWEBP_DB_USER', 'identifz_aem');
define('AWEBP_DB_PASS', 'U*XF}OmOb5hh');
define('AWEBP_DB_NAME', 'identifz_glc_aem');


$GLOBALS["db_link"] = mysql_connect(AWEBP_DB_HOST, AWEBP_DB_USER, AWEBP_DB_PASS, true);
$db_linkdb = mysql_select_db(AWEBP_DB_NAME, $GLOBALS["db_link"]);
?>