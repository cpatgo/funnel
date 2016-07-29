<?php

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$cnt_d = date('M');
$cnt_yr = date('Y');

?>
<p></p>
 
<script type="text/javascript"> 
function checkAll(formname, checktoggle)
{
     var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = checktoggle;
          }
      }
}
</script>

<?php 

$newp = $_GET['p'];
$plimit = "20";

$query_Search = "";
if(isset($_POST['Search']))
{
	$start_year = $_POST['start_year'];
	$start_month = $_POST['start_month'];
	$start_day = $_POST['start_day'];
	
	$last_year = $_POST['last_year'];
	$last_month = $_POST['last_month'];
	$last_day = $_POST['last_day'];
	
	$start_date = $start_year."-".$start_month."-".$start_day;
	$end_date = $last_year."-".$last_month."-".$last_day;
	$query_Search .= " and date >= '$start_date' and date <= '$end_date' ";
}	

if(isset($_POST['payout']))
{
	$total_array = $_POST['content'];
	$cnt = count($total_array );
	for($i = 0; $i < $cnt; $i++)
	{
		$u_id = $total_array[$i];
		$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where amount >= '$setting_min_withdrawal' and id = '$u_id' ");
		$totalrows = mysqli_num_rows($quer);
		if($totalrows > 0)
		{
			while($row = mysqli_fetch_array($quer))
			{	
				$left_amnt = 0;
				$username = get_user_name($u_id);
				$request_amount = $row['amount'];
				
				$admin_tds = $request_amount*($setting_withdrawal_tax/100);
				$admin_tax = $request_amount*($setting_admin_tax/100);
				$left_amnt = $request_amount-($admin_tax+$admin_tds);
				$date = date('Y-m-d'); 
				
				
				mysqli_query($GLOBALS["___mysqli_ston"], "insert into payment_information (user_id , income , tax , tds , date , amount) values ('$u_id' , '$request_amount' , '$admin_tax' , '$admin_tds' , '$date' , '$left_amnt') ");
				
				mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = 0 where id = '$u_id' ");
				
			}
		}
	}	
	print "<p>Payment has been Transfered From Wallet !<br>Payment is on Hold !!</p>"; 
}
else
{
	$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where amount >= '$setting_min_withdrawal' $query_Search ");
	$totalrows = mysqli_num_rows($quer);
	if($totalrows != 0)
	{
		while($ro = mysqli_fetch_array($quer))
			$totalamount = $totalamount+$ro['amount'];
			
		$admin_tds = $totalamount*($setting_withdrawal_tax/100);
		$admin_tax = $totalamount*($setting_admin_tax/100);
		$left_amnt = $totalamount-($admin_tax+$admin_tds);
			
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
?>
			
			<table align="center" hspace = 0 cellspacing=0 cellpadding=0 border=0 height="40" width=100%> 
			<tr>
			<form name="myformtt" method="post">
			<th colspan=7 align="right">
			Start  Date&nbsp;<select name="start_year" style="width:70px;">
			<option value="">YYYY</option>
			<?php
				$yr = date('Y');
			 for($i = 1930; $i <= $yr; $i++) 
			 { ?>
			 	<option <?php if($year == $i) { ?> selected="selected" <?php } ?> value="<?php print $i; ?>"><?php print $i; ?></option>
			<?php } ?> 
			</select>
			<select name="start_month" style="width:52px;">
			<option value="">MM</option>
			<?php
			 for($i = 1; $i <= 12; $i++) 
			 { ?>
			 	<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?php print $i; ?>"><?php print $i; ?></option>
			<?php } ?> 
			</select>
			<select name="start_day" style="width:52px;">
			<option value="">DD</option>
			<?php
			 for($i = 1; $i <= 31; $i++) 
			 { ?>
			 	<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?php print $i; ?>"><?php print $i; ?></option>
			<?php } ?> 
			</select> 
			End Date&nbsp;
			<select name="last_year" style="width:70px;">
			<option value="">YYYY</option>
			<?php
				$yr = date('Y');
			 for($i = 1930; $i <= $yr; $i++) 
			 { ?>
			 	<option <?php if($year == $i) { ?> selected="selected" <?php } ?> value="<?php print $i; ?>"><?php print $i; ?></option>
			<?php } ?> 
			</select>
			<select name="last_month" style="width:52px;">
			<option value="">MM</option>
			<?php
			 for($i = 1; $i <= 12; $i++) 
			 { ?>
			 	<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?php print $i; ?>"><?php print $i; ?></option>
			<?php } ?> 
			</select>
			<select name="last_day" style="width:52px;">
			<option value="">DD</option>
			<?php
			 for($i = 1; $i <= 31; $i++) 
			 { ?>
			 	<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?php print $i; ?>"><?php print $i; ?></option>
			<?php } ?> 
			</select> 
			<input type="submit" name="Search" value="Search">	</th>
			</form>
			</tr>
			<form name="myformtt" method="post">
			<tr>
				<th colspan=7>&nbsp;</th>
			</tr>
			<tr>
				<th colspan=2 height=30 align=left style="padding-left:120px;"><p>Total Amount</p></th>
				<th colspan=2 align=right style="padding-right:50px; width:400px;"><p><?php print $totalamount;?> INR</p></th>
				<th colspan=3 rowspan=4 valign=top align=center ><p>For Payment Click Button</p><input type="submit" name="payout" value="Payout">	</th>
				</tr>
			<tr>
				<th colspan=2 height=30 align=left style="padding-left:120px;"><p>Admin Tax </p></th>
				<th colspan=2 align=right style="padding-right:50px;"><p><?php print $admin_tax; ?> INR</p></th>
			</tr>
			<tr>
				<th colspan=2 height=30 align=left style="padding-left:120px;"><p>TDS Tax </p></th>
				<th colspan=2 align=right style="padding-right:50px;"><p><?php print $admin_tds; ?> INR</p></th>
			</tr>
			<tr>
				<th colspan=2 height=30 align=left style="padding-left:120px;"><p>Net Amount</p></th>
				<th colspan=2 align=right style="padding-right:50px;"><p><?php print $left_amnt;?> INR</p></th>
			</tr>
			<tr>
				<th colspan=6>&nbsp;	</th>
			</tr>

			<tr>
			<th height=30 class="message tip"><strong>
			<a style="color:#660033; font-size:8px; width:30px; cursor:pointer;" onclick="javascript:checkAll('myformtt', true);">Check All</a><br />
         <a style="color:#660033; font-size:8px; cursor:pointer;" onclick="javascript:checkAll('myformtt', false);">UnCheck All</a>
			</strong></th>
			<th height=30 class="message tip"><strong>User Id</strong></th>
			<th class="message tip"><strong>Wallet Amount</strong></th>
			<th class="message tip"><strong>Admin Tax</strong></th>
			<th class="message tip"><strong>TDS Tax</strong></th>
			<th class="message tip"><strong>Net Income</strong></th>
			<th class="message tip"><strong>Date</strong></th>
			</tr>
<?php		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where amount >= '$setting_min_withdrawal' $query_Search LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{	
			$left_amnt = 0;
			$u_id = $row['id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			
			$admin_tds_anount = $request_amount*($setting_withdrawal_tax/100);
			$admin_tax_anount = $request_amount*($setting_admin_tax/100);
			$left_amnt = $request_amount-($admin_tds_anount+$admin_tax_anount);
			
			$paid_date = $row['app_date'];
			$date = $row['date'];
			$payment_mode = $row['payment_mode'];
			$information = $row['information'];
			$mode = $row['mode'];
?>
	
	<tr>
			<td class="input-medium" style="padding-left:10px">
			<input type="checkbox" name="content[]" value="<?php print $u_id; ?>"/>
			</td>
			<td class="input-medium" style="padding-left:10px"><?php print $username; ?></td>
			<td class="input-medium" style="text-align:right; padding-right:10px; width:300px;"> <?php print $request_amount; ?> INR</small></td>
			<td class="input-medium" style="text-align:right; padding-right:10px; width:100px;"><?php print $admin_tax_anount; ?> INR</small></td>
			<td class="input-medium" style="text-align:right; padding-right:10px; width:100px;"><?php print $admin_tds_anount; ?> INR</small></td>
			<td class="input-medium" style="text-align:right; padding-right:10px; width:300px;"><?php print $left_amnt; ?> INR</small></td>
			<td class="input-medium" style="padding-left:10px; width:150px;"><?php print $date; ?></small></td>
			</tr>
<?php	}	?>	 
</form>
<?php 	
print "</form>
		<tr><td colspan=7>&nbsp;</td></tr><td style=\"text-align:left; padding-left:10px;\" colspan=7 height=30px width=400 class=\"message tip\"><strong>";
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
	else{ print "<p>There is No Member for Payment on $cnt_d $cnt_yr !</p>"; }
}	?>

