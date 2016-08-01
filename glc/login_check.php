<?php
session_start();
//ini_set('display_errors','off');
include('config.php');

$user_class = getInstance('Class_User');
//Check users table
$user = $user_class->user_login_check($_REQUEST['username'], $_REQUEST['password']);
//Check also temp user who are already paid
if(empty($user)) $user = $user_class->temp_user_login_check($_REQUEST['username'], $_REQUEST['password'], 1);
    
if(!empty($user))
{   
    $user = $user[0];
    //If user is free and account is not yet activated, show error
    if($user['activate_date'] === '0000-00-00' && $user['type'] === 'F'):
        printf('<script type="text/javascript">window.location="%s/glc/login.php?err=3";</script>', GLC_URL);
        die();
    endif;

    $_SESSION['dennisn_user_id'] = $user['id_user'];
    $_SESSION['dennisn_user_type'] = $user['type'];
    $_SESSION['dennisn_user_full_name'] = $user['f_name']." ".$user['l_name'];
    $_SESSION['dennisn_user_reg_date']=$user['date'];
    $_SESSION['dennisn_username'] = $user['username'];
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
    $creds = array();
    $creds['user_login'] = $_REQUEST['username'];
    $creds['user_password'] = $_REQUEST['password'];
    $creds['remember'] = true;
    $user = wp_signon( $creds, false );

    if(is_wp_error($user)):
        printf('<script type="text/javascript">window.location="%s/glc/login.php?err=1";</script>', GLC_URL);
    endif;
    
    $_SESSION['dennisn_user_name'] = $_REQUEST['username'];
    $_SESSION['dennisn_user_email'] = $_REQUEST['email'];
    $_SESSION['dennisn_user_login'] = 1;    
    
    if(isset($_COOKIE['referral'])) setcookie('referral', false, time() - 60*100000, '/');
    
    // login to AEM software automatically using their singlesignon
    include_once(dirname(__FILE__) . "/class/aem/api/singlesignon_sameserver.php");
    


    printf('<script type="text/javascript">window.location="%s/glchub/";</script>', GLC_URL);
}
else
{   
    //This time check temp users who aren't paid yet
    $user = $user_class->temp_user_login_check($_REQUEST['username'], $_REQUEST['password'], 0);
    if(!empty($user)):
        printf('<script type="text/javascript">window.location="%s/glc/index_customer.php";</script>', GLC_URL);
    else:
        printf('<script type="text/javascript">window.location="%s/glc/login.php?err=1";</script>', GLC_URL);
    endif;
}
?>
