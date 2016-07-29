<?php
# soap_proxy.php

exit;

require_once dirname(dirname(__FILE__)) . '/functions/base.php';
require_once awebdesk_functions('file.php');
require_once awebdesk_functions('soap.php');

$post = adesk_file_get("php://input");
$post = "
    <?xml version='1.0' ?>
    <soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
        <soap:Body>
            <ajaxCall>
                <ajaxAPI>test.php</ajaxAPI>
                <ajaxAccess>admin</ajaxAccess>
                <ajaxMethod>f</ajaxMethod>
                <ajaxArgs>1,2</ajaxArgs>
            </ajaxCall>
        </soap:Body>
    </soap:Envelope>
";    

adesk_soap_parse($post);

?>
