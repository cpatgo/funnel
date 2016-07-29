<?php
/********************************************
*
*	Filename:	common.php
*	Author:		Ahmet Oguz Mermerkaya
*	E-mail:		ahmetmermerkaya@hotmail.com
*	Begin:		Tuesday, Feb 24, 2009  09:50
*
*********************************************/

require_once('include/config.php');
require_once('include/functions.php');

$db = NULL;
$treeManager = NULL;
if (defined("DEMO_MODE") === true && DEMO_MODE === true)
{
	require_once('include/classes/DemoTreeManager.php');		
	$treeManager = new DemoTreeManager(null);
}
else if (TARGET_PLATFORM == DATABASE_PLATFORM ) 
{
	require_once('include/classes/Mysql.php');
	require_once('include/classes/DBTreeManager.php');
	$db = new MySQL($dbHost, $dbUsername, $dbPassword, $dbName);	
	$treeManager = new DBTreeManager($db);
}
else if (TARGET_PLATFORM == FILE_SYSTEM_PLATFORM) 
{	
	require_once("include/classes/FileTreeManager.php");
	$treeManager = new FileTreeManager(FILE_ROOT);
}
?>