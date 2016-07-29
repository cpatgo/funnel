<?php
ini_set("display_errors",'off');
session_start();
//
include("condition.php");
include("../function/setting.php");
?>
<div class="ibox-content">
<?php

if(isset($_POST['submit']))
{ 
	$join_by = $_POST['join_by'];
	$date = $_POST['date'];
	$date = date('Y-m-d',strtotime($date));
	
	if($join_by == 0)
		$u_type = 'F';
	elseif($join_by == 1)
		$u_type = 'B';	
	elseif($join_by == 2)
		$board = 'board_break';	
	elseif($join_by == 3)
		$board = 'board_break_second';	
	elseif($join_by == 4)
		$board = 'board_break_third';	
	elseif($join_by == 5)
		$board = 'board_break_fourth';	
	elseif($join_by == 6)
		$board = 'board_break_fifth';							
	
		if($join_by > 1)
			$sql_search = "SELECT * FROM users as t1 inner join $board as t2 on t1.id_user = t2.user_id and t1.date = '$date'  ";
		else
			$sql_search = "SELECT * FROM users WHERE date = '$date' and type = '$u_type' ";
		
		$id_query = mysqli_query($GLOBALS["___mysqli_ston"], $sql_search);
		$num = mysqli_num_rows($id_query);
		if($num != 0)
		{?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th colspan=2>Total Members</th>
				<th colspan=3><?=$num;?> Members</th>
			</tr>
			<tr>
				<th>Date</th>
				<th>Username</th>
				<th>Name</th>
				<th>Phone No</th>
				<th>Email</th>
			</tr>
			</thead>
			<tbody>
			<?php
			while($row = mysqli_fetch_array($id_query))
			{
				$date = $row['date'];
				$username = $row['username'];
				$name = $row['f_name']." ".$row['l_name']; 
				$phone_no = $row['phone_no']; 
				$email = $row['email']; 	
			?>	
				<tr>
					<td><?=$date;?></td>
					<td><?=$username;?></td>
					<td><?=$name;?></td>
					<td><?=$phone_no;?></td>
					<td><?=$email;?></td>
				</tr>
			<?php
			$j = 1;
			}
			print"</tbody></table>";
		}		
		else{ print "<B style=\"color:#ff0000; font-size:12pt;\">There is No information to show !</B>"; }
}
else
{ ?>
<form name="myform" action="index.php?page=joining_report" method="post">
<table class="table table-bordered">
	<tr>
		<th>Filter :</th>
		<td>
	 		<select name="join_by" required>
				<option value="">Select</option>
				<option value="0">Free</option>
				<option value="1">Paid</option>
				<option value="2">Stage 1</option>
				<option value="3">Stage 2</option>
				<option value="4">Stage 3</option>
				<option value="5">Stage 4</option>
				<option value="6">Stage 5</option>
			</select>
			
		</td>
		<td>
			<div class="form-group" id="data_1" style="margin:0px">
				<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" value="<?=date('d-m-Y')?>">
				</div>
			</div>
		</td>
		<td class="text-center">
			<input type="submit" name="submit" value="submit" class="btn btn-primary"  />
		</td>
 	</tr>
</table>
</form>

<table class="table table-bordered">
	<tr>
		<th>Sr.</th>
		<th>Username</th>
		<th>Name</th>
		<th>Phone No.</th>
		<th>Email</th>
		<th>Date</th>
	</tr>
<?php  
	$sql1 = "select * from users ";
	$query1 = mysqli_query($GLOBALS["___mysqli_ston"], $sql1);
	//$num = mysql_num_rows($query_users);
	$sr = 1;
	while($rowa = mysqli_fetch_array($query1))
	{
		print "	<tr>
					<td>$sr</td>
					<td>".$rowa['username']."</td>
					<td>".$rowa['f_name'].' '.$rowa['l_name']."</td>
					<td>".$rowa['phone_no']."</td>
					<td>".$rowa['email']."</td>
					<td>".$rowa['date']."</td>
				</tr>";
		$sr++;
	}
print "</table>";
} ?>
</div>

<script> $('#data_1 .input-group.date').datepicker({
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