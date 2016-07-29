<?php
session_start();
require_once("config.php");
include("function/functions.php");
?>

<?php
if(isset($_POST['read']))
{
	$table_id = $_POST['table_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$receive_id  = $row['receive_id'];
		$title = $row['title'];
		$message = $row['message'];
		$message_date = $row['message_date'];
		$mode = $row['mode'];
	}
	$qqq = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM admin WHERE id_user = '1'");
	while($rrrr = mysqli_fetch_array($qqq))
	{
		$name = $rrrr['username'];
	
	}
	?> 
	<div style="text-align:left; padding-left:10px;">
		<div style="height:30px;"><?=$Title;?> : <?=$title; ?></div>
		<div style="height:30px;"><?=$To;?> : <?=$name; ?></div>
		<div style="height:30px;"><?=$Date;?> : <?=$message_date; ?></div>
		<div style="height:auto; margin-top:20px;"><?=$Message;?> : <?=$message; ?></div>
	</div>
	
<?php
}
else
{
	$id_user = $_SESSION['dennisn_user_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE id_user = '$id_user' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<div class="ibox-content">
		<table class="table table-bordered">
<?php					
			while($row = mysqli_fetch_array($query))
			{
				$receive_id  = $row['receive_id'];
				//$receive_id = get_receive_id($row['receive_id']);
				$title = $row['title'];
				$message = $row['message'];
				$message_date = $row['message_date'];
				$mode = $row['mode'];
				$id = $row['id'];
				
				$que = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM admin WHERE id_user = '1'");
				while($rrr = mysqli_fetch_array($que))
				{
					$name = $rrr['username'];
				
				}
			 
?>
				<tr height="30">
					<td><?=$title; ?></td>
					<td><?=$name; ?></td>
					<td>
						<form action="" method="post">
						<input type="hidden" name="table_id" value="<?=$id; ?>"  />
						<input type="submit" name="read" value="<?=$message; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; " />
						
						</form>
					</td>
					<td><?=$message_date; ?></td>
				</tr>
<?php 		}
			print "</tbody></table></div>"; 
	}	
	 else{ print "<B style=\"color:#ff0000; font-size:12pt;\">$No_info_to_show</B>"; }
}?>



