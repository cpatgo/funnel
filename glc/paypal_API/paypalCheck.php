<?php

// require
require_once("db_connect.php");
require_once("sendEmail.php");
		
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);


if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);

// get variabes
$email = $_POST['custom'];  
$pass = $_POST['item_number'];  
$patientName = $_POST['first_name'] . " " . $_POST['last_name'];

$invalid = "no";

if (strcmp ($res, "VERIFIED") == 0) {
	
	// get form.
	// chec if row exists
	$row = mysqli_fetch_row(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM forms WHERE email = '$email' AND password = '$pass'"));
	if ($row) {
	
		$fileName = $row[13];
		$form = strtoupper($row[5]);
		
		// update
		mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE forms SET paid = '1' WHERE email = '$email' AND password = '$pass'");
		
		// PAYMENT VALIDATED & VERIFIED!
		$mail = new sendmail();
		$mail->SetCharSet("ISO-8859-1");
		$mail->from($patientName,$email);
		$mail->to("forms@nu-health.net");
		$mail->subject(strtoupper($form."_COYH"));
		$mail->text("$email;$pass");
		$mail->attachment("../../".$fileName);
		$mail->send();
		
		// delete file
		unlink("../../".$fileName);
		
	}
	
	else { 
		$invalid = "yes";
	}
}

else if (strcmp ($res, "INVALID") == 0 || $invalid == "yes") {

	// PAYMENT INVALID & INVESTIGATE MANUALY!
	$mail = new sendmail();
	$mail->SetCharSet("ISO-8859-1");
	$mail->from($patientName,$email);
	$mail->to("malconium@comcast.net");
	$mail->subject("Payment attempted, but failed");
	$mail->text("The payment for this user has not gone through.");
	$mail->send();
	
	// delete file
	//unlink("../../".$fileName);
}
}
fclose ($fp);
}
?>