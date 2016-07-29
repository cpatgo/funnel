#!/usr/local/bin/php
<?php
/**
* A script to run operations of AEM via cronjob
*/

// set file name
$fileName = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
// set folder
$backupFolder = dirname(dirname(dirname(dirname(__FILE__)))) . '/cache/';


$SWITCH = false; // when this is false, the file will not create backups!




require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/awebdesk/includes/dbbackup.php');

?>