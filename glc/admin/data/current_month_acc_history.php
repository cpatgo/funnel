<?php
session_start();
include("function/account_maintain.php");

$sys_date = date('m');
	
$sqli = "select * from account where MONTH(date) = '$sys_date' group by date desc";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sqli);
$num = mysqli_num_rows($query);
if($num > 0)
{
?>
<div class="ibox-content">	
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center" colspan="2">Date</th>
			<th class="text-center">Transaction</th>
		</tr>
		</thead>
		<tbody>
<?php
	$le = 1;
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$user_id = $row['user_id'];
		$credit = $row['cr'];
		$debit = $row['dr'];
		$date = $row['date'];
		$date1 = date('M d , Y',strtotime($date));
		$debt = 0;
		$crdt = 0;
	?>
		<tr>
			<td class="text-center">
				<a href="javascript:void(0)" onClick="show_hide('img<?=$id; ?>');">
					<img src="images/plus.png" id="img<?=$id; ?>"></a>
			</td>
			<td>
				<a style="color:#0066BF;" href="javascript:void(0)" onClick="show_hide('img<?=$id;?>');" >
					<?=$date1; ?>
				</a>
			</td>
			<td>
				<table width="100%">
					<tr>
						<th width="25%">Total Credit</th>
						<td width="25%"><span class="blackText" id="tot_credit<?=$le;?>"></span></td>
						<th width="25%">Total Debit</th>
						<td width="25%"><span class="blackText" id="tot_debit<?=$le;?>"></span></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="display: none;" id="<?=$id; ?>">
			<td colspan="3">
				<table class="table table-bordered">
					<thead>
					<tr>
						<th class="text-center">TransID</th>
						<th class="text-center">Date</th>
						<th class="text-left">Description</th>
						<th class="text-center">Credit</th> 
						<th class="text-center">Debit</th> 
						<th class="text-center">Wallet Balance</th>
					</tr>
					</thead>
					<tbody>
		<?php
			
				$sr_no = 1;
				$sql = "select * from account where date = (Select date from account where id = '$id')";
				$quew = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				while($rows = mysqli_fetch_array($quew))
				{
					$credits = $rows['cr'];
					$debits = $rows['dr'];
					$type = $rows['type'];
					$acc = $rows['account'];
					$crdt = $crdt + $credits;
					$debt = $debt + $debits;
					$wall_bal = $rows['wallet_balance'];	
		?>	
	
					<tr class="text-center">
						<td><?=$sr_no;$sr_no++; ?></td>
						<td><?=$date1; ?></td>
						<td style="font-size:11px;" align="left"><?=$acc; ?></td>
						<td><?="$".$credits; ?></td>
						<td><?="$".$debits; ?></td>
						<td><?="$".$wall_bal; ?></td>
					</tr>
		<?php 	} 
				echo '<script language="javascript">
						document.getElementById("tot_debit'.$le.'").innerHTML='.$debt.'
						document.getElementById("tot_credit'.$le.'").innerHTML='.$crdt.'
					</script>'; 
			
			?>
						</tbody>
					</table>
				</td>
			</tr>
	<?php
	$le++;
	}
	echo "</table></div>";
}	
else{ print "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</b>";}
?>


<script type="text/javascript">
function show_hide(id)
{
	
	var CurrentRowClick = 	id.replace("img", "");
	
	if(document.getElementById(CurrentRowClick).style.display == "none")
	{
		document.getElementById(CurrentRowClick).style.display="";		
		document.getElementById("img"+CurrentRowClick).src="images/minus.png";
	}
	else
	{
		document.getElementById(CurrentRowClick).style.display="none";
		document.getElementById("img"+CurrentRowClick).src="images/plus.png";
	}
	
	if(document.getElementById("prv_open").value!=CurrentRowClick) 
	{
		hide_prv_row(document.getElementById("prv_open").value);
	}
	document.getElementById("prv_open").value = CurrentRowClick;
	
}
</script>