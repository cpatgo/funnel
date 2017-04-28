<?php
define('AWEBP_DB_HOST', 'localhost');
define('AWEBP_DB_USER', 'identifz_min_wp');
define('AWEBP_DB_PASS', ';%+MlWZ6]9-!SWfaa');
define('AWEBP_DB_NAME', 'identifz_glc_1min_aem');


$GLOBALS["db_link"] = mysql_connect(AWEBP_DB_HOST, AWEBP_DB_USER, AWEBP_DB_PASS, true);
$db_linkdb = mysql_select_db(AWEBP_DB_NAME, $GLOBALS["db_link"]);
?>