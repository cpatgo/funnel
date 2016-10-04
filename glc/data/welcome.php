<?php
session_start();
include("condition.php");
require_once("config.php");
include("function/functions.php");
include("function/setting.php");

$id = $_SESSION['dennisn_user_id'];
$level = get_level($id);
if($level == 1) { $lvl = "Premium Board"; } 
else { $lvl = "Standard Board"; }
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
while($row = mysqli_fetch_array($query))
{
	$name = $row['f_name']." ".$row['l_name'];
	$date = $row['date'];
	$db_time = $row['time'];
	$time = date('h:i A' ,  $db_time );
	$username = $row['username'];
	$password = $row['password'];
	$real_parent = get_user_name($row['real_parent']);
	$dob = $row['dob'];
	$address = $row['address'];
	$email = $row['email'];
	$birthDate = $dob;
	$birthDate = explode("-", $birthDate);
	$birthDate = $birthDate[2].'-'.$birthDate[1];
	
	$date = date('d-m-Y', strtotime($date));
	$dob = date('d-m-Y', strtotime($dob));
} 
print $_SESSION['regtra'];
//unset($_SESSION['regtra']);
//get user messages
function display_msq($id, $db_time)
{
	$membership_class = getInstance('Class_Membership');
	$missed = get_missed_commission($id);
	if($missed != "") {
		return '<div class="alert alert-danger">You have completed a Pay Cycle at '.date("d/m/Y H:i:s", $missed).', but unfortunately you missed out on earning your Affiliate commission because you are <b>not qualified</b>.  To earn commissions in our Affiliate Rewards Program, you must qualify by enrolling a <b>minimum of 2 Members</b> who purchase any one of our VIP product packages. <a href="index.php?page=faq">Click here for more details</a> </div>';
	}	
	$qreferrals = get_paid_member($id);
	if($qreferrals < 2) {
		return '<div class="alert alert-danger">Please note, in order to earn commissions in our Affiliate Rewards Program, you must qualify by enrolling a <b>minimum of any 2 Paid Membership</b>. For more details, please review the GLC Rewards Pay Plan <a href="/wp-content/uploads/2016/06/GLC-partner-rewards-payplan-06-03-16-.pdf"target="_blank">here</a>.
			<script type="text/javascript">
				function AlertIt() {
					alert("Do I have to sell subscriptions to earn?\n\nThe answer is “YES”. Our Independent Affiliate Rewards Program is a sales commission program and is based on you working with your enroller and other team members to earn a commission. Our subscription is a one-time sale and the company is only able to make money and provide you with commissions, if there are new sales. Each person signing up as an affiliate is required to sell 2 subscriptions. To continually earn commissions, you will need to sell at least 1 subscription every 4 months. Be sure to review the GLC Affiliate terms to make sure you understand how Our Affiliate Rewards Program works.\n\n");
				}
			</script></div>';
	}
	$qreferrals2 = $membership_class->is_qualified($id, $db_time, true);
	if(!$qreferrals2) {
		return '<div class="alert alert-danger">Unfortunately, you have not enrolled the 2 Members <b>within the last 6 months</b> and can no longer earn Affiliate commissions. To earn commissions in our Affiliate Rewards Program, you must qualify by enrolling a <strong>minimum of 2 Paid Memberships</strong> who purchased any one of our VIP product packages. <a href="index.php?page=faq">Click here for more details</a> </div>';
	}	
}
?>

<div class="row">
	<div class="col-lg-4">
		<div class="ibox float-e-margins">
			<?php
			/*	$sql = "SELECT * FROM message WHERE receive_id = '$id' limit 3 order by id desc";
				$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				$num = mysqli_num_rows($query);
				if($num == 0){ $num = 0;} */
		  $num = 0;
			?>
			<div class="ibox-title">
				<h5><?=$sms_info;?></h5>
				<div class="ibox-tools">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="ibox-content ibox-heading">
				<h3><i class="fa fa-envelope-o"></i> Important Messages</h3>
			</div>
			<div class="ibox-content">
				<?php echo display_msq($id, $db_time); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Commissions per Stage - Year to Date</h5>
				<div class="ibox-tools">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="ibox-content table-responsive">
				<table class="table table-bordered">
					<thead>
					<tr>
						<th>Pay Level</th>
						<th>Stage 1</th>
						<th>Stage 2</th>
						<th>Stage 3</th>
						<th>Stage 4</th>
						<th>Stage 5</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th>Total Count</th>
						<td><?php echo total_num_commissions_per_level($id, 1); ?></td>
						<td><?php echo total_num_commissions_per_level($id, 2); ?></td>
						<td><?php echo total_num_commissions_per_level($id, 3); ?></td>
						<td><?php echo total_num_commissions_per_level($id, 4); ?></td>
						<td><?php echo total_num_commissions_per_level($id, 5); ?></td>
					</tr>
					<tr>
						<th>Total Amount</th>
						<td><?php echo "$".number_format(total_sum_commissions_per_level($id, 1),2); ?></td>
						<td><?php echo "$".number_format(total_sum_commissions_per_level($id, 2),2); ?></td>
						<td><?php echo "$".number_format(total_sum_commissions_per_level($id, 3),2); ?></td>
						<td><?php echo "$".number_format(total_sum_commissions_per_level($id, 4),2); ?></td>
						<td><?php echo "$".number_format(total_sum_commissions_per_level($id, 5),2); ?></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<!--<p align="center" style="color:#A90000;"><B><em><?=$congra;?>!!!</em></B></p>-->
<?php 
$cur_date =date("m-d"); 
if($cur_date == $birthDate) 
{
?>
<div style="border:solid 1px #000000; width:100%; height:100px;"><span style="font-size:24pt; text-align:center; font-family:'Times New Roman', Times, serif; color:#FF0000; font-weight:bold; font-style:italic;">Happy Birthday To You  <?php print "<span style=\"color:#000;\">$username"; ?></span>
<img src="images/happy1.jpeg" align="right" /><div align="center" style="font-size:24px; color:#000000;"><br />
<?=$date="On Your ".(date("Y-m-d") - $dob)."Years Old ";?></div></div>
 <?php } ?>
 
<!--<div class="ibox-content">
	<table class="table table-bordered">
		<tr>
			<td><?=$content;?></td>
		</tr>
	</table>
</div>
<div class="ibox-content">	
	<table class="table table-bordered">
		<thead><tr><th colspan="2"><?=$user_info;?></th></tr></thead>
		<tbody>
			<tr><td><?=$appli_user;?>:</td>		<td width="50%"><?=$username; ?></td></tr>
			<tr><td><?=$spon_info;?>:</td>		<td><?=$real_parent; ?></td></tr>
			<tr><td><?=$date_o_b;?>:</td>		<td><?=$dob; ?></td></tr>
			<tr><td><?=$do_join;?>:</td>		<td><?=$date; ?></td></tr>
			<tr><td><?=$to_join;?>:</td>		<td><?=$time; ?></td></tr>
			<tr><td><?=$names;?>:</td>			<td><?=$name; ?></td></tr>
			<tr><td><?=$add_ress;?>:</td>		<td><?=$address; ?></td></tr>
			<tr><td><?=$e_mail; ?>:</td>		<td><?=$email; ?></td></tr>
			<tr><td><?=$amt_paid;?>:</td>		<td><?=$xvfx; ?>XXXXXXXX</td></tr>
		</tbody>
	</table>
</div>-->
<div class="ibox-title">
	<h5><?=$board_status;?></h5>
	<!-- <div class="ibox-tools">
		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
		<a class="close-link"><i class="fa fa-times"></i></a>
	</div> -->
</div>
<div class="ibox-content table-responsive">
	<table class="table table-bordered">
		<thead>
		<tr>
			<th><?=$board;?></th>
			<th class="text-center"><?=$seed_board;?></th>
			<th class="text-center"><?=$sprt_bord;?></th>
			<th class="text-center"><?=$splng_bord;?></th>
			<th class="text-center"><?=$oak_bord;?></th>
			<th class="text-center"><?=$mat_oak_bord;?></th>
			<!--<th class="text-center"><?=$harv_bord;?></th>-->			  			  			  			
		</tr>
		</thead>
		<tbody>	
		<tr>
			  <td><B>My Current Board</B></td>
			  <td class="text-center">
			  	<?php
				$b_b_id = get_board_break_id($id , "board_break");
				$c = count($b_b_id);
				for($g = 0; $g < $c; $g++)
				{
				  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE board_id = '$b_b_id[$g]' AND mode = 1");	
				  while($row = mysqli_fetch_array($query))
    			  {
					$board_id_c_1 = $row['board_id'];
					
					$bd_name = "first_board_name";
					$bord_name_c_1 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_first&bbid=$board_id_c_1\" >View Now</a></small>
					<br />";
				  }
				}	
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				$b_b_id_2 = get_board_break_id($id , "board_break_second");
				$m = count($b_b_id_2);
				for($g = 0; $g < $m; $g++)
				{
				  $query_2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE board_id = '$b_b_id_2[$g]' AND mode = 1");	
				  while($rows = mysqli_fetch_array($query_2))
    			  {
					$board_id_c_2 = $rows['board_id'];
					
					$bd_name = "second_board_name";
					$bord_name_c_2 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_second&bbid=$board_id_c_2\" >View Now</a></small>
					<br />";
				  }
				}	
				?>
			  </td>
			  <td class="text-center">
			  		<?php
				$b_b_id_3 = get_board_break_id($id , "board_break_third");
				$n = count($b_b_id_3);
				for($g = 0; $g < $n; $g++)
				{
				  $query_3 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE board_id = '$b_b_id_3[$g]' AND mode = 1");	
				  while($rowa = mysqli_fetch_array($query_3))
    			  {
					$board_id_c_3 = $rowa['board_id'];
					
					$bd_name = "third_board_name";
					$bord_name_c_3 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_third&bbid=$board_id_c_3\" >View Now</a></small>
					<br />";
				  }
				}	
				?>
			  </td>
			   <td class="text-center">
			  	<?php
				$b_b_id_4 = get_board_break_id($id , "board_break_fourth");
				$x = count($b_b_id_4);
				for($g = 0; $g < $x; $g++)
				{
				  $query_4 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fourth WHERE board_id = '$b_b_id_4[$g]' AND mode = 1");	
				  while($ro = mysqli_fetch_array($query_4))
    			  {
					$board_id_c_4 = $ro['board_id'];
					
					$bd_name = "fourth_board_name";
					$bord_name_c_4 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_fourth&bbid=$board_id_c_4\" >View Now</a></small>
					<br />";
				  }
				}	
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				$b_b_id_5 = get_board_break_id($id , "board_break_fifth");
				$y = count($b_b_id_5);
				for($g = 0; $g < $y; $g++)
				{
				  $query_5 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fifth WHERE board_id = '$b_b_id_5[$g]' AND mode = 1");	
				  while($rowaa = mysqli_fetch_array($query_5))
    			  {
					$board_id_c_5 = $rowaa['board_id'];
					
					$bd_name = "five_board_name";
					$bord_name_c_5 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_fifth&bbid=$board_id_c_5\" >View Now</a></small>
					<br />";
				  }
				}	
				?>
			  </td>
			  <!--<td class="text-center">
			  	<?php
				/*$b_b_id_6 = get_board_break_id_6($id);
				$z = count($b_b_id_6);
				for($g = 0; $g < $z; $g++)
				{
				  $query_6 = mysql_query("SELECT * FROM board_sixth WHERE board_id = '$b_b_id_6[$g]' AND mode = 1");	
				  while($rowss = mysql_fetch_array($query_6))
    			  {
					$board_id_c_6 = $rowss['board_id'];
					
					$bd_name = "six_board_name";
					$bord_name_c_6 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_sixth&bbid=$board_id_c_6\" >$bord_name_c_6$board_id_c_6</a></small>
					<br />";
				  }
				}	*/
				?>
			  </td>-->
			</tr>
		<tr>
			  <td><B>No. of Cycles</B></td>
			  <td class="text-center">
			  	<?php
				  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE pos1 = '$id' AND mode = 0");	
				  print $num_of_cycle = mysqli_num_rows($query);
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				  $query_2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE pos1 = '$id' AND mode = 0");	
				   print $num_of_cycle_2 = mysqli_num_rows($query_2);
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				  $query_3 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE pos1 = '$id'  AND mode = 0");	
				  print $num_of_cycle_3 = mysqli_num_rows($query_3);	
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				  $query_4 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fourth WHERE pos1 = '$id'  AND mode = 0");	
				  print $num_of_cycle_4 = mysqli_num_rows($query_4);	
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				  $query_5 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fifth WHERE pos1 = '$id' AND mode = 0");	
				  print $num_of_cycle_5 = mysqli_num_rows($query_5);		
				?>
			  </td>
			</tr>			
		</table>
</div>
<?php
function get_board_break_id($id, $board_title)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from $board_title where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}

function my_bords_name($nme)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select $nme from setting");
	while($r = mysqli_fetch_array($q))
	{
		$board_name = $r[$nme];
	}
	return $board_name;
}

function users_username($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
	}
	return $username;
}
function total_num_commissions_per_level($id, $level)
{	
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select count(amount) as total_count from income where user_id = '$id' and amount > 0 and level = '$level'");
	$row = mysqli_fetch_array($q);
	$count = $row[0];
	if($count > 0)
	{
		return $count;
	}
	else
	{
		return 0;
	}
}
function total_sum_commissions_per_level($id, $level)
{	
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select sum(amount) as total_amount from income where user_id = '$id' and amount > 0 and level = '$level'");
	$row = mysqli_fetch_array($q);
	$amount = $row[0];
	if($amount > 0)
	{
		return $amount;
	}
	else
	{
		return 0;
	}
}
?>