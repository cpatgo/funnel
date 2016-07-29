<?php
/**
* Class for sending email
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Mail
{
    function send_email($to = "", $subject = "", $message = "", $from = "")
    {
        if(file_exists('db.php')) require_once(dirname(__FILE__).'/config.php');
        if(file_exists('db.php')) require_once(dirname(__FILE__).'/function/setting.php');
        if(file_exists('db.php')) require_once(dirname(__FILE__).'/function/send_mail.php');
        $SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $message);
        return $SMTPChat = $SMTPMail->SendMail();
    }
}