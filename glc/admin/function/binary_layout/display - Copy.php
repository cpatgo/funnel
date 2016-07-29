<?php


function display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent,$all_child,$l,$qualify_time,$comming_board_username,$inserting_board,$path)
{
	for($i = 1; $i < 16; $i++)
	{
		$child[$i] = $all_child[$i][0]."@".$all_child[$i][1]."@".$all_child[$i][2];
		if($pos[$i] == '' or $pos[$i] == 0) { $img[$i] = "d"; }
		$u_type[$i] = get_type($user_name[$i]);
	} 
	$inserting_username = get_user_name($inserting_board);
?>

<style type="text/css" media="all">
@import "css/global.css";

</style>


<style type="text/css"> 
.imgBox { background: url(images/<?php echo $path; ?>/oak1.png) no-repeat; width:100px; height:103px; text-align:center; }
 .imgBox:hover {  background: url(images/<?php echo $path; ?>/oak_hover.png) no-repeat;width:100px; height:103px; }
 
 .imgBox_n { background: url(images/<?php echo $path; ?>/oak2.png) no-repeat;  height:134px;width:130px; }
 .imgBox_n:hover {  background: url(images/<?php echo $path; ?>/oak_nhover.png) no-repeat;width:130px; height:134px;  }
 
 .imgBox_n1 { background: url(images/<?php echo $path; ?>/oak2n.png) no-repeat;  height:103px;width:100px; }
 .imgBox_n1:hover {  background: url(images/<?php echo $path; ?>/oak_nhovern.png) no-repeat; height:103px;width:100px; }
 
 .imgBox_n2 { background: url(images/<?php echo $path; ?>/oak2nn.png) no-repeat;  height:80px;width:80px; }
 .imgBox_n2:hover {  background: url(images/<?php echo $path; ?>/oak_nhovernn.png) no-repeat; height:80px;width:80px; }
 
 .imgBox_n_bottom{ background: url(images/<?php echo $path; ?>/oak_bottom.png) no-repeat; height:64px;width:129px; 
 }
 .imgBox_n_bottom:hover{ background: url(images/<?php echo $path; ?>/oak_bottom_hover.png) no-repeat; height:64px;width:129px; 
 }

  </style>


<script src="js2/jquery.js" type="text/javascript"></script>
<script src="js2/jtip.js" type="text/javascript"></script>

	<!--<table  class="message success" style="color:#000000;">
	<tr>
		<th width=250 height=100><img src=images/d.png height=50/><br />Blank Position </td>
		<th width=250 height=100><img src=images/b.png height=50/><br />Active Member</td>
		<th width=250 height=100><img src=images/a.png height=50/><br />Blocked Account </td>
	</tr>
	</table>-->
<div style="margin-top:-50px;padding-left:70px;">
	<table width=800 border=0 hspace=0 vspace=0 cellspacing=0 cellpadding=0>
	<tr><th colspan=10 width="800" valign="top" align="center">
	<?php 
	
	if($real_parent[1] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print $real_parent[1]; ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">New Member</div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</tr>

	<tr>
	<tr><th colspan=10 width=900  valign="top">
	<span class="formInfo">
	<a style="margin-left:-400px;" href="binary_tree/user1.php?name=<?php print $name[1]; ?>&username=<?php print $user_name[1]; ?>&child=<?php print $child[1]; ?>&date=<?php print $date[1]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[1]; ?>&sponser_name=<?php echo $real_parent[1]; ?>&id=<?php echo $pos[1]; ?>" class=" jTip " id="two" name="<?php print $name[1]; ?>" >
	<center><div class="imgBox" style="margin-left:0px; display:block;">
	<div style="padding-top:65px; padding-right:0px; font-size:10px;">
	<?php 
		print "<u>".$user_name[1]."</u>
		
		<br>"."";
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
	</div> </div></center>
	<!--<img src="images/oak1.png" height=150 style="margin-left:-30px;" class="imgbox1"/>-->
	
	</a><span class="dashboard_button_heading">
	</span>
	</span>
	</th></tr>
	<tr>
	<th colspan="10" valign="top"><img src="function/binary_layout/arrow.png" style="padding-top:10px; width:400px;" /></th>
	</tr>
	<tr><th colspan=10 width=800 style="background-image:url(back_line.png);background-repeat: no-repeat;background-position: center;" height=10>
	</th></tr>
	<tr><th colspan=4 width=400 valign="top" align="center">	
	<span class="formInfo">
	<a style="margin-left:-400px;" href="binary_tree/user2.php?name=<?php print $name[2]; ?>&username=<?php print "<u>".$user_name[2]; ?>&child=<?php print $child[2]; ?>&date=<?php print $date[2]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[2]; ?>&sponser_name=<?php print $real_parent[2]; ?>" class="jTip " id="three" name="<?php print $name[2]; ?>">
	<center><div class="imgBox_n" style="margin-left:0px; display:block;">
		<div style="padding-top:50px; padding-right:0px; font-size:10px;">
		<?php 
			print "<u>".$user_name[2]."</u>
			
			<br>"."";
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
		</div>
	 </div></center>
	</a><span class="dashboard_button_heading"><center>
	</center>
	</span>
	</span>
	
	</th>
	<th rowspan="9"><img src=images/button-blue.jpg height=350 width="1px"/></th>
	<th colspan=4 width=400 valign="top" align="center">
	
	<span class="formInfo">
	<a style="margin-left:-500px;" href="binary_tree/user3.php?name=<?php print $name[3]; ?>&username=<?php print "<u>".$user_name[3]; ?>&child=<?php print $child[3]; ?>&date=<?php print $date[3]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[3]; ?>&sponser_name=<?php echo $real_parent[3]; ?>" class="jTip" id="four" name="<?php print $name[3]; ?>">
	<center><div class="imgBox_n" style=""> </div></center>
	<!--<img src="images/oak2.png" height=120/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:150px; margin-top:-60px;">
	<?php 
		print "<u>".$user_name[3]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th></tr>
	<tr>
	<th colspan=4><img src="function/binary_layout/arrow.png" width="50%" style="padding-top:5px;" /></th>
	<th colspan=4><img src="function/binary_layout/arrow.png" width="50%" style="padding-top:5px;" /></th>
	</tr>
	<tr><th colspan=2 width=200 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user4.php?name=<?php print $name[4]; ?>&username=<?php print "<u>".$user_name[4]; ?>&child=<?php print $child[4]; ?>&date=<?php print $date[4]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[4]; ?>&sponser_name=<?php echo $real_parent[4]; ?>" class="jTip" id="five" name="<?php print $name[4]; ?>">
	<center><div class="imgBox_n1" style="margin-left:0px;"> </div></center>
	<!--<img src="images/oak2.png" height=90/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:130px; margin-top:-60px;">
	<?php 
		print "<u>".$user_name[4]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th colspan=2 width=200 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user5.php?name=<?php print $name[5]; ?>&username=<?php print "<u>".$user_name[5]; ?>&child=<?php print $child[5]; ?>&date=<?php print $date[5]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[5]; ?>&sponser_name=<?php echo $real_parent[5]; ?>" class="jTip" id="six" name="<?php print $name[5]; ?>">
	<center><div class="imgBox_n1" style="margin-left:0px;"> </div></center>
	<!--<img src="images/oak2.png" height=90/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:130px; margin-top:-60px;">
	<?php 
		print "<u>".$user_name[5]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th colspan=2 width=200 valign="top" align="center">

	<span class="formInfo">
	<a href="binary_tree/user6.php?name=<?php print $name[6]; ?>&username=<?php print "<u>".$user_name[6]; ?>&child=<?php print $child[6]; ?>&date=<?php print $date[6]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[6]; ?>&sponser_name=<?php echo $real_parent[6]; ?>" class="jTip" id="server" name="<?php print $name[6]; ?>">
	<center><div class="imgBox_n1" style="margin-left:0px;"> </div></center>
	<!--<img src="images/oak2.png" height=90/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:130px; margin-top:-60px;">
	<?php 
		print "<u>".$user_name[6]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th colspan=2 width=200 valign="top" align="center">
	
	<span class="formInfo">
	<a style="margin-left:-200px;" href="binary_tree/user7.php?name=<?php print $name[7]; ?>&username=<?php print "<u>".$user_name[7]; ?>&child=<?php print $child[7]; ?>&date=<?php print $date[7]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[7]; ?>&sponser_name=<?php echo $real_parent[7]; ?>" class="jTip" id="eight" name="<?php print $name[7]; ?>">
	<center><div class="imgBox_n1" style="margin-left:0px;"> </div></center>
	<!--<img src="images/oak2.png" height=90/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:130px; margin-top:-60px;">
	<?php 
		print "<u>".$user_name[7]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th>
	</tr>
	<tr>
	<th colspan=2><img src="function/binary_layout/arrow.png"  width="50%" style="padding-top:8px;" /></th>
	<th colspan=2><img src="function/binary_layout/arrow.png"  width="50%" style="padding-top:8px;" /></th>
	<th colspan=2><img src="function/binary_layout/arrow.png"  width="50%" style="padding-top:8px;" /></th>
	<th colspan=2><img src="function/binary_layout/arrow.png"  width="50%" style="padding-top:8px;" /></th>
	</tr>
	<!--<tr>
	<th colspan=8 height="5">&nbsp;</th>
	<tr>-->
	<th width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user8.php?name=<?php print $name[8]; ?>&username=<?php print "<u>".$user_name[8]; ?>&child=<?php print $child[8]; ?>&date=<?php print $date[8]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[8]; ?>&sponser_name=<?php echo $real_parent[8]; ?>" class="jTip" id="nine" name="<?php print $name[8]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[8]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th width=120 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user9.php?name=<?php print $name[9]; ?>&username=<?php print "<u>".$user_name[9]; ?>&child=<?php print $child[9]; ?>&date=<?php print $date[9]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[9]; ?>&sponser_name=<?php echo $real_parent[9]; ?>" class="jTip" id="ten" name="<?php print $name[9]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[9]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th width=120 valign="top" align="center">

	<span class="formInfo">
	<a href="binary_tree/user10.php?name=<?php print $name[10]; ?>&username=<?php print "<u>".$user_name[10]; ?>&child=<?php print $child[10]; ?>&date=<?php print $date[10]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[10]; ?>&sponser_name=<?php echo $real_parent[10]; ?>" class="jTip" id="eleven" name="<?php print $name[10]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[10]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th width=120 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user11.php?name=<?php print $name[11]; ?>&username=<?php print "<u>".$user_name[11]; ?>&child=<?php print $child[11]; ?>&date=<?php print $date[11]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[11]; ?>&sponser_name=<?php echo $real_parent[11]; ?>" class="jTip" id="twelve" name="<?php print $name[11]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[11]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th>
	<th width=120 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user12.php?name=<?php print $name[12]; ?>&username=<?php print "<u>".$user_name[12]; ?>child=<?php print $child[12]; ?>&date=<?php print $date[12]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[12]; ?>&sponser_name=<?php echo $real_parent[12]; ?>" class="jTip" id="thirteen" name="<?php print $name[12]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[12]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th width=120 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user13.php?name=<?php print $name[13]; ?>&username=<?php print "<u>".$user_name[13]; ?>child=<?php print $child[13]; ?>&date=<?php print $date[13]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[13]; ?>&sponser_name=<?php echo $real_parent[13]; ?>" class="jTip" id="fourteen" name="<?php print $name[13]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[13]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th width=120 valign="top" align="center">

	<span class="formInfo">
	<a href="binary_tree/user14.php?name=<?php print $name[14]; ?>&username=<?php print "<u>".$user_name[14]; ?>&child=<?php print $child[14]; ?>&date=<?php print $date[14]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[14]; ?>&sponser_name=<?php echo $real_parent[14]; ?>" class="jTip" id="fifteen" name="<?php print $name[14]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a><span class="dashboard_button_heading"><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[14]."</u>
		
		<br>"."";
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
	</span>
	</span>
	
	</th><th width=120 valign="top" align="center">
	
	<span class="formInfo">
	<a href="binary_tree/user15.php?name=<?php print $name[15]; ?>&username=<?php print "<u>".$user_name[15]; ?>&child=<?php print $child[15]; ?>&date=<?php print $date[15]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[15]; ?>&sponser_name=<?php echo $real_parent[15]; ?>" class="jTip" id="sixteen" name="<?php print $name[15]; ?>">
	<center><div class="imgBox_n2"> </div></center>
	<!--<img src="images/oak2.png" height=60/>-->
	</a></span><center>
	<div style=" width:90px; margin-top:-50px;">
	<?php 
		print "<u>".$user_name[15]."</u>
		
		<br>"."";
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
	</div>
	</center>
	</th></tr>
	<tr>
	<th colspan=10 width=800 height=20></th></tr>
	<tr><th colspan=2 width=240 align="center">
	<?php if($comming_board_username[1] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[1]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:25px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</td>
	<th colspan=2 width=240 align="center">
	<?php if($comming_board_username[2] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[2]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:25px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</td>
	<th colspan=2 width=240 align="center">
	<?php if($comming_board_username[3] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[3]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:25px;">New Member</div>
		 </div>
		
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</td>
	<th colspan=2 width=240 valign="top" align="center">
	<?php if($comming_board_username[4] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[4]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:25px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</td></tr>
<!--	<tr>
	<tr>
	<th colspan=8 height="55">&nbsp;</th>
	<tr>
	<tr>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[0][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[1][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[2][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[3][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[4][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[5][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[6][0]; ?></th>
		<th width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[7][0]; ?></th>
	</th>
	</tr>
-->	</table>

</div>
<?php }  

function search_display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent,$all_child,$l,$qualify_time,$comming_board_username,$inserting_board)
{
	for($i = 1; $i < 16; $i++)
	{
		$child[$i] = $all_child[$i][0]."@".$all_child[$i][1]."@".$all_child[$i][2];
		if($pos[$i] == '' or $pos[$i] == 0) { $img[$i] = "e"; }
	} 
?>

<style type="text/css" media="all">
@import "css/global.css";
</style>

<script src="js2/jquery.js" type="text/javascript"></script>
<script src="js2/jtip.js" type="text/javascript"></script>
	<br /><center>
	<table  class="message success" style="color:#000000;">
	<tr>
		<th width=150 height=100><img src=images/e.png height=50/><br />Blank Position </td>
		<th width=150 height=100><img src=images/u.png height=50/><br />Unqualified</td>
		<th width=150 height=100><img src=images/o.png height=50/><br /> One Qualification</td>
		<th width=150 height=100><img src=images/q.png height=50/><br />Qualified</td>
		<th width=150 height=100><img src=images/f.png height=50/><br />Blocked Account </td>
	</tr>
	</table>
	<br />	
	<table width=800 border=0 hspace=0 vspace=0 cellspacing=0 cellpadding=0>
	<tr>
	<th colspan=10>&nbsp;</th>
	</tr>
	<tr>
	<th colspan=10 width=850 height=10></th></tr>
	<tr><th colspan=10 width=900 height="150" valign="top">
	<span class="formInfo">
	<a href="binary_tree/user1.php?name=<?php print $name[1]; ?>&username=<?php print "<u>".$user_name[1]; ?>&child=<?php print $child[1]; ?>&date=<?php print $date[1]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[1]; ?>&real_parent=<?php echo $real_parent[1]; ?>&id=<?php echo $pos[1]; ?>" class="message success jTip " id="two" name="<?php print $name[1]; ?>" >
	<img src="images/<?php print $img[1]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
		

		
		
	</th></tr>
	<tr>
	<th colspan=10><img src="function/binary_layout/arrow.png" /></th>
	</tr>
	<!--<tr><th colspan=10 width=800 style="background-image:url(back_line.png);background-repeat: no-repeat;background-position: center;" height=10>
	</th></tr>-->
	<tr><th colspan=4 width=400 valign="top">	
	<span class="formInfo">
	<a href="binary_tree/user2.php?name=<?php print $name[2]; ?>&username=<?php print "<u>".$user_name[2]; ?>&child=<?php print $child[2]; ?>&date=<?php print $date[2]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[2]; ?>&real_parent=<?php print $real_parent[2]; ?>" class="jTip message error" id="three" name="<?php print $name[2]; ?>">
	<img src="images/<?php print $img[2]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th>
	<th rowspan="9"><img src=images/button-blue.jpg height=350 width="1px"/></th>
	<th colspan=4 width=400 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user3.php?name=<?php print $name[3]; ?>&username=<?php print "<u>".$user_name[3]; ?>&child=<?php print $child[3]; ?>&date=<?php print $date[3]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[3]; ?>&real_parent=<?php echo $real_parent[3]; ?>" class="jTip message error" id="four" name="<?php print $name[3]; ?>">
	<img src="images/<?php print $img[3]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th></tr>
	<tr>
	<th colspan=4><img src="function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=4><img src="function/binary_layout/arrow.png" width=50%/></th>
	</tr>
	<tr><th colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user4.php?name=<?php print $name[4]; ?>&username=<?php print "<u>".$user_name[4]; ?>&child=<?php print $child[4]; ?>&date=<?php print $date[4]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[4]; ?>&real_parent=<?php echo $real_parent[4]; ?>" class="jTip message warning" id="five" name="<?php print $name[4]; ?>">
	<img src="images/<?php print $img[4]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user5.php?name=<?php print $name[5]; ?>&username=<?php print "<u>".$user_name[5]; ?>&child=<?php print $child[5]; ?>&date=<?php print $date[5]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[5]; ?>&real_parent=<?php echo $real_parent[5]; ?>" class="jTip message warning" id="six" name="<?php print $name[5]; ?>">
	<img src="images/<?php print $img[5]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th colspan=2 width=200 valign="top">

	<span class="formInfo">
	<a href="binary_tree/user6.php?name=<?php print $name[6]; ?>&username=<?php print "<u>".$user_name[6]; ?>&child=<?php print $child[6]; ?>&date=<?php print $date[6]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[6]; ?>&real_parent=<?php echo $real_parent[6]; ?>" class="jTip message warning" id="server" name="<?php print $name[6]; ?>">
	<img src="images/<?php print $img[6]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user7.php?name=<?php print $name[7]; ?>&username=<?php print "<u>".$user_name[7]; ?>&child=<?php print $child[7]; ?>&date=<?php print $date[7]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[7]; ?>&real_parent=<?php echo $real_parent[7]; ?>" class="jTip warning" id="eight" name="<?php print $name[7]; ?>">
	<img src="images/<?php print $img[7]; ?>.png"  height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th>
	</tr>
	<tr>
	<th colspan=2><img src="function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=2><img src="function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=2><img src="function/binary_layout/arrow.png" width=50%/></th>
	<th colspan=2><img src="function/binary_layout/arrow.png" width=50%/></th>
	</tr>
	<tr>
	<th colspan=8 height="5">&nbsp;</th>
	<tr>
	<th width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user8.php?name=<?php print $name[8]; ?>&username=<?php print "<u>".$user_name[8]; ?>&child=<?php print $child[8]; ?>&date=<?php print $date[8]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[8]; ?>&real_parent=<?php echo $real_parent[8]; ?>" class="jTip message warning" id="nine" name="<?php print $name[8]; ?>">
	<img src="images/<?php print $img[8]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user9.php?name=<?php print $name[9]; ?>&username=<?php print "<u>".$user_name[9]; ?>&child=<?php print $child[9]; ?>&date=<?php print $date[9]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[9]; ?>&real_parent=<?php echo $real_parent[9]; ?>" class="jTip message warning" id="ten" name="<?php print $name[9]; ?>">
	<img src="images/<?php print $img[9]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th width=120 valign="top">

	<span class="formInfo">
	<a href="binary_tree/user10.php?name=<?php print $name[10]; ?>&username=<?php print "<u>".$user_name[10]; ?>&child=<?php print $child[10]; ?>&date=<?php print $date[10]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[10]; ?>&real_parent=<?php echo $real_parent[10]; ?>" class="jTip message warning" id="eleven" name="<?php print $name[10]; ?>">
	<img src="images/<?php print $img[10]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user11.php?name=<?php print $name[11]; ?>&username=<?php print "<u>".$user_name[11]; ?>&child=<?php print $child[11]; ?>&date=<?php print $date[11]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[11]; ?>&real_parent=<?php echo $real_parent[11]; ?>" class="jTip message warning" id="twelve" name="<?php print $name[11]; ?>">
	<img src="images/<?php print $img[11]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th>
	<th width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user12.php?name=<?php print $name[12]; ?>&username=<?php print "<u>".$user_name[12]; ?>child=<?php print $child[12]; ?>&date=<?php print $date[12]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[12]; ?>&real_parent=<?php echo $real_parent[12]; ?>" class="jTip message warning" id="thirteen" name="<?php print $name[12]; ?>">
	<img src="images/<?php print $img[12]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user13.php?name=<?php print $name[13]; ?>&username=<?php print "<u>".$user_name[13]; ?>child=<?php print $child[13]; ?>&date=<?php print $date[13]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[13]; ?>&real_parent=<?php echo $real_parent[13]; ?>" class="jTip message warning" id="fourteen" name="<?php print $name[13]; ?>">
	<img src="images/<?php print $img[13]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th width=120 valign="top">

	<span class="formInfo">
	<a href="binary_tree/user14.php?name=<?php print $name[14]; ?>&username=<?php print "<u>".$user_name[14]; ?>&child=<?php print $child[14]; ?>&date=<?php print $date[14]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[14]; ?>&real_parent=<?php echo $real_parent[14]; ?>" class="jTip message warning" id="fifteen" name="<?php print $name[14]; ?>">
	<img src="images/<?php print $img[14]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th><th width=120 valign="top">
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user15.php?name=<?php print $name[15]; ?>&username=<?php print "<u>".$user_name[15]; ?>&child=<?php print $child[15]; ?>&date=<?php print $date[15]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[15]; ?>&real_parent=<?php echo $real_parent[15]; ?>" class="jTip message warning" id="sixteen" name="<?php print $name[15]; ?>">
	<img src="images/<?php print $img[15]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
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
	</span>
	</span>
	
	</th></tr>
	
	<tr>
	<th colspan=10 width=800 height=20></th></tr>
	<tr><th colspan=2 width=240><center>
	<?php if($real_parent[1] != '')
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print $real_parent[1]; ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">New Member</div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</center></td>
	<th colspan=2 width=240><center>
	<?php if($real_parent[1] != '')
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print $real_parent[1]; ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">New Member</div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</center></td>
	<th colspan=2 width=240><center>
	<?php if($real_parent[1] != '')
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print $real_parent[1]; ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">New Member</div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</center></td>
	<th colspan=2 width=240><center>
	<?php if($comming_board_username[4] != '')
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print $comming_board_username[4]; ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">New Member</div>
<?php	} ?>	
	<img src="function/binary_layout/upper_arrow.png" /></th>
	</center></td>
	<tr>
	
	</table></center>

<?php 
	
 } 
function get_type($name)
	{
		$sql = "select type from users where username = '$name' ";
		$qur = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		while($row = mysqli_fetch_array($qur))
		return $type = $row['type'];
	}
