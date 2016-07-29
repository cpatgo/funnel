<?php
include('./header.php');
?>

<div class="c1_MainBox c1_SamplesMainbox">
	<div class="c1_MainBoxContainer">
		<h1>Sample home page</h1>
		<p>this page simulates your own homepage. All the sample banners point to this page, normally they would point to your own homepage.</p>
		<a class="c1_sButton" href="./">Back to Samples & tests home</a>
	</div>
</div>
<div class="c1_Wrapper">
	<div class="c1_WrapperContainer">
<div class="c1_WideSampleBox">
<h2>Referral (click) tracking</h2>
<p>
To track affiliate referrals, this page contains click tracking code.
<br />
You have to put this click tracking code into your homepage as well. 
The best practice is to put it somewhere into the page footer, so it is included in every page.
</p>

<p>Tracking code used on this page:
<br />
<pre>
&lt;script id="pap_x2s6df8d" src="<?php echo $urlPart?>/scripts/trackjs.js" type="text/javascript"&gt;
&lt;/script&gt;
&lt;script type="text/javascript"&gt;
papTrack();
&lt;/script&gt;
</pre>
</p>
<p>
This is standard tracking code that you can use also on your pages. 
<br />
Note that when you'll insert it to your page, you'll have to set the full path to the scripts directory,
so it will look like this:
<br />
<pre>
&lt;script id="pap_x2s6df8d" src="<strong><?php echo $urlPart?>/scripts/trackjs.js</strong>" type="text/javascript"&gt;
</pre>
</div>
<div class="c1_WideSampleBox">
<h2>Advanced referral (click) tracking</h2>
In click tracking code you can use folloving additional variables. These variables are optional and they must be placed above <b>papTrack();</b> or <b>PostAffTracker.track();</b> (in newer versions)
<pre>
var AffiliateID='affiliate id';   // click will be saved for affiliate specified by this parameter
var BannerID='banner id';         // click will be saved for banner specified by this parameter
var CampaignID='campaign id';     // click will be saved for campaign specified by this parameter
var Channel='channel';            // channel
var Data1='data1';                // extra data 1
var Data2='data2';                // extra data 2
</pre>
</div>
</div></div>

<?php
include('./footer.php');
?>
