<?php
session_start();
require_once("../config.php");
$id = $_REQUEST['user_id'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select username, f_name, l_name, country, state, city, time, payment_type, (SELECT membership from memberships where id = initial) as initial, (SELECT membership from memberships where id = current) as current from users u LEFT JOIN user_membership um ON u.id_user = um.user_id WHERE id_user = '$id'");
$num = mysqli_num_rows($query);
if($num == 0)
{
	print "Member do not exist!";
}	
else
{	
	$row = mysqli_fetch_array($query);
}
?>
<div class="row">
	<div class="col-lg-3">	
		<a href="mail_compose.html" class="btn btn-block btn-default">Memeber Profile</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Member Enroller</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Member Enrollees</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Member Commisions</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Member Wallet</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Pay Stage 1</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Pay Stage 2</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Pay Stage 3</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Pay Stage 4</a>
		<a href="mail_compose.html" class="btn btn-block btn-primary">Pay Stage 5</a>
	</div>
	<div class="col-lg-9">	
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Memeber Profile</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<table class="table table-bordered">
					<thead><tr><th colspan="3">Member Details :</th></tr></thead>
					<tbody>
						<tr>
							<td>Username</td>
							<td><?php echo $row['username']; ?></td>
						</tr>
						<tr>
							<td>First Name</td>
							<td><?php echo $row['f_name']; ?></td>
						</tr>
						<tr>
							<td>Last Name</td>
							<td><?php echo $row['l_name']; ?></td>
						</tr>
					</tbody>
					<thead><tr><th colspan="3">Contact Details :</th></tr></thead>
					<tbody>
						<tr>
							<td>Country</td>
							<td><?php echo $row['country']; ?></td>
						</tr>
						<tr>
							<td>State</td>
							<td><?php echo $row['state']; ?></td>
						</tr>
						<tr>
							<td>City</td>
							<td><?php echo $row['city']; ?></td>
						</tr>
					</tbody>
					<thead><tr><th colspan="3">Membership:</th></tr></thead>
					<tbody>
						<tr>
							<td>Registration Date</td>
							<td><?php echo date("m/d/Y h:i:s;", $row['time']); ?></td>
						</tr>
						<tr>
							<td>Reg. Membership</td>
							<td><?php echo $row['initial']; ?></td>
						</tr>
						<tr>
							<td>Current Membership</td>
							<td><?php echo $row['current']; ?></td>
						</tr>
						<tr>
							<td>Payment Type</td>
							<td><?php echo $row['payment_type']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Member Enroller</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Member Enrollees</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Member Commisions</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Member Wallet</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Pay Stage 1</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Pay Stage 2</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Pay Stage 3</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Pay Stage 4</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
		<div class="ibox float-e-margins m-b">
			<div class="ibox-title">
				<h5>Pay Stage 5</h5> 
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				
			</div>
		</div>
	</div>
</div>