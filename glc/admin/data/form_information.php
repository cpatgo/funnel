<?php

include("condition.php");
include("../function/functions.php");


$newp = $_GET['p'];
$plimit = "15";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM form_data ");
		$totalrows = mysqli_num_rows($query);
		if($totalrows == '')
		{
			print "<font color=\"#FF0000\" size=\"+2\">There Is No Block Member !</font>";
		}
		else{
		print "
		
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=700>
			<tr><td colspan=2 height=30 class=\"message tip\"><strong>Total Form:</strong></td>
					<td colspan=4 class=\"message tip\"><strong>&nbsp; $totalrows</strong></td></tr>
					<tr><td colspan=6>&nbsp;</td></tr>
			<tr><td class=\"message tip\"><strong>Date</strong></td>
				<td class=\"message tip\"><strong>Data1</strong></td>
				<td class=\"message tip\"><strong>Data2</strong></td>
				<td class=\"message tip\"><strong>Data3</strong></td>
				<td class=\"message tip\"><strong>Data4</strong></td>
				<td class=\"message tip\"><strong>Data5</strong></td>
				<tr>";
				
		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
				
			
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }   
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM form_data LIMIT $start,$plimit ");
		while($id_row = mysqli_fetch_array($query))
			{
				$data1 = $id_row['data1'];
				$data2 = $id_row['data2'];
				$data3 = $id_row['data3'];
				$data4 = $id_row['data4'];
				$data5 = $id_row['data5'];
				$date = $id_row['date'];
				
				print "<tr><td class=\"input-medium\">$date</td>
							<td class=\"input-medium\">$data1</td>
							<td class=\"input-medium\">$data2</td>
							<td class=\"input-medium\">$data3</td>
							<td class=\"input-medium\">$data4</td>
							<td class=\"input-medium\">$data5</td>
							</tr>";
			}
			print "<tr><td colspan=6>&nbsp;</td></tr><td colspan=6 height=30px width=400 class=\"message tip\"><strong>";
			if ($newp>1)
			{ ?>
				<a href="<?php echo "index.php?page=form_information&p=".($newp-1);?>">&laquo;</a>
			<?php 
			}
			for ($i=1; $i<=$pnums; $i++) 
			{ 
				if ($i!=$newp)
				{ ?>
					<a href="<?php echo "index.php?page=form_information&p=$i";?>"><?php print_r("$i");?></a>
					<?php 
				}
				else
				{
					 print_r("$i");
				}
			} 
			if ($newp<$pnums) 
			{ ?>
			   <a href="<?php echo "index.php?page=form_information&p=".($newp+1);?>">&raquo;</a>
			<?php 
			} 	
			print "</table>"; 
}