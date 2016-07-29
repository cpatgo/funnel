<?php
session_start();
require_once("../config.php");
$id = $_SESSION['admin_id'];


print "<br>".$_SESSION['error'].$_SESSION['success']."<br>";
$_SESSION['error'] = $_SESSION['success'] = '';

if(isset($_POST['submit']))
{ 
	
	$title = $_REQUEST['title'];
	$message = $_REQUEST['message'];
	$message_date = date('y-m-d');
	$username = $_REQUEST['username'];

	if($title != '')
	{		
		if($username == 'All' or $username == 'all')
		{
			$quu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users");
			while($rrr = mysqli_fetch_array($quu))
			{
				$all_user[] = $rrr ['id_user'];	
			}
			
			$cnt = count($all_user);
			for($i = 0; $i < $cnt; $i++)
			{
			 	$all_user[$i];
			 	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO message (id_user,receive_id, title, message, message_date) VALUES ('0','$all_user[$i]' , '$title' , '$message', '$message_date') ");	
			}
			$_SESSION['success'] = "<B style=\"color:#015A08; font-size:12pt;\">Message send successfully!</B>";
				
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=compose\"";
			echo "</script>";
		}
		else
		{
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
			$num = mysqli_num_rows($query);
			if($num > 0)
			{
				while($row = mysqli_fetch_array($query))
					$receive_id = $row['id_user'];
					
				mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO message (id_user,receive_id, title, message, message_date) 
				VALUES ('0','$receive_id' , '$title' , '$message', '$message_date') ");	
				$_SESSION['success'] = "<B style=\"color:#015A08; font-size:12pt;\">Message send 
				successfully!</B>";
					
				echo "<script type=\"text/javascript\">";
				echo "window.location = \"index.php?page=compose\"";
				echo "</script>";
			}
			else
			{
				$_SESSION['error'] = "<B style=\"color:#FF0000; font-size:12pt;\">Please Enter Correct Username!</B>";
				echo "<script type=\"text/javascript\">";
				echo "window.location = \"index.php?page=compose\"";
				echo "</script>";
			}
		}
	}
	
	else
	{
		$_SESSION['error'] = "<B style=\"color:#FF0000; font-size:12pt;\">Please Enter Title!</B>";
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=compose\"";
		echo "</script>";
		
	}	
}
else
{ ?>
<div class="ibox-content">
<form name="message" action="" method="post">
<table class="table table-bordered"> 
  	<input type="hidden" name="id" value=""  />
	<input type="hidden" name="id_user" value=""  />
	<thead><tr><th colspan="2">Compose Message</th></tr></thead>
	<tr>
		<th width="40%">Title</th>
		<td><input type="text" name="title" /></td>
	</tr>
	<tr>
		<th>Username</th>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<th>Message</th>
		<td><textarea name="message"></textarea></td>
	</tr>
	<!-- <tr>
		<td>Message Date</td>
		<td><input type="text" name="message_date" class="flexy_datepicker_input" /></td>
	</tr>-->
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Send" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>
<?php }?>