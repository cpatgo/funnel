<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');
if(!isset($_GET['do'])) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

$user_class = getInstance('Class_User');
$users = get_users("orderby=ID");

//$aem_con = mysqli_connect('localhost', 'identifz_glc', '1EAOGi~KUCsb', 'identifz_glc_aem') or die("Error " . mysqli_error($aem_con));
// $aem_con = mysqli_connect("localhost","root","1234","glcv2_aem");
// if (mysqli_connect_errno())
// {
// 	echo "Failed to connect to MySQL: " . mysqli_connect_error();
// }

//$query = mysqli_query($aem_con, "select * from system_date where id = 1 ");
foreach($users as $key => $value) {
	$glc_user = $user_class->get_by_username($value->data->user_login);
	$glc_user = $glc_user[0];
	$membership = get_user_meta($value->ID, 'membership', true);

    if($membership == 'Executive' || $membership == 'Leadership') $membership = 'Professional';

    $params = array(
        'api_user'     => $aem_username,
        'api_pass'     => $aem_password,
        'api_action'   => 'user_add',
        'api_output'   => 'serialize',
    );

    $aem_settings = array(
        'Free'          => 'aem_free_id',
        'Executive'     => 'aem_executive_id', 
        'Leadership'    => 'aem_leadership_id', 
        'Professional'  => 'aem_professional_id',
        'Masters'       => 'aem_masters_id',
        'Founder'       => 'aem_founder_id'
    );
    $aem_group_id = glc_option($aem_settings[$membership]);

    // here we define the data we are posting in order to perform an update
    $post = array(
        'username' => $value->data->user_login,
        'password'   => $value->data->user_pass,
        'password_r' => $value->data->user_pass, 
        'email'      => $value->data->user_email,
        'first_name'  => $glc_user['f_name'],
        'last_name'   => $glc_user['l_name'],
        'group' => $aem_group_id, 
    );

    if(empty($post['group'])) continue;

    $user_class->curl_request($params, $post);
    // $query = sprintf("Update aweb_globalauth SET password = '%s' WHERE username = '%s'", $value->data->user_pass, $value->data->user_login);
    // mysqli_query($aem_con, $query);

    printf("USER ID: %d has been added to AEM<br>Membership: %s", $value->ID, $membership);
}