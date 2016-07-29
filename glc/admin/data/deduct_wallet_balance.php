<?php
if(isset($_POST['submit']))
{
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$date = date('Y-m-d',strtotime($date));
	
	$sql = "insert into deduct_amount (amount , date) values('$amount', '$date')";
	mysqli_query($GLOBALS["___mysqli_ston"], $sql);
}
elseif(isset($_POST['change']))
{
	$amount = $_POST['change_amount'];
	$date = $_POST['change_date'];
	$date = date('Y-m-d',strtotime($date));
	
	$sql = "update deduct_amount set amount = '$amount' , date = '$date' order by id desc limit 1";
	mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=deduct_wallet_balance\"";
	echo "</script>";
}
?>
<div class="ibox-content">
<form method="post">
<table class="table table-bordered"> 
	<thead><tr><th colspan="2">Deduct Wallet Balance</th></tr></thead>
	<?php 
		$last_sql = "select amount ,date from deduct_amount order by id desc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $last_sql);
		$num = mysqli_num_rows($query);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($query))
			{
				$date = date('m-d-Y',strtotime($row[1]));
	?>
				<tr>
					<td colspan="2">
					<form method="post">
					<table class="table table-bordered"> 
						<tr>
							<th>Your Last Enter Amount</th>
							<th><input type="text" name="change_amount" value="<?=$row[0]?>" /></th>
							<th>Your Last Enter Date</th>
							<th>
							<div class="form-group" id="data_1" style="margin:0px">
								<div class="input-group date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" name="change_date" value="<?=$date?>">
								</div>
							</div>
							<td>
							<input type="submit" name="change" value="Change" class="btn btn-success">
							</td>
						</tr>
					</table>
					</form>
					</td>
				</tr>
	<?php 	}
		}?>
	<tr>
		<th width="40%">Amount</th>
		<td><input type="text" name="amount" value=""></td>
	</tr>
	
	<tr>
		<th>Date</th>
		<td>
			 <div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date">
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

						
<!-- Data picker -->

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