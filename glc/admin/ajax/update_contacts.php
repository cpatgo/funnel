<?php
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
$iContact = getClass('Class_Icontact');
$iContact::getInstance()->setConfig(array(
  'appId'       => $icontact_appId, 
  'apiPassword' => $icontact_apiPassword, 
  'apiUsername' => $icontact_apiUsername
));
$oiContact = $iContact::getInstance();
if(strpos($icontact_apiUsername, 'beta')) $oiContact->useSandbox();

try {
  	$user_class = getInstance('Class_User');
  	$users = $user_class->get_users();
  	foreach ($users as $key => $value) {
  		//Add contact to icontact
  		$addContact = $oiContact->addContact($value['email'], null, null, $value['f_name'], $value['l_name']);
  		//Add contact to list
  		$subscribeContactToList = $oiContact->subscribeContactToList($addContact->contactId, $icontact_contactList, 'normal');
  	}
  	echo json_encode(array('type' => 'success', 'message' => 'Successfully updated the contacts in iContact.'));
  	die();
} catch (Exception $oException) { // Catch any exceptions
	$error = json_decode($oiContact->getLastResponse());
	$response = array(
		'type' => 'error',
		'message' => sprintf('%s', $error->errors[0])
	);
	echo json_encode($response);
	die();
}

