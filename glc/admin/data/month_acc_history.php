<?php
session_start();
$newp = $_GET['p'];
$plimit = "10";

?>
<div class="ibox-content"> 	 
<form method="post" action="">
<table class="table table-bordered">
	<thead><tr><th>Search By Month</th></tr></thead>
	<tr>
		<td class="text-center">
			<select name="month">
				<option value="">Month</option>
				<?php
				for($i = 1; $i <= 12; $i++) 
				{ ?>
					<option <?php if($month == $i) { ?> selected="selected" <?php } ?> 
					value="<?=$i; ?>"><?=$i; ?>		</option>
				<?php	
				} ?> 
			</select>
			<select name="year">
				<option value="">Year</option>
				<?php
					$yr = date('Y');
					//$p_yr = date('Y', strtotime("-2year"));
				for($i = 2014; $i <= $yr; $i++) 
				{ ?>
					<option <?php if($year == $i) { ?> selected="selected" <?php } ?> 
					value="<?=$i; ?>"><?=$i; ?></option>
				<?php 	
				} ?> 
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

if(isset($_REQUEST['year']) and isset($_REQUEST['month']))
{	
	unset($_SESSION['year']);
	unset($_SESSION['month']);
	$_SESSION['year'] = $year = $_REQUEST['year'];
	$_SESSION['month'] =$month = $_REQUEST['month'];
}
else
{
	$year = $_SESSION['year'];
	$month = $_SESSION['month'];
}
if(isset($_REQUEST['search']) or isset($_GET['p']))
{
	
	$sr_no = 1;
	$sql = "select account.* , users.username from account inner join users on users.id_user = account.user_id where YEAR(account.date) = '$year' AND MONTH(account.date) = '$month' order by account.id desc";	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Username</th>
			<th class="text-left">	Description</th>
			<th class="text-center">Credit</th> 
			<th class="text-center">Debit</th> 
			<th class="text-center">Wallet Balance</th>
		</tr>
		</thead>
<?php
$pnums = ceil ($totalrows/$plimit);
	
	if ($newp==''){ $newp='1'; }
	
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
	
	
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }

	//$sql = "select * from account where YEAR(date) = '$year' AND MONTH(date) = '$month' and user_id = '$user_id' ORDER BY date ASC LIMIT $start,$plimit";
	$sql = "select account.* , users.username from account inner join users on users.id_user = account.user_id where YEAR(account.date) = '$year' AND MONTH(account.date) = '$month' order by account.id desc";
	$query1 = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
			
		while($rows = mysqli_fetch_array($query1))
		{
			$credit = $rows['cr'];
			$debit = $rows['dr'];
			$type = $rows['type'];
			$date = $rows['date'];
			$acc = $rows['account'];
			$wall_bal = $rows['wallet_balance'];
			
			$username = $rows['username'];
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
		echo "</table>";
?>
		<!--<div class="dataTables_footer">
		<div id="sorting-advanced_paginate" class="dataTables_paginate paging_full_numbers">-->
	<?php
		/*if ($newp>1)
		{ 
		?>
			<a id="sorting-advanced_previous" class="previous paginate_button paginate_button_disabled" 
			href="<?php echo "index.php?page=month_acc_history&p=".($newp-1);?>">Previous</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ 
			?>
				<a class="paginate_button" href="<?php echo "index.php?page=month_acc_history&p=$i";?>">
					<?php print_r("$i");?>
				</a>
			<?php 
			}
			else
			{
			?>	<a class="paginate_active" ><?php print_r("$i"); ?></a>
			<?php
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a id="sorting-advanced_next" class="next paginate_button" href="<?php echo"index.php?page=month_acc_history&p=".($newp+1);?>">Next</a>
		<?php  
		} 
		print "</div></div>";*/
	}
	else{ print "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</b>";}
}

/*for($i = 99999999999999999999999999998548595995330233236626; $i > 1; $i++){ }*/
?>
</div>