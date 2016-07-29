<?php
include("condition.php");
include("../function/functions.php");
include("../function/daily_income.php");

if(isset($_POST['submit']))
{
	$turn_process = $_REQUEST['turn_process'];
	mysqli_query($GLOBALS["___mysqli_ston"], "update income_process set mode = '$turn_process' ");
}

$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income_process where id = 1 ");
while($r = mysqli_fetch_array($qu))
{
	$process_mode = $r['mode'];
}

if($process_mode == 1) 
{
	$system_turn = "On";
	$system_process ="Off";
	$turn_process = 0;
}
else
{
	$system_process ="On";
	$system_turn = "Off";
	$turn_process = 1;
}
?>
<div class="ibox-content">
<form name="pay_form" action="index.php?page=system_on_off" method="post">
<table class="table table-bordered">
	<input type="hidden" name="turn_process" value="<?=$turn_process; ?>" >
	<thead><tr><th colspan="2">System On/Off Panel</th></tr></thead>
	<tr><th colspan="2" class="text-center">System Is Currently <?=$system_process; ?></th></tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Turn <?=$system_turn; ?>" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>

