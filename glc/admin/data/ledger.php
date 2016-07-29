<?php
session_start();
require_once("../config.php");
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
?>
<div style="float:left; width:150px; height:60px; text-align:right;">
<?php
	$username = $_POST['username'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
?>	
<form action="index.php?page=ledger" method="post">  
	<input type="hidden" name="start_date" value="<?php print $start_date; ?>" />
	<input type="hidden" name="end_date" value="<?php print $end_date; ?>" />
	<input type="hidden" name="username" value="<?php print $username; ?>" />
	<input type="submit" name="excel_post" value="Download Excel" />
</form>
</div>
<div style="float:right; width:500px; height:60px; text-align:right;">
	<form action="index.php?page=ledger" method="post"> Search : 
	<input type="text" style="width:100px;" placeholder="Start Date" class="input-medium flexy_datepicker_input" name="start_date"  />
	<input type="text" style="width:100px;" placeholder="End Date" class="input-medium flexy_datepicker_input" name="end_date"  />
	<input type="text" style="width:100px;" placeholder="User Id" class="input-medium flexy_datepicker_input" name="username"  />
	<input type="submit" name="search_post" value="Search" />
	</form>
</div>
<?php
$search_que = '';
$search_que2 = '';
if(isset($_POST['search_post']))
{
	$username = $_POST['username'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	if($username != '')
	{
		$search_id = get_new_user_id($username);
		if($search_id == 0)
		{
			print "Incorrect User Id !";
		}	
		else
		{
			$search_que = " where user_id = '$search_id' ";
			$search_que2 = " and user_id = '$search_id' ";
		}	
	}	
	if($start_date != '' and $end_date != '' and $username == '')
	{
		$search_que = " where date >= '$start_date' and date <= '$end_date' ";
		$search_que2 = " and date >= '$start_date' and date <= '$end_date' ";
	}
	if($start_date != '' and $end_date != '' and $search_id > 0)
	{
		$search_que .= " and date >= '$start_date' and date <= '$end_date' ";
		$search_que2 .= " and date >= '$start_date' and date <= '$end_date' ";
	}
}

if(isset($_POST['excel_post']))
{
	$username = $_POST['username'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	if($username != '')
	{
		$search_id = get_new_user_id($username);
		if($search_id == 0)
		{
			print "Incorrect User Id !";
		}	
		else
		{
			$search_que = " where user_id = '$search_id' ";
			$search_que2 = " and user_id = '$search_id' ";
		}	
	}	
	if($start_date != '' and $end_date != '' and $username == '')
	{
		$search_que = " where date >= '$start_date' and date <= '$end_date' ";
		$search_que2 = " and date >= '$start_date' and date <= '$end_date' ";
	}
	if($start_date != '' and $end_date != '' and $search_id > 0)
	{
		$search_que .= " and date >= '$start_date' and date <= '$end_date' ";
		$search_que2 .= " and date >= '$start_date' and date <= '$end_date' ";
	}
	
	
	$cnt = 0;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income $search_que ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{ 
			$income_ladger_status[$cnt][0] = $row['date'];
			$income_ladger_status[$cnt][1] = $row['left_income'];
			$income_ladger_status[$cnt][2] = 0;
			$income_ladger_status[$cnt][3] = 'System';
			$income_ladger_status[$cnt][4] = '-';
			$income_ladger_status[$cnt][5] = '-';
			$cnt++;
		}
	}	
	
	$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from payment_information where mode = 1 $search_que2 ");
	$nums = mysqli_num_rows($quer);
	if($nums != 0)
	{
		while($rrr = mysqli_fetch_array($quer))
		{ 
			$income_ladger_status[$cnt][0] = $rrr['date'];
			$income_ladger_status[$cnt][1] = 0;
			$income_ladger_status[$cnt][2] = $rrr['amount'];
			$income_ladger_status[$cnt][3] = 'Withdrawal';
			$income_ladger_status[$cnt][4] = $rrr['pay_information'];
			$income_ladger_status[$cnt][5] = $rrr['tds']." INR ";
			$cnt++;
		}
	}	
	
	$que = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher $search_que ");
	$numr = mysqli_num_rows($que);
	if($numr != 0)
	{
		while($rrrr = mysqli_fetch_array($que))
		{ 
			$income_ladger_status[$cnt][0] = $rrrr['date'];
			$income_ladger_status[$cnt][1] = 0;
			$income_ladger_status[$cnt][2] = $rrrr['epin_amount'];
			$income_ladger_status[$cnt][3] = 'e-Voucher Generate';
			$income_ladger_status[$cnt][4] = '-';
			$income_ladger_status[$cnt][5] = '-';
			$cnt++;
		}
	}	
	
	$sorted_act = multid_sort($income_ladger_status, 0);
	
	$totan_inc2 = count($sorted_act);
	
	if($totan_inc2 > 0)
	{
		$save_excel_file_path = "../UserInfo/";
		$unique_name = "UserInformation".time();
		$sep = "\t"; 
		$fp = fopen($save_excel_file_path.$unique_name.".xls", "w"); 
		$insert = ""; 
		$insert_rows = ""; 
		$result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users ");              
		
		$insert_rows.="Sr. No. \t Date \t Particular \t Cheque No. \t Cr. \t Tax. \t Dr. \t Balance \t";
		
		$srno = 1;
		$insert_rows.="\n";
		fwrite($fp, $insert_rows);
				
		$left_amount = 0;
		for($i = 0; $i < $totan_inc2; $i++)
		{
			$insert = "";
			$j = $i+1;
			$date = $sorted_act[$i][0];
			$cr_amount = $sorted_act[$i][1];
			$dr_amount = $sorted_act[$i][2];
			$particular = $sorted_act[$i][3];
			$cheque_no = $sorted_act[$i][4];
			$tax_amount = $sorted_act[$i][5];
			
			if($cr_amount > 0)
				$left_amount = $left_amount+$cr_amount;
			if($dr_amount > 0)
				$left_amount = $left_amount-$dr_amount;
				
			$insert .= $j.$sep;
			$insert .= $date.$sep;
			$insert .= $particular.$sep;
			$insert .= $cheque_no.$sep;
			$insert .= $cr_amount.$sep;
			$insert .= $tax_amount.$sep;
			$insert .= $dr_amount.$sep;
			$insert .= $left_amount.$sep;
				
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
}


$cnt = 0;
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income $search_que ");
$num = mysqli_num_rows($query);
if($num != 0)
{
	while($row = mysqli_fetch_array($query))
	{ 
		$income_ladger_status[$cnt][0] = $row['date'];
		$income_ladger_status[$cnt][1] = $row['left_income'];
		$income_ladger_status[$cnt][2] = 0;
		$income_ladger_status[$cnt][3] = 'System';
		$income_ladger_status[$cnt][4] = '-';
		$income_ladger_status[$cnt][5] = '-';
		$cnt++;
	}
}	

$quer = mysqli_query($GLOBALS["___mysqli_ston"], "select * from payment_information where mode = 1 $search_que2 ");
$nums = mysqli_num_rows($quer);
if($nums != 0)
{
	while($rrr = mysqli_fetch_array($quer))
	{ 
		$income_ladger_status[$cnt][0] = $rrr['date'];
		$income_ladger_status[$cnt][1] = 0;
		$income_ladger_status[$cnt][2] = $rrr['amount'];
		$income_ladger_status[$cnt][3] = 'Withdrawal';
		$income_ladger_status[$cnt][4] = $rrr['pay_information'];
		$income_ladger_status[$cnt][5] = $rrr['tds']." INR ";
		$cnt++;
	}
}	

$que = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher $search_que ");
$numr = mysqli_num_rows($que);
if($numr != 0)
{
	while($rrrr = mysqli_fetch_array($que))
	{ 
		$income_ladger_status[$cnt][0] = $rrrr['date'];
		$income_ladger_status[$cnt][1] = 0;
		$income_ladger_status[$cnt][2] = $rrrr['epin_amount'];
		$income_ladger_status[$cnt][3] = 'e-Voucher Generate';
		$income_ladger_status[$cnt][4] = '-';
		$income_ladger_status[$cnt][5] = '-';
		$cnt++;
	}
}	

$sorted_act = multid_sort($income_ladger_status, 0);

$totan_inc2 = count($sorted_act);

if($totan_inc2 > 0)
{
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=700>
			<tr>
			<td class=\"message tip\"><strong>Sr. No.</strong></th> 
			<td width=150 class=\"message tip\"><strong>Date</strong></th> 
			<td width=150 class=\"message tip\"><strong>Particular</strong></td>
			<td width=150 class=\"message tip\"><strong>Cheque No.</strong></td>
			<td width=150 class=\"message tip\"><strong>Cr. </strong></th> 
			<td width=150 class=\"message tip\"><strong>Tax</strong></th> 
			<td width=150 class=\"message tip\"><strong>Dr. </strong></td>
			<td width=150 class=\"message tip\"><strong>Balance</strong></td>
			</tr>";
			
	$left_amount = 0;
	$peg = $_REQUEST['p'];
	$total_rows = 80;
	if($peg == '' or $peg == 1)
	{
		$start = 0;
		if($total_rows < $totan_inc2)
			$end = $total_rows;
		else
			$end = $totan_inc2;	
	}	
	else
	{
		$start = (($peg-1)*$total_rows);	
		$end_rws = (($peg-1)*$total_rows)+$total_rows;
		if($end_rws < $totan_inc2)
			$end = $end_rws;
		else
			$end = $totan_inc2;	
	}		
			
	for($i = $start; $i < $end; $i++)
	{
		$j = $i+1;
			$date = $sorted_act[$i][0];
			$cr_amount = $sorted_act[$i][1];
			$dr_amount = $sorted_act[$i][2];
			$particular = $sorted_act[$i][3];
			$cheque_no = $sorted_act[$i][4];
			$tax_amount = $sorted_act[$i][5];
			
			if($cr_amount > 0)
				$left_amount = $left_amount+$cr_amount;
			if($dr_amount > 0)
				$left_amount = $left_amount-$dr_amount;
			
			print "<tr align=\"center\"><td style=\"width:60px;\" class=\"input-small\">$j</td>
			<td width=150 class=\"input-small\">$date</td>
			<td width=150 class=\"input-small\">$particular</td>
			<td width=150 class=\"input-small\">$cheque_no</td>
			<td width=150 class=\"input-small\">$cr_amount INR</td>
			<td width=150 class=\"input-small\">$tax_amount</td>
			<td width=150 class=\"input-small\">$dr_amount INR</td>
			<td width=150 class=\"input-small\">$left_amount INR</td>
			</tr>";
	}
	print"
		<tr>
		<td colspan=8>&nbsp;</td>
		</tr>
		<tr>
		<td colspan=9 class=\"message tip\" style=\"text-align:left; padding-left:10px;\"><strong>"; 
		for($k = 1; $k <= ($totan_inc2/$total_rows)+1; $k++)
		{ 
			if($peg == $k)  
				print $k; 
			else  { ?>
				<a href="index.php?page=ledger&p=<?php print $k; ?>"><?php print $k; ?></a>
	<?php			}
		}
		print "</strong></td>
		</tr>
	</table>";
}		
else{ print "There is No information to show !"; }


function multid_sort($arr, $index) {
    $b = array();
    $c = array();
    foreach ($arr as $key => $value) {
        $b[$key] = $value[$index];
    }

    asort($b);

    foreach ($b as $key => $value) {
        $c[] = $arr[$key];
    }

    return $c;
}



