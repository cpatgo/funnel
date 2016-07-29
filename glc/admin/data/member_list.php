<?php
include("../function/functions.php");

$newp = (isset($_GET['p'])) ? $_GET['p'] : '';
$user_search = (isset($_REQUEST['user_search'])) ? $_REQUEST['user_search'] : '';
$plimit = "20";
?>
<div class="ibox-content">	
	<!-- <div style="width:25%; text-align:right; float:left;  height:70px;">
		<form action="index.php?page=member_list" method="post">
			<input type="hidden" name="excel_user_search" value="<?php print $user_search; ?>" />
			<input type="submit" name="Excel" value="Download Excel" class="btn btn-primary" />
		</form>
	</div> -->
	<!-- <div style="width:55%; text-align:right; float:right;  height:50px; font:Verdana; font-size:12px; display:block;">
		<form action="index.php?page=member_list" method="post">
			<div style=" float:left;">
				<label for="Search For User">Search</label>
				<input type="text" name="user_search" value="" />
				<input type="submit" name="search" value="Search" class="btn btn-primary" />
			</div>
		</form>
	</div> -->
<?php
if(!empty($user_search))
{
 	$sql = "SELECT * FROM users where username = '$user_search'";
}
else
{
 	$sql = "SELECT * FROM users";
}
if(isset($_POST['Excel']))
{	

// 	$save_excel_file_path = "../UserInfo/";
	
// 	$unique_name = "UserInformation".time();
// 	$sep = "\t"; 
// 	$fp = fopen($save_excel_file_path.$unique_name.".xls", "w"); 
// 	$insert = ""; 
// 	$insert_rows = ""; 
	
// 	$insert_rows.="No. \t User Id \t Name \t Joining date \t Joining Kit \t Kit Delivered By \t Kit Status \t";
	
// 	$insert_rows.="\n";
// 	fwrite($fp, $insert_rows);
// 	$srno = 0;
// print	$excel_user_search = $_REQUEST['excel_user_search'];
// 	if($excel_user_search != '')
// 	{
// 		$sql = "SELECT * FROM users where username = '$excel_user_search'";
// 	}
// 	else
// 	{
// print		$sql =  "SELECT * FROM users";
// 	}
// 	$que = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
// 	while($row = mysqli_fetch_array($que))
// 	{
// 		$insert = "";
// 		$srno++;	
// 		$id = $row['id_user'];
// 		$username = get_user_name($id);
// 		$real_parent = get_user_name($row['real_parent']);
// 		$name = $row['f_name']." ".$row['l_name'];
// 		$type = $row['type'];
// 		$date = $row['date'];
// 		$phone_no = $row['phone_no'];
// 		$db_time = $row['time'];
// 		$time = date('H:i:s' ,  $db_time );
		
// 		$kit_status = $row['kit_status'];
// 		$delivered_info = $row['delivered_info'];	
// 		$joining_kit = get_joining_kit_name($id);
		
// 		if($kit_status > 0)
// 		{
// 			$delinfo = "Delivered";
// 		}
		
// 		$insert .= $srno.$sep;
// 		$insert .= $username.$sep;
// 		$insert .= $real_parent.$sep;
// 		$insert .= $name.$sep;
// 		$insert .= $date." ".$time.$sep;
// 		$insert .= $joining_kit.$sep;
// 		$insert .= $delivered_info.$sep;
// 		$insert .= $delinfo.$sep;
			
// 		$srno++;
	
// 		$insert = str_replace($sep."$", "", $insert);
		
// 		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
// 		$insert .= "\n";
// 		fwrite($fp, $insert);
// 	}
	
// 	fclose($fp);
// 	$full_path = "../UserInfo/".$unique_name.".xls";
	
// 	print "Excel File Download Successfully !!";
}

if(isset($_POST['kit_submit']))
{
	$user_id = $_POST['user_id'];
	$phone_no = $_POST['phone_no'];
	$delivered_info = $_POST['delivered_info'];
	$username = $_POST['username'];
	$date = date('Y-m-d');
	
	mysqli_query($GLOBALS["___mysqli_ston"], "update users set delivered_info = '$delivered_info' , kit_status = 1 where id_user = '$user_id' ");
	
	$db_msg = $kit_delivered_message_setting;
	include("../function/full_message.php");
	send_sms($phone_no,$full_message);
	
	print "Joining Kit Information Saved Successfully !";
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$totalrows = mysqli_num_rows($query);
if($totalrows > 0)
{	?>
	<div class="ibox-content">
	<table class="table table-striped table-bordered table-hover dataTables">
	<thead>
	<tr>
		<th class="text-center">No.</th>
		<th class="text-center">User Id</th>
		<th class="text-center">Name</th>
		<th class="text-center">Enroller</th>
		<th class="text-center">Joining date</th>
		<th class="text-center">Membership</th>
		<th class="text-center">Kit Delivered By</th>
		<th class="text-center">Status</th>
	</tr>
	</thead>
<?php	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$srno = $start;
	if(!empty($user_search))
	{
		$sql = "SELECT * FROM users where username= '$user_search'";
	}
	else
	{
		$sql = "SELECT * FROM users LIMIT $start,$plimit";
	}

	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	while($row = mysqli_fetch_array($query))
	{
		$srno++;	
		$id = $row['id_user'];
		$username = get_user_name($id);
		$real_parent = get_user_name($row['real_parent']);
		if($real_parent == '')
			$real_parent = "Top Member";
		else
			$real_parent = $real_parent;
		$name = $row['f_name']." ".$row['l_name'];
		$type = $row['type'];
		$date = $row['date'];
		$phone_no = $row['phone_no'];
		$db_time = $row['time'];
		$time = date('H:i:s' ,  $db_time );
		
		$kit_status = $row['kit_status'];
		$delivered_info = $row['delivered_info'];
		$joining_kit = get_joining_kit_name($id);
	
		echo "
			<tr class=\"text-center\">
				<td>$srno</td>
				<td>$username</td>
				<td>$name</td>
				<td>$real_parent</td>
				<td>$date <br> $time</td>
				<td>$joining_kit</small></td>"; 
		
		if($kit_status == 0)
		{ ?>
			<form action="index.php?page=member_list" method="post">
				<input type="hidden" name="user_id" value="<?php print $id; ?>"  />
				<input type="hidden" name="username" value="<?php print $username; ?>"  />
				<input type="hidden" name="user_phone" value="<?php print $phone_no; ?>"  />
				<td><textarea name="delivered_info" style="width:110px; height:40px;"></textarea></td>
				<td><input type="submit" name="kit_submit" value="Update" class="btn btn-primary" /></td>
			</form>
			</tr>
<?php	}	
		else
		{
			echo "
				<td>$delivered_info</td>
				<td>Delivered</small></td>
			</tr>"; 
		}
	}
	echo "</tbody></table></div></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
		if ($newp>1)
		{ ?>
			<li id="DataTables_Table_0_previous" class="paginate_button previous">
				<a href="<?="index.php?page=member_list&p=".($newp-1);?>">Previous</a>
			</li>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<li class="paginate_button ">
					<a href="<?="index.php?page=member_list&p=$i";?>"><?php print_r("$i");?></a>
				</li>
				<?php 
			}
			else
			{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <li id="DataTables_Table_0_next" class="paginate_button next">
				<a href="<?="index.php?page=member_list&p=".($newp+1);?>">Next</a>
		   </li>
		<?php 
		} 
		?>
		</ul></div>
<?php
}
else
{
	echo "<B style=\"color:#ff0000; font-size:12pt;\">There are no information to show !</B>";
}


if(isset($_POST['Excel']))
{
	// echo "<script type=\"text/javascript\">";
	// echo "window.location = \"$full_path\"";
	// echo "</script>";
	printf('<script type="text/javascript">window.location="/glc/admin/ajax/export.php";</script>');
}


function get_joining_kit_name($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM products where id = (SELECT voucher_type FROM e_voucher where used_id = '$id') ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$res = $row['products_name'];
		}	
	}
	else
		$res = "None";

	return $res;
}


?>
