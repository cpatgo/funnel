<?php
session_start();
include("function/functions.php");
$id = $_SESSION['dennisn_user_id'];
?>
<?php
if(isset($_POST['read']))
{
	$table_id = $_POST['table_id'];
	mysqli_query($GLOBALS["___mysqli_ston"], "update message set mode = 1 where id = '$table_id' ");
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$receive_id  = $row['receive_id'];
		$title = $row['title'];
		$message = $row['message'];
		$message_date = $row['message_date'];
		$mode = $row['mode'];
	}
	
	$qqq = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM admin WHERE id_user = '$id_user'");
	while($rrrr = mysqli_fetch_array($qqq))
	{
		$name = $rrrr['username'];
	}
	?>
		<div style="text-align:left; padding-left:10px;">
			<div style="height:30px;"><?=$From;?> : <?=$name; ?></div>
			<div style="height:30px;"><?=$Title;?> : <?=$title; ?></div>
			<div style="height:30px;"><?=$Date;?> : <?=$message_date; ?></div>
			<div style="height:auto; margin-top:20px;"><?=$Message;?> : <?=$message; ?></div>
		</div>
		
<?php
}
else
{
	$id = $_SESSION['dennisn_user_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE receive_id = '$id' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<div class="ibox-content">
		<table class="table table-bordered">
		<?php					
		while($row = mysqli_fetch_array($query))
		{
			$id  = $row['id'];
			$receive_id  = $row['receive_id'];
			//$receive_id = get_receive_id($row['receive_id']);
			$title = $row['title'];
			$message = $row['message'];
			$message_date = $row['message_date'];
			$mode = $row['mode'];
			
			$que = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM admin WHERE id_user = '$id_user'");
			while($rrr = mysqli_fetch_array($que))
			{
				$name = $rrr['username'];
			
			}
?>
			<tr height="30">
			<form action="" method="post">
				<input type="hidden" name="table_id" value="<?=$id; ?>"  />
				<td width="200" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
					<input type="submit" name="read" value="<?=$title; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td>
						
				<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
					<input type="submit" name="read" value="<?=$message; ?>" style=" width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; vertical-align:top; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td>
				
				<td width="150" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
					<input type="submit" name="read" value="<?=$name; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td> 
					
				<td width="100" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
					<input type="submit" name="read" value="<?=$message_date; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
				</td>
			</form>
			</tr>
<?php 	}
		print "</tbody></table></div>";
	}
	 else{ print "<B style=\"color:#ff0000; font-size:12pt;\">$No_info_to_show</B>"; }
} ?>			
