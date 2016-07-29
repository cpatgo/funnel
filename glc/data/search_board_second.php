<?php
require_once("config.php");
include("condition.php");
require_once("function/functions.php");
require_once("function/setting.php");
include("function/binary_layout/display.php");

$id = $_SESSION['dennisn_user_id'];

if(isset($_POST['submit']))
{ 
	 $username = $_REQUEST['username'];
	$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
	$num = mysqli_num_rows($qu);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($qu))
		{
			$user_id = $row['id_user'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE pos1 = '$user_id' ");
			while($row = mysqli_fetch_array($query))
			{
				$board_id = $row['board_id'];
			}	 
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=my_board_second&bbid=$board_id\"";
			echo "</script>";
		}		
	}
	else
	{	echo "<B style=\"color:#FF0000; font-size:12pt;\">$Error</B>"; }	
	
	if($board_id != '')
	{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE board_id = '$board_id' ");
		$bnum = mysqli_num_rows($query);
		if($bnum > 0)
		{
			while($row = mysqli_fetch_array($query))
			{
				$bb_level = $row['level'];
				?>
					<div style="width:400px; height:40px; float:left;">
						<B style="color:#003D79; font-size:12pt;"><?=$board_names[$bb_level+1]; ?></B>
					</div>
					<div style="width:200px; height:50px; float:right; text-align:center;">
						<B>	
							<a href="#" class="big-link" data-reveal-id="myModal" style="text-align:left; color:#660033; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9pt; font-weight:bold;">
								<?=$Print_Board;?>
							</a>
						</B>
					</div> <br /><br /><br />
					<?php 
				for($i=1 ; $i <16; $i++)
				{
					$poss[$i] = $bbid = $row['pos'.$i];
					$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$bbid' ");
					while($id_row = mysqli_fetch_array($id_query))
					{
						$type = $id_row['type'];
						$position[$i] = get_user_pos($id);
						$date[$i] = $id_row['date'];
						$gender[$i] = $id_row['gender'];
						$user_name[$i] = $id_row['username'];
						$real_parent_u[$i] = get_user_name($id_row['real_parent']);	
						$chld = get_qualification_summary($bbid,$bb_level,$board_id);
						$cnt = count($chld);
						$img[$i] = get_img($type);
						for($k = 0 ; $k < $cnt; $k++)
						{
							$real_child[$i][$k] = $chld[$k][0];
							$real_info_time[$i][$k] = $chld[$k][1];
						}
						$name[$i] = $id_row['f_name']." ".$id_row['l_name']; 
						$parent_u_name[$i] = get_user_name($id_row['parent_id']);	
					}
				}
				$page = "index.php?val=board_position&open=3";
				search_display($poss,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$r,$m);	
				
	?>			
				
		<link rel="stylesheet" href="css/reveal.css">
		<script type="text/javascript" src="js/jquery-1.6.min.js"></script>
		<script type="text/javascript" src="js/jquery.reveal.js"></script>		

		<div id="myModal" class="reveal-modal" style="margin-left:-570px; width:800px;">
			<h1 align="center"><?=$Reveal_Modal;?></h1>
			<?php
				search_display($poss,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$r,$m);	
			?>
			<a class="close-reveal-modal">&#215;</a>
		</div>
				
				
<?php		}
		}	
		else { echo "<B style=\"color:#FF0000; font-size:12pt;\">$Plz_en_Cor_B_Id</B>"; }	
	}
	
	
}
else
{

?>
<div class="ibox-content">		
<form name="myform" action="index.php?page=search_board_second" method="post">
<table class="table table-bordered">
  <thead><tr><th colspan="2"><?=$Search_Board;?></th></tr></thead>
	<tbody>
	<!--<tr>
		<td><?=$Search_By;?> :</td>
		<td>
			<input type="radio" name="search" value="username" ><?=$username1;?>
			<input type="radio" name="search" value="board_id" ><?=$Board_Id;?>
		</td>
	</tr>-->
	<tr>
		<td><?=$Enter_Username;?></td>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
	<td colspan="2" align="center">
		<input type="submit" value="<?=$Submit;?>" name="submit" class="btn btn-primary" />
	</td>
	</tr>
	</tbody>
</table>
</form>
</div>

<?php }


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

	
			

function get_board_break_id($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$id' order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[0] = $r['board_b_id'];
		$b_b_id[1] = $r['level'];
	}
	return $b_b_id;
}

function get_send_level_board($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$id'  order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$second_level_board = $r['board_b_id'];
	}
	return $second_level_board;
}

function get_qualification_summary($id,$level,$u_bbb_id)
{
	$h = 0;
	if($id != 0)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_second where board_id = '$u_bbb_id' ");
		while($rrr = mysqli_fetch_array($q))
		{
			$time = $rrr['time'];
			$mode = $rrr['mode'];
			if($mode == 0)
				$time_qur = "and time <= '$time'";
			else
				$time_qur = "";	
		}	
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_second WHERE qualified_id = '$id' and level = '$level' $time_qur group by user_id  order by time ");
		while($row = mysqli_fetch_array($query))
		{
			$username_id = $row['user_id'];
			$username[$h][0] = get_user_name($username_id);
			$username[$h][1] = $row['time'];
			$h++;
		}
	}
	return $username;	
}		


function get_current_board($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_second WHERE user_id = '$id' order by id desc limit 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$level = $row['level'];
	}
	return $level;
}	


/*function chk_member($req_id,$level) 
	{
		$tbl_qur = $tbl_qur2 = ""; 
	for($i = 2; $i <= $level; $i++)
	{
		$j = $i-1;
		$k = ($i+1)-1;
		if($j==1)
			$tbl_qur .= "t".$j.".id_user AS lev".$j." ,";
		if($i == $level)
			$tbl_qur .= "t".$i.".id_user AS lev".$i." ";
		else
			$tbl_qur .= "t".$i.".id_user AS lev".$i." , ";
			
		$tbl_qur2 .= "LEFT JOIN users AS t".$k." ON t".$k.".parent_id = t".$j.".id_user ";	
	}
	$query = mysql_query("SELECT $tbl_qur
				FROM users AS t1
				$tbl_qur2
				WHERE t1.id_user= '$req_id'");	
	
			$result = mysql_query($query);
			
				if (!$result)
				 {
					$message  = 'Invalid query: ' . mysql_error() . "\n";
					$message .= 'Whole query: ' . $query;
					die($message);
				}
	
			$k = 0;
			while ($row = mysql_fetch_assoc($result))
			{
				for($i = 0; $i < $level; $i++)
				{
					$j = $i+1;
					$lev_arr[$k][$i] = $row['lev'.$j];
					if($req_id === $row['lev'.$j])
					$find = 1;
					else 
					$find = 0;
				}
				$k++;					
			}
			
			return $find;
	}	
*/		
 ?>
