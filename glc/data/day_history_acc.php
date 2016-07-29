<?php
session_start();
$user_id = $_SESSION['dennisn_user_id'];
?>
<div class="ibox-content">	
<form method="post" action="">
<table class="table table-bordered">
	<thead><tr><th><?=$Search_By_Day;?></th></tr></thead>
	<tr>
	<td class="text-center">
	<select name="day">
	<option value=""><?=$Date;?></option>
	<?php
	 for($i = 1; $i <= 31; $i++) 
	 { ?>
		<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
		<?=$i; ?></option>
	<?php } ?> 
	</select>
	
	<select name="month">
	<option value=""><?=$Month;?></option>
	<?php
	 for($i = 1; $i <= 12; $i++) 
	 { ?>
		<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>"><?=$i; ?></option>
	<?php } ?> 
	</select>
	
	<select name="year">
	<option value=""><?=$Year;?></option>
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
			<input type="submit" value="<?=$Search;?>" name="search" class="btn btn-primary">
		</td>
	</tr>	
</table>	
</form>	

<?php
$date = $_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day'];

if(isset($_REQUEST['search']))
{
	
	$sr_no = 1;
	$sql = "select * from account where date = '$date' and user_id = '$user_id' ORDER BY date DESC";	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr No.</th>
			<th class="text-center">Date</th>
			<th class="text-left">Description</th>
			<th class="text-center">Credit</th> 
			<th class="text-center">Debit</th> 
			<th class="text-center">Wallet Balance</th>
		</tr>
		</thead>
<?php
		while($rows = mysqli_fetch_array($query))
		{
			$credit = $rows['cr'];
			$debit = $rows['dr'];
			$type = $rows['type'];
			$date = $rows['date'];
			$acc = $rows['account'];
			$wall_bal = $rows['wallet_balance'];
?>
		<tr class="text-center">
			<td><?=$sr_no; $sr_no++; ?></td>
			<td><?=$date; ?></td>
			<td style="font-size:11px;" class="text-left"><?=$acc; ?></td>
			<td><?="$".$credit; ?></td>
			<td><?="$".$debit; ?></td>
			<td><?="$".$wall_bal; ?></td>
		</tr>
<?php
		}
	}
	else{ echo "<B style=\"color:#FF0000; font-size:12pt;\">$No_info_to_show</b>"; }
}
?>
	</table>
</div>
