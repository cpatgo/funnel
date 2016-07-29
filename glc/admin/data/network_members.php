<?php
ini_set("display_errors","off");
include "../function/functions.php";
/********************************************
*
*	Filename:	index.php
*	Author:		Ahmet Oguz Mermerkaya
*	E-mail:		ahmetmermerkaya@hotmail.com
*	Begin:		Tuesday, Feb 23, 2009  10:21
*
*********************************************/

define("IN_PHP", true);

require_once("common.php");



?>	
<div align="right">
	<form action="" method="post">
		<input type="text" value="" name="search_by_name" />
		<input type="submit" name="tree_member" value="Search" class="btn btn-primary" />
	</form>
	</div> 
	<?php 
	if(isset($_POST['tree_member']))
	{
		$name = $_POST['search_by_name'];
		$sql = "select id_user from users where username = '$name' ";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$num = mysqli_num_rows($query);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($query))
			{
				$id = $row[0];
			}
			$_SESSION['tree_session_id'] = $id;	
		}
		else
		{
			print "<div style=\"color:red; font-size:14px;\" align=\"center\">Please Use Correct Name For Search</div>";
		}
	}
	else
	{
		$_SESSION['tree_session_id'] = 1;
	}
	
$rootName =  strtoupper(get_user_name($_SESSION['tree_session_id']))." Tree";
$treeElements = $treeManager->getElementList($_SESSION['tree_session_id'], "manageStructure.php");
// var_dump($treeManager);
?>

<div id="wrap">
	<div id="annualWizard">	
		<ul class="simpleTree" id='pdfTree'>		
			<li class="root" id='<?=$treeManager->getRootId();  ?>'><span><?=$rootName; ?></span>
				<ul><?=$treeElements; ?></ul>				
			</li>
		</ul>						
	</div>	
</div> 
<div id='processing'></div>
<?php //unset($_SESSION['tree_session_id']);?>