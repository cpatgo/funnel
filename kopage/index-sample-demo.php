<!doctype html>
<html>
<head>

<title>Example, embedded Kopage Sitebuilder</title>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

</head><body>
<h1 style="font-family:Helvetica,Arial,sans-serif;margin:5% auto 0;text-align:center">Welcome to our website builder</h1>
<p style="font-family:Helvetica,Arial,sans-serif;text-align:center;">This example shows how you can add Kopage Sitebuilder to your PHP website using include() function.</p>
<div style="max-width:80%;margin:5% auto;border:5px solid rgba(0,0,0,0.1);padding:25px">


<?php

// Your own Kopage Demo website.
// How to setup demo page?
// www.kopage.com/docs/1_1?item=setup-a-demo

// is it located on separate domain?
// define('kopageDemoWebsite','http://pro-wizard.com/demo');

// or by default, in /demo folder? If so, 
// demo configuration is in kopage.demo.config.php file.

define('kopageDemoWebsite','demo');

define('kopageDemoOnly',1);
define('_LiveDemo',"Live Demo");


include('kopage.php');

?>


</div>
</body></html>