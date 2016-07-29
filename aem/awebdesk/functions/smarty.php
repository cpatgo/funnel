<?php
// smarty.php

// Some functions useful for smarty.

require_once(awebdesk_classes('smarty.php'));

function adesk_smarty_noaccess(&$smarty, $obj = null) {
    if ($obj != null)
        $obj->nocontent = true;

    $smarty->assign('content_template', 'noaccess.tpl.htm');
}

function adesk_smarty_redirect(&$smarty, $page) {
    $mesg = $smarty->get_template_vars('resultMessage');
    if ($mesg !== null) {
        if (strstr($page, "?") !== false) {
            $page .= "&info=" . urlencode($mesg);
        } else {
            $page .= "?info=" . urlencode($mesg);
        }
    }

    header("Location: $page");
    exit;
    return true;
}

function adesk_smarty_submitted(&$smarty, &$assets) {
    $formSubmitted = $_SERVER['REQUEST_METHOD'] == 'POST';
    if ( $formSubmitted ) {
        $submitResult = $assets->formProcess($smarty);
        $smarty->assign('submitResult', $submitResult);
    }
    $smarty->assign('formSubmitted', $formSubmitted);
}

function adesk_smarty_message_clear(&$smarty) {
    $smarty->clear_assign('resultMessage');
    $smarty->clear_assign('resultStatus');
	if (isset($_SESSION))
		unset($_SESSION['adesk_smarty_message']);
    return true;
}

function adesk_smarty_message_update(&$smarty, $subject) {
    $text = sprintf(_a("Changes to %s saved"), $subject);
    adesk_smarty_message($smarty, $text, 1);
}

function adesk_smarty_message_insert(&$smarty, $subject) {
    $text = sprintf(_a("Added new %s"), $subject);
    adesk_smarty_message($smarty, $text, 1);
}

function adesk_smarty_message_delete(&$smarty, $subject) {
    adesk_smarty_message($smarty, sprintf(_a("Deleted %s"), $subject), 1);
}

function adesk_smarty_message(&$smarty, $message, $status = 0) {
    $allvars = $smarty->get_template_vars();
    if (isset($allvars['resultMessage'])) {
        $smarty->assign('resultMessage', $allvars['resultMessage'] . '; ' . $message);
		$_SESSION["adesk_smarty_message"] = $allvars['resultMessage'] . '; ' . $message;
	} else {
        $smarty->assign('resultMessage', $message);
		$_SESSION["adesk_smarty_message"] = $message;
	}
    if (isset($allvars['resultStatus'])) {
        $smarty->assign('resultStatus', $allvars['resultStatus'] . '; ' . $status);
		$_SESSION["adesk_smarty_status"] = $allvars['resultStatus'] . '; ' . $status;
	} else {
        $smarty->assign('resultStatus', $status);
		$_SESSION["adesk_smarty_status"] = $status;
	}
    return true;
}

function adesk_smarty_load_get(&$smarty) {
    $smarty->clear_assign('get');
    $smarty->assign('get', array_merge($_GET, $_POST), false);
}

?>
