<?php


function trim_name_display($strname) {

	$max_char = 8;
	$trun_char = 6;

	if($strname === "<a href='index.php?page=invite_friends' target='_blank' style='color: #0066BF'>Invite Friend</a>") {
		return $strname;
	}
	else{
		if (strlen($strname) > $max_char) {
			$strname = substr($strname, 0, $trun_char) . '...';
			return $strname; 
		}
		else {
			return $strname;
		}	
	}
}


function display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent,$all_child,$l,$qualify_time,$comming_board_username,$inserting_board,$path)
{
	for($i = 1; $i < 8; $i++)
	{
		$child[$i] = $all_child[$i][0]."@".$all_child[$i][1]."@".$all_child[$i][2];
		if($pos[$i] == '' or $pos[$i] == 0) { $img[$i] = "d"; }
		$u_type[$i] = get_type($user_name[$i]);
		$class[$i] = "empty";
		if($user_name[$i] != "")
		{
			$class[$i] = ($user_name[$i] == $_SESSION['dennisn_username'])?"yourpos":"filled";
		} else {
			$user_name[$i] = "<a href='index.php?page=invite_friends' target='_blank' style='color: #0066BF'>Invite Friend</a>";
		}

	} 
	$inserting_username = get_user_name($inserting_board);
	
?>

<style type="text/css" media="all">
@import "css/global.css";

</style>

 
<style type="text/css"> 
a{color:#2895f1;}
.table-board{
	max-width: 800px;
	margin: 0 auto;
}
 .filled { background: url(images/<?=$path; ?>/filled.png) no-repeat;  height:120px;width:120px; }
 .empty { background: url(images/<?=$path; ?>/empty.png) no-repeat;  height:120px;width:120px; }
 .yourpos { background: url(images/<?=$path; ?>/yourpos.png) no-repeat;  height:120px;width:120px; }
 /*
.imgBox { background: url(images/<?=$path; ?>/oak1.png) no-repeat; width:100px; height:113px; text-align:center; }
 .imgBox:hover {  background: url(images/<?=$path; ?>/oak_hover.png) no-repeat;width:100px; height:113px; }
 */
 .imgBox_n { background: url(images/<?=$path; ?>/oak2.png) no-repeat;  height:90px;width:80px; }
 .imgBox_n:hover {  background: url(images/<?=$path; ?>/oak_nhover.png) no-repeat;width:80px; height:82px;  }
 
 .imgBox_n1 { background: url(images/<?=$path; ?>/oak2.png) no-repeat;  height:90px;width:82px; }
 .imgBox_n1:hover {  background: url(images/<?=$path; ?>/oak_nhover.png) no-repeat; height:82px;width:80px; }
 
 .imgBox_n2 { background: url(images/<?=$path; ?>/oak2nn.png) no-repeat;  height:62px;width:60px; }
 .imgBox_n2:hover {  background: url(images/<?=$path; ?>/oak_nhovernn.png) no-repeat; height:62px;width:60px; }
 .imgBox_n_top{ background: url(images/<?=$path; ?>/oak_top.png) no-repeat; height:64px;width:130px; line-height:22px; 
 }
 .imgBox_n_top:hover{ background: url(images/<?=$path; ?>/oak_top_hover.png) no-repeat; height:64px;width:130px; line-height:22px;
 }
 
 .imgBox_n_bottom{ background: url(images/<?=$path; ?>/oak_bottom.png) no-repeat; height:39px;width:80px; line-height:22px; 
 }
 .imgBox_n_bottom:hover{ background: url(images/<?=$path; ?>/oak_bottom_hover.png) no-repeat; height:39px;width:80px; line-height:22px;
 }
.imgBox div a
{
    line-height: 120px;
    text-align: left;
    width: 120px;
	color: #fff;
	font-weight: bold;
}
.imgBox_n div 
{
    position: absolute;
    top: 300px;
    width: 90px;
}
.imgBox_n1 div 
{
    position: absolute;
    top: 460px;
    width: 80px;
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
<div>
	<table class="table table-hover table-board">
<!--	<tr><th colspan=10 valign="top" align="center">
	<?php 
	//print $path;
	if($real_parent[1] != '')
	{ ?>
		<div class="imgBox_n_top">
		 <div style=" padding-top:20px;">
		<?=$real_parent[1]; ?></div>
		</div>
		<?php
	}
	else
	{ ?>
		<div class="imgBox_n_top">
			<div style=" padding-top:20px;">New Member</div>
		</div>
		<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></th>
	</tr>
-->
	<tr>
	<tr><td colspan=10 align="center">
	<div class="<?php echo $class[1]; ?> imgBox" style="margin-left:0px; display:block;">
	<div>
	<a  href="binary_tree/user1.php?name=<?=$name[1]; ?>&username=<?=$user_name[1]; ?>&child=<?=$child[1]; ?>&date=<?=$date[1]; ?>&level=<?=$level; ?>&gender=<?=$gender[1]; ?>&sponser_name=<?=$real_parent[1]; ?>&id=<?=$pos[1]; ?>" class="jTip" id="two" name="<?=$name[1]; ?>" style="text-decoration:none;">&nbsp;
	<?php 
		print trim_name_display($user_name[1]);
		if($all_child[1][0] != '')
		{
			print "<font color=green>".trim_name_display($all_child[1][0])."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[1][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[1][0] )."<br>";
			if($all_child[1][1] != '')
			{
				print "<font color=green>".trim_name_display($all_child[1][1])."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[1][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[1][1] ); 
			}	
			
		}	?></a>
	</div> </div>
	<!--<img src="images/oak1.png" height=150 style="margin-left:-30px;" class="imgbox1"/>-->
	</td></tr>
	<tr>
	<td colspan="10" align="center"><img src="function/binary_layout/arrow.png"  /></td>
	</tr>
	<!--<tr><th colspan=10 width=800 style="background-image:url(back_line.png);background-repeat: no-repeat;background-position: center;" height=10>
	</th></tr>-->
	<tr>
	<td colspan="4" valign="top" align="center">	
	<div class="<?php echo $class[2]; ?> imgBox" style="display:block;">
		<div>
		<a style="margin-left:0px;" href="binary_tree/user2.php?name=<?=$name[2]; ?>&username=<?="".$user_name[2]; ?>&child=<?=$child[2]; ?>&date=<?=$date[2]; ?>&level=<?=$level; ?>&gender=<?=$gender[2]; ?>&sponser_name=<?=$real_parent[2]; ?>" class="jTip" id="three" name="<?=$name[2]; ?>" >
		<?php 
			print trim_name_display($user_name[2]);
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
		</a>
		</div>
	 </div>
	
	
	</td>
	<!--<th rowspan="9"><img src=images/button-blue.jpg height=350 width="1px"/></th>-->
	<td colspan="4" valign="top" align="center">
	
	<div class="<?php echo $class[3]; ?> imgBox" style="display:block;">
		<div>
		<a style="margin-left:0px;" href="binary_tree/user3.php?name=<?=$name[3]; ?>&username=<?="".$user_name[3]; ?>&child=<?=$child[3]; ?>&date=<?=$date[3]; ?>&level=<?=$level; ?>&gender=<?=$gender[3]; ?>&sponser_name=<?=$real_parent[3]; ?>" class="jTip" id="four" name="<?=$name[3]; ?>">
		<?php 
			print "".trim_name_display($user_name[3]);
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=120/>-->
	
	
	</td>
	</tr>
	<tr>
	<td colspan=4 align="center"><img src="function/binary_layout/arrow2.png"  /></td>
	<td colspan=4 align="center"><img src="function/binary_layout/arrow2.png"   /></td>
	</tr>
	<tr>
	<td colspan="2" valign="top" align="center">
	<div class="<?php echo $class[4]; ?> imgBox" style="display:block;">
		<div>
			<a href="binary_tree/user4.php?name=<?=$name[4]; ?>&username=<?="".$user_name[4]; ?>&child=<?=$child[4]; ?>&date=<?=$date[4]; ?>&level=<?=$level; ?>&gender=<?=$gender[4]; ?>&sponser_name=<?=$real_parent[4]; ?>" class="jTip" id="five" name="<?=$name[4]; ?>" style="text-decoration:none;">
			<?php 
				print "".trim_name_display($user_name[4]);
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
				</a>
			</div>
	 </div>
	<!--<img src="images/oak2.png" height=90/>-->
	
	
	</td>
	<td colspan="2" valign="top" align="center">
	<div class="<?php echo $class[5]; ?> imgBox" style="display:block;">

		<div>
		<a href="binary_tree/user5.php?name=<?=$name[5]; ?>&username=<?="".$user_name[5]; ?>&child=<?=$child[5]; ?>&date=<?=$date[5]; ?>&level=<?=$level; ?>&gender=<?=$gender[5]; ?>&sponser_name=<?=$real_parent[5]; ?>" class="jTip" id="six" name="<?php print $name[5]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[5]);
		if($all_child[5][0] != '')
		{
			print "<font color=green>" . trim_name_display($all_child[5][0]) ."</font><br>";
			//print "<font color=red>".date('d-m-Y ' ,$qualify_time[5][0] )."<br>";
			//print date('h:j:s A' ,$qualify_time[5][0] )."<br>";
			if($all_child[5][1] != '')
			{
				print "<font color=green>".$all_child[5][1]."</font><br>";
				//print "<font color=red>".date('d-m-Y ' ,$qualify_time[5][1] )."<br>";
				//print date('h:j:s A' ,$qualify_time[5][1] ); 
			}	
		}	?>
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=90/>-->
	
	
	
	</td>
	<td colspan="2" valign="top" align="center">

	<div class="<?php echo $class[6]; ?> imgBox" style="display:block;">
		<div>
			<a href="binary_tree/user6.php?name=<?php print $name[6]; ?>&username=<?php print "".$user_name[6]; ?>&child=<?php print $child[6]; ?>&date=<?php print $date[6]; ?>&level=<?php print $level; ?>&gender=<?=$gender[6]; ?>&sponser_name=<?=$real_parent[6]; ?>" class="jTip" id="server" name="<?php print $name[6]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[6]);
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=90/>-->
	</td>
	<td colspan="2"  valign="top" align="center">
	
	<div class="<?php echo $class[7]; ?> imgBox" style="display:block;">
		<div>
		<a href="binary_tree/user7.php?name=<?php print $name[7]; ?>&username=<?php print "".$user_name[7]; ?>&child=<?php print $child[7]; ?>&date=<?php print $date[7]; ?>&level=<?php print $level; ?>&gender=<?=$gender[7]; ?>&sponser_name=<?=$real_parent[7]; ?>" class="jTip" id="eight" name="<?php print $name[7]; ?>" style="text-decoration:none;">
		<?php 
		print "".$user_name[7];	
		//echo '<script>console.log("'.$user_name[7].'")</script>';
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
		}	?></a>
	</div>
	</div>
	<!--<img src="images/oak2.png" height=90/>-->
	</td>
	</tr>
	<!--<tr>
	<td colspan=2><img src="function/binary_layout/arrow3.png" /></td>
	<td colspan=2><img src="function/binary_layout/arrow3.png"/></td>
	<td colspan=2><img src="function/binary_layout/arrow3.png"/></td>
	<td colspan=2><img src="function/binary_layout/arrow3.png" /></td>
	</tr>
	<!--<tr>
	<td colspan=8 height="5"></td>
	<tr>
	<td valign="top" align="center">
	<div class="imgBox_n2" style="display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
			<a href="binary_tree/user8.php?name=<?php print $name[8]; ?>&username=<?php print "".$user_name[8]; ?>&child=<?php print $child[8]; ?>&date=<?php print $date[8]; ?>&level=<?php print $level; ?>&gender=<?=$gender[8]; ?>&sponser_name=<?=$real_parent[8]; ?>" class="jTip" id="nine" name="<?php print $name[8]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[8])."
		
		"."";
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=60/>
	</td>
	<td valign="top" align="center">
	<div class="imgBox_n2" style="display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user9.php?name=<?php print $name[9]; ?>&username=<?php print "".$user_name[9]; ?>&child=<?php print $child[9]; ?>&date=<?php print $date[9]; ?>&level=<?php print $level; ?>&gender=<?=$gender[9]; ?>&sponser_name=<?=$real_parent[9]; ?>" class="jTip" id="ten" name="<?php print $name[9]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[9])."
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
		</a>
		</div>
	</div>
			<!--<img src="images/oak2.png" height=60/>
	</td><td valign="top" align="center">
	
	<div class="imgBox_n2" style=" display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user10.php?name=<?php print $name[10]; ?>&username=<?php print "".$user_name[10]; ?>&child=<?php print $child[10]; ?>&date=<?php print $date[10]; ?>&level=<?php print $level; ?>&gender=<?=$gender[10]; ?>&sponser_name=<?php echo $real_parent[10]; ?>" class="jTip" id="eleven" name="<?php print $name[10]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[10])."
		
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=60/>
	
	</td><td valign="top" align="center">
	
	<div class="imgBox_n2" style=" display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user11.php?name=<?php print $name[11]; ?>&username=<?php print "".$user_name[11]; ?>&child=<?php print $child[11]; ?>&date=<?php print $date[11]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[11]; ?>&sponser_name=<?php echo $real_parent[11]; ?>" class="jTip" id="twelve" name="<?php print $name[11]; ?>" style="text-decoration:none;">			
	<?php 
		print "".trim_name_display($user_name[11])."
		
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=60/>
	

	
	</td>
	<td valign="top" align="center">
	
	<div class="imgBox_n2" style="display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user12.php?name=<?php print $name[12]; ?>&username=<?php print "".$user_name[12]; ?>child=<?php print $child[12]; ?>&date=<?php print $date[12]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[12]; ?>&sponser_name=<?php echo $real_parent[12]; ?>" class="jTip" id="thirteen" name="<?php print $name[12]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[12])."
		
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=60/>
	</td>
	<td valign="top" align="center">
	
	<div class="imgBox_n2" style="display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user13.php?name=<?php print $name[13]; ?>&username=<?php print "".$user_name[13]; ?>child=<?php print $child[13]; ?>&date=<?php print $date[13]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[13]; ?>&sponser_name=<?php echo $real_parent[13]; ?>" class="jTip" id="fourteen" name="<?php print $name[13]; ?>" style="text-decoration:none;">
	<?php 
		print "".trim_name_display($user_name[13])."
		
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=60/>
	
	</td>
	<td valign="top" align="center">
	
	<div class="imgBox_n2" style="display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user14.php?name=<?php print $name[14]; ?>&username=<?php print "".$user_name[14]; ?>&child=<?php print $child[14]; ?>&date=<?php print $date[14]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[14]; ?>&sponser_name=<?php echo $real_parent[14]; ?>" class="jTip" id="fifteen" name="<?php print $name[14]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[14])."
		
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
		</a>
		</div>	
	</div>
	<!--<img src="images/oak2.png" height=60/>
	</td>
	<td valign="top" align="center">
	<div class="imgBox_n2" style="display:block;">
		<div style="padding-top:40px;font-size:10px; display:block;">
		<a href="binary_tree/user15.php?name=<?php print $name[15]; ?>&username=<?php print "".$user_name[15]; ?>&child=<?php print $child[15]; ?>&date=<?php print $date[15]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[15]; ?>&sponser_name=<?php echo $real_parent[15]; ?>" class="jTip" id="sixteen" name="<?php print $name[15]; ?>" style="text-decoration:none;">
		<?php 
		print "".trim_name_display($user_name[15])."
		
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
		</a>
		</div>
	</div>
	<!--<img src="images/oak2.png" height=60/>
	
	
	</td></tr>-->
	<tr>
	<td colspan=10 width=800 height=20></td></tr>
	
	
<!--	<tr>
	<tr>
	<td colspan=8 height="55"></td>
	<tr>
	<tr>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[0][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[1][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[2][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[3][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[4][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[5][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[6][0]; ?></td>
		<td width=100 height=30 align="center"><img src="images/o.png" height=50/><br /><?php print $comming_board_username[7][0]; ?></td>
	</td>
	</tr>
-->	</table>
<!--
<table width="100%" style="padding-right:100px; font-size:10px;">
<tr><td align="center">
	<?php if($comming_board_username[1] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td align="center">
	<?php if($comming_board_username[2] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td align="center">
	<?php if($comming_board_username[3] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
		
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	
	<td valign="top" align="center">
	<?php if($comming_board_username[4] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	<td align="center">
	<?php if($comming_board_username[5] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td  align="center">
	<?php if($comming_board_username[6] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td  align="center">
	<?php if($comming_board_username[7] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
		
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	
	<td valign="top" align="center">
	<?php if($comming_board_username[8] != '')
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	</tr>
</table>
-->
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
<table width="100%" style="padding-right:100px;">
<tr><td align="center">
	<?php if($comming_board_username[1] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[1]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td align="center">
	<?php if($comming_board_username[2] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[2]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td align="center">
	<?php if($comming_board_username[3] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[3]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
		
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	
	<td valign="top" align="center">
	<?php if($comming_board_username[4] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[4]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	<td align="center">
	<?php if($comming_board_username[5] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[5]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td  align="center">
	<?php if($comming_board_username[6] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[6]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>

	<td  align="center">
	<?php if($comming_board_username[7] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[7]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
		
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	
	<td valign="top" align="center">
	<?php if($comming_board_username[8] != '')
	{ ?>
		<div class="imgBox_n_bottom" style=""> </div>
		<div style=" width:150px; margin-top:-40px;">
		<?php print get_user_name($comming_board_username[8]); ?></div><?php
	}
	else
	{ ?>
		<div class="imgBox_n_bottom" style="display:block;">
			<div style="padding-top:8px;">New Member</div>
		 </div>
<?php	} ?>	
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	</tr>
</table>
<style type="text/css" media="all">
@import "css/global.css";
</style>

<script src="js2/jquery.js" type="text/javascript"></script>
<script src="js2/jtip.js" type="text/javascript"></script>
	<br /><center>
	<table  class="message success" style="color:#000000;">
	<tr>
		<td width=150 height=100><img src=images/e.png height=50/><br />Blank Position </td>
		<td width=150 height=100><img src=images/u.png height=50/><br />Unqualified</td>
		<td width=150 height=100><img src=images/o.png height=50/><br /> One Qualification</td>
		<td width=150 height=100><img src=images/q.png height=50/><br />Qualified</td>
		<td width=150 height=100><img src=images/f.png height=50/><br />Blocked Account </td>
	</tr>
	</table>
	<br />	
	<table width=800 border=0 hspace=0 vspace=0 cellspacing=0 cellpadding=0>
	<tr>
	<td colspan=10>&nbsp;</td>
	</tr>
	<tr>
	<td colspan=10 width=850 height=10></td></tr>
	<tr><td colspan=10 width=900 height="150" valign="top">
	<span class="formInfo">
	<a href="binary_tree/user1.php?name=<?php print $name[1]; ?>&username=<?php print "".$user_name[1]; ?>&child=<?php print $child[1]; ?>&date=<?php print $date[1]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[1]; ?>&real_parent=<?php echo $real_parent[1]; ?>&id=<?php echo $pos[1]; ?>" class="message success jTip " id="two" name="<?php print $name[1]; ?>" >
	<img src="images/<?php print $img[1]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[1]."<br>";
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
		

		
		
	</td></tr>
	<tr>
	<td colspan=10><img src="function/binary_layout/arrow.png" /></td>
	</tr>
	<!--<tr><td colspan=10 width=800 style="background-image:url(back_line.png);background-repeat: no-repeat;background-position: center;" height=10>
	</td></tr>-->
	<tr><td colspan=4 width=400 valign="top">	
	<span class="formInfo">
	<a href="binary_tree/user2.php?name=<?php print $name[2]; ?>&username=<?php print "".$user_name[2]; ?>&child=<?php print $child[2]; ?>&date=<?php print $date[2]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[2]; ?>&real_parent=<?php print $real_parent[2]; ?>" class="jTip message error" id="three" name="<?php print $name[2]; ?>">
	<img src="images/<?php print $img[2]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[2]."<br>";
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
	
	</td>
	<td rowspan="9"><img src=images/button-blue.jpg height=350 width="1px"/></td>
	<td colspan=4 width=400 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user3.php?name=<?php print $name[3]; ?>&username=<?php print "".$user_name[3]; ?>&child=<?php print $child[3]; ?>&date=<?php print $date[3]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[3]; ?>&real_parent=<?php echo $real_parent[3]; ?>" class="jTip message error" id="four" name="<?php print $name[3]; ?>">
	<img src="images/<?php print $img[3]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[3]."<br>";
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
	
	</td></tr>
	<tr>
	<td colspan=4><img src="function/binary_layout/arrow.png" width=50%/></td>
	<td colspan=4><img src="function/binary_layout/arrow.png" width=50%/></td>
	</tr>
	<tr><td colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user4.php?name=<?php print $name[4]; ?>&username=<?php print "".$user_name[4]; ?>&child=<?php print $child[4]; ?>&date=<?php print $date[4]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[4]; ?>&real_parent=<?php echo $real_parent[4]; ?>" class="jTip message warning" id="five" name="<?php print $name[4]; ?>">
	<img src="images/<?php print $img[4]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[4]."<br>";
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
	
	</td><td colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user5.php?name=<?php print $name[5]; ?>&username=<?php print "".$user_name[5]; ?>&child=<?php print $child[5]; ?>&date=<?php print $date[5]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[5]; ?>&real_parent=<?php echo $real_parent[5]; ?>" class="jTip message warning" id="six" name="<?php print $name[5]; ?>">
	<img src="images/<?php print $img[5]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[5]."<br>";
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
	
	</td><td colspan=2 width=200 valign="top">

	<span class="formInfo">
	<a href="binary_tree/user6.php?name=<?php print $name[6]; ?>&username=<?php print "".$user_name[6]; ?>&child=<?php print $child[6]; ?>&date=<?php print $date[6]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[6]; ?>&real_parent=<?php echo $real_parent[6]; ?>" class="jTip message warning" id="server" name="<?php print $name[6]; ?>">
	<img src="images/<?php print $img[6]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[6]."<br>";
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
	
	</td><td colspan=2 width=200 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user7.php?name=<?php print $name[7]; ?>&username=<?php print "".$user_name[7]; ?>&child=<?php print $child[7]; ?>&date=<?php print $date[7]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[7]; ?>&real_parent=<?php echo $real_parent[7]; ?>" class="jTip warning" id="eight" name="<?php print $name[7]; ?>">
	<img src="images/<?php print $img[7]; ?>.png"  height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[7]."<br>";
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
	
	</td>
	</tr>
	<tr>
	<td colspan=2><img src="function/binary_layout/arrow.png" width=50%/></td>
	<td colspan=2><img src="function/binary_layout/arrow.png" width=50%/></td>
	<td colspan=2><img src="function/binary_layout/arrow.png" width=50%/></td>
	<td colspan=2><img src="function/binary_layout/arrow.png" width=50%/></td>
	</tr>
	<tr>
	<td colspan=8 height="5">&nbsp;</td>
	<tr>
	<td width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user8.php?name=<?php print $name[8]; ?>&username=<?php print "".$user_name[8]; ?>&child=<?php print $child[8]; ?>&date=<?php print $date[8]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[8]; ?>&real_parent=<?php echo $real_parent[8]; ?>" class="jTip message warning" id="nine" name="<?php print $name[8]; ?>">
	<img src="images/<?php print $img[8]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[8]."<br>";
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
	
	</td><td width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user9.php?name=<?php print $name[9]; ?>&username=<?php print "".$user_name[9]; ?>&child=<?php print $child[9]; ?>&date=<?php print $date[9]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[9]; ?>&real_parent=<?php echo $real_parent[9]; ?>" class="jTip message warning" id="ten" name="<?php print $name[9]; ?>">
	<img src="images/<?php print $img[9]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[9]."<br>";
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
	
	</td><td width=120 valign="top">

	<span class="formInfo">
	<a href="binary_tree/user10.php?name=<?php print $name[10]; ?>&username=<?php print "".$user_name[10]; ?>&child=<?php print $child[10]; ?>&date=<?php print $date[10]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[10]; ?>&real_parent=<?php echo $real_parent[10]; ?>" class="jTip message warning" id="eleven" name="<?php print $name[10]; ?>">
	<img src="images/<?php print $img[10]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[10]."<br>";
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
	
	</td><td width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user11.php?name=<?php print $name[11]; ?>&username=<?php print "".$user_name[11]; ?>&child=<?php print $child[11]; ?>&date=<?php print $date[11]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[11]; ?>&real_parent=<?php echo $real_parent[11]; ?>" class="jTip message warning" id="twelve" name="<?php print $name[11]; ?>">
	<img src="images/<?php print $img[11]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[11]."<br>";
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
	
	</td>
	<td width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user12.php?name=<?php print $name[12]; ?>&username=<?php print "".$user_name[12]; ?>child=<?php print $child[12]; ?>&date=<?php print $date[12]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[12]; ?>&real_parent=<?php echo $real_parent[12]; ?>" class="jTip message warning" id="thirteen" name="<?php print $name[12]; ?>">
	<img src="images/<?php print $img[12]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[12]."<br>";
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
	
	</td><td width=120 valign="top">
	
	<span class="formInfo">
	<a href="binary_tree/user13.php?name=<?php print $name[13]; ?>&username=<?php print "".$user_name[13]; ?>child=<?php print $child[13]; ?>&date=<?php print $date[13]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[13]; ?>&real_parent=<?php echo $real_parent[13]; ?>" class="jTip message warning" id="fourteen" name="<?php print $name[13]; ?>">
	<img src="images/<?php print $img[13]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[13]."<br>";
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
	
	</td><td width=120 valign="top">

	<span class="formInfo">
	<a href="binary_tree/user14.php?name=<?php print $name[14]; ?>&username=<?php print "".$user_name[14]; ?>&child=<?php print $child[14]; ?>&date=<?php print $date[14]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[14]; ?>&real_parent=<?php echo $real_parent[14]; ?>" class="jTip message warning" id="fifteen" name="<?php print $name[14]; ?>">
	<img src="images/<?php print $img[14]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[14]."<br>";
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
	
	</td><td width=120 valign="top">
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user15.php?name=<?php print $name[15]; ?>&username=<?php print "".$user_name[15]; ?>&child=<?php print $child[15]; ?>&date=<?php print $date[15]; ?>&level=<?php print $level; ?>&gender=<?php echo $gender[15]; ?>&real_parent=<?php echo $real_parent[15]; ?>" class="jTip message warning" id="sixteen" name="<?php print $name[15]; ?>">
	<img src="images/<?php print $img[15]; ?>.png" height=50/>
	</a><span class="dashboard_button_heading"><center>
	<div style="background:#E2FAAB; width:90px; height:60px; border:solid 1px #006600;">
	<?php 
		print "".$user_name[15]."<br>";
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
	
	</td></tr>
	
	<tr>
	<td colspan=10 width=800 height=20></td></tr>
	<tr><td colspan=2 width=240><center>
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
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	</center></td>
	<td colspan=2 width=240><center>
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
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	</center></td>
	<td colspan=2 width=240><center>
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
	<br /><img src="function/binary_layout/upper_arrow.png" /></td>
	</center></td>
	<td colspan=2 width=240><center>
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
	<img src="function/binary_layout/upper_arrow.png" /></td>
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
