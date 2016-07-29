<?php
session_start();

include("condition.php");
include("../function/setting.php");


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
			print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=700>";
			$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "select sum(left_income) , sum(admin_tax) from income where user_id = '$user_id' and type = '$income_type[1]' ");
				while($row1 = mysqli_fetch_array($query1))
				{ 
					$tatal_admin_tax = $row1[1];
					$tatal_income = $row1[0];
				}
				print "<tr><td width=150 class=\"message tip\"><strong>Total Income</strong></td>
				<td width=150 height=30px class=\"message tip\"><b>$tatal_income INR</b></td>
				<td width=150 class=\"message tip\"><strong>Total Tax</strong></td>
				<td width=150 height=30px class=\"message tip\"><b>$tatal_admin_tax INR</b></td></tr>
				</tr><tr><td colspan=4>&nbsp;</td></tr>
				<tr>
				<td width=150 class=\"message tip\"><strong>Date</strong></th> 
				<td width=150 class=\"message tip\"><strong>Incone</strong></td>
				<td width=150 class=\"message tip\"><strong>Tax</strong></th> 
				<td width=150 class=\"message tip\"><strong>Left Income</strong></td>
				</tr>";
			
			while($row = mysqli_fetch_array($query))
			{
				$date = $row['date'];
				$amount = $row['amount'];
				$admin_tax = $row['admin_tax']; 
				$left_income = $row['left_income']; 	
				print "<tr><td  align=center width=200 class=\"input-medium\">$date</td>
				<td width=200 class=\"input-medium\" style=\"text-align:right; padding-right:20px;\">$amount INR</td>
				<td width=200 class=\"input-medium\" style=\"text-align:right; padding-right:20px;\">$admin_tax INR</td>
				<td width=200 class=\"input-medium\" style=\"text-align:right; padding-right:20px;\"><b>$left_income INR</b></td></tr>";
				$j = 1;
			}
			print"</table>";
		}		
		else{ print "There is No information to show !"; }
	}
}
else
{ ?>

<table width="50%" border="0">
<form name="myform" action="index.php?page=direct_income" method="post">
  <tr>
    <td colspan="2">&nbsp;</td>
  
  </tr>
  <tr>
    <td><p>Enter Username :</p></td>
    <td><p><input type="text" name="username" class="input-medium"  /></p></td>
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="submit" class="button"  /></p></td>
  </tr>
</table>

<?php  } ?>
