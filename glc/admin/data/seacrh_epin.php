<?php
session_start();
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "25";
?>
<div class="ibox-content">
		
<?php
if((isset($_POST['submit'])) or $newp != '')
{
	if($_POST['submit'] == 'Submit')
	{
		$search_mode = $_REQUEST['mode'];
		if($search_mode == 'epin')
		{ ?>
		<form name="franchisee" action="index.php?page=seacrh_epin" method="post">
		<table class="table table-bordered"> 
			<input type="hidden" name="search_mode" value="<?=$search_mode; ?>"  />
			<tr>
				<th>Enter e-Voucher</th>
				<td><input type="text" name="epin" /></td>
			</tr>
			<tr>	
			<tr>
				<td colspan="2" class="text-center">
					<input type="submit" name="submit" value="Search" class="btn btn-primary" />
				</td>
			</tr>
		</table>
		</form>

<?php		}
		elseif($search_mode == 'user')
		{ ?>
			<form name="franchisee" action="index.php?page=seacrh_epin" method="post">
			<table class="table table-bordered"> 
				<input type="hidden" name="search_mode" value="<?=$search_mode; ?>"  />
				<tr>
					<th>Enter Username</th>
					<td><input type="text" name="username" /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="submit" value="Search" class="btn btn-primary" />
					</td>
				</tr>
			</table>
			</form>

<?php		}
		elseif($search_mode == 'date')
		{ ?>
			<form name="franchisee" action="index.php?page=seacrh_epin" method="post">
			<table class="table table-bordered"> 
				<input type="hidden" name="search_mode" value="<?=$search_mode; ?>"  />
				<tr>
					<th>Enter Start Date</th>
					<td><input type="text" name="st_date" /></td>
				</tr>
				<tr>
					<th>Enter End Date </th>
					<td><input type="text" name="end_date" /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="submit" value="Search" class="btn btn-primary" />
					</td>
				</tr>
			</form>
			</table>

<?php	}
		else
		{
			echo "<B style=\"color:#FF0000; font-size:12pt;\">There is some Conflicts !</B>";
		}	
	}	
	elseif($_POST['submit'] == 'Search' or $newp != '')
	{
		if($newp == '')
			 $_SESSION['ednet_mode_search_save'] = $_REQUEST['search_mode'];
		$s_mode = $_SESSION['ednet_mode_search_save'];	 
		
		if($s_mode == 'epin')
		{
			$epin = $_REQUEST['epin'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where voucher = '$epin' ");
			$num = mysqli_num_rows($query);
			if($num > 0)
			{
				echo "<table class=\"table table-bordered\"> ";
				while($row = mysqli_fetch_array($query))
				{
					$mode = $row['mode'];
					$user_id = $row['user_id'];
					$username = get_user_name($user_id);
					$voucher = $row['voucher'];
					$date = $row['date'];
					if($mode == 0)
					{
						$used_id = $row['used_id'];
						$used_date = $row['used_date'];
						$used_username = get_user_name($used_id);
						echo " 
							<thead>
							<tr>
								<th>Username</td>
								<th>Date</td>
								<th>e-Voucher</td>
								<th>Status</td>
								<th>Used Username</td>
								<th>Used Date</td>
							</tr>
							</thead>
							<tr>
								<td>$username</td>
								<td>$date</td>
								<td>$epin</td>
								<td>Used</td>
								<td>$used_username</td>
								<td>$used_date</td>
							</tr>";	
					}
					else
					{
						echo " 
							<thead>
							<tr>
								<th>Username</td>
								<th>Date</td>
								<th>e-Voucher</td>
								<th>Status</td>
							</tr>
							</thead>
							<tr>
								<td>$username</td>
								<td>$date</td>
								<td>$epin</td>
								<td>UnUsed</td>
							</tr>";	
					}
				}
				print "</table>";
			}
			else
			{
				print "<B style=\"color:#FF0000; font-size:12pt;\">
					Entered e-Voucher is wrong !!<br>Please try again !</B>";
			}
		}
		elseif($s_mode == 'user')
		{
			if($newp == '')
			 	$_SESSION['ednet_user_search_save'] = $_REQUEST['username'];
			$username = $_SESSION['ednet_user_search_save'];
			$user_id = get_new_user_id($username);
			if($user_id == 0)
			{
				print "<B style=\"color:#FF0000; font-size:12pt;\">Please Enter Correct Username !</B>";
			}
			else
			{
				$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where user_id = '$user_id' ");
				$totalrows = mysqli_num_rows($qu);
				$total_used = $total_unused = 0;
				while($rr = mysqli_fetch_array($qu))
				{
					$mode = $rr['mode'];
					if($mode == 0)
						$total_used++;
					else
						$total_unused++;
				}
				if($totalrows > 0)
				{
					echo "
						<table class=\"table table-bordered\">
							<thead>
							<tr>
								<th>Total Used e-Voucher</td>
								<th colspan=2>$total_used</td>
								<th>Total Unused e-Voucher</td>
								<th colspan=2>$total_unused</td>
							</tr>
							<tr>
								<th>Username</td>
								<th>Date</td>
								<th>e-Voucher</td>
								<th>Status</td>
								<th>Used Username</td>
								<th>Used Date</td>
							</tr>
							<thead>";
									  
					$pnums = ceil ($totalrows/$plimit);
					if ($newp==''){ $newp='1'; }
						
					$start = ($newp-1) * $plimit;
					$starting_no = $start + 1;
					
					if ($totalrows - $start < $plimit) { $end_count = $totalrows;
					} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
						
					
					if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
					} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; } 				  
									  
					$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher 
					where user_id = '$user_id' LIMIT $start,$plimit ");				  
					while($row = mysqli_fetch_array($qu))
					{
						$mode = $row['mode'];
						$voucher = $row['voucher'];
						$user_id = $row['user_id'];
						$username = get_user_name($user_id);
						$voucher = $row['voucher'];
						$date = $row['date'];
						if($mode == 0)
						{
							$used_id = $row['used_id'];
							$used_date = $row['used_date'];
							$used_username = get_user_name($used_id);
							$status = "Used";
						}
						else
						{
							$used_date = '';
							$used_username = '';
							$status = "Unused";
						}	
						echo "
							<tr>
								<td>$username</td>
								<td>$date</td>
								<td>$voucher</td>
								<td>$status</td>
								<td>$used_username</td>
								<td>$used_date</td>
							</tr>";	
					}
					echo "</table>";
					?>
					<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
					<ul class="pagination">
					<?php
						if ($newp>1)
						{ ?>
							<li id="DataTables_Table_0_previous" class="paginate_button previous">
								<a href="<?="index.php?page=seacrh_epin&p=".($newp-1);?>">Previous</a>
							</li>
						<?php 
						}
						for ($i=1; $i<=$pnums; $i++) 
						{ 
							if ($i!=$newp)
							{ ?>
								<li class="paginate_button ">
									<a href="<?="index.php?page=seacrh_epin&p=$i";?>">
										<?php print_r("$i");?>
									</a>
								</li>
								<?php 
							}
							else
							{ ?>
								<li class="paginate_button active">
									<a href="#"><?php print_r("$i"); ?></a>
								</li><?php 
							}
						} 
						if ($newp<$pnums) 
						{ ?>
						   <li id="DataTables_Table_0_next" class="paginate_button next">
								<a href="<?="index.php?page=seacrh_epin&p=".($newp+1);?>">Next</a>
						   </li>
						<?php 
						} 
						?>
						</ul></div>
					<?php
				}
				else 
				{ 
				echo "<B style=\"color:#FF0000; font-size:12pt;\">User ".$username." Have no e-Voucher</B>"; 
				}
			}
		}
		elseif($s_mode == 'date')
		{
			if($newp == '')
			{
			 	$_SESSION['ednet_st_date_search_save'] = $_REQUEST['st_date'];
				$_SESSION['ednet_ed_date_search_save'] = $_REQUEST['end_date'];
			}
			$start_date = $_SESSION['ednet_st_date_search_save'];
			$end_date = $_SESSION['ednet_ed_date_search_save'];
			if($start_date != '' or $end_date != '')
			{
				$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher 
				where date >= '$start_date' and date <= '$end_date' ");
				$totalrows = mysqli_num_rows($qu);
				if($totalrows > 0)
				{
					$totalrows = mysqli_num_rows($qu);
					$total_used = $total_unused = 0;
					while($rr = mysqli_fetch_array($qu))
					{
						$mode = $rr['mode'];
						if($mode == 0)
							$total_used++;
						else
							$total_unused++;
					}
					echo "
						<table class=\"table table-bordered\">
							<thead>
							<tr>
								<th>Total Used e-Voucher</th>
								<th colspan=2>$total_used</th>
								<th>Total Unused e-Voucher</th>
								<th colspan=2>$total_unused</th>
							</tr>
							<tr>
								<th>Username</th>
								<th>Date</th>
								<th>e-Voucher</th>
								<th>Status</th>
								<th>Used Username</th>
								<th>Used Date</th>
							</tr>
							</thead>";
					$pnums = ceil ($totalrows/$plimit);
					if ($newp==''){ $newp='1'; }
						
					$start = ($newp-1) * $plimit;
					$starting_no = $start + 1;
					
					if ($totalrows - $start < $plimit) { $end_count = $totalrows;
					} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
						
					
					if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
					} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; } 			  
					
					$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where date >= '$start_date' 
					and date <= '$end_date' LIMIT $start,$plimit ");			  
					while($row = mysqli_fetch_array($qu))
					{
						$mode = $row['mode'];
						$voucher = $row['voucher'];
						$user_id = $row['user_id'];
						$username = get_user_name($user_id);
						$voucher = $row['voucher'];
						$date = $row['date'];
						if($mode == 0)
						{
							$used_id = $row['used_id'];
							$used_date = $row['used_date'];
							$used_username = get_user_name($used_id);
							$status = "Used";
						}
						else
						{
							$used_date = '';
							$used_username = '';
							$status = "Unused";
						}	
						echo "
							<tr>
								<td>$username</td>
								<td>$date</td>
								<td>$voucher</td>
								<td>$status</td>
								<td>$used_username</td>
								<td>$used_date</td>
							</tr>";	
					}
					echo "</table>";
					?>
					<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
					<ul class="pagination">
					<?php
						if ($newp>1)
						{ ?>
							<li id="DataTables_Table_0_previous" class="paginate_button previous">
								<a href="<?="index.php?page=seacrh_epin&p=".($newp-1);?>">Previous</a>
							</li>
						<?php 
						}
						for ($i=1; $i<=$pnums; $i++) 
						{ 
							if ($i!=$newp)
							{ ?>
								<li class="paginate_button ">
									<a href="<?="index.php?page=seacrh_epin&p=$i";?>">
										<?php print_r("$i");?>
									</a>
								</li>
								<?php 
							}
							else
							{ ?>
								<li class="paginate_button active">
									<a href="#"><?php print_r("$i"); ?></a>
								</li><?php 
							}
						} 
						if ($newp<$pnums) 
						{ ?>
						   <li id="DataTables_Table_0_next" class="paginate_button next">
								<a href="<?="index.php?page=seacrh_epin&p=".($newp+1);?>">Next</a>
						   </li>
						<?php 
						} 
						?>
						</ul></div>
					<?php
				}
				else 
				{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There is No e-Voucher to Show !</B>"; }	
			}
			else 
			{ 
				echo "<B style=\"color:#FF0000; font-size:12pt;\">
				Please Enter Start Date Or End Date !</B>"; 
			}
		}	
	}
}
else
{?>
<form name="franchisee" action="index.php?page=seacrh_epin" method="post">
<table class="table table-bordered"> 
	<thead><tr><th colspan="2">Select Search Mode </th></tr></thead>
	<tbody>
	<tr>
		<th width="50%">e-Voucher Mode</th>
		<td><input type="radio" name="mode" value="epin" checked="checked" /></td>
	</tr>
	<tr>
		<th>User Mode</th>
		<td><input type="radio" name="mode" value="user" /></td>
	</tr>
	<tr>
		<th>Date Mode</th>
		<td><input type="radio" name="mode" value="date" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
		</td>
	</tr>
	</tbody>
</table>
</form>
</div>
<?php } ?>
