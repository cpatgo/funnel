<?php
session_start();
require_once("../config.php");
//include("function/functions.php");
?>

<div class="ibox-content">
<?php
if(isset($_POST['read']))
{
	$table_id = $_POST['table_id'];
	mysqli_query($GLOBALS["___mysqli_ston"], "update message set mode = 1 where id = '$table_id' ");
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$id  = $row['id'];
		$title = $row['title'];
		$message = $row['message'];
		$message_date = $row['message_date'];
		$mode = $row['mode'];
		$receive_id = $row['id_user'];
		
	}
	if($receive_id == 0)
	{
		$name = "Admin";
	}
	else
	{	
		$qqq = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$receive_id'");
		while($rrrr = mysqli_fetch_array($qqq))
		{
			$name = $rrrr['f_name']." ".$rrrr['l_name'];
		
		}
	}	
?> 
		<div style="height:30px; text-align:left; padding-left:10px;">From : <?=$name;?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Title : <?=$title; ?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Date : <?=$message_date; ?></div>
		<div style="height:auto; text-align:left; padding-left:10px; margin-top:20px;">
			Message : <?=$message; ?>
		</div>
		
<?php
}
else
{
	$id = $_SESSION['admin_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE receive_id = '$id_user' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<table class="table table-bordered"> 
	<?php
		while($row = mysqli_fetch_array($query))
		{
			$id  = $row['id'];
			//$receive_id = get_receive_id($row['receive_id']);
			$title = $row['title'];
			$message = $row['message'];
			$message_date = $row['message_date'];
			$mode = $row['mode'];
			$receive_id = $row['id_user'];
			
			$que = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$receive_id'");
			while($rrr = mysqli_fetch_array($que))
			{
				$name = $rrr['f_name']." ".$rrr['l_name'];
			}
?>
			<tr>
			<form action="" method="post">
				<input type="hidden" name="table_id" value="<?=$id; ?>"  />
				<td>
				<input type="submit" name="read" value="<?=$title; ?>" style="width:120px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td>
					
				<td>
				<input type="submit" name="read" value="<?=$message; ?>" style=" padding-top:5px; width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td>
			
				<td>
				<input type="submit" name="read" value="<?=$name; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td> 
				
				<td>
				<input type="submit" name="read" value="<?=$message_date; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td>
			</form>
			</tr>
		</table>
	</div>
<?php 	}
	}
	else 
	{ print "<B style=\"color:#FF0000; font-size:12pt;\">There is no information to show</B>";}
} ?>			
		
