<?php
include("../function/functions.php");
$error = "";

$income_class = getInstance('Class_Income');

//deny
$deny = (isset($_GET["deny"]))?$_GET["deny"]:"";
if ($deny != ""):
	mysqli_query($GLOBALS["___mysqli_ston"], "update income set approved = 2  where id = '$deny' ");	
	mysqli_query($GLOBALS["___mysqli_ston"], "delete from income_reserve where income_id = '$deny' ");
endif;

//return commission
$return = (isset($_GET["return"]))?$_GET["return"]:"";
if (!empty($return)):
	$income = $income_class->get_income($return);
	if(!empty($income)):
		$income = $income[0];
		$income_class->return_commission($income);
		//Get reserve percentage
		$percentage = glc_option('reserve_percentage');
	    //Compute income less reserve
	    $reserve = (float)$income['other'] * ((float)$percentage / 100);
		$income_reserve = array(
	        'income_id' => $income['id'],
	        'income' => $income['other'],
	        'reserve' => $reserve,
	        'reserve_percentage' => $percentage,
	        'date_created' => date('Y-m-d H:i:s', $income['time'])
	    );
	    //Insert income reserve for the commission
	    $income_class->insert_rolling_reserve($income_reserve);
	endif;
endif;

//approve
$approve = (isset($_GET["approve"]))?$_GET["approve"]:"";
if ($approve != "") mysqli_query($GLOBALS["___mysqli_ston"], "update income set approved = 1  where date = '$approve' ");

$duplicate_check_sql = "SELECT CONCAT(user_id,'-',time,'-',level) as trans, COUNT(*) FROM income GROUP BY trans HAVING COUNT(*) > 1";
$duplicate_check_query = mysqli_query($GLOBALS["___mysqli_ston"], $duplicate_check_sql);
$duplicate_check_num = mysqli_num_rows($duplicate_check_query);
if($duplicate_check_num > 0){
	$trans = "";
	while($duplicate_check_row = mysqli_fetch_array($duplicate_check_query))
	{
		$trans .= ", ".$duplicate_check_row['trans'];
	}
	$error = "<div class='alert alert-danger'>Alert! Duplicate Commision ".$trans."</div>";
}
foreach ($_POST as $key => $value) {
		filter_input(INPUT_POST, $key);
		$$key = $_POST[$key];
		$key = $value;
	}
	
	$sql = "SELECT i.id, id_user, username, i.date, i.time, amount, level, board_type, i.type, other, other_type, reenter, co_comm     
			FROM income i
			INNER JOIN users u ON i.user_id = u.id_user
			WHERE approved = 0 ORDER BY date DESC";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
?>
<?php echo $error; ?>
<div class="ibox-content">	
<h2>Latest</h2>
<table class="table table-striped table-bordered pendingCommDatatable">
	<thead>
		<tr>
			<th class="text-center">ID</th>
			<th class="text-center">Trans. #</th>
			<th class="text-center">Username</th>
			<th class="text-center">Date</th>
			<th class="text-center">Level</th>
			<th class="text-center">Position</th>
			<th class="text-center">Status</th>
			<th class="text-center">Member C.</th>
			<th class="text-center">Re Enter</th>
			<th class="text-center">Advanced C.</th>
			<th class="text-center">Forfeited C.</th>
			<th class="text-center">Blocked C.</th>
			<th class="text-center">Company C.</th>
			<th class="text-center">Rolling Reserve</th>
			<th class="text-center">Total</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="7" class="text-right"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
		</tr>
	</tfoot>
	<tbody>
<?php
	$olddate = 0;
	$i = 0;
	while($row = mysqli_fetch_array($query))
	{	
		$i++;
		$date = $row['date'];
		$time = date('H:i:s m-d-Y' , $row['time']);
		if($row['other'] > 0 && ($row['other_type'] == 'advanced comm')) {
			$level = $row['level'];
		} else {
			$level = $row['level'];
		}
		
		if((int)$row['type'] === 3){
			$board_position = 'Step 2';
		} else {
			$is_partial = $income_class->is_partial($row['id']);
			$board_position = ($is_partial) ? 'Step 3<br>(Partial Amount)' : 'Step 3<br>(Full Amount)';
		}
		
		$status = "Pending";
 
		if($olddate > 0 && $olddate != $date) echo tableBreak($date, $olddate);
		$olddate = $date;

		//Get rolling reserve 
		$reserve = '0.00';
		$rolling_reserve = $income_class->get_rolling_reserve($row['id']);
		if(!empty($rolling_reserve)) $reserve = $rolling_reserve[0]['reserve'];
		?>
		<tr class="text-center">
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['id_user']."-".$row['time']; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td><?php echo $time; ?></td>
			<td><?php echo $level; ?></td>
			<td><?php echo $board_position; ?></td>
			<td><?php echo $status; ?></td>
			<td><?php echo number_format($row['amount'],2); ?></td>
			<td><?php echo number_format($row['reenter'],2); ?></td>
			<td><?php if($row['other_type'] == 'advanced comm') echo number_format($row['other'],2); ?></td>
			<td><?php if($row['other_type'] == 'less than 2 qp') echo number_format($row['other'],2); ?></td>
			<td><?php if($row['other_type'] == 'blocked member') echo number_format($row['other'],2); ?></td>
			<td><?php echo number_format($row['co_comm'],2); ?></td>
			<td><?php echo number_format($reserve,2); ?></td>
			<?php 
				$total_row = (intval($row['amount']) + intval($row['co_comm']) + intval($row['other']) + intval($row['reenter']));			
				$flag = "";
				$id = $row['id'];
				$new_level = intval($level) - 1;
				
				switch ($level) {
					case 1:
						if($total_row != 396) {
							$flag = "class='text-danger'";	
						}
						break;
					case 2:
						if($total_row != 600) { 
							$flag = "class='text-danger'";	
						}
						break;
					case 3:
						if($total_row != 1000) { 
							$flag = "class='text-danger'";	
						}
						break;
					case 4:
						if($total_row != 2000) { 
							$flag = "class='text-danger'"; 					
						}
						break;
					case 5:
						if($total_row != 4800) { 
							$flag = "class='text-danger'";	
						}
						break;
				}			
				printf('<td %s>%s</td>', $flag, number_format($total_row,2));
			?>

			<th class="text-center">
				<!-- Deny button -->
				<a onclick="return confirm('Deny commission <?php echo $row['id_user'].'-'.$row['time']; ?> for member <?php echo $row['username']; ?>?');" title="Deny" class="icon-5 info-tooltip float-left" href="index.php?page=pending_commissions&deny=<?php echo $row["id"]; ?>"><i class="fa fa-square-o"></i> Deny</a>
				<?php if($row['other_type'] == 'less than 2 qp' || $row['other_type'] == 'blocked member'): ?>
					<br><br>
					<!-- Return commission -->
					<a onclick="return confirm('Return commission <?php echo $row['id_user'].'-'.$row['time']; ?> for member <?php echo $row['username']; ?>?');" title="Retun Commission" class="icon-5 info-tooltip float-left" href="index.php?page=pending_commissions&return=<?php echo $row["id"]; ?>"><i class="fa fa-square-o"></i> Return Commission</a>
				<?php endif; ?>
			</th>
		</tr>
	<?php } ?>
	</tbody>
	</table>
	<div>
		<br>
		<a  class="btn btn-primary pull-right" onclick="return confirm('Approve commission for date <?php echo date("m/d/Y", strtotime($olddate)); ?>?');" title="Approve" href="index.php?page=pending_commissions&approve=<?php echo $olddate; ?>">
		Approve Commission <?php echo $olddate > 0 ? "for ". date("m/d/Y", strtotime($olddate)) : ""; ?></a></div>
		<div class="clearfix"></div>
</div>
<?php 
function tableBreak($date,$btndate) {
	?>
	</tbody>
	</table>
	<div>
		<br>
		<a  class="btn btn-primary pull-right" onclick="return confirm('Approve commission for date <?php echo date("m/d/Y", strtotime($btndate)); ?>?');" title="Approve" href="index.php?page=pending_commissions&approve=<?php echo $btndate; ?>">
		Approve Commission <?php echo $btndate > 0 ? "for ". date("m/d/Y", strtotime($btndate)) : ""; ?></a></div>
	<div class="clearfix"></div>
	<h2><?php echo date("m/d/Y", strtotime($date)); ?></h2>
	<table class="table table-striped table-bordered pendingCommDatatable">
	<thead>
		<tr>
			<th class="text-center">ID</th>
			<th class="text-center">Trans. #</th>
			<th class="text-center">Username</th>
			<th class="text-center">Date</th>
			<th class="text-center">Level</th>
			<th class="text-center">Status</th>
			<th class="text-center">Member C.</th>
			<th class="text-center">Re Enter</th>
			<th class="text-center">Advanced C.</th>
			<th class="text-center">Forfeited C.</th>
			<th class="text-center">Blocked C.</th>
			<th class="text-center">Company C.</th>
			<th class="text-center">Rolling Reserve</th>
			<th class="text-center">Total</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="7" class="text-right"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
			<th class="text-center"></th>
		</tr>
	</tfoot>
	<tbody>
	<?php
}
?>
