<?php
include("../function/functions.php");

$sql = "SELECT * FROM users u
       LEFT JOIN user_membership m ON u.id_user = m.user_id";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$membership_class = getInstance('Class_Membership');
?>
<div class="ibox-content">	
<table class="table table-striped table-bordered table-hover dataTables">
	<thead>
	<tr>
		<th class="text-center">Id</th>
		<th class="text-center">Username</th>
		<th class="text-center">Name</th>		
		<th class="text-center">Joining date</th>
		<th class="text-center">Enroller</th>
		<th class="text-center">Qualified<br >Referrals</th>
		<th class="text-center">Total<br >Referrals</th>
		<th class="text-center">Type</th>
		<th class="text-center">Membership</th>
		<th class="text-center">Level</th>
		<th class="text-center">Actions</th>
	</tr>
	</thead>
<?php
	while($row = mysqli_fetch_array($query))
	{	
		$srno++;	
		$id = $row['id_user'];
		$username = get_user_name($id);
		$real_parent = get_user_name($row['real_parent']);
		if($real_parent == '')
			$real_parent = "Top Member";
		else
			$real_parent = $real_parent;
		$name = $row['f_name']." ".$row['l_name'];
		$type = $membership_class->is_qualified($row['id_user'], $row['time']);
		$date = $row['date'];
		$phone_no = $row['phone_no'];
		$db_time = $row['time'];
		$time = date('m/d/Y H:i:s' ,  $db_time );
		$qreferrals = get_paid_member($id);
		$referrals = get_referrals($id);
		//memberships
		switch ($row['initial']) {
			case 1:
				$initial = "Free";
				break;
			case 2:
				$initial = "Executive";
				break;
			case 3:
				$initial = "Leadership";
				break;
			case 4:
				$initial = "Professional";
				break;
			case 5:
				$initial = "Masters";
				break;
			case 6:
				$initial = "Founder";
				break;
			default:
			        $initial = "Unknown";
				break;
		}
		switch ($row['current']) {
			case 1:
				$current = "Free";
				break;
			case 2:
				$current = "Executive";
				break;
			case 3:
				$current = "Leadership";
				break;
			case 4:
				$current = "Professional";
				break;
			case 5:
				$current = "Masters";
				break;
			case 6:
				$current = "Founder";
				break;
			default:
			        $initial = "Unknown";
				break;
		}
		?>
		<tr class="text-center">
			<td><?php echo $id; ?></td>
			<td><a href="index.php?page=member_details&user_id=<?php echo $id; ?>"><?php echo $username; ?></a></td>
			<td><?php echo $name; ?></td>
			<td><?php echo $time; ?></td>
			<td><?php echo $real_parent; ?></td>
			<td><?php echo $qreferrals; ?></td>
			<td><?php echo $referrals; ?></td>
			<td><?php echo $type; ?></td>
			<td>
				<p><span class="label"><?php echo $initial; ?></span></p>
				<p><span class="label label-info"><?php echo $current; ?></span></p>
			</td>
			<td> <?php echo get_levels($id); ?></td>
			<td> 
				<a href="index.php?page=edit_profile&username=<?php echo $username; ?>&submit=submit"><i class="fa fa-edit"></i> Edit</a>&nbsp;&nbsp;
				<a href="index.php?page=member_details&user_id=<?php echo $id; ?>"><i class="fa fa-search"></i> Details</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
