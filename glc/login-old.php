<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GLC | Login</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">

<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class=" text-center animated fadeInDown">
	<div class="container-fluid">
	<div class="row col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
	<div class="text-center">
		<h1 class="logo">
			<a href="/"><img src="images/glc-logo.png" alt="GLobal Lerning Center" /> Global <span style="color: #006e60;">Learning</span> Center</a>
		</h1>
	</div>
	<div class="row">	<h3>Welcome to GLC</h3></div>
		<!--<p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
			Continually expanded and constantly improved Inspinia Admin Them (IN+)
		</p>-->
		<p>Login in. To see it in action.</p>
		<form class="m-t" role="form" action="login_check.php">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Username" required="" name="username">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="Password" required="" name="password">
			</div>
			<button type="submit" name="submit" class="btn btn-primary block full-width m-b">Login</button>

			<a href="forgot_password.php"><small>Forgot password?</small></a>
			<p class="text-muted text-center"><small>Do not have an account?</small></p>
			<a class="btn btn-sm btn-white btn-block" href="register.php">Create an account</a>
		</form>
		<p class="m-t"> 
			<small>Copyright &copy; 2015 <a href="<?php printf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']) ?>" target="_blank">GLC</a></small> 
		</p>
		</div>
	</div>
</div>

<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
