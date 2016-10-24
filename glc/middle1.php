<?php
session_start();

$user_class = getInstance('Class_User');
$income_class = getInstance('Class_Income');
$check_user = $user_class->check_user($_SESSION['dennisn_user_id'], $_SESSION['dennisn_username']);
if(empty($check_user)) printf('<script type="text/javascript">window.location="%s/myhub";</script>', GLC_URL);

$val = $_REQUEST['page'];

if($val == 'faq') printf('<script type="text/javascript">window.open("/glc-faq","_blank");</script>');
if($val == 'contact') printf('<script type="text/javascript">window.location="/submit-ticket";</script>');
if($val == 'welcome') printf('<script type="text/javascript">window.location="/glc/index.php";</script>');
$_SESSION['ses_l'][0] = '';

$cn = count($_SESSION['ses_l']);

if(!isset($_REQUEST['set']))
{
	if($_SESSION['ses_l'][$cn-1] != $val)
	{
		$_SESSION['ses_l'][] = $val;
	}
}
else
{
	$cx =  count($_SESSION['ses_l']);
	$new_ses = $_SESSION['ses_l'];
	$nex = array_splice($new_ses, 0,$cx-1);
	unset($_SESSION['ses_l']);
	$_SESSION['ses_l'] = $nex;
}

$n_c = count($_SESSION['ses_l']);

$id = $_SESSION['dennisn_user_id'];
$direct_member = direct_member($id);
$totl_message = inbox_message($id);
$total_commisions = $income_class->get_total_commission($id);
$pending_commissions = $income_class->get_total_pending_commission($id);

$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
while($row = mysqli_fetch_array($query))
{
	$id_user = $row['id_user'];
	$username = $row['username'];
}

$sql = "Select * from menu where menu_file = (Select parent_menu from menu where menu_file = '$val') limit 1";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
while($row = mysqli_fetch_array($query))
{
	$menu = $row['menu'];
}
if($val == '') {$menu = 'Overview'; }
if($val == 'user_profile' || $val == 'change_password') $menu = 'My Settings';
if($val == 'upgrade_account') $menu = '';
// if($val == 'welcome') {$menu = 'Dashboard'; }

?>

<head>
<style>
span.question {
  cursor: pointer;
  display: inline-block;
  width: 16px;
  height: 16px;
  background-color: #89A4CC;
  line-height: 16px;
  color: White;
  font-size: 13px;
  font-weight: bold;
  border-radius: 8px;
  text-align: center;
  position: relative;
}
span.question:hover { background-color: #3D6199; }
div.tooltip {
  background-color: #3D6199;
  color: White;
  position: absolute;
  left: 25px;
  top: -25px;
  z-index: 1000000;
  width: 250px;
  border-radius: 5px;
}
div.tooltip:before {
  border-color: transparent #3D6199 transparent transparent;
  border-right: 6px solid #3D6199;
  border-style: solid;
  border-width: 6px 6px 6px 0px;
  content: "";
  display: block;
  height: 0;
  width: 0;
  line-height: 0;
  position: absolute;
  top: 40%;
  left: -6px;
}
.refer-a-friend {
	width: 100%;
	height: 68px;
}

</style>
</head>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?=$menu;?></h2>
		<ol class="breadcrumb">
			<?php if($menu !== 'Overview') printf('<li><a href="index.php">Overview</a></li>');?>
			<?php if(!empty($menu)) printf('<li><a>%s</a></li>', $menu); ?>
			<?php if(!empty(get_submenu_tit($val))) printf('<li class="active"><strong>%s</strong></li>', get_submenu_tit($val)) ?>
		</ol>
	</div>

	<div class="col-lg-2">
		<div class="title-action">
		<?php
		if($n_c > 1) {?>
		<p>
			<a href="index.php?page=<?=$_SESSION['ses_l'][$n_c-2]?>&set=1" class="btn btn-warning btn-large"><i class="fa fa-chevron-left"></i> Back</a>
		</p>
		<?php
		}
		?>
		</div>
	</div>
</div>
<div class="wrapper wrapper-content">
	<?php if($val == 'welcome' or $val == '' or $val == 'matrix_income' or $val == 'request_transfer' or $val == 'documents') { ?>
	<div class="alert alert-success alert-dismissable fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<!-- <strong>Heads up!</strong> Commissions reflected in your dashboard may not be accurate because of the 14-day(s) moneyback guarantee which will affect your commissions earned. -->
		<span>Understand how to make money quickly with the <strong>GLC Rewards Pay Plan!</strong> <a href="/wp-content/uploads/2016/08/GLCHUB-partner-rewards-payplan-8-16-16-.compressed.pdf" target="_blank">Click here for details &raquo; </a></span>
	</div>
	
	<div class="alert alert-info alert-dismissable fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<!-- <strong>Heads up!</strong> Commissions reflected in your dashboard may not be accurate because of the 14-day(s) moneyback guarantee which will affect your commissions earned. -->
		All Affiliate commissions whether received or approved are subject to clawback due to subscription cancellations and/or chargebacks.
	</div>
	
	<?php } ?>
	<div class="row">
	<?php
	if($val == 'welcome' or $val == '')
	{ ?>
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<!-- <span class="label label-info pull-right"><?=$gross;?></span> -->
					<h5>Total Sales Commissions &nbsp;</h5>
					<p title="This is the total of all commissions earned including
both approved and pending commissions."><span class="question">?</span></p>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><?php echo "$".number_format($total_commisions,2);?></h1>
					<div class="stat-percent font-bold text-info">
						<!--<?php $balper = $wallet_bal*4/1000; print $balper."%"; ?>
						<i class="fa fa-level-up"></i> w-->
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<!-- <span class="label label-success pull-right">YTD</span> -->
					<h5>Pending Commissions &nbsp;</h5>
					<p title="This represents commissions that were just created when
you completed step 3. They are pending administrative
approval of the days movements."><span class="question">?</span></p>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><?php echo "$".number_format($pending_commissions,2); ?></h1>
					<div class="stat-percent font-bold text-success">
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Personally Enrolled Affiliates &nbsp;</h5>
					<p title="This is the number of members
you have personally enrolled."><span class="question">?</span></p>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><a href="/glc/index.php?page=direct_members"><?=$direct_member;?></a></h1>
					<div class="stat-percent font-bold text-navy">
					<!--	<?=$direct_member;?>% <i class="fa fa-level-up"></i> -->
					</div>
				</div>
			</div>
		</div>

		<a href="index.php?page=invite_friends">
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-danger pull-right">Invite Friends</span>
					<h5>Refer a Friend</h5>
				</div>
				<div class="ibox-content no-padding">
					<img src="images/refer-a-friend.png" class="refer-a-friend" alt="Invite friends" />
				</div>
			</div>
		</div>
		</a>

		<div class="col-lg-12">
			<?php
			$file = $val.".php";
			if ($val == '')
			include("data/welcome.php");
			else
			include("data/".$file);
			?>
			
		</div>
		<div class="col-lg-12">
				<div class="footer">
					<div class="pull-right">
						<strong><?=$copyright;?></strong> &copy; <?php echo date('Y') ?>
						<a href="/" target="_blank">Global Learning Center, LLC</a>
					</div>
				</div>
			</div>
	<?php
	}
	else
	{ ?>
		<div class="col-lg-12">
			<?php
			$file = $val.".php";
			if ($val == '')
			include("data/welcome.php");
			else
			include("data/".$file);
			?>
		</div>
		<div class="col-lg-12">
			<div class="footer">
				<div class="pull-right">
					<strong><?=$copyright;?></strong> &copy; <?php echo date("Y") ?>
					Global Learning Center, LLC
				</div>
			</div>
		</div>
	<?php
	}
	?>
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

function total_commissions($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select sum(amount) as total from income where user_id = '$id' and amount > 0");
	$row = mysqli_fetch_array($q);
	$amount = $row[0];
	if($amount > 0)
	{
		return $amount;
	}
	else
	{
		return 0;
	}
}

function pending_commissions($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select sum(amount) as total from income where user_id = '$id' and amount > 0 and approved = 0");
	$row = mysqli_fetch_array($q);
	$amount = $row[0];
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
	$sql = "Select * from menu where menu_file = '$val'";
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
