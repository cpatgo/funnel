<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/lifterlms/includes/class.llms.student.php');
if(!isset($_GET['do'])) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

$users = get_users("orderby=ID");

foreach($users as $key => $value) {
	$membership = get_user_meta($value->ID, 'membership', true);

	if($membership == 'Executive' || $membership == 'Leadership') $membership = 'Professional';

    $lms_membership = get_page_by_title($membership, 'ARRAY_A', 'llms_membership');
    $student = new LLMS_Student($value->ID);
    if($res = $student->enroll($lms_membership['ID'])):
    	echo "<pre>";
    	print_r($res);
    	printf("USER ID: %d has been enrolled to LifterLMS <br>Membership: %s<br><br>", $value->ID, $membership);
    else:
    	printf("USER ID: %d failed to enroll to LifterLMS <br>Membership: %s<br><br>", $value->ID, $membership);
    endif;
}