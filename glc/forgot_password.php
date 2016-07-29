<?php 
$site_url = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']);
$err = isset($_REQUEST['err'])?$_REQUEST['err']:"";
switch ($err) {
    case 1:
        $error = "Your email address is not registered.";
        break;
    case 2:
    	$error = "Unable to send email. Please contact administrator.";
    	break;
    case 2:
		$error = "Your token has already expired.";
		break;
	default:
		$error = "";
}
$msg = isset($_REQUEST['msg'])?$_REQUEST['msg']:"";
switch ($msg) {
    case 2:
        $msg = "<p>A password reset Email has been sent to the email address you signed up with.</p><br /><p>Please kindly check your Spam or Junk folder as it may have ended up there.</p>";
        break;
	default:
		$msg = "";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GLC | Forgot Password</title>
<link href="css/bootstrap.min.css" rel="stylesheet">

<link href="css/plugins/steps/jquery.steps.css" rel="stylesheet">  
  
<script type='text/javascript' src='js/jquery.js'></script>
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
body {
	font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
}
a{
	color: #428bca !important;
}
.logo a{
	margin-top: 30px;
	color:#222;
	font-size:35ptfont-weight:600; 
	letter-spacing:-2px;
	display: block;
	text-decoration: none;
}
.ulink{
	text-decoration: underline;
}
legend{
	text-transform: uppercase;
	padding: 10px 0;
	color: #666;
    font-size: 18px;
	margin: 0;
}
div.clear
{
    clear: both;
}

div.product-chooser{
    
}

    div.product-chooser.disabled div.product-chooser-item
	{
		zoom: 1;
		filter: alpha(opacity=60);
		opacity: 0.6;
		cursor: default;
	}

	div.product-chooser div.product-chooser-item{
		padding: 11px;
		border-radius: 6px;
		cursor: pointer;
		position: relative;
		border: 1px solid #efefef;
		margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10x;
		background: #ffffff;
	}
	
	div.product-chooser div.product-chooser-item.selected{
		border: 4px solid #428bca;
		background: #efefef;
		padding: 8px;
		filter: alpha(opacity=100);
		opacity: 1;
	}
	
		div.product-chooser div.product-chooser-item img{
			padding: 0;
		}
		
		div.product-chooser div.product-chooser-item span.title{
			display: block;
			margin: 10px 0 5px 0;
			font-weight: bold;
			font-size: 12px;
		}
		
		div.product-chooser div.product-chooser-item span.description{
			font-size: 12px;
		}
		
		div.product-chooser div.product-chooser-item input{
			position: absolute;
			left: 0;
			top: 0;
			visibility:hidden;
		}
	#options-error{
		position: absolute;
		top: -20px;
		left: 0;
		width: 100%;
	}
	.btn-group{
		width: 100%;
	}
	.btn-group label{
		width: 20%;
		white-space: normal;
	}
	.btn-group label strong{
		font-size: 18px;
	}
    .box{ display: none; }

    .cash{ background: none; }

    .paypal{background: none; }

    .e_pin{ background: none; }
	.free{ background: none; }

	.payments input{display: inline;}
	.full-width{
		width: 100%;
	}
	.boxed {
		background: #eee none repeat scroll 0 0;
		border-radius: 5px;
		display: block;
		padding: 20px 60px;
		margin: 5px 5px 10px;
		overflow: hidden;
		position: relative;
		width: auto;
	}
</style>

</head>
<body class="gray-bg">
<div class="container-fluid">
<div class="row col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-2 col-lg-4 col-lg-offset-4">
	<div class="text-center">
		<h1 class="logo">
			<a href="/"><img src="<?php echo $site_url; ?>/wp-content/uploads/2016/06/glc-logoblk-250x50.png" alt="GLobal Lerning Center" /></a>
		</h1>
	</div>
	<?php echo ($error != '')?'<div class="alert alert-danger">'.$error.' </div>':""; ?>  
	<?php echo ($msg != '')?'<div class="alert alert-success">'.$msg.' </div>':""; ?>  
	<div class="text-center">Already have an account? <a href="<?php printf('%s/glc/login.php', $site_url) ?>" class="ulink">Click here to Login.</a></div>
<div id="box-content">
<div class="clearfix"></div><br />
	<form class="m-t boxed" role="form" action="password.php" method="POST">
		<fieldset>
			<legend>Forgot Password</legend>
			<div class="form-group">
				<input type="email" class="form-control" placeholder="Email Address" required="" name="email">
			</div>
			<button type="submit" name="submit" class="btn btn-primary block full-width btn-lg m-b">Submit</button>
			<br /><br />
		</fieldset>
	</form>
</div>

<div class="text-center">Do not have an account? <a href="/choose-your-membership/" class="ulink">Sign up here.</a></div>
<br />
	<p class="text-center"><a href="/">&laquo; Back to Site</a></p>
</div>
</div>

</body>
</html>