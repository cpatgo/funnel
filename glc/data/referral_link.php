<?php $link = sprintf('%s://www.%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); ?>
<div class="row col-md-6">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5>Invite friends, and friends of friends to join <?php echo $link?></h5>
			<div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-up"></i>
				</a>
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="ibox-content ibox-heading">
			<p>Copy your referral link below &amp; paste it into a new email, then sent it to all of your contacts:</p>
			<a target="_blank" href="<?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>"><?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?></a>
		</div>
		<div class="ibox-content">
			<p>Share your unique referral link with others. When they sign-up, you get credit, and you can start earning unlimited Winback &amp; Virtual Points Sales commissions. <i>see <a href="index.php?page=how_it_works">"How it Works"</a></i>
			</p> 
		<div class="greybox pagination-centered">
		
		</div>
		</div>
	</div>                           
</div>