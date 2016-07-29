<?php
include('./header.php');
?>

<div class="c1_MainBox c1_SamplesMainbox">
	<div class="c1_MainBoxContainer">
		<h1>Banners with channels and SubId tracking</h1>
		<p>PAP allows your affiliates to track their advertising channels. 
This means that they can create for example channel for AdWords, and put their affiliate link on adwords.
The same link can be put to a promotion email, with a different channel code.</p>
		<a class="c1_sButton" href="./">Back to Samples & tests home</a>
	</div>
</div>
<div class="c1_Wrapper">
	<div class="c1_WrapperContainer">

<p>
	Another example would be creating a channel for different places on your site.
	<br/>
	PAP will track not only the user referral, but also through which channel he came. This way your affiliate will know which channel (banner placing) converts most.
	<br /><br />
	The good thing about channels and Sub ID codes is that <strong>they are passed also to sales / leads</strong>.
	So your affiliate will know not only which referral (click) was made through which channel, but also which sales were made through the channel. 
	<br /><br />
	Note that the banner codes used in these sample pages are specially changed to work in the test conditions.
	You can see how this banner will look in the real situation in the banner code example.  
</p>

<div class="c1_WideSampleBox">
	<h2>Channel code example</h2>
	
	<a href="./sample_homepage.php?a_aid=testaff&a_bid=11110001&chan=testchnl">
	<img src="../accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"></a>
	<img src='../scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'>
	
	<h5>Banner code (example)</h5>
	<pre>
&lt;a href="http://www.targetsite.com/?a_aid=testaff&a_bid=11110001<strong>&chnl=testchnl</strong>"&gt;
&lt;img src="<?php echo $urlPart?>/accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"&gt;&lt;/a&gt;
&lt;img src='<?php echo $urlPart?>/scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'&gt;
	</pre>
</div>


<h3>SubId tracking</h3>
<p>
Except channel variable you can use two more custom variables: <strong>data1</strong>, <strong>data2</strong>.
They work exactly like channels, and can be used for additional tracking.<br/>
</p>

<div class="c1_WideSampleBox">
	<h2>SubId data examples</h2>
	
	<a href="./sample_homepage.php?a_aid=testaff&a_bid=11110001&data1=somedata&data2=even_more_data">
	<img src="../accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"></a>
	<img src='../scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'>
	
	<h5>Banner code (example)</h5>
	<pre>
	&lt;a href="http://www.targetsite.com/?a_aid=testaff&a_bid=11110001<strong>&data1=somedata&data2=even_more_data</strong>"&gt;
	&lt;img src="<?php echo $urlPart?>/accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"&gt;&lt;/a&gt;
	&lt;img src='<?php echo $urlPart?>/scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'&gt;
	</pre>
</div>
</div></div>
<?php
include('./footer.php');
?>
