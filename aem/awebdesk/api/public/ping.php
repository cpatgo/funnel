<?php

@session_start();

require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/ajax.php';

adesk_ajax_print('<response><session>' . session_id() . '</session></response>');

?>