<?php
include("../function/functions.php");

$income_class = getInstance('Class_Income');

$approve = (isset($_GET["approve"]))?$_GET["approve"]:"";
if ($approve != ""):
	mysqli_query($GLOBALS["___mysqli_ston"], "update income set approved = 1  where id = '$approve' ");	
	$income = $income_class->get_income($approve);
	if(!empty($income) && $income[0]['amount'] > 0):
		$income = $income[0];
		//Get reserve percentage
		$percentage = glc_option('reserve_percentage');
	    //Compute income less reserve
	    $reserve = (float)$income['amount'] * ((float)$percentage / 100);
		$income_reserve = array(
	        'income_id' => $income['id'],
	        'income' => $income['amount'],
	        'reserve' => $reserve,
	        'reserve_percentage' => $percentage,
	        'date_created' => date('Y-m-d H:i:s', $income['time'])
	    );
	    //Insert income reserve for the commission
	    $income_class->insert_rolling_reserve($income_reserve);
	endif;	
endif;


foreach ($_POST as $key => $value) {
	filter_input(INPUT_POST, $key);
	$$key = $_POST[$key];
	$key = $value;
}
	
	$sql = "SELECT i.id, id_user, username, i.date, i.time, amount, level, board_type, i.type, other, other_type, reenter, co_comm     
			FROM income i
			INNER JOIN users u ON i.user_id = u.id_user
			WHERE approved = 2 ORDER BY date DESC";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
?>
<div class="ibox-content">	
<h2>Today</h2>
<table class="table table-striped table-bordered">
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
			<th class="text-center">Total</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tbody>
<?php
	while($row = mysqli_fetch_array($query))
	{	
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
		$status = ($row['approved'] == 0)?"Pending":"Approved";
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
			<td><?php 
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
			echo "<span ".$flag.">".number_format($total_row,2)."</span>"; ?></td>
			<th class="text-center"><a onclick="return confirm('Approve commissoin <?php echo $row['id_user'].'-'.$row['time']; ?> for member <?php echo $row['username']; ?>?');" title="Approve" class="icon-5 info-tooltip float-left" href="index.php?page=denied_commissions&approve=<?php echo $row["id"]; ?>"><i class="fa fa-square-o"></i> Approve</a></th>
		</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
