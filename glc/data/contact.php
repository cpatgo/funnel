<?php $link = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); ?>
<h1>Questions?<br></h1>
<h2><b>You can browse through our <a href="<?php echo $link?>/my-tickets/">knowledge base</a> for frequently asked questions or you can send us a ticket <a href="<?php echo $link?>/submit-ticket/">here</a>.</b></h2>