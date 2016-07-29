<?php
session_start();
include("condition.php");

?>
		<table width="600" border="0">

<?php
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_pin where mode = 0 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		print "<tr>
			<td>e-Voucher</td>
			<td>Date</td>
			<td>Product Id
			
		  </tr>";
		while($row = mysqli_fetch_array($query))
		{
			$epin = $row['epin'];
			$date = $row['date'];
			$product_id = $row['product_id'];
			
			
			print "<tr>
					<td>$epin</small></td>
					<td>$date</small></td>
					<td>$product_id</small></td>
					
				  </tr>";
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
		