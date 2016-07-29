<?php
session_start();

include("condition.php");
include("../function/setting.php");
?>
<div class="ibox-content">
<?php

if(isset($_POST['submit']))
{ 
	$username = $_REQUEST['username'];
	
	$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($id_query);
	if($num == 0)
	{
		print "Please enter correct Username !";
	}
	else
	{
		while($row = mysqli_fetch_array($id_query))
		{
			$user_id = $row['id_user'];
		}
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$user_id' and type = '$income_type[1]' ");
		$num = mysqli_num_rows($query);
		if($num != 0)
		{
			echo "<table class=\"table table-bordered\">";
			$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "select sum(amount) from income where 
			user_id = '$user_id' and type = '$income_type[1]' ");
			while($row1 = mysqli_fetch_array($query1))
			{ 
				$tatal_income = $row1[0];
			}
			echo "
				<thead>
				<tr>
					<th colspan=2>Total Income - user id ".$username."</th>
					<th colspan=2>$tatal_income</th>
				</tr>
				<tr>
					<th>Date</th> 
					<th>Income</td>
					<th>Board Name</th> 
					<th>Income Type</th> 
				</tr>
				</thead>
				<tbody>";
							
			while($row = mysqli_fetch_array($query))
			{
				$date = date("m/d/Y", strtotime($row['date']));
				$amount = number_format($row['amount'], 2);
				$board_type = $row['board_type']; 
				if($board_type == 1)
					$brd_type = 'First Break';
				else
					$brd_type = 'Break Again';
					
				$board_naam = $setting_board_name[$row['level']];	
				
				echo "
					<tr>
						<td>$date</td>
						<td>$amount</td>
						<td>$board_naam</td>
						<td>$brd_type</td>
					</tr>";
				$j = 1;
			}
			print"</tbody></table>";
		}		
		else{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There is No information to show !</B>"; }
	}
}
else
{ ?>
<form name="myform" action="index.php?page=board_income" method="post">
<table class="table table-bordered"> 
	<tr>
		<th>Enter Username </th>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="submit" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>
<?php  } ?>
