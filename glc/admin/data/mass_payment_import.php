<?php 
if(!isset($_POST['start_date']) || !isset($_POST['end_date'])):
    $date_from = (date('d') <= 15) ? date('m/24/Y', strtotime('-1 months')) : date('m/9/Y');
    $date_to = (date('d') <= 15) ? date('m/8/Y') : date('m/23/Y');
else:
    $date_from = $_POST['start_date'];
    $date_to = $_POST['end_date'];
endif;

$payment_class = getInstance('Class_Payment');
$user_class = getInstance('Class_User');
$payments = $payment_class->get_payments(date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to)));
?>

<div class="ibox-content">
	<form action="ajax/import_payments.php" method="post" enctype="multipart/form-data">
		<?php if($_GET['import'] && $_GET['import'] == '1') printf('<div class="alert alert-success">Payments has been successfully submitted.</div>'); ?>
		<h3>Import the XLS File here.</h3>
		<input type="file" name="csv_file">
		<br>
  		<input type="submit" name="csv_submit" value="Import XLS File" class="btn btn-primary btn-large">
	</form>
</div>
<br><br>

<!-- DATE FILTER -->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Filters</h5>
            </div>
            <div class="ibox-content">
            <div class="row">
                <form method="post" role="form" id="payza_form">    
                    <div class="form-inline">                   
                        <div id="data_1" class="form-group">    
                            <label>From </label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" value="<?php echo (isset($_POST['start_date'])) ? $_POST['start_date'] : $date_from ?>" class="form-control" name="start_date" id="start_date">
                            </div>
                        </div>
                        <div id="data_2" class="form-group">    
                            <label>To </label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" value="<?php echo (isset($_POST['end_date'])) ? $_POST['end_date'] : $date_to ?>" class="form-control" name="end_date" id="end_date">
                                <input type="hidden" id="batch_no" name="batch_no" value="<?php echo isset($_POST['batch_no']) && !empty($_POST['batch_no']) ? $_POST['batch_no'] : '' ?>">
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
<!-- END DATE FILTER -->
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Payment History</h5>
    </div>
    <div class="ibox-content">  
        <table class="table table-striped table-bordered table-hover dataTablePayments">
            <thead>
                <tr>
                    <th class="text-center">Payment ID</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Payment Method</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($payments as $key => $value) { ?>
                <?php 
        			$user = $user_class->get_user($value['user_id']);	
        		?>
                <tr class="text-center">
                	<td><?php echo $value['id'] ?></td>
                	<td><?php echo $user[0]['username'] ?></td>
                	<td><?php echo $value['pay_mode'] ?></td>
                	<td><?php echo $value['amount'] ?></td>
                	<td><?php echo $value['paid_date'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(function() {
	$('#start_date').datepicker();
	$('#end_date').datepicker();

	$('.dataTablePayments').DataTable({
	    "iDisplayLength": 100,
	    responsive: true,
	        "dom": 'T<"clear">lfrtip',
	        "tableTools": {
	            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
	    }
	});
});
</script>