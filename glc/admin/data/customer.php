<?php
include "../config.php";

$sql = "SELECT * FROM temp_users where type = '' "; 
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$num = mysqli_num_rows($query);
if($num > 0)
{
?>
	<div class="ibox-content">
	<table class="table table-bordered">
		<thead>
		<tr>
			<th>Sr. No.</th>
			<th>User Name</th>
			<th>Email</th>
			<th>Phone</th>
		</tr>
		</thead>
		<tbody>
<?php
	$sr = 1;
	while($row = mysqli_fetch_array($query))
	{
		echo "
			<tr>
				<td>$sr</td>
				<td>".$username = $row['username']."</td>
				<td>".$mail = $row['email']."</td>
				<td>".$phone = $row['phone_no']."</td>
			</tr>";
		$sr++;
	}
	print "</tbody></table></div>";
}
else{ echo "<B style=\"color:#ff0000; font-size:12pt;\">There are no users !</B>"; }

?>