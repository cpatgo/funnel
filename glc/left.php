<script>var siteUrl = "<?php echo GLC_URL; ?>";</script>
<?php 
	$user = getInstance('Class_User');
	$profile = $user->get_user($_SESSION['dennisn_user_id']);
	$wp_membership = $user->wp_membership();
?>
<nav class="navbar-default navbar-static-side" role="navigation">
	<?php 
		if($_SERVER['HTTP_HOST'] !== 'glchub.com' && $_SERVER['HTTP_HOST'] !== 'www.glchub.com'):
			printf('<div class="alert alert-danger" style="text-align:center;font-size:20px;margin-bottom:0;"><b>STAGING</b></div>');
		endif;
	?>
		<ul class="nav" id="side-menu">
			<li class="nav-header no-bg">
				<img src="images/glc-logo-white-small.png">
			</li>
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
					<?php if(!empty($profile[0]['user_img'])): ?>
						<img alt="image" class="img-circle" style="width: 48px;height: 48px" src="<?php printf('pictures/%s', $profile[0]['user_img']);?>" />
					<?php else: ?>
						<img alt="image" class="img-circle" src="img/profile_small.jpg" />
					<?php endif; ?>
					<?php if($wp_membership === 'Founder') printf('<img id="founder-badge" src="%s/glc/images/glc-founder.png">', GLC_URL); ?>
					 </span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<span class="clear"> 
						<span class="block m-t-xs"> 
							<strong class="font-bold"><?=$_SESSION['dennisn_username'];?> <br /><i><?=$_SESSION['dennisn_user_full_name'];?></i></strong>
					 	</span> 
						<span class="text-muted text-xs block"><?=$Settings;?> <b class="caret"></b></span> 
					</span> 
					</a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<li><a href="index.php?page=user_profile"><?=$Profile;?></a></li>
						<li><a href="index.php?page=change_password"><?=$change_password;?></a></li>
						<li class="divider"></li>
						<li><a href="logout.php"><?=$logout;?></a></li>
					</ul>
				</div>
				<div class="logo-element">IN+</div>
			</li>
			<?php include "left_menu.php"; ?>
			<li>
				<a href="/myhub/">
					<i class="fa fa-video-camera"></i>
					<span class="nav-label">GLC Hub</span>
				</a> 
			</li>
		</ul>
	</div>
</nav>