<?php
include "condition.php";
$id = $_SESSION['dennisn_user_id'];
$sql = "select * from  point_wallet where user_id='$id'";
$qur = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$cnt = mysqli_num_rows($qur);
if($cnt > 0)
{ ?>
	<div class="ibox-content">	
	<table class="table table-bordered">
		<thead><tr><th><?=$Your_Add_Points_Info;?></th></tr></thead>
		<tbody>
		<?php
			while($row = mysqli_fetch_array($qur))
				$user_point = $row['user_point'];
			?><tr><td><?=$Your_Points_are;?> <?=$user_point;?></td></tr>
		</tbody>
	</table>
	</div>
<?php	
}		
else
{ echo '<B style=\"color:#ff0000; font-size:12pt;\">$There_hav_no_points</B>'; }
?>