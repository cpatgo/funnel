<?php
session_start();

//ini_set("display_errors",'on');
//include("condition.php");
//require_once("config.php");

$id = $_SESSION['dennisn_user_id'];
if(isset($_POST['tree_member']))
{
	$username = $_POST['search_by_email'];
	$prestashop_user_id = $_SESSION['nexus25_user_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$querrrrry = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
			while($rrrr = mysqli_fetch_array($querrrrry))
			{
				$id_user = $rrrr['id_user'];
			}
		}
		$_SESSION['matrix1_session_id'] = $id_user;
	}
	else
	{ echo "<B style=\"color:red; font-size:14px;\">Please Use Correct Name For Search</B>"; }
}
else
{ $_SESSION['matrix1_session_id'] = $_SESSION['dennisn_user_id']; }
	
define("IN_PHP", true);

//require_once("common_matrix1.php");
$matrix1_session_id = $_SESSION['matrix1_session_id'];
$sql = "select * from users where id_user = '$matrix1_session_id' ";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
while($row = mysqli_fetch_array($query))
{
	$welcome_name = "<strong>".$row['email']."(".$row['f_name']." ".$row['l_name'].")</strong>";
} 
$rootName = $welcome_name."&nbsp;Tree";
$treeElements = $treeManager->getElementList( $_SESSION['matrix1_session_id'], "manageStructure_matrix1.php");	
	
	
?>
<div class="ibox-content">	
<div class="contextMenu" id="myMenu2">
	<li class="edit"><img src="js/jquery/plugins/simpleTree/images/page_edit.png" /> </li>
	<li class="delete"><img src="js/jquery/plugins/simpleTree/images/page_delete.png" /> </li>
</div>
<div align="right">
	<form action="" method="post">
		<input type="text" value="" name="search_by_email" />
		<input type="submit" name="tree_member" value="Search" />
	</form>
</div>

<div id="wrap">
	<div id="annualWizard" style="width:960px;">	
		<ul class="simpleTree" id='pdfTree'>		
			<li class="root" id='<?=$treeManager->getRootId();  ?>'><span><?=$rootName; ?></span>
				<ul><?=$treeElements; ?></ul>				
			</li>
		</ul>						
	</div>	
</div> 
<div id='processing'>&nbsp;</div>

 
