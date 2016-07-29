<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="style.css" type="text/css" />
<title>Post Affiliate Pro - Samples & Tests</title>
</head>

<body>
	<div class="c1_TopBar">
		<div class="c1_TopBarContainer">
			<a href="../affiliates/login.php#login">Affiliate login</a><span>|</span>
			<a href="../merchants/login.php#login">Merchant login</a>
		</div>
	</div>
	<div class="c1_Header">
		<div class="c1_HeaderContainer">
			<div class="c1_HeaderInfo">
				<strong>
					<a class="c1_Logo" href="../affiliates/">
						<img src="../themes/signup/_common_templates/img/logo_pap.png" class="LogoImage" />
					</a>
				</strong>
			</div>
			<ul class="c1_nav">
				<li><a href="../affiliates/">Home</a></li>
				<li><a href="../affiliates/signup.php#SignupForm">Sign up</a></li>
				<li><a href="../affiliates/signup.php#ContactUs">Contact us</a></li>
			</ul>
			<div class="clear"></div>
		</div>
	</div>

<?php
function full_url($s) {
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
    return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
}

$completeUrl = full_url($_SERVER);
$urlPart = strstr($completeUrl, "/samples_tests", true);
?>
