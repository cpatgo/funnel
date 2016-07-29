<?php 
//include "../config.php";
if(isset($_REQUEST['submit']))
{
$username = $_REQUEST['username'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
		}
		$inc_up = 0;
		$upline = array();
		 print  "<span style=\"font-size:14px;\">Current Enroller</span>";
		$upline_id = get_upline($id_user);
		print "<table style=\"font-size:12px;\">"; 
			foreach($upline_id as $value)
			{	if($value == 0)
				break;	
				print "<tr><td>".get_user_name($value)."</td></tr>";
			}
		print 
		"</table>";
	}
	else
	{
		print "Please enter correct usernsme !";
	}
}
else
{  ?>
<div class="ibox-content">
<form action="" method="post">
<table class="table table-bordered"> 
	<tr>
		<th>Username :</th>
		<td><input type="text" name="username" value="" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Show" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>
<?php 
} 
function get_upline($id) 
{	//get a query from users that parentid= $pid;
	
	global $inc_up;
	global $upline;
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT real_parent  FROM `users` WHERE id_user='$id'");
	while($row = mysqli_fetch_array($q))
	{	
		$upline[$inc_up]	=$id = $row['real_parent'];
		$inc_up++;
		get_upline($id);
	}
	return $upline;
}
function get_user_name($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;
	}	
}
