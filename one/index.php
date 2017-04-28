<?php

require('includes/config.php');

// Initialize WP and AEM Helper - Mark Revilla
require('includes/wp-helper.php');
require('includes/aem-helper.php');
$wpHelper 	= new WP_Helper('localhost', 'identifz_one', 'Pl71791!197321', 'identifz_glc_1min_wp');
$aemHelper 	= new AEM_Helper('localhost', 'identifz_min_wp', ';%+MlWZ6]9-!SWfaa', 'identifz_glc_1min_aem');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: memberpage.php'); }
//if form has been submitted process it
if(isset($_POST['submit'])){
	//very basic validation
	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}
	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}
	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}
	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}
	}
	//if no errors have been created carry on
	if(!isset($error)){
		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);
		//create the activasion code
		$activasion = md5(uniqid(rand(),true));
		try {
			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (firstname,lastname,password,email,active) VALUES (:firstname, :lastname, :password, :email, :active)');
			$stmt->execute(array(
				':firstname' => $_POST['firstname'],
				':lastname' => $_POST['lastname'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			// Register User to WP and AEM - Mark Revilla
			$wpHelper->registerUser($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password']);
			$aemHelper->registerUser($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password']);

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at 1minute Funnels.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";
			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();
			//redirect to index page
			header('Location: index.php?action=joined');
			exit;
		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
//define page title
$title = '1minute Funnels';
//include header template
require('layout/header.php');
?>


<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h4>Sign Up with your email address</h4>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}
				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>

				<div class="form-group">
					<input type="text" name="firstname" id="firstname" class="form-control input-lg" placeholder="First Name" value="<?php if(isset($error)){ echo $_POST['firstname']; } ?>" tabindex="1">
				</div>
                <div class="form-group">
					<input type="text" name="lastname" id="lastname" class="form-control input-lg" placeholder="Last Name" value="<?php if(isset($error)){ echo $_POST['lastname']; } ?>" tabindex="1">
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2">
				</div>
				<div class="form-group">
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
				</div>
				<div class="form-group">
					<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
				</div>
                <h4>By clicking on Sign up, you agree to 1Minute Funnels <a href="/affiliate-terms">Terms & Conditions</a> and <a href="/privacy-policy">Privacy Policy</a>.
				<div class="form-group">
					<input type="submit" name="submit" value="Sign Up" class="btn btn-primary btn-block btn-lg" tabindex="5">
				</div>
			</form>
			<h4>Already a member? <a href='login.php'>Login here</a></h4>
		</div>
	</div>

</div>

<?php
//include header template
require('layout/footer.php');
?>
