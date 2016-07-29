<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Global Learning Center | Login</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">

<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body class="lucy">
<div class="middle-box text-center loginscreen  animated fadeInDown">
	<div class="whitebox">
		<div><h1 class="logo-name">GLC</h1></div>
		<h3>Welcome to Global Learning Center</h3>
		<!--<p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
			Continually expanded and constantly improved Inspinia Admin Them (IN+)
		</p>-->
		<p>Login in. To see it in action.</p>
		<?php 
		$err = isset($_REQUEST['err'])?$_REQUEST['err']:"";
		switch ($err) {
		    case 1:
		        $error = "Wrong Username or Password";
		        break;
			default:
				$error = "";
		}
		echo ($error != '') ? '<div class="alert alert-danger">'.$error.' </div>': ""; ?>
		<form class="m-t" role="form" action="login_check.php" method="post">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Username" required="" name="username">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="Password" required="" name="password">
			</div>
			<input name="submit" type="submit" value="Login" class="btn btn-primary block full-width m-b" />

			<a href="forgot_password.php"><small>Forgot password?</small></a>
		</form>
		<p class="m-t"> 
			<small>Copyright &copy; 2015 <a href="httP://www.xyz.com" target="_blank">Global Learning Center</a></small> 
		</p>
	</div>
</div>

<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
