<?php
require_once("../config.php");
include("condition.php");
?>
<div class="ibox-content">
<form action="" method="post">
<table class="table table-bordered"> 
	<tr>
		<th width="45%">Start Date</th>
		<td>
			<select name="start_day">
				<option value="">DD</option>
				<?php
				 for($i = 1; $i <= 31; $i++) 
				 { ?>
					<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
						<?=$i; ?></option>
				<?php } ?> 
			</select>
			<select name="start_month">
				<option value="">MM</option>
				<?php
				 for($i = 1; $i <= 12; $i++) 
				 { ?>
					<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
						<?=$i; ?></option>
				<?php } ?> 
			</select>
			<select name="start_year">
				<option value="">YYYY</option>
				<?php
					$yr = date('Y');
				 for($i = 2013; $i <= $yr; $i++) 
				 { ?>
					<option <?php if($year == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
						<?=$i; ?></option>
				<?php } ?> 
			</select>
		</td>
	</tr>
	<tr>
		<th>End Date</th>
		<td>
			<select name="last_day">
				<option value="">DD</option>
				<?php
				 for($i = 1; $i <= 31; $i++) 
				 { ?>
					<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
					<?=$i; ?></option>
				<?php } ?> 
			</select> 
			<select name="last_month">
				<option value="">MM</option>
				<?php
				 for($i = 1; $i <= 12; $i++) 
				 { ?>
					<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
						<?=$i; ?></option>
				<?php } ?> 
			</select>
			<select name="last_year">
				<option value="">YYYY</option>
				<?php
					$yr = date('Y');
				 for($i = 2013; $i <= $yr; $i++) 
				 { ?>
					<option <?php if($year == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
						<?=$i; ?></option>
				<?php } ?> 
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="req_for_leader" value="Request" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>

<?php
if($_REQUEST['req_for_leader'] != ''  or 
	$_REQUEST['start_year'] != '' or
	$_REQUEST['start_month'] != '' or
	$_REQUEST['start_day'] != '' or
	$_REQUEST['last_year'] != '' or
	$_REQUEST['last_month'] != ''  or
	$_REQUEST['last_day'] != '')
{	print "<p></p>";
	if($_REQUEST['start_year'] == '')
	{
		$_REQUEST['start_year'] = '1930' ;
	}
	if($_REQUEST['start_month'] == '')
	{
		$_REQUEST['start_month'] = '01' ;
	}
	if($_REQUEST['start_day'] == '')
	{
		$_REQUEST['start_day'] = '01';
	}
	if($_REQUEST['last_year'] == '')
	{
		$_REQUEST['last_year'] = date("Y");
	}
	if($_REQUEST['last_month'] == '')
	{
		$_REQUEST['last_month'] = date("m");
	}
	if($_REQUEST['last_day'] == '')
	{
		$_REQUEST['last_day'] = date("d");
	}
	$start_date = $_REQUEST['start_year']."/".$_REQUEST['start_month']."/".$_REQUEST['start_day'];
	$last_date = $_REQUEST['last_year']."/".$_REQUEST['last_month']."/".$_REQUEST['last_day'];
	$query = "select (select tab1.username from users as tab1 where tab1.id_user = tab2.real_parent) as user, count(tab2.real_parent) as num,
	 (select tab1.email from users as tab1 where tab1.id_user = tab2.real_parent) as email,
	 (select tab1.phone_no  from users as tab1 where tab1.id_user = tab2.real_parent) as phone_no 
 from users as tab2 where date between '$start_date' and '$last_date'  and tab2.real_parent > 0  group by tab2.real_parent order by num desc limit 10";
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{ ?>
		<table class="table table-bordered"> 
			<thead>
				<tr>
					<th class="text-center">S.No.</th>
					<th class="text-center">Top Leader Username</th>
					<th class="text-center">Email</th>
					<th class="text-center">Mobile</th> 
					<th class="text-center">Member</th> 
				</tr>
			</thead>
			<tbody>
	<?php	
		$i = 1;
		while($row = mysqli_fetch_array($result))
		{	
			echo "
				<tr>
					<td>".$i."</td>
					<td>".$row['user']."</td>
					<td>".$row['email']."</td>
					<td>".$row['phone_no']."</td>
					<td>".$row['num']."</td>
				<tr>";
			$i++;
		}
		print "<tbody>";
		print "</table>";
	}
	else
	{
		echo "<B style=\"color:#FF0000; font-size:12pt;\">There is no Leader in Board</B>";
	}
}

?>
</div>			