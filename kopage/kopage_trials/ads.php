<?php

// are there users who should not have ads on their pages?
define('noAdsUsers','user1,user2,user3,etc');

if(!defined('noAds') && !in_array($u,explode(',',noAdsUsers))){
	
	// define ads here.
	// IMPORTANT: if you add content ads, make sure block (like A, DIV, etc)
	// has classname defined to "kedit-data-widget", when it's not,
	// it'll became part of content when website saving changes!
	
	
	// ad displayed on the bottom of page,
	// before </body> tag, with custom CSS this ad can be fixed to page.
	
	define('_defaultPreBodyEnd','<a class="kedit-data-widget" href="#" style="background:#7CA8CE;color:#fff;padding:20px;font-size:150%;display:block;text-align:center">Body-End Ad (set in /kopage_trials/ads.php file)</a>');
	
	define('_defaultPreContent','<a class="kedit-data-widget" href="#" style="background:#7CA8CE;color:#fff;padding:20px;font-size:150%;display:block;text-align:center">Pre-Content Ad. (set in /kopage_trials/ads.php file)</a>');
	
	define('_defaultPostContent','<a class="kedit-data-widget" href="#" style="background:#7CA8CE;color:#fff;padding:20px;font-size:150%;display:block;text-align:center">After-Content Ad. (set in /kopage_trials/ads.php file)</a>');
	
	
	
	
}

?>