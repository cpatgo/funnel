<?php
session_start();
require_once("config.php");
include "condition.php";
require("function/setting.php");
include("function/functions.php");
$id = $_SESSION['dennisn_user_id'];

if($_SESSION['leave_session'] == 1):
	$url = $_SESSION['o_url'];
	$sql = "select * from point_wallet where user_id = '$id' and user_point > 0";
	$qur = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$cnt = mysqli_num_rows($qur);
	$time = date("H:i:s");
	$date = date('Y-m-d');
	$ip = $_SERVER['REMOTE_ADDR'];
	 if($cnt > 0):
		while($row = mysqli_fetch_array($qur))
		{
			$user_point = $row['user_point'] - $user_point_wallet;
		}
		if($user_point > 0):
			$sql_for_update_point = "update point_wallet set user_point= '$user_point' 
			where user_id ='$id'";
			$qur_for_update_point = mysqli_query($GLOBALS["___mysqli_ston"], $sql_for_update_point);
			$sql_for_info = "insert into classified_info (ip, user_id, point, url, date, time) 
		 	values('$ip', '$id','$user_point', '$url','$date', '$time')";
		
			mysqli_query($GLOBALS["___mysqli_ston"], $sql_for_info);
		endif;
	endif;
	unset($_SESSION['req_cnt']);
	unset($_SESSION['o_url']);
	unset($_SESSION['leave_session']);
?>
<script>
	$(document).ready(function(){
		window.open(' <?=$url;?>','_blank');
	});	
</script>
<?php
endif;
print serch_board();
$class_search_id = $_REQUEST['class_search_id'];
bigoak($id,$class_search_id);
$_SESSION['req_cnt'] = 1;
?>

<script>
$(document).ready(function(){
	l_pos = 120;
	t_pos = 70;
	f_top = 10;
	f_left = 350;
	d_pos = 60;

var k = 1;
var l = 0;
	
for (var i=0;i< 16;i++)
{ 	
	var p = l+2;	
	for(var j = 0; j <=i; j++)
	{
		var r_top = <?php print rand(100,200); ?>	
		if(j == 0)
			var leftpos = f_left-40*i;
		else
			leftpos = leftpos+70;	
		if(k >= 37)
		{	
			break;	
		}
		var ttpos = f_top+(30*i);
		var ran = Math.floor((Math.random() * 30) + 1); 
		var ran1 = Math.floor((Math.random() * 25) + 1); 
		$('#div'+k).animate({top:ttpos+ran,left:leftpos+ran1},10*k*(j+1));
		/*if(k === 3*l)
		{
		$("#div"+k).css({top: ttpos+150, left: leftpos+150, position:'absolute'});
		continue;
		}*/
		k++;
		l= l+2;
	}
	for( ; k >=37 && k <=38; k++)
	{	
		var ttpos = f_top+(25*10);
		leftpos = leftpos-37*3+60;
		$('#div'+k).animate({top:ttpos,left:leftpos},10*k*(j+1));
	}
	// Left Side
	var ttpos = f_top+(25*10+0);
	$('#div39').animate({top:ttpos,left:550+0},10*39);
	var ttpos = f_top+(25*10+40);
	$('#div40').animate({top:ttpos,left:555-30},10*40);
	var ttpos = f_top+(25*10+50);
	$('#div41').animate({top:ttpos,left:560+30},10*41);
	var ttpos = f_top+(25*10+70);
	$('#div42').animate({top:ttpos,left:565-30},10*42);
	var ttpos = f_top+(25*10+80);
	$('#div43').animate({top:ttpos,left:560+30},10*43);
	var ttpos = f_top+(25*10+100);
	$('#div44').animate({top:ttpos,left:550-0},10*44);
	
	// Right Side
	var ttpos = f_top+(25*10+0);
	leftpos = leftpos-37*3+60;
	$('#div'+45).animate({top:ttpos,left:100},10*45);
	var ttpos = f_top+(25*10+20);
	$('#div'+46).animate({top:ttpos,left:140},10*46);
	var ttpos = f_top+(25*10+40);
	$('#div'+47).animate({top:ttpos,left:100},10*47);
	var ttpos = f_top+(25*10+50);
	$('#div'+48).animate({top:ttpos,left:140},10*48);
	var ttpos = f_top+(25*10+60);
	$('#div'+49).animate({top:ttpos,left:100},10*49);
	var ttpos = f_top+(25*10+70);
	$('#div'+50).animate({top:ttpos,left:140},10*50);
	var ttpos = f_top+(25*10+100);
	$('#div'+51).animate({top:ttpos,left:100},10*51);
}
 
});
// script for drag and drop
$(function() {
for(var i =1; i<=52; i++ )
{
$( "#div"+i ).draggable();
}
$( "#droppable" ).droppable({
drop: function( event, ui ) {
$( this )
.addClass( "ui-state-highlight" )
.find( "p" )
.html( "Dropped!" );
}
});
});
</script>

<style>
#droppable { width: 150px; height: 150px; padding: 0.5em; float: left; margin: 10px; }
.tree
{
	background:url(images/ads.png) no-repeat;
	height:560px; 
	width:780px; 
	text-align:center;
	position:relative;
}
.pic{
	
}
.pic:hover .show{
	display:block;
	color:#fff;
	background:#000;	
	height:auto;
    position: relative;
	top:-10px;
	border:solid 3px #009900;
	border-radius:10px;
	width:250px;
	opacity :0.8;
	
}
.des{
		color:#FFFFFF;
		 margin-left:10px;
 	   /*padding-top:10px;*/
	   margin-bottom:-10px;
 	 }
.ad{
		color:#000000;
		 margin-left:30px;
 	   padding-top:10px;
	}
.show{
	display:none;
	z-index: 50;
}
</style>

<!--<marquee behavior="slide" direction="down" width="100%" style="position:absolute; padding-left:200px;"><img src="images/eagle.gif"/></marquee>-->
<?php

function bigoak($id,$class_search_id)
{
	$point_sql = "select * from point_wallet where user_id ='$id'";
	$point_query = mysqli_query($GLOBALS["___mysqli_ston"], $point_sql);
	while($point_row = mysqli_fetch_array($point_query)):
	if($class_search_id == 0)
	{ $sql = "select * from ads where user_id ='$id' order by id desc"; }
	else
	{ $sql = "select * from ads where user_id ='$id' and catg_id = '$class_search_id' order by id desc"; }
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$cnt = mysqli_num_rows($query);
	if($cnt > 0)
	{
		$i = 1;
		print "<div class=\"tree\">";
		while($row = mysqli_fetch_array($query))
		{
			{ 
			//for(;$i<50;):  //temprolly
				if($point_row['user_point'] > 0 )
				{ ?>
				<div id="div<?=$i; ?>" style="position:absolute;" >
					<table style="padding:10px;">
						<tr>
							<!--<td rowspan="2"></td>-->
							<td style="text-align:left;">
							<div class="pic">
								<form action="index.php?page=advertisment" method="post">
									<input type="submit" name="submit" value="" style="background:url(images/fruit.png); height:23px; width:30px;border:none; cursor:pointer;" class="oppci" />
									<input type="hidden" name="ref" value="<?=$row['url']; ?>" />
								</form>	
								<div class="show">
									<?php /*?><img src="<?php echo $row['img']?>" style="padding:0 0 30px 30px; height:50px; width:50px;" /><?php */?>
									<br />
									<p class="des" style="font-weight:bolder;"><?=$row['title']; ?></p>
									<hr/>
									<p class="des" style=""><?=$row['detail']; ?></p>
									<hr />
									<p class="des"><?=$By;?> <?=get_user_name($id); ?> &nbsp;&nbsp;</p>
									<p class="des"><?=$Clik_on_Acron;?></p>
								</div>
							</div>
							</td>
						</tr>
					</table>
				</div>
				<?php $i++;
				//endfor;
				} 
			}
		}
	print "</div>";
	}
	else
	{
		print "<p class\"ad\" style=\"padding-top:20px; color:#FF0000; font-size:12pt; font-weight:bold;\">There have no classified here</p>";
	}
endwhile;
}

?>
<!--<div id='MicrosoftTranslatorWidget' class='Dark' style='color:white;background-color:#555555'></div><script type='text/javascript'>
setTimeout(function(){{var s=document.createElement('script');s.type='text/javascript';s.charset='UTF-8';s.src=((location && location.href && location.href.indexOf('https') == 0)?'https://ssl.microsofttranslator.com':'http://www.microsofttranslator.com')+'/ajax/v3/WidgetV3.ashx?siteData=_LWgCt1VfD6KwYK6E4tsg2LkEFW1iE5433Do0IsNKcn_kGU_EaeD9m8gz-loldr86tsDiFw1efIHar_SefxO0xWKhxKFB7UoNGK4-wxZd4YnCx5gmEoqTHd1xcQGj38w&ctf=True&ui=true&settings=Manual&from=en';var p=document.getElementsByTagName('head')[0]||document.documentElement;p.insertBefore(s,p.firstChild); }},0);
</script>-->
<?php 
	/*?><a style="color:#fff;" href="index.php?page=advertisment&ref=<?php print $row['url']; ?>" >
		<img src="images/fruit.png" height="30" width="30" class="oppci"/>
	</a><?php */
							
function serch_board()
{
?>
<div class="ibox-content">
<table class="table table-bordered">
	<tr>
		<td>
			<form name="my_form" action="index.php?page=bigoakclassified" method="post">
				<select name="class_search_id" onchange='this.form.submit()'>
					<option value="">All Classified</option>
						<?php
						$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ads_category");
						while($rrr = mysqli_fetch_array($qu))
						{ 
							$id = $rrr['id'];
							$catg_name = $rrr['catg_name'];
							?>
							<option value="<?=$id; ?>"><?=$catg_name; ?></option>
				<?php	}	?>	
					<!--<option value="0">All Classified</option>-->	
				</select>
				<noscript>
					<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
				</noscript>
			</form>
		</td>
	</tr>
</table>
</div>

<?php } ?>