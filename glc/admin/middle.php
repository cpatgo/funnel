<?php
// session_start();
//ini_set("display_errors",'off');
$val = $_REQUEST['page'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM admin");
while($row = mysqli_fetch_array($query))
{
	$id_user = $row['id_user'];
	$username = $row['username'];
}

$sql = "Select * from admin_menu where menu_file = (Select parent_menu from admin_menu where menu_file = '$val') limit 1";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
while($row = mysqli_fetch_array($query))
{
	$menu = $row['menu'];
}
if($val == '')
{$menu = 'Dashboard';}
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?=$menu;?></h2>
		<ol class="breadcrumb">
			<?php if($menu !== 'Dashboard') printf('<li><a href="index.php">Dashboard</a></li>');?>
			<li><a><?=$menu;?></a></li>
			<?php if(!empty(get_submenu_tit($val))) printf('<li class="active"><strong>%s</strong></li>', get_submenu_tit($val)) ?>
		</ol>
	</div>
	<div class="col-lg-2">
		<div class="widget style1">
				<div class="row">
					<div class="col-xs-4 text-center">
						<i class="fa fa-calendar fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">
						<span> Today </span>
						<h2 class="font-bold"><?php echo date("m/d/Y", time()); ?></h2>
					</div>
				</div>
		</div>
	</div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<?php
			$file = $val.".php";
			if ($val == '')
			include("data/projects_summary.php");
			else
			include("data/".$file);
			
			?>
			<div class="footer">
				<div class="pull-right">
					<strong>Copyright</strong> &copy; <?php echo date('Y'); ?> 
					Global Learning Center, LLC
				</div>
			</div>
		</div>
	</div>
</div>
<?php

function direct_member($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE real_parent = '$id' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		return $num;
	}
	else{return 0;}
}

function inbox_message($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE receive_id = '$id'");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		return $num;
	}
	else{return 0;}
}

function wallet_balance($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($q))
		$amount = $row['amount'];
	if($amount > 0)
	{
		return $amount;
	}
	else
	{
		return 0;
	}
}

function total_investment($id)
{
	$sql = "select sum(update_fees) as fees from reg_fees_structure where user_id = '$id'";
	$q = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	while($row = mysqli_fetch_array($q))
		$amount = $row['fees'];
	if($amount > 0)
	{
		return $amount;
	}
	else
	{
		return 0;
	}
}



function get_submenu_tit($val)
{
	$sql = "Select * from admin_menu where menu_file = '$val'";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	while($row = mysqli_fetch_array($query))
	{
		$menu_title = $row['menu'];
		$menu_file = $row['menu_file'];
	}
	if($menu_title == '')
	return "";
	else
	return $menu_title;
}
?>
