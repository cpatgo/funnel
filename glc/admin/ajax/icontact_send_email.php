<?php
// This file will perform ajax requests for Icontact
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);
$action = (isset($_POST['action'])) ? $_POST['action'] : '';
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$iContact = getClass('Class_Icontact');
$iContact::getInstance()->setConfig(array(
  'appId'       => $icontact_appId, 
  'apiPassword' => $icontact_apiPassword, 
  'apiUsername' => $icontact_apiUsername
));
$oiContact = $iContact::getInstance();
if(strpos($icontact_apiUsername, 'beta')) $oiContact->useSandbox();

//Ajax request to send single email to a contact
parse_str($_POST['details'], $data);
if($action === 'send_single'):
	$contactIds = json_decode($data['contactIds']);
elseif($action === 'send_multiple'):
	$contactIds = $data['contactIds'];
endif;

try {
	$message = $oiContact->addMessage($data['subject'], $icontact_campaignid, null, $data['body']);
	$sendEmail = $oiContact->sendMessage($contactIds, $message->messageId);
	$response = array('type' => 'success', 'message' => "Email was sent successfully.");
	echo json_encode($response);
  	die();
} catch (Exception $oException) { // Catch any exceptions
  	$error_obj = json_decode($oiContact->getLastResponse());
  	$response = array('type' => 'success', 'message' => "Email was sent successfully.");
	echo json_encode($response);
  	die();
}