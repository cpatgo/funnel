<?php

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";
?>
<p></p>
<div style="width:90%; text-align:right;height:70px;">
<div style="width:25%; text-align:right; float:left;  height:70px;">
<form action="index.php?page=tds_report" method="post">
<input type="submit" name="Excel" value="Download Excel" class="normal-button" />
</form>
</div>
<div style="width:65%; text-align:right; float:right; height:70px;">
<form action="index.php?page=user_information" method="post"><font style="color:#002953; font-style:normal;">User Id : </font>
<input type="text" name="search_username"  />
<input type="submit" name="Search" value="Search" class="normal-button" />
</form>
</div>
</div>
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
	
		$insert_rows.="Sr. No. \t PAN of the Deduction \t Name of the Deductee \t Father Name \t Total Amount \t T.D.S. \t Payment Made \t Phone No. \t";
	
	$srno = 1;
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$yy++;
		$id = $row['id_user'];
		$username = $row['username'];
		$name = $row['f_name']." ".$row['l_name'];
		$address = $row['address'];
		$father_name = $row['father_name'];
		$phone_no = $row['phone_no'];
		$pan_no = $row['pan_no'];
		$delivered_info = $row['delivered_info'];
		
		
		$que = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(amount) , SUM(tds) , pay_mode , pay_information from payment_information where user_id = '$id' and mode = 1 ");
		while($rrr = mysqli_fetch_array($que))
		{
			$income = $rrr[0];
			$tds = $rrr[1];
			$pay_mode = $rrr['pay_mode'];
			$pay_information = $rrr['pay_information']; 	
		}
		
		$psy_mode_info = $pay_mode." ".$pay_information;

		$insert .= $srno.$sep;
		$insert .= $pan_no.$sep;
		$insert .= $name.$sep;
		$insert .= $father_name.$sep;
		$insert .= $income.$sep;
		$insert .= $tds.$sep;
		$insert .= $psy_mode_info.$sep;
		$insert .= $phone_no.$sep;
			
		$srno++;

		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	$full_path = "../UserInfo/".$unique_name.".xls";
	
	print "Excel File Download Successfully !!";
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

$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users ");
$totalrows = mysqli_num_rows($query);
print "

	<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=100%>
	
	<tr>
	<th class=\"message tip\"><strong>Sr.No.</strong></th>
	<th class=\"message tip\"><strong>PAN of the Deduction </strong></th>
	<th class=\"message tip\"><strong>Name of the Deductee </strong></th>
	<th class=\"message tip\"><strong>Father Name </strong></th>
	<th class=\"message tip\"><strong>Total Amount</strong></th>
	<th class=\"message tip\"><strong>T.D.S.</strong></th>
	<th class=\"message tip\"><strong>Payment Made</strong></th>
	<th class=\"message tip\"><strong>Phone No</strong></th>
	</tr>";
	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$yy = 0;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query))
	{
		$yy++;
		$id = $row['id_user'];
		$username = $row['username'];
		$name = $row['f_name']." ".$row['l_name'];
		$address = $row['address'];
		$father_name = $row['father_name'];
		$phone_no = $row['phone_no'];
		$pan_no = $row['pan_no'];
		$delivered_info = $row['delivered_info'];
		
		
		$que = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(amount) , SUM(tds) , pay_mode , pay_information from payment_information where user_id = '$id' and mode = 1 ");
		while($rrr = mysqli_fetch_array($que))
		{
			$income = $rrr[0];
			$tds = $rrr[1];
			$pay_mode = $rrr['pay_mode'];
			$pay_information = $rrr['pay_information']; 	
		}
	
		print "<tr>
		<td class=\"input-medium\" style=\"padding-left:5px\">$yy</td>
		<td class=\"input-medium\" style=\"padding-left:5px\">$pan_no</td>
		<td class=\"input-medium\" style=\"padding-left:5px\">$name</td>
		<td class=\"input-medium\" style=\"padding-left:5px; width:120px;\">$father_name</td>
		<td class=\"input-medium\" style=\"padding-left:5px\">$income</small></td>
		<td class=\"input-medium\" style=\"padding-left:5px; width:120px;\">$tds</td>
		<td class=\"input-medium\" style=\"text-align:center;\">$pay_mode <br />$pay_information</small></td>
		<td class=\"input-medium\" style=\"text-align:center;\">$phone_no</small></td></tr>"; 
	}
	print "<tr><td colspan=4>&nbsp;</td></tr><td style=\"text-align:left; padding-left:5px; padding-right:10px\" colspan=8 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=tds_report&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=tds_report&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=tds_report&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr></table>";

if(isset($_POST['Excel']))
{
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"$full_path\"";
	echo "</script>";
}

?>
