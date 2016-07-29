<?php

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$cnt_d = date('M');
$cnt_yr = date('Y');

$newp = $_GET['p'];
$plimit = "20";

if(isset($_POST['submit']))
{
	$req_date = $_POST['submit'];
	
	$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from payment_information where date = '$req_date' ");
	$totalrows = mysqli_num_rows($quer);
	if($totalrows != 0)
	{
		print " 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=100%>
			<tr><th height=30 class=\"message tip\"><strong>User Name</strong></th>
			<th class=\"message tip\"><strong>Income</strong></th>
			<th class=\"message tip\"><strong>Net Amount</strong></th>
			<th class=\"message tip\"><strong>Payment Mode</strong></th>
			<th class=\"message tip\"><strong>Payment Information</strong></th>
			<th class=\"message tip\"><strong>Date</strong></th>
			<th class=\"message tip\"><strong>Status</strong></th></tr>";
		
			while($row = mysqli_fetch_array($quer))
			{	
				$left_amnt = 0;
				$tbl_id = $row['id'];
				$u_id = $row['user_id'];
				$username = get_user_name($u_id);
				$request_amount = $row['income']; 	 	
				$pay_mode = $row['pay_mode'];
				$date = $row['date'];
				$pay_information = $row['pay_information'];
				$amount = $row['amount'];
				$mode = $row['mode'];
				
				if($mode == 0)
					$payment_status = "Pending";
				else
					$payment_status = "Paid";	
				
	?>							
				<tr>
				<td class="input-medium" style="padding-left:5px; width:130px;"><?php print $username; ?></td>
				<td class="input-medium" style="text-align:right; padding-right:10px;  width:120px;"><?php print $request_amount; ?> INR</small></td>
				<td class="input-medium" style="text-align:right; padding-right:10px; width:120px;"><?php print $amount; ?> INR</small></td>
				<td class="input-medium" style="padding-left:5px; width:125px;"><?php print $pay_mode; ?></small></td>
				<td class="input-medium" style="padding-left:5px; width:150px;"><?php print $pay_information; ?></small></td>
				<td class="input-medium" style="padding-left:5px; width:100px;"><?php print $date; ?></small></td>
				<td class="input-medium" style="padding-left:5px; width:80px;"><?php print $payment_status; ?></small></td>
				</tr>
<?php			} ?>
			</table>
<?php
	}	
}
else
{
	$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from payment_information group by date  ");
	$totalrows = mysqli_num_rows($quer);
	if($totalrows > 0)
	{
		while($ro = mysqli_fetch_array($quer))
		{
			$req_date = $ro['date'];
			
			
		print " 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=100%>
			<tr><th height=30 class=\"message tip\"><strong>Date</strong></th>
			<th class=\"message tip\"><strong>Total Income</strong></th>
			<th class=\"message tip\"><strong>Admin Tax</strong></th>
			<th class=\"message tip\"><strong>TDS Tax</strong></th>
			<th class=\"message tip\"><strong>Net Income</strong></th></tr>";
		
		
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			if ($totalrows - $start < $plimit) { $end_count = $totalrows;
			} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
				
				
			
			if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
			} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from payment_information where date = '$req_date' LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($query))
			{	
				$left_amnt = 0;
				$u_id = $row['id'];
				$totalincome = $totalincome+$row['income'];
				$totaltax = $totaltax+$row['tax'];
				$totaltds = $totaltds+$row['tds'];
				$totalamount = $totalamount+$row['amount'];
			}	
					
?>
				<tr>
				<form name="myf" action="" method="post">
				<td class="input-medium" style="padding-left:40px">
				<input type="submit" name="submit" style="border:none; background:none; text-decoration:underline;" value="<?php print $req_date; ?>" /></td>
				
<?php				print "<td class=\"input-medium\" style=\"padding-left:40px\"> $totalincome INR</small></td>
				<td class=\"input-medium\" style=\"padding-left:40px\">$totaltax INR</small></td>
				<td class=\"input-medium\" style=\"padding-left:40px\">$totaltds INR</small></td>
				<td class=\"input-medium\" style=\"padding-left:40px\">$totalamount INR</small></td>
				</form>
				</tr>"; 
				
			print "<tr><td colspan=7>&nbsp;</td></tr><td colspan=7 height=30px width=400 class=\"message tip\"><strong>";
			if ($newp>1)
			{ ?>
				<a href="<?php echo "index.php?page=payment_report&p=".($newp-1);?>">&laquo;</a>
			<?php 
			}
			for ($i=1; $i<=$pnums; $i++) 
			{ 
				if ($i!=$newp)
				{ ?>
					<a href="<?php echo "index.php?page=payment_report&p=$i";?>"><?php print_r("$i");?></a>
					<?php 
				}
				else
				{
					 print_r("$i");
				}
			} 
			if ($newp<$pnums) 
			{ ?>
			   <a href="<?php echo "index.php?page=payment_report&p=".($newp+1);?>">&raquo;</a>
			<?php 
			} 
			print "</table>";	
		}
	}	
	else{ print "<p>There is No Member for Payment on $cnt_d $cnt_yr !</p>"; }
}	
?>

