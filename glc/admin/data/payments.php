<?php
include("../function/functions.php");
foreach ($_POST as $key => $value) {
		filter_input(INPUT_POST, $key);
		$$key = $_POST[$key];
		$key = $value;
	}
	
	$sql = "SELECT id_user, username, real_parent as enroller, time, membership, p.amount, um.payment_type  
			FROM payments p
			LEFT JOIN users u ON p.user_id = u.id_user
			INNER JOIN user_membership um ON u.id_user = um.user_id
			INNER JOIN memberships m ON um.initial = m.id
			WHERE 1=1 ";
	
	//Filters
	$psd = DateTime::createFromFormat("m/d/Y", $period_start_date);
	$ped = DateTime::createFromFormat("m/d/Y", $period_end_date); 
	$period_start_date 	= date_format($psd, 'Y-m-d');
	$period_end_date 	= date_format($ped, 'Y-m-d'); 
	//$start_date_unix = date_format($date_timepicker_start_val, 'U'); //to timestamp
	if($period_start_date 	!= "" && $period_end_date	!= "") 		
	{ 
		$sql .=  " AND date BETWEEN '".$period_start_date."' AND '".$period_end_date."' ";
	} else {
		if($period_start_date 	!= "") 		{ $sql .=  " AND date >= '".$period_start_date."' ";  }
		if($period_end_date 	!= "") 		{ $sql .=  " AND date <= '".$period_end_date."' ";  }
	}
	if(isset($_GET['date'])) { $sql .=  " AND date = '".$_GET['date']."' ";  }
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$class_payment = getInstance('Class_Payment');
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Filters</h5>
			</div>
			<div class="ibox-content">
			<div class="row">
				<form method="post" role="form">	
					<div class="form-inline">					
						<div id="data_1" class="form-group">	<label>From</label>
							<div class="input-group date">
							
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" value="<?php echo ($period_start_date != "")?date("m/d/Y", strtotime($period_start_date)):""; ?>" class="form-control" name="period_start_date">
							</div>
						</div>
						<div id="data_1" class="form-group">	<label>To</label>
							<div class="input-group date">
							
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" value="<?php echo ($period_end_date != "")?date("m/d/Y", strtotime($period_end_date)):""; ?>" class="form-control" name="period_end_date">
							</div>
						</div>
						<input type="submit" value="Search" name="search" class="btn btn-primary">
					</div>
				</form>
			</div>
			</div>
		</div>
	</div>
</div>
<div class="ibox-content">	
<table class="table table-striped table-bordered table-hover dataTables">
	<thead>
		<tr>
			<th class="text-center">Trans. #</th>
			<th class="text-center">Username</th>
			<th class="text-center">Date</th>
			<th class="text-center">Membership Level</th>
			<th class="text-center">Payment Type</th>
			<th class="text-center">Amount</th>
		</tr>
	</thead>
<?php
	$payment_pool = array();
	while($row = mysqli_fetch_array($query))
	{
		if($row['payment_type'] == 'authorize_net' || $row['payment_type'] == 'authorize_net_2'):
			$invoiceid = '';
			$authorize = $class_payment->get_authorize_data($row['id_user'], $row['amount']);
			$invoiceid = (!empty($authorize)) ? $authorize[0]['orderid'] : 'NO INVOICE';
		else:
			$invoiceid = $row['time'];
		endif;

		$date = $row['date'];
		$time = date('H:i:s m-d-Y' , $row['time']);
		?>
		<tr class="text-center">
			<td><?php echo $row['id_user']."-".$invoiceid; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td><?php echo $time; ?></td>
			<td><?php echo $row['membership']; ?></td>
			<td><?php echo $row['payment_type']; ?></td>
			<td><?php echo "$".number_format($row['amount'],2); ?></td>
		</tr>
	<?php }?>
	<tfoot>
		<tr>
			<th colspan="5" class="text-right"></th>
			<th class="text-center"></th>
		</tr>
	</tfoot>
	</tbody>
	</table>
</div>
