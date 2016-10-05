<?php
//session_start();
$backup_path = "/home/globallearningce/public_html/glc/backup/";

/*
 * Define GLC_URL based on the current environment
 * Example value: http://globallearningcenter.com
 * Define siteUrl that can be used in javascript files
 */
define('GLC_URL', sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']));
define('GLC_MATRIX_URL', GLC_URL . '/glc');

//Dwolla Sandbox
define('DWOLLA_KEY', 'CwN4qtB3yHtHZIVApPmgUVFFty5MmHU7n5kZx0CHk2gFQPLyG0');
define('DWOLLA_SECRET', 'KfyTwlFKHGivLbeNn5drNlfKvOjLV8rYll4qDHG2NeUnTvXLSJ');
define('DWOLLA_ID', '812-740-5026');
define('DWOLLA_PIN', '5555');

//Define referral links of Payza and Bitcoin
$payza_referral_link = 'https://secure.payza.com/?7pEKrtkXRqjiiuBZMmm03iHo4pA6VfLe%2bZntK%2fIf528%3d';
$bitcoin_referral_link = 'https://www.coinbase.com/join/55fd86854e60763a240001aa';

//Dwolla Live
// define('DWOLLA_KEY', '8XylVVBr2DbGgDw4TiQeurVvaZ2E9LkqKHFGkcRB0fHhNbAItq');
// define('DWOLLA_SECRET', 'MjpACh7uBym04WeSExwAM8rMU4LWnMO0FW2eD8lmslzchYJpXV');
// define('DWOLLA_ID', '812-794-7283');
// define('DWOLLA_PIN', '5542');

// PayPal settings
//sandbox logins
define('CLIENT_ID', 'AUjYo6zQBW4GCa18afB3FhHKIZmSOasQLGOy90WJZ23lUuHlktdkgdDpGGeaPn1IkrcprjBBLZbSZI7d'); //your PayPal client ID
define('CLIENT_SECRET', 'EAyMsp0mvuTCpRwb2JtfDHXE8D93z7zt34_47wgYxxf7m_N1XZscxl7iL6bYOYaD1AEmMfIydlwdXYmU'); //PayPal Secret
//live logins
//define('CLIENT_ID', 'AR0cFrYAHxyh-UN3U828ky3MDrcI8lJep-dK01zxJX1McIkVkg1ZCKKXDt78O6RCk3MkGe5IOWzCB1ie'); //your PayPal client ID
//define('CLIENT_SECRET', 'EL6LUtZn51XkLdSHDyGvCHsbZ3HVjJT54hwgYX649fW24w2l1axmeOEjmUaEVvCC9eNhyoO7vsWwEYO_'); //PayPal Secret

define('RETURN_URL', GLC_URL.'/paypal/order_process.php'); //return URL where PayPal redirects user
define('CANCEL_URL', GLC_URL.'/index.php'); //cancel URL
define('PP_CURRENCY', 'USD'); //Currency code
define('PP_CONFIG_PATH', __DIR__); //PayPal config path (sdk_config.ini)

// Bitcoin Settings
/*--sandbox--*/
// $coinbaseAPIKey = 'BgpUvkgK0suj9apk';
// $coinbaseAPISecret = 'W5lYiC5EWkXLAlqY4FJLec7Ma0ydRfZj';
// $coinbaseurl = "https://sandbox.coinbase.com";
/*--live--*/
$coinbaseAPIKey = 'OIA5512ezDiB9o46';
$coinbaseAPISecret = 'OKorUGg5x64Q0geumk7USytCVjap4vxS';
$coinbaseurl = "https://www.coinbase.com";

$url_sms = "http://www.smsappgw.com/";

// Database host
$dbHost = "localhost";

//Default icontact is sandbox
$icontact_appId 		= 'xcjXDIaX01OzCA5tr8T2cjpjciZTdJoA';
$icontact_apiPassword   = '8618d9ee49';
$icontact_apiUsername   = 'onlinegun1-beta';
$icontact_contactList	= 237165;
$icontact_campaignid 	= 138947;
$icontact_welcome_email = 2153078;
$icontact_welcome_affiliate = 2153079;
$icontact_payment_email = 2152718;
$icontact_account_activation = 2153080;
$icontact_stage2_lack_enrollee = 2153081;
$icontact_earned_2_enrollees = 2153082;
$icontact_cycle_completed = 2153083;
$icontact_new_affiliate = 2153084;
$icontact_step2_commission = 2153076;
$icontact_step3_commission = 2153077;
$icontact_membership_upgrade = 2153101;

//Default account of edata is testing account
$edata_username = "sarahgregorio29";
$edata_password = "test1234";

// Default account of echeck for Xpressdraft is testing account
// STAGING
$echeck_username = "TestMerchant";
$echeck_password = "a4441febf272df8bbce24bb816c7775db22dec06";

$aem_username = "admin";
$aem_password = "uj2Kf5T8kudu";

//Default account of authorize is testing account
// $authorize_id = "5GpNvt28n";
// $authorize_key = "7nccb6457M2UHg2d";
    // 2nd authorize account
    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";

$server_host = $_SERVER['HTTP_HOST'];
if($server_host === 'glcdev.saepiosecurity.com' || $server_host === 'www.glcdev.saepiosecurity.com'):

	// database configuration
	$dbUsername = "katssoft_glc";
	$dbPassword = "G1(iHHw;7k+W";
	$dbName = "katssoft_glcdev";

	// payza payment gateway
	$payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
	$payza_executive = 'KGLWVVzLy3JL8d+1mSlcyg==';
	$payza_leadership = '8ITnd9rzGaJ1872GVTYn6g==';
	$payza_professional = 'Gqba/fEA397y53cDXHeQPA==';
	$payza_masters = 'Xgoo7YlmAG6Pug2dfv+inQ==';


elseif($server_host === 'glc.saepiosecurity.com'):

	// database configuration
	$dbUsername = "katssoft_glc";
	$dbPassword = "G1(iHHw;7k+W";
	$dbName = "katssoft_glc";

	// payza payment gateway
	//$payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
	$payza_checkout = 'https://secure.payza.com/checkout';
    $payza_executive = '2h1JJVR4mjf8eGhP0BBf7Q==';
	$payza_leadership = '/ms3CYny0l1x15h/FnEtJg==';
	$payza_professional = 'iDU2YDBZ6PHeqHk7zTvI7g==';
	$payza_masters = 'PMlrfVZmcragGWv4epgDNw==';

    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";
    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";


elseif($server_host === 'glcdev.local'):

	// database configuration
	$dbUsername = "root";
	$dbPassword = "1234";
	$dbName = "katssoft_glcdev";

	// payza payment gateway
	$payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
	$payza_executive = '+V7VyvBl0+JTmXKUUpZwSw==';
	$payza_leadership = '3xibmDBhSKOWeKBIWOPeRQ==';
	$payza_professional = '9p/L97/7E8jzGVYCiOaqtw==';
	$payza_masters = '2u/4idXn8eUxtdR6sqtHrg==';

    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";

elseif($server_host === 'glc.dev'):

    // database configuration
    $dbUsername = "ghostman";
    $dbPassword = "shtLu9m6w6eVGQDT";
    $dbName = "katssoft_glc";

    // payza payment gateway
    $payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
    $payza_executive = '+V7VyvBl0+JTmXKUUpZwSw==';
    $payza_leadership = '3xibmDBhSKOWeKBIWOPeRQ==';
    $payza_professional = '9p/L97/7E8jzGVYCiOaqtw==';
    $payza_masters = '2u/4idXn8eUxtdR6sqtHrg==';

    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";
    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";

elseif($server_host === 'glc.cretetech.com' || $server_host === 'www.glc.cretetech.com'):

    // database configuration
    $dbUsername = "cielbleu_glcdev";
    $dbPassword = "G1(iHHw;7k+W";
    $dbName = "cielbleu_glcdev";

    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";
    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";

elseif($server_host === 'glcv2.cretetech.com' || $server_host === 'www.glcv2.cretetech.com'):

    // database configuration
    $dbUsername = "cielbleu_wp816";
    $dbPassword = "X9E3cmO1ed)S";
    $dbName = "cielbleu_glcv2";

    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";
    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";

elseif($server_host === 'glcv2.identifz.com' || $server_host === 'www.glcv2.identifz.com'):

    // database configuration
    $dbUsername = "identifz_glc";
    $dbPassword = "1EAOGi~KUCsb";
    $dbName = "identifz_glc";

    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";
    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";

elseif($server_host === 'glc.app'):

    // database configuration
    $dbUsername = "homestead";
    $dbPassword = "secret";
    $dbName = "glcdev";

    // payza payment gateway
    $payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
    $payza_executive = '+V7VyvBl0+JTmXKUUpZwSw==';
    $payza_leadership = '3xibmDBhSKOWeKBIWOPeRQ==';
    $payza_professional = '9p/L97/7E8jzGVYCiOaqtw==';
    $payza_masters = '2u/4idXn8eUxtdR6sqtHrg==';

    // $authorize_id = "55KtfW6b";
    // $authorize_key = "6ym946Abq2Yt63Wr";

elseif($server_host === 'glc.branch'):

    // database configuration
    $dbUsername = "ghostman";
    $dbPassword = "shtLu9m6w6eVGQDT";
    $dbName = "katssoft_glc";

    // payza payment gateway
    $payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
    $payza_executive = '+V7VyvBl0+JTmXKUUpZwSw==';
    $payza_leadership = '3xibmDBhSKOWeKBIWOPeRQ==';
    $payza_professional = '9p/L97/7E8jzGVYCiOaqtw==';
    $payza_masters = '2u/4idXn8eUxtdR6sqtHrg==';  

elseif($server_host === 'glcv2.local'):

    // database configuration
    $dbUsername = "root";
    $dbPassword = "1234";
    $dbName = "glcv2";

    // payza payment gateway
    $payza_checkout = 'https://sandbox.payza.com/SandBox/checkout';
    $payza_executive = '+V7VyvBl0+JTmXKUUpZwSw==';
    $payza_leadership = '3xibmDBhSKOWeKBIWOPeRQ==';
    $payza_professional = '9p/L97/7E8jzGVYCiOaqtw==';
    $payza_masters = '2u/4idXn8eUxtdR6sqtHrg==';

    // $authorize_id = "3Xu5c4nGAa";
    // $authorize_key = "2bPgvBQ2VX9597nP";

elseif($server_host === 'glchub.dev'):

    // database configuration
    $dbUsername = "root";
    $dbPassword = "1234";
    $dbName = "glcv2";

else:
	// database configuration
    // default production server (GLCHUB.COM)
	$dbUsername = "wwwglchu_glcuser";
	$dbPassword = "7*hHqFw{+RJh";
	$dbName = "wwwglchu_glc";

	// payza payment gateway
	$payza_checkout = 'https://secure.payza.com/checkout';
    $payza_executive = '2h1JJVR4mjf8eGhP0BBf7Q==';
	$payza_leadership = '/ms3CYny0l1x15h/FnEtJg==';
	$payza_professional = 'iDU2YDBZ6PHeqHk7zTvI7g==';
	$payza_masters = 'PMlrfVZmcragGWv4epgDNw==';

	//Icontact
	$icontact_appId 		= 'MYFInwZj3WiTVifhYrbVFW2gIEyYjP4W';
	$icontact_apiPassword   = 'benzaces#';
	$icontact_apiUsername   = 'onlinegun1';
	$icontact_contactList	= 120584;
	$icontact_campaignid 	= 30552;
	$icontact_welcome_email = 589722;
	$icontact_welcome_affiliate = 589711;
	$icontact_payment_email = 589722;
    $icontact_account_activation = 602084;
    $icontact_stage2_lack_enrollee = 603674;
    $icontact_earned_2_enrollees = 603673;
    $icontact_cycle_completed = 603672;
    $icontact_new_affiliate = 603671;
    $icontact_step2_commission = 614995;
    $icontact_step3_commission = 614999;
    $icontact_membership_upgrade = 619596;
    
	//Edata
	$edata_username = "GlobalLearning1US";
	$edata_password = "Edata#A2a3b5g37w$";

    //Echeck
    $echeck_username = "GLCWeb";
    $echeck_password = "a0963c226664907e3c424f79e0aee9937bfb5a0e";

    //Authorize
    // $authorize_id = "86CZbA8nq9";
    // $authorize_key = "8a8572Q4gUKqxR6c";

    $aem_username = "admin";
    $aem_password = "uj2Kf5T8kudu";
    
endif;

define('aem_username', $aem_username);
define('aem_password', $aem_password);
define('icontact_appId', $icontact_appId);
define('icontact_apiPassword', $icontact_apiPassword);
define('icontact_apiUsername', $icontact_apiUsername);
define('icontact_contactList', $icontact_contactList);
define('icontact_campaignid', $icontact_campaignid);
define('icontact_welcome_email', $icontact_welcome_email);
define('icontact_welcome_affiliate', $icontact_welcome_affiliate);
define('icontact_payment_email', $icontact_payment_email);
define('icontact_account_activation', $icontact_account_activation);
define('icontact_stage2_lack_enrollee', $icontact_stage2_lack_enrollee);
define('icontact_earned_2_enrollees', $icontact_earned_2_enrollees);
define('icontact_cycle_completed', $icontact_cycle_completed);
define('icontact_new_affiliate', $icontact_new_affiliate);
define('icontact_step2_commission', $icontact_step2_commission);
define('icontact_step3_commission', $icontact_step3_commission);
define('icontact_membership_upgrade', $icontact_membership_upgrade);

// Create connection
$con=($GLOBALS["___mysqli_ston"] = mysqli_connect($dbHost, $dbUsername, $dbPassword)) or die("Error " . mysqli_error($con));
((bool)mysqli_query($con, "USE " . $dbName));

$q_c_dd = mysqli_query($GLOBALS["___mysqli_ston"], "select * from system_date where id = 1 ");

while($row_q_c_d = mysqli_fetch_array($q_c_dd))
{
	$current_d = $row_q_c_d['sys_date'];
}

$systems_date = $current_d; // date('Y-m-d', strtotime(" + 7 hours 44 minutes"));


function data_logs($from,$title,$message,$type_data)
{
	$date = date('Y-m-d');
	$time = time();

	mysqli_query($GLOBALS["___mysqli_ston"], "insert into logs (title , message , user_id , date , time , type) values ('$title' , '$message' , '$from' , '$date' , '$time' , '$type_data') ");
}
/*
$forget_password_sms = " #username #password ";

$kit_delivered_message_setting = " #username #date ";
$transaction_pin_regenerate_message_setting = " #user_pin #username ";

function send_sms($mobile,$message)
{
	//print $mobile."<br>".$message;

	//Change your configurations here.
	//---------------------------------
	$username="aliencares";
	$api_password="aliencares123";
	$sender="EIGHTS";
	$domain="fast.bulksmspark.in";
	$priority="1";// 1-Normal,2-Priority,3-Marketing
	$method="POST";
	//---------------------------------

	$username=urlencode($username);
	$password=urlencode($api_password);
	$sender=urlencode($sender);
	$message=urlencode($message);

	$parameters="user=$username&password=$api_password&sender=$sender&mobiles=$mobile&message=$message&route=4";

	$url ="http://fast.bulksmspark.co.in/sendhttp.php?user=$username&password=$password&mobiles=$mobile&message=$message&sender=$sender&route=4";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$curl_scraped_page = curl_exec($ch);
	curl_close($ch);
	//echo $curl_scraped_page;
}*/

/*
 * Autoloading
 */
function &getInstance($className)
{
    // This is like object cache
    static $instances = array();
    if (!isset($instances[$className])):
        $filePath = sprintf('%s/%s.php', dirname(__FILE__), strtolower(str_replace('_', '/', $className)));
        if (!class_exists($className)):
            if (!file_exists($filePath)):
                throw new Exception(sprintf('File not found. [%s]', $filePath));
            endif;
            include_once $filePath;
        endif;
        $instances[$className] = new $className($GLOBALS["___mysqli_ston"]);
    endif;
    return $instances[$className];
}

/*
 * Autoloading
 */
function &getClass($className)
{
    // This is like object cache
    static $instances = array();
    if (!isset($instances[$className])):
        $filePath = sprintf('%s/%s.php', dirname(__FILE__), strtolower(str_replace('_', '/', $className)));
        if (!class_exists($className)):
            if (!file_exists($filePath)):
                throw new Exception(sprintf('File not found. [%s]', $filePath));
            endif;
            include_once $filePath;
        endif;
        $instances[$className] = new $className();
    endif;
    return $instances[$className];
}

/*
 * Template Autoloading
 */
function getTemplate($template)
{
    $templateFilePath = sprintf('%s/%s.php', dirname(__FILE__), strtolower(str_replace('_', '/', $template)));
    if (!file_exists($templateFilePath)):
    	echo $templateFilePath;
        throw new Exception(sprintf('File not found. [%s]', $templateFilePath));
    endif;
    include_once $templateFilePath;
}

/*
 * Get the saved setting from options table
 */
function glc_option($option)
{
	$data = '';
    $result = mysqli_query($GLOBALS["___mysqli_ston"], sprintf("SELECT option_value FROM options WHERE option_name='%s'", $option));
    if($result->num_rows < 1) return '';
    while($row = $result->fetch_assoc()) {
        return $row['option_value'];
    }
}

/*
 * Update the saved setting from options table
 */
function glc_update_option($option_name, $option_value)
{
	$check_option = mysqli_query($GLOBALS["___mysqli_ston"], sprintf("SELECT * FROM options WHERE option_name='%s'", $option_name));
    if($check_option->num_rows < 1):
        $data = array('option_name' => $option_name, 'option_value' => $option_value);
        $sql_array = array_to_sql($data);
        return mysqli_query($GLOBALS["___mysqli_ston"], sprintf('INSERT INTO options (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']));
    else:
        return mysqli_query($GLOBALS["___mysqli_ston"], sprintf("UPDATE options SET option_value='%s' WHERE option_name='%s'", $option_value, $option_name));
    endif;
}

/*
 * Convert array to sql
 */
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

/*
 * Convert array to sql
 */
function post_var($field_name)
{
    return (isset($_POST[$field_name]) && !empty($_POST[$field_name])) ? $_POST[$field_name] : '';
}

/*
 * Auto login user function
 */
function glc_auto_login($user_id, $data)
{
    // var_dump($data);
    // die();
    $_SESSION['dennisn_user_id'] = $user_id;
    $_SESSION['dennisn_user_type'] = $data['type'];
    $_SESSION['dennisn_user_full_name'] = $data['f_name']." ".$data['l_name'];
    $_SESSION['dennisn_user_reg_date']=$data['activate_date'];
    $_SESSION['dennisn_username'] = $data['username'];
    $token = base64_encode(sprintf('%s-%s', base64_encode($user_id), base64_encode($data['password'])));
    $_SESSION['dennisn_usertoken'] = $token;

    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
    $creds = array();
    $creds['user_login'] = $data['username'];
    $creds['user_password'] = $data['password'];
    $creds['remember'] = true;
    $user = wp_signon($creds, false);

    if(is_wp_error($user)):
        $result = array('result' => 'error', 'message' => sprintf('%s', $user->get_error_message())); 
        die(json_encode($result));
    endif;
    wp_set_current_user($user->ID);

    $_SESSION['dennisn_user_name'] = $data['username'];
    $_SESSION['dennisn_user_email'] = $data['email'];
    $_SESSION['dennisn_user_login'] = 1; 
    $_SESSION['dennisn_user_wp_id'] = $user->ID;
    $_SESSION['dennisn_user_wppw'] = $data['password'];
    $_SESSION['show_message'] = 0; 

    $_SESSION['registration_success_message'] = "You are now logged and you have access to everything your GLC Membership allows. We have sent you several emails, so please make sure to check your spam or junk mail folders in case these messages ended up there. These emails contain IMPORTANT information about your account.<br>Enjoy!";

    // login to AEM software automatically using their singlesignon
    include_once(dirname(__FILE__) . "/class/aem/api/singlesignon_sameserver.php");

    if(isset($_COOKIE['referral'])) setcookie('referral', false, time() - 60*100000, '/');
    return true;
}