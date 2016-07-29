<?php
include('./header.php');
?>

<div class="c1_MainBox c1_SamplesMainbox">
	<div class="c1_MainBoxContainer">
		<h1>Banners with different link types</h1>
		<p>the banner below use different affiliate linking types to demonstrate this functionality.</p>
		<div class="c1_Note">Note that the banner codes used in these sample pages are specially changed to work in the test conditions.
You can see how this banner will look in the real situation in the banner code example.  </div>
		<a class="c1_sButton" href="./">Back to Samples & tests home</a>
	</div>
</div>
<div class="c1_Wrapper">
	<div class="c1_WrapperContainer">
	
<div class="c1_WideSampleBox">
	<h2>Standard links (redirect)</h2>
	
	<a href="../scripts/click.php?a_aid=testaff&a_bid=11110001">
	<img src="../accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"></a>
	<img src='../scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'>
	
	<h5>Banner code (example)</h5>
	<pre>
&lt;a href="<strong><?php echo $urlPart?>/scripts/click.php?a_aid=testaff&a_bid=11110001</strong>"&gt;
&lt;img src="<?php echo $urlPart?>/accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"&gt;&lt;/a&gt;
&lt;img src='<?php echo $urlPart?>/scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'&gt;
	</pre>
</div>

<div class="c1_WideSampleBox">
	<h2>New style links (URL parameters)</h2>
	
	<a href="./sample_homepage.php?a_aid=testaff&a_bid=11110001">
	<img src="../accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"></a>
	<img src='../scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'>
	
	<h5>Banner code (example)</h5>
	<pre>
&lt;a href="<strong>http://www.targetsite.com/?a_aid=testaff&a_bid=11110001</strong>"&gt;
&lt;img src="<?php echo $urlPart?>/accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"&gt;&lt;/a&gt;
&lt;img src='<?php echo $urlPart?>/scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'&gt;
	</pre>
</div>


<div class="c1_WideSampleBox">
	<h2>SEO links (require mod_rewrite)</h2>
	
	<a href="./reftestaff/11110001.html">
	<img src="../accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"></a>
	<img src='../scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'>
	
	<h5>Banner code (example)</h5>
	<pre>
&lt;a href="<strong>http://www.targetsite.com/reftestaff/11110001.html</strong>"&gt;
&lt;img src="<?php echo $urlPart?>/accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"&gt;&lt;/a&gt;
&lt;img src='<?php echo $urlPart?>/scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'&gt;
	</pre>
</div>

<div class="c1_WideSampleBox">
	<h2>DirectLink style (no URL parameters)</h2>
	
	<a href="./sample_homepage.php">
	<img src="../accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"></a>
	<img src='../scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'>
	
	<h5>Banner code (example)</h5>
	<pre>
&lt;a href="<strong>http://www.targetsite.com/</strong>"&gt;
&lt;img src="<?php echo $urlPart?>/accounts/default1/banners/sample_image_banner.gif" alt="" title="" WIDTH="468" HEIGHT="60"&gt;&lt;/a&gt;
&lt;img src='<?php echo $urlPart?>/scripts/imp.php?a_aid=testaff&a_bid=11110001' width='1' height='1' border='0'&gt;
	</pre>
</div>

</div></div>

<?php
include('./footer.php');
?>
