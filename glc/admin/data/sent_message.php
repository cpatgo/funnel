<?php
session_start();
require_once("../config.php");
//include("function/functions.php");
$id = $_SESSION['admin_id'];

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
		$qqq = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$receive_id'");
		while($rrrr = mysqli_fetch_array($qqq))
		{
			$name = $rrrr['f_name']." ".$rrrr['l_name'];
		
		}
?> 
		<div class="ibox-content">
			<div style="height:30px; text-align:left">Title : <?=$title; ?></div>
			<div style="height:30px; text-align:left">To : <?=$name; ?></div>
			<div style="height:30px; text-align:left">Date : <?=$message_date; ?></div>
			<div style="height:auto; text-align:left; margin-top:20px;">Message : <?=$message; ?></div>
		</div>
	
<?php
}
else
{ 
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE id_user = '0' order by id desc");
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
				
				$que = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$receive_id'");
				while($rrr = mysqli_fetch_array($que))
				{
					$name = $rrr['f_name']." ".$rrr['l_name'];
				
				}
			 
				?>
				<tr>
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
			</table>
			</div>
<?php 			} 
	}	
	 else { print "<B style=\"color:#FF0000; font-size:12pt;\">There is no information to show</B>";}
}?>

