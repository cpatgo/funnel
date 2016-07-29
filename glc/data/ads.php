<?php
require_once("config.php");
include("condition.php");
$id = $_SESSION['dennisn_user_id'];
?>
<!--<table width=100%>
			<tr>
				<td colspan=2 bgcolor="#BBC9D3" class="glod_hed" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px;"><strong style="color:#000000">Ads </strong></td>
				</tr>
				<tr>
				<td height=10px></td>
			</tr>
		</table>-->

<?php
$sql = "select url,count( * ) AS c from classified_info where user_id = '$id' group by url";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$num = mysqli_num_rows($query);
if($num > 0){
?>
	<div class="ibox-content">
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center"><?=$Sr_No;?></th>
			<th class="text-center"><?=$Url;?></th>
			<th class="text-center"><?=$Click;?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		while($row = mysqli_fetch_array($query))
		{
			$url = $row['url'];
			$cnt = $row['c'];
			print "
				<tr>
					<td>$i</td>
					<td>$url</td>
					<td>$cnt</td>
				</tr>";
		}
		print "</tbody></table></div>";
}
else
{ echo "<B style=\"color:#ff0000; font-size:12pt;\">$There_r_no_ad_here</B>"; }
?>

		 
		