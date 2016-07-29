<?php 
session_start();
?>
<script type="text/javascript">  var _gaq = _gaq || [];  _gaq.push(['_setAccount', 'UA-22897853-1']);  _gaq.push(['_trackPageview']);  (function() {    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);  })();</script>

<style type="text/css">
.maindiv{ width:690px; margin:0 auto; padding:20px; background:#CCC;}
.innerbg{ padding:6px; background:#FFF;}
.result{ border:solid 1px #CCC; margin:10px 2px; padding:4px 2px;}
.title{ font-weight:bold; color:#c24f00; text-decoration:none; font-size:14px;}
.urlBox{ width:300px; padding:2px; border:solid 1px #999;}
.pad{ padding:3px 0px;}
</style>

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
	$("#getmetaData").click(function(){
	
	var element = $(this);
	var metaDataholder = $("#metaDataholder");
	
	var url = $("#url").val();
	var url = 'http://'+url;
	//alert(url);
	var category_id = $("#category_id").val();
	var dataString = 'url='+ url+'&category_id='+ category_id;
	//alert(category_id); 
	if(url=='')
	{
	alert("Please Enter URL");
	}
	else
	{
	$("#flash").show();
	$("#flash").fadeIn(400).html('<img src="images/ajax-loader1.gif" alt="Loader"> loading.....');
	
	$.ajax({
	type: "POST",
	url: "data/fetch-metadata.php",
	data: dataString,
	cache: false,
	success: function(html){
	metaDataholder.html('');
	$("#metaDataholder").append(html);
	$("#flash").hide();
	}
	});
	
	}
	return false;});});
</script>
<div class="ibox-content">	
	<div style="text-align:center;">
		<div style="display:none;"><img src="ajax-loader.gif"  /></div>
	</div>  
	<div class="maindiv">
	<div class="innerbg">
		<table class="table table-bordered">
			<tr>
				<td><?=$Category;?></td>
				<td colspan="3">
				<?php $que = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ads_category "); ?>
					<select name="category_id" id="category_id">
						<option value=""><?=$Select_Category;?></option>
						<?php
						while($row = mysqli_fetch_array($que))
						{ 
							$id = $row['id'];
							$category_name = $row['catg_name'];
						?>
							<option value="<?=$id; ?>"><?=$category_name; ?></option>
				<?php	}	?>		
					</select>
				</td>
			</tr>
			<tr>
				<td><?=$Enter_URL;?></td>
				<td colspan="2"><input name="url" type="text" class="urlBox" id="url" /></td>
				<td>
					<input type="submit" name="getmetaData" id="getmetaData" value="<?=$Submit_URL;?>" class="btn btn-primary" />
					<?=$_REQUEST['url']; ?>
				</td>
			</tr>
		</table>
		<div id="flash"></div>
		<div id="metaDataholder"></div>
<?php
function getHTML($url,$timeout)
{
       $ch = curl_init($url); // initialize curl with given url
       curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
       return @curl_exec($ch);
}

$html=getHTML("http://www.egyptian-planet.com/news-90.html",10);

preg_match_all('/<img .*src=["|\']([^"|\']+)/i', $html, $matches);
foreach ($matches[1] as $key=>$value) {
    echo $value."<br>";
}
?>
	</div>
	</div>
</div>
<!--<div style="padding:4px; text-align:right;">
	<h1>
		<a href="http://www.webinfopedia.com/extract-meta-data-from-url-using-php.html" style="font-weight:bold; color:#FFF; padding:4px 8px; background:#333; text-decoration:none;">Go back to tutorial</a>
	</h1>
</div>-->