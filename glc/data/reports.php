<?php
session_start();
$user_id = $_SESSION['dennisn_user_id'];
if(!isset($_REQUEST['payday']))
{
	$year =(isset($_REQUEST['year']))?$_REQUEST['year']:date("Y"); 
	$month =(isset($_REQUEST['year']))?$_REQUEST['month']:date("m"); 
} else {
	$year = ""; 
	$month = ""; 
}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Filters</h5>
			</div>
			<div class="ibox-content">
			<div class="row">
				<form method="post" role="form" class="col-lg-6 b-r">	
					<label>Filter By Pay Circle Day</label>	
					<div class="form-inline">					
						<div id="data_1" class="form-group">	
							<div class="input-group date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" value="" class="form-control" name="payday">
							</div>
						</div>
						<input type="submit" value="<?=$Search;?>" name="search" class="btn btn-primary">
					</div>
				</form>
				<div class="col-lg-1 b-r"></div>
				<form method="post" role="form" class="col-lg-6"  action="">				
					<div id="data_1" class="form-group">
						<label>Filter By Month</label>
						<div class="form-inline">
							<select name="month" class="form-control">
								<option value=""><?=$Month;?></option>
								<?php
								for($i = 1; $i <= 12; $i++) 
								{ ?>
									<option <?php if($month == $i) { ?> selected="selected" <?php } ?> 
									value="<?=$i; ?>"><?=$i; ?>		</option>
								<?php	
								} ?> 
							</select>
							<select name="year" class="form-control">
								<option value=""><?=$Year;?></option>
								<?php
									$yr = date('Y');
									//$p_yr = date('Y', strtotime("-2year"));
								for($i = 2014; $i <= $yr; $i++) 
								{ ?>
									<option <?php if($year == $i) { ?> selected="selected" <?php } ?> 
									value="<?=$i; ?>"><?=$i; ?></option>
								<?php 	
								} ?> 
							</select>						
							<input type="submit" value="<?=$Search;?>" name="search" class="btn btn-primary">
						</div>
					</div>
				</form>
				
			</div>
			</div>
		</div>
	</div>
</div>
<div class="ibox-content"> 	 
<?php	
	$sr_no = 1;
	$sql = "select * from account where user_id = '$user_id' ";
	if($year != "") $sql .= " AND YEAR(date) = '".$year."' ";
	if($month != "") $sql .= " AND MONTH(date) = '".$month."' ";
	if(isset($_REQUEST['payday']) && !empty($_REQUEST['payday'])) $sql .= " AND date = '".date("Y-m-d",strtotime($_REQUEST['payday']))."' ";
	$sql .= " order by id desc";	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{ ?>
	<table class="table table-striped table-bordered dataTables">
		<thead>
		<tr>
			<th class="text-center"><?=$Date;?></th>
			<th class="text-center"><?=$Description;?></th>
			<th class="text-center">Earned</th> 
			<th class="text-center">Requested</th> 

			<!--<th class="text-center"><?=$Wallet_Balance;?></th>-->
		</tr>
		</thead>
<?php
			
		while($rows = mysqli_fetch_array($query))
		{
			$credit = $rows['cr'];
			$debit = $rows['dr'];
			$type = $rows['type'];
			$date = date("m/d/Y",strtotime($rows['date']));
			$acc = $rows['account'];
			$wall_bal = $rows['wallet_balance'];
?>
			<tr class="text-center">
				<td><?=$date; ?></td>
				<td style="font-size:11px;" class="text-center"><?=$acc; ?></td>
				<td><?="$".number_format($credit,2); ?></td>
				<td><?="$".number_format($debit,2); ?></td>
				<!--<td><?="$".number_format($wall_bal,2); ?></td>-->
			</tr>
<?php } ?>
	</table></div>
<?php	}
	else{ echo $No_info_to_show." for ";
		  echo (isset($_REQUEST['payday']))?isset($_REQUEST['payday']):$month."/".$year;}
?>
