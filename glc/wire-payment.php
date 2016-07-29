<?php 
require_once("config.php");
$order_number 	= isset($_REQUEST['order'])?$_REQUEST['order']:"";
$order = explode("-", $order_number);
$temp_id 	= $order[0]; 
$temp_time 	= $order[1];
$query = mysqli_query($GLOBALS["___mysqli_ston"], 
"SELECT id_user, time, amount
FROM temp_users t
INNER JOIN memberships m ON m.membership = t.membership 
WHERE id_user = '$temp_id' AND time = '$temp_time' ");
$num = mysqli_num_rows($query);
if($num == 0)
{
	header( 'Location: //'.$_SERVER['HTTP_HOST'] ) ;
} else {
	while($row = mysqli_fetch_array($query))
	{
		$amount =  $row['amount'];
	}	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GLC | Wire Transfer</title>
<link href="css/bootstrap.min.css" rel="stylesheet">

<link href="css/plugins/steps/jquery.steps.css" rel="stylesheet">  
  
<script type='text/javascript' src='js/jquery.js'></script>
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
body {
	font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
}
a{
	color: #2895f1 !important;
}
.btn-primary{
	background: #2895f1 !important;
}
.btn-primary:hover{
	background: #01483d !important;
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
	#box-content{
		width: 50%;
    	margin: 0 auto;
	}
</style>

</head>
<body class="gray-bg">
<div class="container-fluid">
<div>
<div class="text-center">
	<h1 class="logo">
		<a href="/"><img src="images/glc-logo-small.png" alt="GLobal Lerning Center" /></a>
	</h1>
</div>
<div id="box-content">
<div class="clearfix"></div>
<br>
<div class="alert alert-success">
	Your account has been successfully created, but is not active until your payment has been confirmed.
</div>
<form class="m-t boxed" action="login_check.php" role="form">
<fieldset>
<legend>Wire Transfer</legend>
<h3>Thank you for your product order.</h3>
<p>When paying by wire please be sure to include your <br />
<strong>Order # <?php echo $order_number; ?></strong>  and your account <strong>Email address</strong>. </p>
<p>
Wiring Instructions:<br />
<table>
<tr>
<td>Direct To:</td>  
<td>Wire Transit Routing Number 121000248</td>  
</tr>
<td valign="top">Bank Name:</td>  
<td>Wells Fargo Bank N.A.<br />420 Montgomery<br />San Francisco, CA 94104</td>  
</tr>
<tr>
<td>Beneficiary Acct No:       </td>  
<td>1409031984</td>  
</tr>                  
 <tr>
<td>Beneficiary:        </td>  
<td>Global Learning Center LLC</td>  
</tr> 
<tr>
<td>CHIPS Participant              </td>  
<td>0407</td>  
</tr>                     
<tr>
<td>Amount: </td>  
<td>$<?php echo $amount; ?></td>  
</tr>                     
</table>
</p>
<p>
Once your wire transfer payment has been received, you will receive an email confirming that your your Membership subscription is now activate.</p><p>Note: In choosing wire transfer as your payment method, your bank will charge you a fee to process your wire.</p><p>Any Questions, please email <strong>support@globallearningcenter.net</strong>.
</p>
<br />
<p class="text-center"><a href="javascript:window.print()">Click to Print This Page</a></p>
</fieldset>
</form>
</div>
<br />
	<p class="text-center"><a href="/">&laquo; Back to Site</a></p>
</div>
</div>

</body>
</html>
<?php } ?>