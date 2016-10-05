<nav class="navbar-default navbar-static-side" role="navigation">
	<?php 
		if($_SERVER['HTTP_HOST'] !== 'glchub.com' && $_SERVER['HTTP_HOST'] !== 'www.glchub.com'):
			printf('<div class="alert alert-danger" style="text-align:center;font-size:20px;margin-bottom:0;"><b>STAGING</b></div>');
		endif;
	?>
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header no-bg"><img src="../images/glc-logo-white-small.png"></li>
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
					<img alt="image" class="img-circle" src="img/profile_small.jpg" />
					 </span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<span class="clear"> 
						<span class="block m-t-xs"> 
							<strong class="font-bold">Admin</strong>
					 	</span> 
						<span class="text-muted text-xs block">Admin <b class="caret"></b></span> 
					</span> 
					</a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<!--<li><a href="index.php?page=user_profile">Profile</a></li>
						<li><a href="index.php?page=edit_profile">Settings</a></li>
						<li><a href="index.php?page=inbox">Mailbox</a></li>
						<li class="divider"></li>-->
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>
				<div class="logo-element">IN+</div>
			</li>
			<?php include "left_menu.php"; ?>
		</ul>
	</div>
</nav>