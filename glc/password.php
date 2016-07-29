<?php
ini_set("display_errors",'off');
if(file_exists(dirname(__FILE__).'/config.php')) require_once(dirname(__FILE__).'/config.php');
if(file_exists(dirname(__FILE__).'/class/database.php')) require_once(dirname(__FILE__).'/class/database.php');
/**
* Class for resetting the password
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Password extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
        // If email is not set, redirect user to homepage
        if(isset($_POST['email'])) $this->check_user($_POST['email']);
        if(isset($_POST['password'])) $this->reset_password($_POST['password'], $_POST['confirm_password'], $_POST['token']);
    }

    function check_user($email)
    {
        if(file_exists('config.php')) require_once(dirname(__FILE__).'/config.php'); 


        // Check if the user exists in the database
        $user = $this->select(sprintf('Select * From users Where email="%s"', $email));
        if(empty($user)) $this->redirect('glc/forgot_password.php?err=1');
        $user = $user[0];
        // Prepare email details
        // Generate token for the user
        /*
         * Parts of the token:
         * time() - to check for the validity of token. If the time is more than 24 hours, token should be invalid.
         * upper case, 8 characters of sha1(firstname) - Just to make token longer and harder to crack
         * base64_encode of user email - Must be decoded to get the user id
         * upeer case, 8 characters of sha1(firstname) - Just to make token longer and harder to crack
         *
         * How to get user id from the token:
         *
         */
        $token = sprintf('%s-%s%s%s', time(), strtoupper(substr(sha1($user['f_name']), 0, 8)), base64_encode($user['id_user']), strtoupper(substr(sha1($user['l_name']), 0, 8)));
        $data = array(
            'to' => $user['email'],
            'subject' => 'Reset Your Global Learning Center Account Password',
            'message' => sprintf("Please click the link below to reset your password.<br><a href=\"%s/glc/reset_password.php?token='%s'\">%s/glc/reset_password.php?token='%s'</a>", GLC_URL, $token, GLC_URL, $token)
        );
        $email = $this->send_reset_password_link($data);
        if($email) $this->redirect('glc/forgot_password.php?msg=2');
        $this->redirect('glc/forgot_password.php?err=2');
    }
    function send_reset_password_link($data)
    {
        include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        return wp_mail($data['to'], $data['subject'], $data['message']);
    }
    function reset_password($password, $confirm_password, $token)
    {
        if(empty($token)) $this->redirect(sprintf('glc/reset_password.php?err=1', $token));
        if($password !== $confirm_password) $this->redirect(sprintf('glc/reset_password.php?token=%s&err=2', $token));
        $token = explode("-", $token);
        $token[1] = str_replace("'", "", $token[1]);
        $user_id = base64_decode(substr(substr($token[1], 8), 0, -8));
        
        //Update password of user in glc db
        $update = $this->update(sprintf('UPDATE users SET password = "%s" WHERE id_user = %d', sha1($password), $user_id));
        $user = $this->select(sprintf('SELECT * FROM users WHERE id_user = %d', $user_id));
        $user = $user[0];

        //Also update the password in wordpress database
        include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        $wp_user = get_user_by('email', $user['email']);
        wp_set_password($password, $wp_user->ID);
        
        if($update) $this->redirect('glc/reset_password.php?msg=1');
        $this->redirect('glc/reset_password.php?err=3');
    }
    function redirect($uri)
    {
        printf('<script type="text/javascript">window.location = "%s/%s"</script>', GLC_URL, $uri);
    }
}
new Password($GLOBALS["___mysqli_ston"]);