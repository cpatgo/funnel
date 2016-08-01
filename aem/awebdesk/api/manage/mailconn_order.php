<?php
// init.php

@set_magic_quotes_runtime(0);
ini_set('magic_quotes_runtime', 0);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(dirname(__FILE__))))) . '/manage/awebdeskend.inc.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/basic.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/ajax.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/mail.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/mailer.php';


if ( !isset($site) ) $site = adesk_site_get();
if ( !isset($admin) ) $admin = adesk_admin_get();


// Preload the language file
adesk_lang_get('admin');



adesk_ajax_declare('mailconn_order', 'adesk_api_mailconn_order');
adesk_ajax_run();


?>
