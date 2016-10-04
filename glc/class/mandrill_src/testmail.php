<?php
try {

    require_once 'Mandrill.php';
    $mandrill = new Mandrill('dmHv4vt3NItaV8gTP9y3dg');
    $message = array(
        'html' => '<p>Example HTML content</p>',
        'text' => 'Example text content',
        'subject' => 'example subject',
        'from_email' => 'no-reply@glchub.com',
        'from_name' => 'GLC Hub',
        'to' => array(
            array(
                'email' => 'sarahgregorio29@gmail.com',
                'name' => 'Sarah Gregorio',
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => 'no-reply@glchub.com'),
        'important' => false,
        'track_opens' => null,
        'track_clicks' => null,
        'auto_text' => null,
        'auto_html' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'bcc_address' => 'no-reply@glchub.com',
        'tracking_domain' => null,
        'signing_domain' => null,
        'return_path_domain' => null,
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => array(
            array(
                'name' => 'merge1',
                'content' => 'merge1 content'
            )
        ),
        'merge_vars' => array(
            array(
                'rcpt' => 'sarahgregorio29@gmail.com',
                'vars' => array(
                    array(
                        'name' => 'merge2',
                        'content' => 'merge2 content'
                    )
                )
            )
        ),
        'tags' => array('password-resets'),
        //'subaccount' => 'customer-123',
        'google_analytics_domains' => array('example.com'),
        'google_analytics_campaign' => 'message.from_email@example.com',
        'metadata' => array('website' => 'www.example.com'),
        'recipient_metadata' => array(
            array(
                'rcpt' => 'sarahgregorio29@gmail.com',
                'values' => array('user_id' => 123456)
            )
        ),
        // 'attachments' => array(
        //     array(
        //         'type' => 'text/plain',
        //         'name' => 'myfile.txt',
        //         'content' => 'ZXhhbXBsZSBmaWxl'
        //     )
        // ),
        // 'images' => array(
        //     array(
        //         'type' => 'image/png',
        //         'name' => 'IMAGECID',
        //         'content' => 'ZXhhbXBsZSBmaWxl'
        //     )
        // )
    );
    $async = false;
    $ip_pool = 'Main Pool';
    $send_at = '2000-12-01 00:00:00';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    print_r($result);
    /*
    Array
    (
        [0] => Array
            (
                [email] => recipient.email@example.com
                [status] => sent
                [reject_reason] => hard-bounce
                [_id] => abc123abc123abc123abc123abc123
            )
    
    )
    */
} catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    throw $e;
}
?>