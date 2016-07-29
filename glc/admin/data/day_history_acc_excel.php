<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");

$date = $_SESSION['date'];
	
	$sqli = "select t1.date as Date , t1.account as Account_Info , t1.cr as Credit , t1.dr as Debit , t1.wallet_balance as Wallet_Balance , t2.username Username from account t1 inner join users t2 on t1.user_id = t2.id_user where t1.date = '$date' ";
	
	//group by t1.user_id;
?>
<div class="ibox-content">	
<?php	
	$sql = $sqli;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		$file_name = time()."Day_History".date('Y-m-d');
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		$fp = fopen('users_files/'.$file_name.'.xls', "w");
		$schema_insert = "";
		$schema_insert_rows = "";
		//start of printing column names as names of MySQL fields

		//start of adding column names as names of MySQL fields
	//	$schema_insert_rows = "Transaction Type". "\t";
		for ($i = 0; $i < (($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false); $i++)
		{
				
				$schema_insert_rows.=strtoupper(str_replace("_"," ",((($___mysqli_tmp = mysqli_fetch_field_direct($result, $i)->name) && (!is_null($___mysqli_tmp))) ? $___mysqli_tmp : false))) . "\t";
		}
		$schema_insert_rows.="\n";
		//echo $schema_insert_rows;
		fwrite($fp, $schema_insert_rows);
		//end of adding column names
		//start while loop to get data
		while($row = mysqli_fetch_row($result))
		{
		//set_time_limit(60); //
			$schema_insert = "";
		//	$schema_insert = strtoupper("r")."\t";
			for($j=0; $j<(($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false);$j++)
			{
				if($j == 8)
				{ $schema_insert .= strtoupper("AC - ".strip_tags("$row[$j]").$sep); }
				elseif($j == 11)
				{
					if($row[$j] > 0)
					$schema_insert .= strtoupper(strip_tags("Paid").$sep);
					else
					$schema_insert .= strtoupper(strip_tags("Unpaid").$sep);
				}
				elseif($j == 3)
				{ $schema_insert .= strtoupper(strip_tags(get_user_name($row[$j])).$sep); }
				else
				{
					if(!isset($row[$j]))
					$schema_insert .= strtoupper("NULL".$sep);
					elseif ($row[$j] != "")
					$schema_insert .= strtoupper(strip_tags("$row[$j]").$sep);
					else
					$schema_insert .= strtoupper("".$sep);
				}
			}
			$schema_insert = (str_replace($sep."$", "", $schema_insert));
			
			//this corrects output in excel when table fields contain \n or \r
			//these two characters are now replaced with a space
			
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\n";
			//$schema_insert = (trim($schema_insert));
			//print $schema_insert .= "\n";
			//print "\n";
			fwrite($fp, $schema_insert);
		}
		fclose($fp);
		print "<B style=\"color:#167C1E; font-size:12pt;\">Excel File Created Successfully !!</B>";
	}	
	else
	{ print "<B style=\"color:#FF0000; font-size:12pt;\">There is No users to write !</B>"; }
	
	?>
	<p style=" font-weight:bold; font-size:18px; margin-top:30px;">
		<a style="color:#333368;" href="index.php?page=day_history_acc">Back</a>
	</p>
	<p style="font-weight:bold; font-size:18px;">	
		click here for download file = 
		<a href="users_files/<?=$file_name;?>.xls"><?=$file_name; ?>.xls</a>
	</p>
</div>	
