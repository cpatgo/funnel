<?php
include("../function/functions.php");
$error = "";
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
	
	$sql = "SELECT i.id, id_user, username, i.date, i.time, amount, level, board_type, i.type, other, other_type, reenter, co_comm, approved      
			FROM income i
			INNER JOIN users u ON i.user_id = u.id_user
			WHERE 1=1 ";
	
	//Filters
	$psd = DateTime::createFromFormat("m/d/Y", $period_start_date);
	$ped = DateTime::createFromFormat("m/d/Y", $period_end_date); 
	$period_start_date 	= date_format($psd, 'Y-m-d');
	$period_end_date 	= date_format($ped, 'Y-m-d'); 
	//$start_date_unix = date_format($date_timepicker_start_val, 'U'); //to timestamp
	if($period_start_date 	!= "" && $period_end_date	!= "") 		
	{ 
		$sql .=  " AND from_unixtime(i.time,'%Y-%m-%d') BETWEEN '".$period_start_date."' AND '".$period_end_date."' ";
	} else {
		if($period_start_date 	!= "") 		{ $sql .=  " AND i.date >= '".$period_start_date."' ";  }
		if($period_end_date 	!= "") 		{ $sql .=  " AND i.date <= '".$period_end_date."' ";  }
	}

$sql1 = $sql." AND level = 1";
$query1 = mysqli_query($GLOBALS["___mysqli_ston"], $sql1);
$sql2 = $sql." AND level = 2";
$query2 = mysqli_query($GLOBALS["___mysqli_ston"], $sql2);
$sql3 = $sql." AND level = 3";
$query3 = mysqli_query($GLOBALS["___mysqli_ston"], $sql3);
$sql4 = $sql." AND level = 4";
$query4 = mysqli_query($GLOBALS["___mysqli_ston"], $sql4);
$sql5 = $sql." AND level = 5";
$query5 = mysqli_query($GLOBALS["___mysqli_ston"], $sql5);

if(isset($_GET['msg']) && !empty($_GET['msg'])) printf("<div class='alert alert-success'>Commission has been updated.</div>");

include dirname(__FILE__).'/commission_summary.php';
?>
<br>
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

			<div class="pull-right text-right col-lg-4">
				<input type="submit" value="Edit Commission" name="edit_commission" id="edit_commission" class="btn btn-primary" data-toggle="modal" data-target="#myModal5">				
			</div>
		</div>
	</form>
</div>
</div>
<?php echo $error; ?>
<?php
CommisionByLevel($query1,1);
CommisionByLevel($query2,2);
CommisionByLevel($query3,3);
CommisionByLevel($query4,4);
CommisionByLevel($query5,5);

function CommisionByLevel($query,$level) {
	require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
	$income_class = getInstance('Class_Income');
?>
<div class="ibox-title">
	<h5>Stage <?php echo $level; ?></h5>
</div>
<div class="ibox-content">	
	<table class="table table-striped table-bordered table-hover dataTablesMovement">
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
			</tr>
		</thead>
		<?php
		while($row = mysqli_fetch_array($query))
		{	$date = $row['date'];
			$time = date('H:i:s m-d-Y' , $row['time']);

			//Get rolling reserve 
			$reserve = 0;
			$rolling_reserve = $income_class->get_rolling_reserve($row['id']);
			if(!empty($rolling_reserve)) $reserve = $rolling_reserve[0]['reserve'];

			//Get the board position where the commission was earned
			if((int)$row['type'] === 3){
				$board_position = 'Step 2';
			} else {
				$is_partial = $income_class->is_partial($row['id']);
				$board_position = ($is_partial) ? 'Step 3<br>(Partial Amount)' : 'Step 3<br>(Full Amount)';
			}

			if($row['other'] > 0 && ($row['other_type'] == 'advanced comm')) {
				//$level = '<i class="fa fa-long-arrow-up"></i> '.$row['level'];
				$level = $row['level'];
			} else {
				$level = $row['level'];
			}
			switch ($row['approved']) {
				case 0:
					$status = "Pending";
					break;
				case 1:
					$status = "Approved";
					break;
				case 2:
					$status = "Denied";
					break;
			}
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
								//mysqli_query($GLOBALS["___mysqli_ston"], "update income set level  = '$new_level' where id = '$id' ");
							}
							break;
						case 2:
							if($total_row != 600) { 
								$flag = "class='text-danger'";	
								//mysqli_query($GLOBALS["___mysqli_ston"], "update income set level  = '$new_level' where id = '$id' ");
							}
							break;
						case 3:
							if($total_row != 1000) { 
								$flag = "class='text-danger'";	
								//mysqli_query($GLOBALS["___mysqli_ston"], "update income set level  = '$new_level' where id = '$id' ");
							}
							break;
						case 4:
							if($total_row != 2000) { 
								$flag = "class='text-danger'";
								//mysqli_query($GLOBALS["___mysqli_ston"], "update income set level  = '$new_level' where id = '$id' ");						
							}
							break;
						case 5:
							if($total_row != 4800) { 
								$flag = "class='text-danger'";	
								//mysqli_query($GLOBALS["___mysqli_ston"], "update income set level  = '$new_level' where id = '$id' ");
							}
							break;
					}			
				?>
				<td <?php echo $flag; ?>>
					<?php echo number_format($total_row,2);  ?>	
				</td>
			</tr>
		<?php } ?>
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
			</tr>
		</tfoot>
		</tbody>
	</table>
</div>

<!-- Modal -->
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                	<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Edit Commission</h4>
                <small class="font-bold">
                	Type in the commission ID NUMBER and click "Next" button to get the commission data.
                </small>
            </div>
            <form id="edit_commission_form">
	            <div class="modal-body">
	            	<label class="control-label">Commission ID</label>
	                <div class="input-group">
	                	<input type="text" placeholder="Commission ID" id="commission_id" class="form-control">
	                	<span class="input-group-btn com-btn"> 
	                		<button type="button" id="commission_next_btn" class="btn btn-primary">Next</button>
	                	</span>
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-white" id="btn-close" data-dismiss="modal">Close</button>
	                <button type="button" class="btn btn-primary" id="save_commission">Save changes</button>
	            </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {
        var commission_page = "<?php printf('%s/glc/admin/index.php?page=commissions', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";

        $('body').on('click', '#edit_commission', function(e){
        	e.preventDefault();
        });

        $('body').on('click', '#commission_next_btn', function(e){
        	e.preventDefault();
        	$('body').find('.commission_fields').remove();
        	$('body').find('.alert').remove();
        	var commission_id = $('body').find('#commission_id').val();
        	$.ajax({
                method: "post",
                url: ajax_url+"commission.php",
                data: {
                    'action':'get_commission',
                    'commission_id': commission_id
                },
                dataType: 'json',
                success:function(result) {;
                    console.log(result);
                    if(result.type == 'success'){
                        $('body').find('.modal-body').append(result.form);
                    } else {
                        $('body').find('.modal-body').after('<div class="alert alert-danger">'+result.message+'</div>');
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });

        $('body').on('click', '#save_commission', function(e){
        	e.preventDefault();
        	var commissions = $('body').find('#edit_commission_form').serialize();
        	$.ajax({
                method: "post",
                url: ajax_url+"commission.php",
                data: {
                    'action':'update_commission',
                    'fields': commissions
                },
                dataType: 'json',
                success:function(result) {;
                    console.log(result);
                    if(result.type == 'success'){
                        window.location.href = commission_page+'&msg=1';
                    } else {
                        $('body').find('.modal-body').after('<div class="alert alert-danger">'+result.message+'</div>');
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });
    });
</script>