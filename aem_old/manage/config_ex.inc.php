<?php
define('AWEBP_DB_HOST', 'localhost');
define('AWEBP_DB_USER', 'cielbleu_glcaem');
define('AWEBP_DB_PASS', 'Dn+l8nTwt?BT');
define('AWEBP_DB_NAME', 'cielbleu_glcv2_aem');


$GLOBALS["db_link"] = mysql_connect(AWEBP_DB_HOST, AWEBP_DB_USER, AWEBP_DB_PASS, true);
$db_linkdb = mysql_select_db(AWEBP_DB_NAME, $GLOBALS["db_link"]);
?>