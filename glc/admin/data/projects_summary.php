<?php
include("condition.php");
include("../function/setting.php");

$all_user = get_all_users();
$total_used_pin = total_used_pin();
$total_unused_pin = get_total_unused_pin();
//$get_total_board_vouchers = get_total_board_vouchers();
//$get_total_board_vouchers_pending = get_total_board_vouchers_pending();
//$get_total_board_vouchers_transfer = get_total_board_vouchers_transfer();

//monthly
$total_month_used_pin = total_month_used_pin();
$total_month_unused_pin = total_month_unused_pin();

$current_month_joining = get_all_current_month_joining();
//$get_month_total_board_vouchers = get_month_total_board_vouchers();
//$get_total_month_board_vouchers_pending = get_total_month_board_vouchers_pending();
//$get_total_month_board_vouchers_transfer = get_total_month_board_vouchers_transfer();
?>
<?php
//Number of members added in the last 7 days by day
function MembersPerDay($i,$membership){
	$d = date("Y-m-d", strtotime('-'. $i .' days'));

	$start_datetime = strtotime(sprintf('%s 00:00:00', $d));
	$end_datetime = strtotime(sprintf('%s 23:59:00', $d));

	$s_sql = sprintf("SELECT count(*)
					FROM user_membership um 
					INNER JOIN users u 
					ON um.user_id = u.id_user
					INNER JOIN memberships m 
					ON um.initial = m.id
					WHERE u.time BETWEEN '%s' AND '%s'
					AND um.initial = %d
			", $start_datetime, $end_datetime, $membership);

	$s =  mysqli_query($GLOBALS["___mysqli_ston"], $s_sql);
	$row = mysqli_fetch_array($s);
	return $row[0];
}
function PaymentsPerDay($i,$membership){
	$d = date("Y-m-d", strtotime('-'. $i .' days'));

	$start_datetime = strtotime(sprintf('%s 00:00:00', $d));
	$end_datetime = strtotime(sprintf('%s 23:59:00', $d));

	$s_sql = sprintf("SELECT sum(m.amount)
					FROM user_membership um 
					INNER JOIN users u 
					ON um.user_id = u.id_user
					INNER JOIN memberships m 
					ON um.initial = m.id
					WHERE u.time BETWEEN '%s' AND '%s'
					AND um.initial = %d
			", $start_datetime, $end_datetime, $membership);

	$s =  mysqli_query($GLOBALS["___mysqli_ston"], $s_sql);
	$row = mysqli_fetch_array($s);
	return $row[0];
}
$pp_query =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id_user) FROM temp_users");
$pp_row = mysqli_fetch_array($pp_query);
$pending_payments = $pp_row[0];

$pd_query =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(image_id) FROM documents WHERE approved = 0");
$pd_row = mysqli_fetch_array($pd_query);
$pending_docs = $pd_row[0];

$pc_query =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id) FROM income WHERE approved = 0");
if($pc_query){
$pc_row 	= mysqli_fetch_array($pc_query);
$pending_commisions = $pc_row[0];
} else { $pending_commisions = 0; }

$pr_query =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id) FROM paid_unpaid WHERE paid = 0");
$pr_row 	= mysqli_fetch_array($pr_query);
$pending_requests = $pr_row[0];

$mu_query =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id) FROM membership_upgrade WHERE status = 0");
$mu_row 	= mysqli_fetch_array($mu_query);
$pending_upgrade = $mu_row[0];
?>
<div class="row">
            <div class="col-lg-3">
				<div class="widget style1 <?php echo ($pending_payments > 0)?"red":"lazur"; ?>-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-bank fa-5x"></i>
                        </div>
                        <div class="col-xs-4 text-right">
                            <span> Pending Payments </span>
                            <h2 class="font-bold"><?php echo ($pending_payments > 0)?"<a href='index.php?page=pending_members' class='text-white'>".$pending_payments."</a>":"0"; ?></h2>
                        </div>
                        <div class="col-xs-4 text-right">
                            <span> Pending Upgrades </span>
                            <h2 class="font-bold"><?php echo ($pending_upgrade > 0)?"<a href='index.php?page=pending_upgrade' class='text-white'>".$pending_upgrade."</a>":"0"; ?></h2>
                        </div>
                    </div>
                </div>
                <!--div class="widget style1">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <i class="fa fa-calendar fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> Today </span>
                                <h2 class="font-bold"><?php echo date("m/d/Y", time()); ?></h2>
                            </div>
                        </div>
                </div-->
            </div>
            <div class="col-lg-3">
                <div class="widget style1 <?php echo ($pending_docs > 0)?"red":"navy"; ?>-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-file-image-o fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Pending Documents </span>
                            <h2 class="font-bold"><?php echo ($pending_docs > 0)?"<a href='index.php?page=documents' class='text-white'>".$pending_docs."</a>":"0"; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="widget style1 <?php echo ($pending_commisions > 0)?"red":"lazur"; ?>-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-exchange fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Pending Commisions </span>
                            <h2 class="font-bold"><?php echo ($pending_commisions > 0)?"<a href='index.php?page=commissions' class='text-white'>".$pending_commisions."</a>":"0"; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="widget style1 <?php echo ($pending_requests > 0)?"red":"yellow"; ?>-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Pending Requests </span>
                            <h2 class="font-bold"><?php echo ($pending_requests > 0)?"<a href='index.php?page=withdrawal_balance_request' class='text-white'>".$pending_requests."</a>":"0"; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Number of members added in the last 7 days by day</h5>
			</div>
			<div class="ibox-content">
				<table class="table table-striped table-bordered dataTablesNewMembers">
					<thead>
					<tr>
						<th></th>
						<th colspan="4">COUNT</th>
						<th colspan="4">PAYMENTS RECEIVED</th>
					</tr>
					<tr>
						<th>Date</th>
						<th>Free</th>
						<th>Executive</th>
						<th>Leadership</th>
						<th>Professional</th>
						<th>Masters</th>
						<th>Total</th>
						<th>Free</th>
						<th>Executive</th>
						<th>Leadership</th>
						<th>Professional</th>
						<th>Masters</th>
						<th>Total</th>
					</tr>
					</thead>
					<tbody>
					<?php
					for($i = 0; $i < 7; $i++) 
					{
						$mpd_total = $ppd_total = 0;
					  ?>
					  <tr>
						<td><?php echo "<a target='_blank' href='index.php?page=payments&date=".date("Y-m-d", strtotime('-'. $i .' days'))."'>".date("m/d/Y", strtotime('-'. $i .' days'))."</a>"; ?></td>
						<td><?php echo $mpd1 = MembersPerDay($i,1); $mpd_total+=$mpd1; ?></td>
						<td><?php echo $mpd2 = MembersPerDay($i,2); $mpd_total+=$mpd2; ?></td>
						<td><?php echo $mpd3 = MembersPerDay($i,3); $mpd_total+=$mpd3;?></td>
						<td><?php echo $mpd4 = MembersPerDay($i,4); $mpd_total+=$mpd4;?></td>
						<td><?php echo $mpd5 = MembersPerDay($i,5); $mpd_total+=$mpd5;?></td>
						<td><strong><?php echo $mpd_total; ?></strong></td>
						<td><?php $ppd1 = PaymentsPerDay($i,1); echo number_format($ppd1, 2); $ppd_total+=$ppd1;?></td>
						<td><?php $ppd2 = PaymentsPerDay($i,2); echo number_format($ppd2, 2); $ppd_total+=$ppd2;?></td>
						<td><?php $ppd3 = PaymentsPerDay($i,3); echo number_format($ppd3, 2); $ppd_total+=$ppd3;?></td>
						<td><?php $ppd4 = PaymentsPerDay($i,4); echo number_format($ppd4, 2); $ppd_total+=$ppd4;?></td>
						<td><?php $ppd5 = PaymentsPerDay($i,5); echo number_format($ppd5, 2); $ppd_total+=$ppd5;?></td>
						<td><strong><?php echo number_format($ppd_total, 2);?></strong></td>
					  </tr>
					  <?php
					  
					}
					?>
					
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th>Free</th>
							<th>Executive</th>
							<th>Leadership</th>
							<th>Professional</th>
							<th>Masters</th>
							<th>Total</th>
							<th>Free</th>
							<th>Executive</th>
							<th>Leadership</th>
							<th>Professional</th>
							<th>Masters</th>
							<th>Total</th>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>
</div>
<?php
//Completed Boards each Day
function CompletedBoards($i, $board) {
	$d = date("Y-m-d", strtotime('-'. $i .' days'));
	$s =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(*) FROM ".$board." WHERE DATE(FROM_UNIXTIME(`time`)) = '".$d."' and mode = 0");
	$row = mysqli_fetch_array($s);
	return $row[0];
}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Completed Boards each Day</h5>
			</div>
			<div class="ibox-content">
				<table class="table table-striped table-bordered dataTablesCompletedBoards">
					<thead>
					<tr>
						<th></th>
						<th colspan="5">COMPLETED BOARDS</th>
					</tr>
					<tr>
						<th>Date</th>
						<th>Stage 1</th>
						<th>Stage 2</th>
						<th>Stage 3</th>
						<th>Stage 4</th>
						<th>Stage 5</th>
					</tr>
					</thead>
					<tbody>
					<?php
					for($i = 0; $i < 7; $i++) 
					{
					  ?>
					  <tr>
						<td><?php echo date("m/d/Y", strtotime('-'. $i .' days')); ?></td>
						<td><?php echo CompletedBoards($i, "board"); ?></td>
						<td><?php echo CompletedBoards($i, "board_second"); ?></td>
						<td><?php echo CompletedBoards($i, "board_third"); ?></td>
						<td><?php echo CompletedBoards($i, "board_fourth"); ?></td>
						<td><?php echo CompletedBoards($i, "board_fifth"); ?></td>
					  </tr>
					  <?php  
					}
					?>
					
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th>Stage 1</th>
							<th>Stage 2</th>
							<th>Stage 3</th>
							<th>Stage 4</th>
							<th>Stage 5</th>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>
</div>   
<?php
//Total Unique Members
function TotalUniqueMembers($i) {
	$d = date("Y-m-d", strtotime('-'. $i .' days'));
	$s =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(*) FROM `users` where date <= '".$d."'");
	$row = mysqli_fetch_array($s);
	return $row[0];
}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Total Unique Members</h5>
			</div>
			<div class="ibox-content">
				<table class="table table-striped table-bordered dataTablesTotalUniqueMembers">
					<thead>
						<tr>
							<th>Date</th>
							<th>Total Unique Members</th>
						</tr>
					</thead>
					<tbody>
					<?php
					for($i = 0; $i < 7; $i++) 
					{
					  ?>
					  <tr>
						<td><?php echo date("m/d/Y", strtotime('-'. $i .' days')); ?></td>
						<td><?php echo TotalUniqueMembers($i); ?></td>
					  </tr>
					  <?php  
					}
					?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?php echo TotalUniqueMembers(0); ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>            
<div class="ibox-content">
<table class="table table-bordered">
	<thead><tr><th class="text-center">Total</th><th class="text-center">This Month</th></tr></thead>
	<tbody>
	<tr>
		<td>
		<table class="table table-bordered">
			<tr>
				<th>Members</th>
				<th><?=$all_user;?></th>
			</tr>
			<tr>
				<th>Used Pins</th>
				<th><?=$total_used_pin;?></th>
			</tr>
			<tr>
				<th>UnUsed Pins</th>
				<th><?=$total_unused_pin;?></th>
			</tr>
			<tr>
				<th>Vouchers In Account</th>
				<th><?=$get_total_board_vouchers;?></th>
			</tr>
			<tr>
				<th>Vouchers Pending In Account</th>
				<th><?=$get_total_board_vouchers_pending;?></th>
			</tr>
			<tr>
				<th>Vouchers Transfered In Account</th>
				<th><?=$get_total_board_vouchers_transfer;?></th>
			</tr>
		</table>
		</td>
		<td>
		<table class="table table-bordered">
			<tr>
				<th>Member Joings</th>
				<th><?=$current_month_joining;?></th>
			</tr>
			<tr>
				<th>Used Pins</th>
				<th><?=$total_month_used_pin;?></th>
			</tr>
			<tr>
				<th>UnUsed Pins</th>
				<th><?=$total_month_unused_pin;?></th>
			</tr>
			<tr>
				<th>Vouchers In Account</th>
				<th><?=$get_month_total_board_vouchers;?></th>
			</tr>
			<tr>
				<th>Vouchers Pending In Account</th>
				<th><?=$get_total_month_board_vouchers_pending;?></th>
			</tr>
			<tr>
				<th>Vouchers Transfered In Account</th>
				<th><?=$get_total_month_board_vouchers_transfer;?></th>
			</tr>
		</table>
		</td>
		</tr>
	</tbody>
</table>
</div>
<?php
// total 

function get_all_users()
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users ");
	$all_user = mysqli_num_rows($query);
	return $all_user;
}

function total_used_pin()
{
$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 0 ");
$total_pin = mysqli_num_rows($quer);	
	return $total_pin;
}

function get_total_unused_pin()
{
$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 1 ");
$total_pin = mysqli_num_rows($quer);	
	return $total_pin;
}

/*function get_total_board_vouchers()
{
$quer = mysql_query("select * from board_voucher ");
$total_bpin = mysql_num_rows($quer);	
	return $total_bpin;
}

function get_total_board_vouchers_pending()
{
$quer = mysql_query("select * from board_voucher where user_id = 0 ");
$total_bpin = mysql_num_rows($quer);	
	return $total_bpin;
}

function get_total_board_vouchers_transfer()
{
	$quer = mysql_query("select * from board_voucher where user_id != 0 ");
	$total_bpin = mysql_num_rows($quer);	
	return $total_bpin;
}*/


//monthly

function get_all_current_month_joining()
{
	$joining = 0;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users ");
	while($row = mysqli_fetch_array($query))
	{
		$curr_date = date('Y-m');
		$d = $row['date'];
		$dat = explode('-' ,$d);
		$db_date = $dat[0]."-".$dat[1];
		if($db_date == $curr_date)
		{
			$joining++;
		}
	}
	return $joining;		
}

function total_month_used_pin()
{
	$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 0 ");
	$total_pin = 0;	
	while($row = mysqli_fetch_array($quer))
	{
		$curr_date = date('Y-m');
		$d = $row['used_date'];
		$dat = explode('-' ,$d);
		$db_date = $dat[0]."-".$dat[1];
		if($db_date == $curr_date)
		{
			$total_pin++;
		}	
	}
	return $total_pin;
}

function total_month_unused_pin()
{
	$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 1 ");
	$total_pin = 0;	
	while($row = mysqli_fetch_array($quer))
	{
		$curr_date = date('Y-m');
		$d = $row['date'];
		$dat = explode('-' ,$d);
		$db_date = $dat[0]."-".$dat[1];
		if($db_date == $curr_date)
		{
			$total_pin++;
		}	
	}
	return $total_pin;
}

/*function get_month_total_board_vouchers()
{
	$quer = mysql_query("select * from board_voucher ");
	$total_bpin = 0;
	while($row = mysql_fetch_array($quer))
	{
		$curr_date = date('Y-m');
		$d = $row['date'];
		$dat = split('-' ,$d);
		$db_date = $dat[0]."-".$dat[1];
		if($db_date == $curr_date)
		{
			$total_bpin++;
		}	
	}
	return $total_bpin;	
}

function get_total_month_board_vouchers_pending()
{
	$quer = mysql_query("select * from board_voucher where user_id = 0 ");
	$total_bpin = 0;
	while($row = mysql_fetch_array($quer))
	{
		$curr_date = date('Y-m');
		$d = $row['date'];
		$dat = split('-' ,$d);
		$db_date = $dat[0]."-".$dat[1];
		if($db_date == $curr_date)
		{
			$total_bpin++;
		}	
	}	
	return $total_bpin;
}

function get_total_month_board_vouchers_transfer()
{
	$quer = mysql_query("select * from board_voucher where user_id != 0 ");
	$total_bpin = 0;
	while($row = mysql_fetch_array($quer))
	{
		$curr_date = date('Y-m');
		$d = $row['issue_date'];
		$dat = split('-' ,$d);
		$db_date = $dat[0]."-".$dat[1];
		if($db_date == $curr_date)
		{
			$total_bpin++;
		}	
	}		
	return $total_bpin;
}*/

?>
