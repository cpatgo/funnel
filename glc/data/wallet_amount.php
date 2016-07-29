<?php
session_start();
include("condition.php");
require_once("config.php");
include("function/setting.php");

$id = $_SESSION['dennisn_user_id'];
?>
<div class="ibox-content">	
<table class="table table-bordered">	
	<thead>
	<tr>
		<th class="text-center"><?=$Wallet_Balance;?></th> 
		<th class="text-center"><?=$Wallet_Points;?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM wallet WHERE id = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$income = $row['amount'];
	}
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from point_wallet where user_id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$user_point = $row['user_point'];
	}			
	print "
	<tr>
		<td class=\"text-center\">$income INR</small></td>
		<td class=\"text-center\">$user_point Pt</small></td>
	</tr>";
	?>
	</tbody>
</table>
</div>