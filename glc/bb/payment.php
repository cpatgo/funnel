<?php
	require('includes/coinbase.inc.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

		<title>Payment - The Sentient Robots Rights Organization</title>
	</head>
	<body>
		<div class="container">

	      <div class="starter-template" style="text-align:center; padding-top:50px;">
	        <h1>S.R.R.O.</h1>
	        <h3>Sentient Robots Rights Organization</h3>

	        <p><img src="logo.png" /></p>

	        <p>It's time to pay. You decided to donate...</p>

	        <p style="font-size:32px;"><b><?php echo $_POST['amount']; ?> BTC</b></p>

	        <hr/>

	        <?php echo $button->embedHtml; ?>

	        <p>&nbsp;</p>
	      </div>

	    </div><!-- /.container -->
	</body>
</html>