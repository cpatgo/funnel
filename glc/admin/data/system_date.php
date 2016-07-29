<?php
session_start();
include("condition.php");

if(isset($_POST['submit']))
{
	$system_date = $_REQUEST['system_date'];
	$system_date = date('Y-m-d', strtotime($system_date));
	if($system_date == '')
	{
		print "Please Enter System Date !!";
	}
	else
	{
		mysqli_query($GLOBALS["___mysqli_ston"], "update system_date set sys_date = '$system_date' where id = 1 ");
		print "System Date Changed Successfully !!";
	}	
}

else
{ 
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from system_date where id = 1 ");
	while($row = mysqli_fetch_array($q))
	{
		$current_d = $row['sys_date'];
		$current_d = date('d-m-Y', strtotime($current_d));
	}
?>
<div class="ibox-content">
<form name="my_form" action="index.php?page=system_date" method="post" >
<table class="table table-bordered">
	<thead><tr><th colspan="2">System Date</th></tr></thead>
	<tr>
		<th>System Date</th>
		<th><?=$current_d; ?></th>
	</tr>
	<tr>
		<th>Change System Date</th>
		<td>
			<div class="form-group" id="data_1" style="margin:0px">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="system_date" />
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>
<?php 
}  
?>

<!-- Data picker -->

<script> 
$('#data_1 .input-group.date').datepicker({
	todayBtn: "linked",
	keyboardNavigation: false,
	forceParse: false,
	calendarWeeks: true,
	autoclose: true
});

$('#data_2 .input-group.date').datepicker({
	startView: 1,
	todayBtn: "linked",
	keyboardNavigation: false,
	forceParse: false,
	autoclose: true
});

$('#data_3 .input-group.date').datepicker({
	startView: 2,
	todayBtn: "linked",
	keyboardNavigation: false,
	forceParse: false,
	autoclose: true
});

$('#data_4 .input-group.date').datepicker({
	minViewMode: 1,
	keyboardNavigation: false,
	forceParse: false,
	autoclose: true,
	todayHighlight: true
});

$('#data_5 .input-daterange').datepicker({
	keyboardNavigation: false,
	forceParse: false,
	autoclose: true
});
</script>	

