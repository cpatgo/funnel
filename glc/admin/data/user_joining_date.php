<style>
table tr td{
	text-align:center;
}
</style>
<?php
	
if(isset($_POST['change']))
{
	if($_SESSION['date_session'] == 1)
	{
		 $ch_date = $_REQUEST['c_date']."<br />";
		 $id_user = $_REQUEST['id_user'];
		 $name = $_REQUEST['name'];
		
		mysqli_query($GLOBALS["___mysqli_ston"], "update users set date = '$ch_date' where id_user = '$id_user'");
		
		print "User $name 's Joining Date Update";
		
		$_SESSION['date_session'] = 0;
	}
	else
	{
		
	}
}
else
{
	$_SESSION['date_session'] = 1;
	$blank_date = "0000-00-00";
	$search_blank_date = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where date = '$blank_date'");
	$num = mysqli_num_rows($search_blank_date);
	if($num == 0)
	{
		echo "<B style=\"color:#ff0000; font-size:12pt;\">There have no information</B>";
	}
	else
	{
	$sr_no = 1;
	?>
	<div class="ibox-content">
	<table class="table table-bordered">
		<thead>
		<tr>
			<th>Sr. No.</th>
			<th>User Name</th>
			<th>Date</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
	<?php
		while($check = mysqli_fetch_array($search_blank_date))
		{
			echo "
				<form method=post>
				<tr>
					<td>".$sr_no."</td>
					<td>".$username = $check['username']."</td>
					<td>
					   <input type=hidden value=\"".$name = $check['username']."\" name=name>
					   <input type=hidden value=\"".$id_user = $check['id_user']."\" name=id_user>
					   <input type=text value=\"".$show_blank_date = $check['date']."\" name=c_date>
					</td>
					<td><input type=submit name=change value=change class=btn btn-primary></td>		
					</tr>
				</form>";
			
			$sr_no++;
		}
		print "</tbody></table></div>";
	}
 }
 ?>

