<?php
# soap.php

function adesk_soap_parse($post) {
    # The SOAP requests we handle must come through HTTP/POST.  They must also be sent as text/xml, or application/soap+xml
    # ideally, in which case php://input will not be a blank string but will contain the full, unmodified text sent in the
    # body of the POST request.

    if ($post == '')
        exit;

    require_once awebdesk_functions('xml.php');

    $top = adesk_xml_read($post);

    if (!is_array($top))
        exit;

    # We need the <soap:Envelope> tag first.

    if (!isset($top['soap:Envelope']))
        exit;

    $env = $top['soap:Envelope'];

    # Then we need <soap:Body>.

    if (!isset($env['soap:Body']))
        exit;

    $body = $env['soap:Body'];

    if (!isset($body['ajaxCall']))
        exit;

    $call = $body['ajaxCall'];

    if (!isset($call['ajaxAPI']) || !isset($call['ajaxAccess']) || !isset($call['ajaxMethod']) || !isset($call['ajaxArgs']))
        exit;

    $api  = $call['ajaxAPI'];
    $acc  = $call['ajaxAccess'];
    $meth = $call['ajaxMethod'];
    $args = $call['ajaxArgs'];
    $args = explode(",", $args);

    # Sanity checking...

    if ($acc != 'admin' && $acc != 'public')
        exit;

    if (preg_match('/\.\./', $api))
        exit;

    $file = adesk_api("$acc/$api");

    if (!file_exists($file))
        exit;

    # Dangerous...be careful
    
    require_once awebdesk_functions('ajax.php');
    adesk_ajax_dontrun();

    require_once $file;

    $func = adesk_ajax_function($meth);

    if ($func)
        return call_user_func_array($func, $args);
}

function adesk_soap_response($ary) {
    $resp = array(
        'soap:Body' => array(
            'ajaxResponse' => $ary
        )
    );

    return $resp;
}

function adesk_soap_respond(&$resp) {
    adesk_xml_headers(false);
    echo adesk_xml_write_new($resp, "soap:Envelope");
}

?>
