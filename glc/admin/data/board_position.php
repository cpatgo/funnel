<?php
session_start();

include("condition.php");
require_once("../function/functions.php");
require_once("../function/setting.php");
include("../function/binary_layout/display.php");
?>
<div class="ibox-content">
<?php

$bbid = isset($_REQUEST['bbid'])?$_REQUEST['bbid']:"";
if($bbid == '')
{
	if(isset($_POST['submit']))
	{
				$username = $_REQUEST['username'];
				$search_type = $_SESSION['board_type_store'] = $_REQUEST['search_type'];
				$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
				$num = mysqli_num_rows($qu);
				if($num > 0)
				{
					while($row = mysqli_fetch_array($qu))
					{
						$id = $row['id_user'];
						$_SESSION['chk_session_store'] = $id;
					}
				}
				else
				{
					print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct Username !</font>";
				}

							
			$b_b_id = get_board_break_id($id,$search_type);
			
			echo "
			<table class=\"table table-bordered\">
				<thead>	
				<tr>
					<th>Board Id</th>	
					<th>Board Level</th></tr>
					<th>Date</th>
				</tr>
				</thead>"; 

			$c = count($b_b_id);
			for($g = 0; $g < $c; $g++)
			{
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board".$search_type." WHERE board_id = '$b_b_id[$g]'  ");
					while($row = mysqli_fetch_array($query))
					{
						$board_id = $row['board_id'];
						$date = $row['date'];
						$level = $row['level'];
						$l_info = $level+1;
								
					echo "
						<tr>
							<td>
								<a href=\"index.php?page=board_position&bbid=$board_id\" >
									EDNET00i00$board_id
								</a>
							</td>
							<td>$l_info Level</small></td>
							<td>$date</small></td>
						</tr>";
					}
					
			}
			$curr_board = get_current_board($id,$search_type);
			
			print "</table>";
		}	
		
		
	else
	{
	?>
	<form name="myform" action="index.php?page=board_position" method="post">
	<table class="table table-bordered"> 
		<tr><th>Board Name</th>
    	<td>
			<select name="search_type" style="width:185px;">
				<option value=""><?=$setting_board_name[1]; ?></option>
				<option value="_second"><?=$setting_board_name[2]; ?></option>
				<option value="_third"><?=$setting_board_name[3]; ?></option>
				<option value="_fourth"><?=$setting_board_name[4]; ?></option>
				<option value="_fifth"><?=$setting_board_name[5]; ?></option>
				<option value="_sixth"><?=$setting_board_name[6]; ?></option>
			</select>	
		</td>
	</tr>
	<tr>
		<th>Username Id</th>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Show" class="btn btn-primary" />
		</td>
	</tr>
	</table>  
	</form>
</div>
	<?php }
}
else
{
		$board_id = $bbid;
		if($board_id != '')
		{
			$search_type = $_SESSION['board_type_store'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board".$search_type." WHERE board_id = '$board_id' ");
			while($row = mysqli_fetch_array($query))
			{
				?>

					<div style="float:right; padding-right:0px; padding-right:50px;">
						<form action="data/print_board.php" target="_new" method="post">
							<input type="hidden" name="bbid" value="<?=$board_id; ?>"  />
							<input type="submit" name="Submit" class="button" value="Print" />
						</form>
					</div>
					<!-- <a href="index.php?page=board_position">
						<img src="images/ip_icon_02_Back1.png" style="height:50px; width:50px" />
					</a>--><br />
				<?php 
				for($i=1 ; $i <16; $i++)
				{
					$pos[$i] = $bbidh = $row['pos'.$i];
					$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$bbidh' ");
					while($id_row = mysqli_fetch_array($id_query))
					{
						$type = $id_row['type'];
						$position[$i] = get_user_pos($id);
						$date[$i] = $id_row['date'];
						$gender[$i] = $id_row['gender'];
						$user_name[$i] = $id_row['username'];
						$real_parent_u[$i] = get_user_name($id_row['real_parent']);
						$cnt = count($chld);
						for($k = 0 ; $k < $cnt; $k++)
						{
							$real_child[$i][$k] = $chld[$k][0];
							$real_info_time[$i][$k] = $chld[$k][1];
						}
						$img[$i] = get_img($type);
						$name[$i] = $id_row['f_name']." ".$id_row['l_name']; 
						$parent_u_name[$i] = get_user_name($id_row['parent_id']);	
					}
				}
			}		
			$page = "index.php?page=board_position";
			$brdtp = "board".$search_type;
			display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$inserting_board,$u,$brdtp);		
		}	
		else { print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct Board Id !</font>"; }	
}	


function get_parent($id)
{
	$p_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
		while($row = mysqli_fetch_array($p_query))
		{
			$user = $row['username'];
		}	
		return $user;
}				
		
 	

function get_img($type)	
{ 
	if($type == 'B') { $imges = "b"; }
	if($type == 'C') { $imges = "d"; }
	return $imges;
}

	
			

function get_board_break_id($id,$type)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break".$type." where user_id = '$id' order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[0] = $r['board_b_id'];
		$b_b_id[1] = $r['level'];
	}
	return $b_b_id;
}

function get_current_board($id,$type)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break".$type." WHERE user_id = '$id' order by id desc limit 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$level = $row['level'];
	}
	return $level;
}		
		
 ?>

