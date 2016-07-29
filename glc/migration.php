<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/lifterlms/includes/class.llms.student.php');

$users = get_users("orderby=ID");

foreach($users as $key => $value) {
	$membership = get_user_meta($value->ID, 'membership', true);
    $lms_membership = get_page_by_title($membership, 'ARRAY_A', 'llms_membership');
    $student = new LLMS_Student($value->ID);
    $student->enroll($lms_membership['ID']);
    printf("USER ID: %d has been enrolled to LifterLMS <br>", $value->ID);
}