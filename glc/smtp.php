<?php
//include_once ('phpmailer/class.phpmailer.php');
require 'phpmailer/PHPMailerAutoload.php';

/*
$msg = "test1";
$email = "virginiya@gmail.com";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "smtp.office365.com";
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Username = "no-reply@cardgenius.com";
$mail->Password = "Card2#eddie";
$mail->AddReplyTo('no-reply@cardgenius.com', CardGenius);
$mail->AddAddress($email);
$mail->SetFrom('no-reply@cardgenius.com', CardGenius);
$mail->Subject = "Email Validation";

$mail->MsgHTML($msg);
*/

$msg = "test1";
$email = "norman.marino@gmail.com";

$mail = new PHPMailer();

//$mail->SMTPDebug = 3;                               
$mail->IsSMTP();
$mail->Host = "usm1.siteground.biz";
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Username = "info@saepiosecurity.com";
$mail->Password = "uF~Xxr8%e86%";
$mail->AddReplyTo('info@saepiosecurity.com', "GLC");
$mail->AddAddress($email);
$mail->SetFrom('info@saepiosecurity.com', "GLC");
$mail->Subject = "Email Validation";

$mail->MsgHTML($msg);

if($mail->Send()){
	echo "send";
} else {
	echo "error";
}
?>