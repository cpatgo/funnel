<?php

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";
?>
<div style="width:90%; text-align:right;height:70px;">
	<div style="width:25%; text-align:right; float:left;  height:70px;">
		<form action="index.php?page=user_information" method="post">
			<input type="submit" name="Excel" value="Download Excel" class="btn btn-primary" />
		</form>
	</div>
	<div style="width:65%; text-align:right; float:right; height:70px;">
		<form action="index.php?page=user_information" method="post">
			<font style="color:#002953; font-style:normal;">User Id : </font>
			<input type="text" name="search_username"  />
			<input type="submit" name="Search" value="Search" class="btn btn-primary" />
		</form>
	</div>
</div>

<div class="ibox-content">	
<?php
$qur_set_search = '';

if(isset($_POST['Excel']))
{

	$save_excel_file_path = "../UserInfo/";
	
	$unique_name = "UserInformation".time();
	$sep = "\t"; 
	$fp = fopen($save_excel_file_path.$unique_name.".xls", "w"); 
	$insert = ""; 
	$insert_rows = ""; 
	$result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users ");              
	
		$insert_rows.="No. \t User Name\t Password \t Security Key \t Name \t E-mail \t Phone No. \t Total Income \t Income Received \t Bank Details \t";
	
	$srno = 1;
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id = $row['id_user'];
		 $username = $row['username'];
		$password = $row['password'];
		$email = $row['email'];
		$beneficiery_name = $row['beneficiery_name'];
		$ac_no = $row['ac_no'];
		$bank = $row['bank'];
		$bank_code = $row['bank_code'];
		$user_pin = $row['user_pin'];
		$phone_no = $row['phone_no'];
		$type = $row['type'];
		$name = $row['f_name']." ".$row['l_name'];
		if($type == 'D')
			$col = "#FF0000";
		else
			$col = "#000000";
		$bank_details= "Beneficiery Name : $beneficiery_name Account No. : $ac_no Bank Name : $bank IFSC Code : $bank_code";	
				
		$total_income = number_format(get_user_total_income($id), 2);	
		$income_receive = number_format(get_user_total_income_receive($id), 2);	

		$insert .= $srno.$sep;
		$insert .= $username.$sep;
		$insert .= $password.$sep;
		$insert .= $user_pin.$sep;
		$insert .= $name.$sep;
		$insert .= $email.$sep;
		$insert .= $phone_no.$sep;
		$insert .= $total_income." ".$sep;
		$insert .= $income_receive." ".$sep;
		$insert .= $bank_details.$sep;
			
		$srno++;

		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	$full_path = "../UserInfo/".$unique_name.".xls";
	
	print "Excel File Download Successfully !!";
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"$full_path\"";
	echo "</script>";
}



if((isset($_POST['Search'])) or ((isset($newp)) and (isset($_POST['search_username']))))
{
	if(!isset($newp))
	{
		$search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		if($search_id == 0)
			print "<div style=\"width:80%; text-align:right; color:#FF0000; font-style:normal; font-size:14px; height:50px; padding-right:100px;\">Enter Correct User Id !</div>";
		else
		{
			$_SESSION['session_search_username'] = $search_id;
			$qur_set_search = " where id_user = '$search_id' ";
		}	
	}
	else
	{	
		$search_id = $_SESSION['session_search_username'];
		$qur_set_search = " where id_user = '$search_id' ";
	}		
}
elseif(isset($_POST['block_member']))
{
	$block_user_id = $_POST['block_user_id'];

	mysqli_query($GLOBALS["___mysqli_ston"], "update users set type = 'D' where id_user = '$block_user_id' " );
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users $qur_set_search ");
$totalrows = mysqli_num_rows($query);
?>
<table class="table table-bordered">
	<thead>
	<tr>
		<th class="text-center">No.</th>
		<th class="text-center">User Name</th>
		<th class="text-center">Password</th>
		<th class="text-center">Security Key</th>
		<th class="text-center">Name</th>
		<th class="text-center">Phone No.</th>
		<th class="text-center">Total Imcome</th>
		<th class="text-center">Imcome Received</th>
		<th class="text-center">Block</th>
	</tr>
	</thead>
	<tbody>
<?php
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$cnt = $plimit*($newp-1);

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users $qur_set_search LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query))
	{
		$cnt++;
		$id = $row['id_user'];
		$username = $row['username'];
		$password = $row['password'];
		$email = $row['email'];
		$beneficiery_name = $row['beneficiery_name'];
		$ac_no = $row['ac_no'];
		$bank = $row['bank'];
		$bank_code = $row['bank_code'];
		$user_pin = $row['user_pin'];
		$phone_no = $row['phone_no'];
		$type = $row['type'];
		$name = $row['f_name']." ".$row['l_name'];
		if($type == 'D')
			$col = "#FF0000";
		else
			$col = "#000000";	
			
		$total_income = number_format(get_user_total_income($id), 2);	
		$income_receive = number_format(get_user_total_income_receive($id),2);	
	?>	
		<tr class="text-center">
			<td style="color:$col;"><?=$cnt;?></td>
			<td style="color:$col;">
			<form action="../login_check.php" target="_new" method="post">
				<input type="hidden" name="username" value="<?=$username; ?>"   />
				<input type="hidden" name="password" value="<?=$password; ?>"   />
				<input type="submit" name="submit" value="<?=$username; ?>" style="background:none; border:none; cursor:pointer; font-size:10px; min-width:70px; color:<?=$col; ?>" />
			</form>
			</td>
			<td style="color:$col;"><?=$password;?></td>
			<td style="color:$col;"><?=$user_pin;?></td>
			<td style="color:$col;"><?=$name;?></td>
			<td style="color:$col;"><?=$phone_no;?></td>
			<td style="color:$col;"><?=$total_income;?> </td>
			<td style="color:$col;"><?=$income_receive;?> </th>
			<td style="color:$col;"> 
			<?php
			if($type == 'D')
			{
				echo "<B style=\"color:#ff0000; font-size:10pt;\">Block Member</B>";
			}
			else
			{?>
				<form action="index.php?page=user_information" method="post">
					<input type="hidden" name="block_user_id" value="<?=$id;?>"   />
					<input type="submit" name="block_member" value="Member" class="btn btn-primary" />
				</form>
	<?php	}
		echo "</td></tr>";
	}
	echo "</tbody></table></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
		if ($newp>1)
		{ ?>
			<li id="DataTables_Table_0_previous" class="paginate_button previous">
				<a href="<?="index.php?page=user_information&p=".($newp-1);?>">Previous</a>
			</li>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<li class="paginate_button ">
					<a href="<?="index.php?page=user_information&p=$i";?>"><?php print_r("$i");?></a>
				</li>
				<?php 
			}
			else
			{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <li id="DataTables_Table_0_next" class="paginate_button next">
				<a href="<?="index.php?page=user_information&p=".($newp+1);?>">Next</a>
		   </li>
		<?php 
		} 
		?>
		</ul></div>

