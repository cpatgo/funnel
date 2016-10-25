<?php
session_start();
$id = $_SESSION['dennisn_user_id'];
?>
<script>var siteUrl = "<?php echo GLC_URL; ?>";</script>
<style type="text/css">
	.blue-btn{
		background-color: #2895f1 !important;
		border-color: #2895f1 !important;
	}
	.nav > li.active {
	    border-left: 4px solid #2895f1 !important;
	    background: #293846;
	 }
</style>
<div class="row border-bottom">
	<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<a class="navbar-minimalize minimalize-styl-2 btn btn-primary blue-btn" href="#"><i class="fa fa-bars"></i> </a>
			<!--<form role="search" class="navbar-form-custom" method="post" action="search_results.html">
				<div class="form-group">
					<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
				</div>
			</form>-->
			<!-- <div style="margin:11px 0 0 20px; float:left">
			<form role="search" class="navbar-form-custom" method="post" action="" style="height:25px;">
				<input type="submit" id="en" value="English" name="lang" style="font-size:0; background:url(img/lang/en.png); height:25px; width:32px; cursor:pointer;" title="English">
				<input type="submit" id="fr" value="French" name="lang" style="font-size:0; background:url(img/lang/fr.png); height:25px; width:32px; cursor:pointer;" title="French">
				<input type="submit" id="sp" value="Spanish" name="lang" style="font-size:0; background:url(img/lang/sp.png); height:25px; width:32px; cursor:pointer;" title="Spanish">
			</form>
			</div> -->
		</div>
		<ul class="nav navbar-top-links navbar-right">
			<li>
				<span class="m-r-sm text-muted welcome-message">
					<?=$welcome_user;?> <B><?=$_SESSION['dennisn_user_full_name'];?></B>
				</span>
			</li>
			<?php
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM message WHERE receive_id = '$id' order by id desc");
				$num = mysqli_num_rows($query);
			?>
			<li class="dropdown">
				<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
					<i class="fa fa-envelope"></i>  
					<span class="label label-warning"><?=$num;?></span>
				</a>
				<ul class="dropdown-menu dropdown-messages">
				<?php
					if($num > 0)
					{
						while($row = mysqli_fetch_array($query))
						{
							$id  = $row['id'];
							$receive_id  = $row['receive_id'];
							$title = $row['title'];
							$message = $row['message'];
							$message_date = $row['message_date'];
							$time = $row['time'];
							
							$datetime1 = new DateTime();
							$datetime2 = new DateTime($time);
							$interval = $datetime1->diff($datetime2);
							$interval->format('%Y-%m-%d %H:%i:%s');
							$days = $interval->format('%d');
							$hour = $interval->format('%H');
							$minute = $interval->format('%i');
							$clock_days = '';
							$clock_hour = '';
							$clock_minute = '';
							
							if($days != 0)
							$clock_days = $days." Days";
							if($hour != 0)
							$clock_hour = $hour." Hour";
							if($minute != 0)
							$clock_minute = $minute." Min";
					?>	
						<li class="divider"></li>
				<?php 	}  ?>
						<li>
							<div class="text-center link-block">
								<a href="index.php?page=inbox">
									<i class="fa fa-envelope"></i><B><?=$Read_All_Mesage;?></B>
								</a>
							</div>
						</li>
				<?php
					}
					else 
					{ ?><li><div class="media-body"><?=$No_info_to_show;?></div></li><?php } ?>
					<!--<li>
						<div class="dropdown-messages-box">
							<a href="profile.html" class="pull-left">
								<img alt="image" class="img-circle" src="img/a4.jpg">
							</a>
							<div class="media-body ">
								<small class="pull-right text-navy">5h ago</small>
								<strong>Chris Johnatan Overtunk</strong> started following 
								<strong>Monica Smith</strong>. <br>
								<small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
							</div>
						</div>
					</li>
					<li class="divider"></li>
					<li>
						<div class="dropdown-messages-box">
							<a href="profile.html" class="pull-left">
								<img alt="image" class="img-circle" src="img/profile.jpg">
							</a>
							<div class="media-body ">
								<small class="pull-right">23h ago</small>
								<strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
								<small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
							</div>
						</div>
					</li>
					<li class="divider"></li>-->
				</ul>
			</li>
			
			<!--<li class="dropdown">
				<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
					<i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
				</a>
				<ul class="dropdown-menu dropdown-alerts">
					<li>
						<a href="mailbox.html">
							<div>
								<i class="fa fa-envelope fa-fw"></i> You have 16 messages
								<span class="pull-right text-muted small">4 minutes ago</span>
							</div>
						</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="profile.html">
							<div>
								<i class="fa fa-twitter fa-fw"></i> 3 New Followers
								<span class="pull-right text-muted small">12 minutes ago</span>
							</div>
						</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="grid_options.html">
							<div>
								<i class="fa fa-upload fa-fw"></i> Server Rebooted
								<span class="pull-right text-muted small">4 minutes ago</span>
							</div>
						</a>
					</li>
					<li class="divider"></li>
					<li>
						<div class="text-center link-block">
							<a href="notifications.html">
								<strong>See All Alerts</strong>
								<i class="fa fa-angle-right"></i>
							</a>
						</div>
					</li>
				</ul>
			</li>-->
			<li><a href="/myhub"><span class="label label-primary">My Hub</span> </a></li>
			<li><a href="logout.php"><i class="fa fa-sign-out"></i> <?=$logout;?></a></li>
		</ul>
	</nav>
</div>