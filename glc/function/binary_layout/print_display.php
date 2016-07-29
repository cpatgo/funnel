<?php
function display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent,$all_child,$l,$qualify_time)
{
	for($i = 1; $i < 16; $i++)
	{
		$child[$i] = $all_child[$i][0]."@".$all_child[$i][1]."@".$all_child[$i][2];
		if($pos[$i] == '' or $pos[$i] == 0) { $img[$i] = "e"; }
	} 
?>

<style>
 th 
 {
 font-size: 10px;
 }
 u
 {
 	color:#333333;
 } 
</style>
<link rel="stylesheet" href="../css/style_all.css" type="text/css" media="screen" />

<script src="js2/jquery.js" type="text/javascript"></script>
<script src="js2/jtip.js" type="text/javascript"></script>
	<br /><center>
	<table  class="message success" style="color:#000000;">
	<tr>
		<th width=150 height=100><img src=../images/e.png height=50/><br />Blank Position </td>
		<th width=150 height=100><img src=../images/u.png height=50/><br />Unqualified</td>
		<th width=150 height=100><img src=../images/o.png height=50/><br /> One Qualification</td>
		<th width=150 height=100><img src=../images/q.png height=50/><br />Qualified</td>
		<th width=150 height=100><img src=../images/f.png height=50/><br />Blocked Account </td>
	</tr>
	</table>
	<br />	
	<table width=800 border=0 hspace=0 vspace=0 cellspacing=0 cellpadding=0>
	<tr>
	<th colspan=10 width=850 height=10></th></tr>
	<tr><th colspan=10 width=900 height="150" valign="top">
	<span class="formInfo">
	<img src="../images/<?php print $img[1]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[1]."</u><br>";
		if($all_child[1][0] != '')
		{
			print "<font color=green>".$all_child[1][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[1][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[1][0] )."<br>";
			if($all_child[1][1] != '')
			{
				print "<font color=green>".$all_child[1][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[1][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[1][1] ); 
			}	
			
		}	?>
	</div></center>
	</span></a>
	</span>
		

		
		
	</th></tr>
	<tr>
	<th colspan=10><img src="../function/binary_layout/arrow.png" /></th>
	</tr>
	<tr><th colspan=10 width=800 style="background-image:url(back_line.png);background-repeat: no-repeat;background-position: center;" height=10>
	</th></tr>
	<tr><th colspan=4 width=400 valign="top">	
	<span class="formInfo">
	<img src="../images/<?php print $img[2]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[2]."</u><br>";
		if($all_child[2][0] != '')
		{
			print "<font color=green>".$all_child[2][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[2][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[2][0] )."<br>";
			if($all_child[2][1] != '')
			{
				print "<font color=green>".$all_child[2][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[2][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[2][1] );
			}	
			
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th>
	<th colspan=4 width=400 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[3]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[3]."</u><br>";
		if($all_child[3][0] != '')
		{
			print "<font color=green>".$all_child[3][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[3][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[3][0] )."<br>";
			if($all_child[3][1] != '')
			{
				print "<font color=green>".$all_child[3][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[3][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[3][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th>
	</tr>
	<tr>
	<th colspan=8 height="5">&nbsp;</th>
	<tr>
	<tr>
	<th colspan=4><img src="../function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=4><img src="../function/binary_layout/arrow.png" width=50%/></th>
	</tr>
	<tr><th colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[4]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[4]."</u><br>";
		if($all_child[4][0] != '')
		{
			print "<font color=green>".$all_child[4][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[4][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[4][0] )."<br>";
			if($all_child[4][1] != '')
			{
				print "<font color=green>".$all_child[4][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[4][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[4][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th><th colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[5]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[5]."</u><br>";
		if($all_child[5][0] != '')
		{
			print "<font color=green>".$all_child[5][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[5][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[5][0] )."<br>";
			if($all_child[5][1] != '')
			{
				print "<font color=green>".$all_child[5][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[5][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[5][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th><th colspan=2 width=200 valign="top">

	<span class="formInfo">
	
	<img src="../images/<?php print $img[6]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[6]."</u><br>";
		if($all_child[6][0] != '')
		{
			print "<font color=green>".$all_child[6][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[6][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[6][0] )."<br>";
			if($all_child[6][1] != '')
			{
				print "<font color=green>".$all_child[6][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[6][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[6][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th><th colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[7]; ?>.png"  height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[7]."</u><br>";
		if($all_child[7][0] != '')
		{
			print "<font color=green>".$all_child[7][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[7][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[7][0] )."<br>";
			if($all_child[7][1] != '')
			{
				print "<font color=green>".$all_child[7][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[7][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[7][1] );
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th>
	</tr>
	<tr>
	<th colspan=8 height="5">&nbsp;</th>
	<tr>
	<tr>
	<th colspan=2><img src="../function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=2><img src="../function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=2><img src="../function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=2><img src="../function/binary_layout/arrow.png" width=50%/></th>
	</tr>
	<tr>
	<th colspan=8 height="5">&nbsp;</th>
	<tr>
	<th width=120 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[8]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[8]."</u><br>";
		if($all_child[8][0] != '')
		{
			print "<font color=green>".$all_child[8][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[8][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[8][0] )."<br>";
			if($all_child[8][1] != '')
			{
				print "<font color=green>".$all_child[8][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[8][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[8][1] );
			}	
		}	 ?>
	</div></center>
	</span></a>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	
	<img src="../images/<?php print $img[9]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[9]."</u><br>";
		if($all_child[9][0] != '')
		{
			print "<font color=green>".$all_child[9][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[9][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[9][0] )."<br>";
			if($all_child[9][1] != '')
			{
				print "<font color=green>".$all_child[9][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[9][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[9][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th><th width=120 valign="top">

	<span class="formInfo">
	<img src="../images/<?php print $img[10]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[10]."</u><br>";
		if($all_child[10][0] != '')
		{
			print "<font color=green>".$all_child[10][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[10][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[10][0] )."<br>";
			if($all_child[10][1] != '')
			{
				print "<font color=green>".$all_child[10][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[10][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[10][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[11]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[11]."</u><br>";
		if($all_child[11][0] != '')
		{
			print "<font color=green>".$all_child[11][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[11][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[11][0] )."<br>";
			if($all_child[11][1] != '')
			{
				print "<font color=green>".$all_child[11][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[11][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[11][1] );
			}	
		}	 ?>
	</div></center>
	</span></a>
	</span>
	
	</th>
	<th width=120 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[12]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[12]."</u><br>";
		if($all_child[12][0] != '')
		{
			print "<font color=green>".$all_child[12][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[12][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[12][0] )."<br>";
			if($all_child[12][1] != '')
			{
				print "<font color=green>".$all_child[12][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[12][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[12][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<img src="../images/<?php print $img[13]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[13]."</u><br>";
		if($all_child[13][0] != '')
		{
			print "<font color=green>".$all_child[13][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[13][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[13][0] )."<br>";
			if($all_child[13][1] != '')
			{
				print "<font color=green>".$all_child[13][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[13][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[13][1] );
			}	
		}	 ?>
	</div></center>
	</span></a>
	</span>
	
	</th><th width=120 valign="top">

	<span class="formInfo">
	<img src="../images/<?php print $img[14]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[14]."</u><br>";
		if($all_child[14][0] != '')
		{
			print "<font color=green>".$all_child[14][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[14][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[14][0] )."<br>";
			if($all_child[14][1] != '')
			{
				print "<font color=green>".$all_child[14][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[14][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[14][1] );
			}	
		}	 ?>
	</div></center>
	</span></a>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<span class="formInfo">
	
	<img src="../images/<?php print $img[15]; ?>.png" height=50/>
	<span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "<u>".$user_name[15]."</u><br>";
		if($all_child[15][0] != '')
		{
			print "<font color=green>".$all_child[15][0]."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[15][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[15][0] )."<br>";
			if($all_child[14][1] != '')
			{
				print "<font color=green>".$all_child[15][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[15][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[15][1] ); 
			}	
		}	?>
	</div></center>
	</span></a>
	</span>
	
	</th></tr>
	<tr>
	<th height="50" colspan=8 height="5">&nbsp;</th>
	<tr>
	</table></center>

<?php } 

