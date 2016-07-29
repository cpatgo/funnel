<?php
session_start();
$id = $_SESSION['dennisn_user_id'];

if(isset($_POST['submit']))
{ 
	$title = $_REQUEST['title'];
	$message = $_REQUEST['message'];
	$username = $_REQUEST['username'];
	$message_date = date('y-m-d');
	
	if($title != '')
	{		
		if($username == 'Admin' or $username == 'admin')
		{
			$quu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from admin");
			while($rrr = mysqli_fetch_array($quu))
			{
				$admin = $rrr ['admin'];	
			}
			mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO message (id_user,receive_id, title, message, message_date) 
			VALUES ('$id','$admin' , '$title' , '$message', '$message_date') ");
			
			$_SESSION['success'] = "<B style=\"color:#1D6B02; font-size:12pt;\">$Msg_send_sucesful</B>";
				
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=compose\"";
			echo "</script>";
		}
		/*else
		{
		$query = mysql_query("select * from users where username = '$username' ");
		$num = mysql_num_rows($query);
		if($num > 0)
		{
			while($row = mysql_fetch_array($query))
			{ $receive_id = $row['id_user']; }
				
			mysql_query("INSERT INTO message (id_user,receive_id, title, message, message_date) 
			VALUES ('$id','$receive_id' , '$title' , '$message', '$message_date') ");	
			print $Msg_send_sucesful;
		}
		}*/
		else
		{
			$_SESSION['error'] = "<B style=\"color:#ff0000; font-size:12pt;\">$Plz_Entr_Crct_Admin_Usrnam</B>";
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=compose\"";
			echo "</script>";
		}
	}	
	else
	{
		$_SESSION['error'] = "<B style=\"color:#ff0000; font-size:12pt;\">$Plz_Entr_Title</B>";
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=compose\"";
		echo "</script>";
		
	}	
}
