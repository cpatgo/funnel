<?php
// increase this on every rebuild
$thisBuild = 19;

// when new version is added, add it to the TOP of this array
$thisUpdater = array(
    '7.1.0',
    '6.3.0',
	'6.2.0',
	'6.1.0',
	 
);

$thisVersion = $thisUpdater[0];

if ( isset($_GET['showv']) ) die("$thisVersion Build $thisBuild");
?>