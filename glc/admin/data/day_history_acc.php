<?php
session_start();
ini_set("display_errors","off");
include("../function/functions.php");
?>
<div class="ibox-content">	
<form method="post" action="">
<table class="table table-bordered">
	<thead><tr><th>Search By Day</th></tr></thead>
	<tr>
	<td class="text-center">
	<select name="day">
	<option value="">Date</option>
	<?php
	 for($i = 1; $i <= 31; $i++) 
	 { ?>
		<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
		<?=$i; ?></option>
	<?php } ?> 
	</select>
	
	<select name="month">
	<option value="">Month</option>
	<?php
	 for($i = 1; $i <= 12; $i++) 
	 { ?>
		<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>"><?=$i; ?></option>
	<?php } ?> 
	</select>
	
	<select name="year">
	<option value="">Year</option>
	<?php
		$yr = date('Y');
	 for($i = 2014; $i <= $yr; $i++) 
	 { ?>
		<option <?php if($year == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>"><?=$i; ?></option>
	<?php } ?> 
	</select>
	</td>
	</tr>		
	<tr>
		<td class="text-center">
			<input type="submit" value="Search" name="search" class="btn btn-primary">
		</td>
	</tr>	
</table>	
</form>	

<?php
$date = $_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day'];
$_SESSION['date'] = $date;
if(isset($_REQUEST['search']))
{
	
	$sr_no = 1;
	$sql = "select t1.* , t2.username from account t1 inner join users t2 on t1.user_id = t2.id_user where t1.date = '$date' ORDER BY t1.date DESC";	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		$total_cr = round($_SESSION['total_cr'],2);
		$total_dr = round($_SESSION['total_dr'],2);
?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="7" class="text-right">
				To Create Excel File <img src="images/click_here.png" /> &nbsp;
				<a href="index.php?page=day_history_acc_excel">Click Here</a>
			</th>
		</tr>
		<tr>
			<th colspan="2" class="text-center">Total Credit</th>
			<th id="tot_credit"></th>
			<th colspan="2" class="text-center">Total Debit</th>
			<th colspan="2" id="tot_debit"></th>
		</tr>
		<tr>
			<th class="text-center">Sr No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Username</th>
			<th class="text-left">  Description</th>
			<th class="text-center">Credit</th> 
			<th class="text-center">Debit</th> 
			<th class="text-center">Wallet Balance</th>
		</tr>
		</thead>
<?php
		while($rows = mysqli_fetch_array($query))
		{
			$user_id = $rows['user_id'];
			$credit = $rows['cr'];
			$debit = $rows['dr'];
			$type = $rows['type'];
			$date = $rows['date'];
			$acc = $rows['account'];
			$wall_bal = $rows['wallet_balance'];
			$username = $rows['username'];
			
			$crdt += $credit;
			$debt += $debit;
	?>
		<tr class="text-center">
			<td><?=$sr_no; $sr_no++; ?></td>
			<td><?=$date; ?></td>
			<td><?=$username; ?></td>
			<td style="font-size:11px;" class="text-left"><?=$acc; ?></td>
			<td><?="$".$credit; ?></td>
			<td><?="$".$debit; ?></td>
			<td><?="$".$wall_bal; ?></td>
		</tr>
<?php
		}
		echo '<script language="javascript">
				document.getElementById("tot_debit").innerHTML='.$debt.'
				document.getElementById("tot_credit").innerHTML='.$crdt.'
			</script>';
	}
	else{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</b>";}
}
?>
	</table>
</div>
