<?php 

/** post parameters **/
$email 		= strip_tags(trim($_POST['friendemail'])); 
$name 		= strip_tags(trim($_POST['friendname'])); 
$username 	= strip_tags( trim($_POST['username']));
$reflink	= strip_tags( trim($_POST['reflink']));

/** start message here **/
$msg = 'Hello '.$name.',<br />
<br />
Your friend '.$username.' invites you to check out a short video on how to earn money with the Global Learning Center (GLC). 
<br /><br />
Everyone that&#39;s currently a Member of the GLC can benefit from its Learning and Earning Centers. GLC has created an automated, lucrative referral Cycle Pay System that rewards Members for simply sharing its online e-Learning courses with your friends, peers and associates. 
<br /><br />
Copy and paste this '.$reflink.' or <a href="'.$reflink.'" target="_blank">Click to watch »</a>';

/** mandrill starts here **/
try {
    require_once 'class/mandrill_src/Mandrill.php';
    $mandrill = new Mandrill('dmHv4vt3NItaV8gTP9y3dg');
    $message = array(
        'html' => utf8_encode($msg),
        'text' => 'Example text content',
        'subject' => $username . ' sent you a free GLCHub.com invite!',
        'from_email' => 'no-reply@glchub.com',
        'from_name' => 'GLC Hub',
        'to' => array(
            array(
                'email' => $email,
                'name' =>  $name,
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
        // 'bcc_address' => 'no-reply@globallearningcenter.net',
        'tracking_domain' => null,
        'signing_domain' => null,
        'return_path_domain' => null,
        'merge' => true,
        'merge_language' => 'mailchimp'
        // 'global_merge_vars' => array(
        //     array(
        //         'name' => 'merge1',
        //         'content' => 'merge1 content'
        //     )
        // ),
        // 'merge_vars' => array(
        //     array(
        //         'rcpt' => 'sarahgregorio29@gmail.com',
        //         'vars' => array(
        //             array(
        //                 'name' => 'merge2',
        //                 'content' => 'merge2 content'
        //             )
        //         )
        //     )
        // ),
        // 'tags' => array('password-resets'),
        // //'subaccount' => 'customer-123',
        // 'google_analytics_domains' => array('example.com'),
        // 'google_analytics_campaign' => 'message.from_email@example.com',
        // 'metadata' => array('website' => 'www.example.com'),
        // 'recipient_metadata' => array(
        //     array(
        //         'rcpt' => 'sarahgregorio29@gmail.com',
        //         'values' => array('user_id' => 123456)
        //     )
        // ),
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
