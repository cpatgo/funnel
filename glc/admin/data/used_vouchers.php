<?php
session_start();


$newp = $_GET['p'];
$plimit = "20";


print "<table width=\"100%\" border=\"0\">";

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_voucher where user_id != 0 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{
		print "
		<tr>
			<th height=30 class=\"message tip\">Total Voucher :</th>
			<th colspan=2 class=\"message tip\">$totalrows</th>
		</tr>
		<tr>
			<th colspan=3>&nbsp;</th>
		</tr>
		<tr>
			<th height=30 class=\"message tip\">Voucher</th>
			<th class=\"message tip\">Date</th>
			<th class=\"message tip\">Voucher Type</th>
			
		  </tr>";
		  
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }    
		  
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_voucher where user_id != 0 LIMIT $start,$plimit ");  
		while($row = mysqli_fetch_array($query))
		{
			$epin = $row['voucher'];
			$date = $row['date'];
			$product_type = $row['type'];
			if($product_type == 'A')
				$type = "TVI";
			else
				$type = "Uni TVI";	
			
			print "<tr>
					<td class=\"input-medium\" style=\"padding-left:80px\">$epin</small></td>
					<td class=\"input-medium\" style=\"padding-left:80px\">$date</small></td>
					<td class=\"input-medium\" style=\"padding-left:90px\">$type</small></td>
					
				  </tr>";
		}
		print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=4 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=used_vouchers&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=used_vouchers&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=used_vouchers&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
	}
	else 
	{
		print "<tr>
					<td colspan=3>There is no e-Voucher to show !</td>		  
				</tr>";
	}
	print "</table>";
?>
</body>
</html>				
		