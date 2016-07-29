<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing email functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Email extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    // BASE FUNCTIONS FOR SENDING EMAIL
    function get_template($file_name, $data)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $file = '';
        $site_url = GLC_URL;

        ob_start();
        $file .= sprintf('%s/email/%s.php', dirname(dirname(__FILE__)), $file_name);
        include(sprintf('%s/email/header.php', dirname(dirname(__FILE__))));
        include($file);
        include(sprintf('%s/email/footer.php', dirname(dirname(__FILE__))));
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    function send_mail($receipient, $subject, $message, $header = '')
    {
        include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        require_once(dirname(dirname(__FILE__)).'/config.php');

        add_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
            $send = wp_mail($receipient, $subject, $message);
        remove_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
        return $send;
    }

    function set_html_content_type() 
    {
        return 'text/html';
    }
    // END BASE FUNCTIONS FOR SENDING EMAIL

    function welcome_email($data = array())
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();
        $referral_link = sprintf('%s/ref/%s', GLC_URL, $data['username']);

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            
            //Add contact to icontact
            $addContact = $oiContact->addContact($data['email_address'], null, null, $data['fname'], $data['lname']);
            //Add contact to list
            $subscribeContactToList = $oiContact->subscribeContactToList($addContact->contactId, icontact_contactList, 'normal');
            //Get email message for GLC
            $glc_welcome = $oiContact->getMessage(icontact_welcome_email);

            //Send first email: Welcome GLC
            $this->send_mail($data['email_address'], $glc_welcome->message->subject, sprintf($glc_welcome->message->htmlBody, $data['fname'], $data['username'], $referral_link));
            // if($data['membership'] !== 'Free' && $data['membership']):
                //Get email message for Affiliate if user membership is not Free
                $affiliate_welcome = $oiContact->getMessage(icontact_welcome_affiliate);
                //Send second email: Welcome Affiliate
                $this->send_mail($data['email_address'], $affiliate_welcome->message->subject, sprintf($affiliate_welcome->message->htmlBody, $data['fname'], $referral_link));
            // endif;
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }

        return true;
    }

    function activation($data = array())
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
        
            //Get email message saying account is activated
            $activation = $oiContact->getMessage(icontact_payment_email);
            $referral_link = sprintf('%s/ref/%s', GLC_URL, $data['username']);
            //Send email: Account activated
            $this->send_mail($data['email_address'], $activation->message->subject, sprintf($activation->message->textBody, $data['fname'], $data['username'], $referral_link));
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }

        return true;
    }

    function activate_account($data = array())
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');

        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();
        $referral_link = sprintf('%s/ref/%s', GLC_URL, $data['username']);

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            
            //Get email message for activating the account
            $activation = $oiContact->getMessage(icontact_account_activation);

            //Token %s/glc/reset_password.php
            $token = sprintf('%s-%s-%s', strtotime('+1 days', time()), base64_encode($data['user_id']), base64_encode($data['pww']));
            $link = sprintf('<a href="%s/glc/activate.php?token=%s">%s/glc/activate.php?token=%s</a>', GLC_URL, $token, GLC_URL, $token);

            //Send first email: Activate account
            $this->send_mail($data['email_address'], $activation->message->subject, sprintf($activation->message->htmlBody, $data['username'], $link, GLC_URL, GLC_URL));
            
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }

        return true;
    }

    function new_affiliate($data = array())
    {
        // If user is free do not email enroller
        if($data['membership'] === 'Free') return true;

        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $user_class = getInstance('Class_User');
        $enroller = $user_class->get_user($data['enroller']);
        $enroller = $enroller[0];

        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            //Get email content
            $referral = $oiContact->getMessage(icontact_new_affiliate);
            //Send email
            $this->send_mail($enroller['email'], $referral->message->subject, sprintf($referral->message->htmlBody, $enroller['username']));

            //Send also email if the user enrolled 2 affiliates for the first time
            $affiliates = $user_class->get_affiliates($data['enroller']);
            if(count($affiliates) === 2):
                $complete_affiliates = $oiContact->getMessage(icontact_earned_2_enrollees);
                $this->send_mail($enroller['email'], $complete_affiliates->message->subject, sprintf($complete_affiliates->message->htmlBody, $enroller['username']));
            endif;
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }

        return true;
    }

    function icontact_stage2_lack_enrollee($data)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            //Get email content
            $cycle = $oiContact->getMessage(icontact_stage2_lack_enrollee);
            //Send email
            $this->send_mail($data['email'], $cycle->message->subject, sprintf($cycle->message->htmlBody, $data['username']));
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }

        return true;
    }

    function icontact_cycle_completed($data, $level)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            //Get email content
            $cycle = $oiContact->getMessage(icontact_cycle_completed);
            //Send email
            $this->send_mail($data['email'], sprintf($cycle->message->subject, $level), sprintf($cycle->message->htmlBody, $data['username'], $level));
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }

        return true;
    }

    function icontact_step2_cycle_completed($data, $level)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            //Get email content
            $cycle = $oiContact->getMessage(icontact_step2_commission);
            //Send email
            $this->send_mail($data['email'], $cycle->message->subject, sprintf($cycle->message->htmlBody, $data['username'], $level));
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }
        return true;
    }

    function icontact_step3_cycle_completed($data, $level)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            //Get email content
            $cycle = $oiContact->getMessage(icontact_step3_commission);
            //Send email
            $this->send_mail($data['email'], $cycle->message->subject, sprintf($cycle->message->htmlBody, $data['username'], $level));
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }
        return true;
    }

    function upgrade_membership($user, $old_membership, $new_membership)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $iContact = getClass('Class_Icontact');
        $iContact::getInstance()->setConfig(array(
          'appId'       => icontact_appId, 
          'apiPassword' => icontact_apiPassword, 
          'apiUsername' => icontact_apiUsername
        ));
        $oiContact = $iContact::getInstance();

        try {
            //user sandbox if in staging
            if(strpos(icontact_apiUsername, 'beta')) $oiContact->useSandbox();
            //Get email content
            $content = $oiContact->getMessage(icontact_membership_upgrade);
            //Send email
            $this->send_mail($user['email'], $content->message->subject, sprintf($content->message->htmlBody, $user['username'], $old_membership, $new_membership));
        } catch (Exception $oException) { // Catch any exceptions
            $error = json_decode($oiContact->getLastResponse());
            return true;
        }
        return true;
    }

    function array_to_sql($data)
    {
        $count = count($data); $values = ''; $flag = 0;
        $keys = implode(',', array_keys($data));
        foreach ($data as $key => $value) {
            if($key === 'apc_1'):
                $values .= sprintf("'%s'%s", $value, ($flag < $count-1) ? ',' : '');
            else:
                $values .= sprintf('"%s"%s', $value, ($flag < $count-1) ? ',' : '');
            endif;
            $flag++;
        }
        return array('keys' => $keys, 'values' => $values);
    }
}