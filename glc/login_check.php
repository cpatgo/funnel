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
        printf('<script type="text/javascript">window.location="%s/glc/login.php?err=3";</script>', 'http://1min.identifz.com');
        die();
    endif;

    $_SESSION['dennisn_user_id'] = $user['id_user'];
    $_SESSION['dennisn_user_type'] = $user['type'];
    $_SESSION['dennisn_user_full_name'] = $user['f_name']." ".$user['l_name'];
    $_SESSION['dennisn_user_reg_date']=$user['date'];
    $_SESSION['dennisn_username'] = $user['username'];

    $token = base64_encode(sprintf('%s-%s', base64_encode($user['id_user']), base64_encode($_REQUEST['password'])));
    $_SESSION['dennisn_usertoken'] = $token;
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
    $creds = array();
    $creds['user_login'] = $_REQUEST['username'];
    $creds['user_password'] = $_REQUEST['password'];
    $creds['remember'] = true;
    $user = wp_signon( $creds, false );

    if(is_wp_error($user)):
        printf('<script type="text/javascript">window.location="%s/glc/login.php?err=1";</script>', 'http://1min.identifz.com');
    endif;
    wp_set_current_user($user->ID);
    
    $_SESSION['dennisn_user_name'] = $_REQUEST['username'];
    $_SESSION['dennisn_user_email'] = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : $user->data->user_email;
    $_SESSION['dennisn_user_login'] = 1;    
    
    if(isset($_COOKIE['referral'])) setcookie('referral', false, time() - 60*100000, '/');
    
    // login to AEM software automatically using their singlesignon

    include_once(dirname(__FILE__) . "/class/aem/api/singlesignon_sameserver.php");
    //Check if login successful in aem
    if((int)$result['result_code'] == 0):
        include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config_ex.inc.php');
        $query = sprintf("UPDATE aweb_globalauth SET password = '%s' WHERE username = '%s'", md5($_REQUEST['password']), $_REQUEST['username']);
        mysql_query($query, $GLOBALS["db_link"]);
        curl_request($params);
    endif;

    // login user to sitebuilder
    $login_result = $user_class->sitebuilder_user_login($_SESSION['dennisn_user_email'], $_REQUEST['password']);
    // if login not successful, update password in builder and relogin
    if((int)$login_result->response_code === 2) $user_class->sitebuilder_user_update($_SESSION['dennisn_user_email'], $_REQUEST['password']);
    // pass the user's data to subdomain
    setcookie('dennisn_user_email', $_SESSION['dennisn_user_email'], time() + (86400 * 30), '/', sprintf('.%s', $_SERVER['HTTP_HOST']), false);
    setcookie('dennisn_usertoken', $token, time() + (86400 * 30), '/', sprintf('.%s', $_SERVER['HTTP_HOST']), false);

    printf('<script type="text/javascript">window.location="%s/myhub/";</script>', 'http://1min.identifz.com');
}
else
{   
    //This time check temp users who aren't paid yet
    $user = $user_class->temp_user_login_check($_REQUEST['username'], $_REQUEST['password'], 0);
    if(!empty($user)):
        printf('<script type="text/javascript">window.location="%s/glc/index_customer.php";</script>', 'http://1min.identifz.com');
    else:
        printf('<script type="text/javascript">window.location="%s/glc/login.php?err=1";</script>', 'http://1min.identifz.com');
    endif;
}
?>
