<style type="text/css">
.fontie{
    font-size: 1vw;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: large;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: x-large;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: large;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: 18px;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: 24px;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: large;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-weight: bold;
}
.row.referafriend .row.col-md-4 .ibox.float-e-margins .ibox-content.ibox-heading h4 a {
	font-size: x-large;
}


</style>
<?php $link = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); ?>
<div class="row referafriend">
	<div class="col-md-4">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>How it Works</h5>
			</div>
			<div>
				<div class="ibox-content no-padding border-left-right">
					<img alt="image" class="img-responsive" src="img/invite_friend.jpg">
				</div>
				<div class="ibox-content profile-content">
					<h4>Welcome to one of the most dynamic self directed learning site and lucrative referral pay systems on the Internet.</h4>
					<p>
					   This is a simple 2x2 Follow Me Matrix that can generate substantial ongoing income for YOU
					</p>
				</div>
			</div>
		</div>
    </div>
    <div class="col-md-4">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				Invite friends
			</div>
			<div class="ibox-content ibox-heading">
				<div class="text-center"><i class="fa fa-envelope fa-5x"></i>
					<h2>Invite friends and get ...</h2> 
				</div>				
			</div>
			<div class="ibox-content">
			<p>Enter an email address below and our system will send your referral an email which includes your unique Affiliate ID, along with a short message asking your friends to join GLC Hub</p><br />
				<div id="emailafriend"></div>
				<form class="pagination-centered" width="100%" method="post" action="" id="referafriend">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Friend Name"id="friendname" name="friendname" required>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Friend Email Address"id="friendemail" name="friendemail" required>
						<input type="hidden" value="<?=$_SESSION['dennisn_user_full_name'];?>" name="username">
						<input type="hidden" value="<?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>" name="reflink">
					</div>
					<div class="form-group">
					<input type="submit" value="Invite" style="width: 100%;" class="btn btn-primary btn-large">
					</div>
					<div class="clearfix"></div>
				</form>
				<div class="sharelinks text-center">
					<h4>Share Your Unique Link Through Social Media</h4> 
					<p>Click on any of the social media links below to share your link</p>
					<a class="btn1 btn1-tweet" target="_blank" href="https://twitter.com/intent/tweet?text=Learn%20While%20You%20Earn%20From%20Anywhere&amp;url=<?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>&amp;via=globallearningcenter"><i class="fa fa-twitter fa-2x"></i></a>
					<a class="btn1 btn1-facebook" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>"><i class="fa fa-facebook fa-2x"></i></a>
					<a class="btn1 btn1-google" target="_blank" href="https://plus.google.com/share?url=<?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>"><i class="fa fa-google-plus fa-2x"></i></a>
				</div>
			</div>
		</div>                           
	</div>		
	<div class="row col-md-4">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Invite friends, and friends of friends</h5>
			</div>
			
			<div class="ibox-content ibox-heading">
				<p>Copy your referral link below &amp; paste it into a new email, then sent it to all of your contacts:</p>
				<? //put a table ?>
				<table border="0">
				  <tr>
				    <td><a class="fontie"target="_blank" href="<?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>"><?php echo $link ?>/ref/<?=$_SESSION['dennisn_username'];?>
				    </a></td>
			      </tr>
			  </table>
				<p>&nbsp;</p>
			</div>
			<div class="ibox-content no-padding border-left-right">
					<img alt="image" class="img-responsive" src="img/refer_friend.jpg">
			</div>
			<div class="ibox-content">
				<p>Share your unique referral link with others. When they sign-up, you get credit, and you can start earning $$$
				</p>
					<div class="row m-t-lg">
						<!-- It will be good to keep track for all the invited friends and the one that have signed up -- >
						<!--div class="col-md-6">
							<span class="bar">5,3,9,6,5,9,7,3,5,2</span>
							<h5><strong>16</strong> Invited</h5>
						</div>
						<div class="col-md-6">
							<span class="line">5,3,9,6,5,9,7,3,5,2</span>
							<h5><strong>7</strong> Joinded</h5>
						</div-->
					</div>				
			</div>
			
		</div>                           
	</div>
</div>  
<script src="js/jquery-2.1.1.js"></script>
<script>
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ) ) {
    return false;
  } else {
    return true;
  }
}

$("#referafriend").submit(function() {
	if(validateEmail($("#friendemail").val()))
	{
		var url = "referafriend.php"; // the script where you handle the form input.

		$.ajax({
			   type: "POST",
			   url: url,
			   data: $("#referafriend").serialize(), // serializes the form's elements.
			   success: function(data)
			   {
				  $("#emailafriend").html("<div class='alert alert-success'>Your email was sent successfully!</div>");
				  document.getElementById("friendname").value="";
				  document.getElementById("friendemail").value="";
			   }
			 });

		return false; // avoid to execute the actual submit of the form.
	} 
		else
	{
		$("#emailafriend").html("<div class='alert alert-error'>Invalid Email</div>");
		$("#friendemail").val('');
		return false; // avoid to execute the actual submit of the form.
	}
});
</script>