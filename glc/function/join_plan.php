<?php
function join_plan1($board_break_info)
{
	//////var_dump($board_break_info);
	$count = count($board_break_info);
	for($pp = 0; $pp < $count; $pp++)
	{
		$first_id = $board_break_info[$pp][0];
		$qquu_first = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$first_id'");
		$num_first = mysqli_num_rows($qquu_first); 
		if($num_first == 0)
		{
			board_break_income($first_id,1,1);
			////var_dump($first_id."1,1");
			//board_break_point($first_id,1);
			$real_par = get_real_parent($first_id);
	
			$board_break_info_second = insert_into_board_second($first_id,$real_par,$spill,$real_par);
			unset($_SESSION['board_second_breal_id']);
			$count_second = count($board_break_info_second);
			for($ij = 0; $ij < $count_second; $ij++)
			{
				$second_id = $board_break_info_second[$ij][0];
				$qquu_second = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$second_id'");
				$num_second = mysqli_num_rows($qquu_second); 
				if($num_second == 0)
				{
					board_break_income($second_id,1,2);
					////var_dump($second_id."1,2");
					//board_break_point($second_id,2);
					
					$real_par1 = get_real_parent($second_id);
					$board_break_info_third = insert_into_board_third($second_id,$real_par1,$spill,$real_par1);
					unset($_SESSION['board_third_breal_id']);
					$count_third = count($board_break_info_third);
					for($jj = 0; $jj < $count_third; $jj++)
					{
						$third_id = $board_break_info_third[$jj][0];
						$qquu_third = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fourth where user_id = '$third_id' ");
						$num_third = mysqli_num_rows($qquu_third); 
						if($num_third == 0)
						{
							board_break_income($third_id,1,3);
							////var_dump($third_id."1,3");
							//board_break_point($third_id,3);
							
							$real_par2 = get_real_parent($third_id);
							$board_break_info_fourth = insert_into_board_fourth($third_id,$real_par2,$spill,$real_par2);
							unset($_SESSION['board_fourth_breal_id']);
							$count_fourth = count($board_break_info_fourth);
							for($kk = 0; $kk < $count_fourth; $kk++)
							{
								$fourth_id = $board_break_info_fourth[$kk][0];
								$qquu_fourth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$fourth_id' ");
								$num_fourth = mysqli_num_rows($qquu_fourth); 
								if($num_fourth == 0)
								{
									board_break_income($fourth_id,1,4);
									////var_dump($fourth_id."1,4");
									//board_break_point($fourth_id,4);
							
									$real_par3 = get_real_parent($fourth_id);
									$board_break_info_fifth = insert_into_board_fifth($fourth_id,$real_par3,$spill,$real_par3);
									unset($_SESSION['board_fifth_breal_id']);
									$count_fifth = count($board_break_info_fifth);
									for($nk = 0; $nk < $count_fifth; $nk++)
									{
										$fifth_id = $board_break_info_fifth[$nk][0];
										/*$qquu_fifth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_sixth where user_id = '$fifth_id' ");
										$num_fifth = mysqli_num_rows($qquu_fifth); 
										if($num_fifth == 0)
										{
											board_break_income($fifth_id,1,5);
											//board_break_point($fifth_id,5);
										
											$real_par4 = get_real_parent($fourth_id);
											$board_break_info_sixth = insert_into_board_sixth($fifth_id,$real_par4,$spill,$real_par4);
										
											$count_sixth = count($board_break_info_sixth);
											for($nkk = 0; $nkk < $count_sixth; $nkk++)
											{
												$sixth_id = $board_break_info_sixth[$nk][0];
											
												$qquu_sixth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_seven where user_id = '$sixth_id' ");
												$num_sixth = mysqli_num_rows($qquu_sixth); 
												if($num_sixth == 0)
												{
													mysqli_query($GLOBALS["___mysqli_ston"], "insert into board_seven (user_id) values ('$sixth_id') ");
													board_break_income($sixth_id,1,6);
													//board_break_point($sixth_id,6);
												}
												else
												{
													board_break_income($sixth_id,2,6);
													//board_break_point($sixth_id,6);
												}	
											}
										}
										else
										{
											board_break_income($fourth_id,2,5);
											//board_break_point($fourth_id,5);
										}
										*/
										board_break_income($fifth_id,1,5);
										////var_dump($fifth_id."1,5");
									}			
								}
								else
								{
									board_break_income($fourth_id,2,4);
									////var_dump($fourth_id."2,4");
									//board_break_point($fourth_id,4);
								}
							}
						}
						else
						{
							board_break_income($third_id,2,3);
							////var_dump($third_id."2,3");
							//board_break_point($third_id,3);
						}
					}	
				}
				else
				{
					board_break_income($second_id,2,2);
					////var_dump($second_id."2,2");
					//board_break_point($second_id,2);
				}
			}	
		}
		else
		{
			board_break_income($first_id,2,1);
			////var_dump($first_id."2,1");
			//board_break_point($first_id,1);
		}
	}
}
//************************************************Plan 2 ***********************************//
function join_plan2($board_break_info_second)
{
	$count_second = count($board_break_info_second);
	for($ij = 0; $ij < $count_second; $ij++)
	{
		$second_id = $board_break_info_second[$ij][0];
		$qquu_second = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$second_id'");
		$num_second = mysqli_num_rows($qquu_second); 		
		if($num_second == 0)
		{
			board_break_income($second_id,1,2);
			//board_break_point($second_id,2);
			
			$real_par1 = get_real_parent($second_id);
			$board_break_info_third = insert_into_board_third($second_id,$real_par1,$spill,$real_par1);
			unset($_SESSION['board_third_breal_id']);
			$count_third = count($board_break_info_third);
			for($jj = 0; $jj < $count_third; $jj++)
			{
				$third_id = $board_break_info_third[$jj][0];
				$qquu_third = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fourth where user_id = '$third_id' ");
				$num_third = mysqli_num_rows($qquu_third); 
				if($num_third == 0)
				{
					board_break_income($third_id,1,3);
					//var_dump($third_id."-1,3");
					//board_break_point($third_id,3);
					
					$real_par2 = get_real_parent($third_id);
					$board_break_info_fourth = insert_into_board_fourth($third_id,$real_par2,$spill,$real_par2);
					unset($_SESSION['board_fourth_breal_id']);
					$count_fourth = count($board_break_info_fourth);
					for($kk = 0; $kk < $count_fourth; $kk++)
					{
						$fourth_id = $board_break_info_fourth[$kk][0];
						$qquu_fourth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$fourth_id' ");
						$num_fourth = mysqli_num_rows($qquu_fourth); 
						if($num_fourth == 0)
						{
							board_break_income($fourth_id,1,4);
							//var_dump($fourth_id."-1,4");
							//board_break_point($fourth_id,4);
					
							$real_par3 = get_real_parent($fourth_id);
							$board_break_info_fifth = insert_into_board_fifth($fourth_id,$real_par3,$spill,$real_par3);
							unset($_SESSION['board_fifth_breal_id']);
							$count_fifth = count($board_break_info_fifth);
							for($nk = 0; $nk < $count_fifth; $nk++)
							{
								$fifth_id = $board_break_info_fifth[$nk][0];
								/* $qquu_fifth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_sixth where user_id = '$fifth_id' ");
								$num_fifth = mysqli_num_rows($qquu_fifth); 
								if($num_fifth == 0)
								{
									board_break_income($fifth_id,1,5);
									//board_break_point($fifth_id,5);
								
									$real_par4 = get_real_parent($fourth_id);
									$board_break_info_sixth = insert_into_board_sixth($fifth_id,$real_par4,$spill,$real_par4);
								
									$count_sixth = count($board_break_info_sixth);
									for($nkk = 0; $nkk < $count_sixth; $nkk++)
									{
										$sixth_id = $board_break_info_sixth[$nk][0];
									
										$qquu_sixth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_seven where user_id = '$sixth_id' ");
										$num_sixth = mysqli_num_rows($qquu_sixth); 
										if($num_sixth == 0)
										{
											mysqli_query($GLOBALS["___mysqli_ston"], "insert into board_seven (user_id) values ('$sixth_id') ");
											board_break_income($sixth_id,1,6);
											//board_break_point($sixth_id,6);
										}
										else
										{
											board_break_income($sixth_id,2,6);
											//board_break_point($sixth_id,6);
										}	
									}
								}
								else
								{
									board_break_income($fourth_id,2,5);
									//board_break_point($fourth_id,5);
								} */
								board_break_income($fifth_id,1,5);
								//var_dump($fifth_id."-1,5");
							}			
						}
						else
						{
							board_break_income($fourth_id,2,4);
							//var_dump($fourth_id."-2,4");
							//board_break_point($fourth_id,4);
						}
					}
				}
				else
				{
					board_break_income($third_id,2,3);
					//var_dump($third_id."-2,3");
					//board_break_point($third_id,3);
				}
			}	
		}
		else
		{
			board_break_income($second_id,2,2);
			//var_dump($second_id."-2,2");
			//board_break_point($second_id,2);
		}
	}
									
}
//**********************************************Plan 3 **********************************//
function join_plan3($board_break_info_third)
{
	$count_third = count($board_break_info_third);
	for($jj = 0; $jj < $count_third; $jj++)
	{
		$third_id = $board_break_info_third[$jj][0];
		$qquu_third = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fourth where user_id = '$third_id' ");
		$num_third = mysqli_num_rows($qquu_third); 
		if($num_third == 0)
		{
			board_break_income($third_id,1,3);
			////var_dump($third_id."1,3");
			//board_break_point($third_id,3);
			
			$real_par2 = get_real_parent($third_id);
			$board_break_info_fourth = insert_into_board_fourth($third_id,$real_par2,$spill,$real_par2);
			unset($_SESSION['board_fourth_breal_id']);
			$count_fourth = count($board_break_info_fourth);
			for($kk = 0; $kk < $count_fourth; $kk++)
			{
				$fourth_id = $board_break_info_fourth[$kk][0];
				$qquu_fourth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$fourth_id' ");
				$num_fourth = mysqli_num_rows($qquu_fourth); 
				if($num_fourth == 0)
				{
					board_break_income($fourth_id,1,4);
					////var_dump($fourth_id."1,4");
					//board_break_point($fourth_id,4);
			
					$real_par3 = get_real_parent($fourth_id);
					$board_break_info_fifth = insert_into_board_fifth($fourth_id,$real_par3,$spill,$real_par3);
					unset($_SESSION['board_fifth_breal_id']);
					$count_fifth = count($board_break_info_fifth);
					for($nk = 0; $nk < $count_fifth; $nk++)
					{
						$fifth_id = $board_break_info_fifth[$nk][0];
						/*$qquu_fifth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_sixth where user_id = '$fifth_id' ");
						$num_fifth = mysqli_num_rows($qquu_fifth); 
						if($num_fifth == 0)
						{
							board_break_income($fifth_id,1,5);
							////var_dump($fifth_id."1,5");
							//board_break_point($fifth_id,5);
						
							$real_par4 = get_real_parent($fourth_id);
							$board_break_info_sixth = insert_into_board_sixth($fifth_id,$real_par4,$spill,$real_par4);
						
							$count_sixth = count($board_break_info_sixth);
							for($nkk = 0; $nkk < $count_sixth; $nkk++)
							{
								$sixth_id = $board_break_info_sixth[$nk][0];
							
								$qquu_sixth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_seven where user_id = '$sixth_id' ");
								$num_sixth = mysqli_num_rows($qquu_sixth); 
								if($num_sixth == 0)
								{
									mysqli_query($GLOBALS["___mysqli_ston"], "insert into board_seven (user_id) values ('$sixth_id') ");
									board_break_income($sixth_id,1,6);
									////var_dump($sixth_id."1,6");
									//board_break_point($sixth_id,6);
								}
								else
								{
									board_break_income($sixth_id,2,6);
									////var_dump($sixth_id."2,6");
									//board_break_point($sixth_id,6);
								}	
							}
						}
						else
						{
							board_break_income($fourth_id,2,5);
							////var_dump($fourth_id."2,5");
							//board_break_point($fourth_id,5);
						}
						*/
						board_break_income($fifth_id,1,5);
						////var_dump($fifth_id."1,5");
					}			
				}
				else
				{
					board_break_income($fourth_id,2,4);
					////var_dump($fourth_id."2,4");
					//board_break_point($fourth_id,4);
				}
			}
		}
		else
		{
			board_break_income($third_id,2,3);
			//board_break_point($third_id,3);
		}
	}
}
//**********************************************Plan 4 **********************************//
function join_plan4($board_break_info_fourth)
{
	$count_fourth = count($board_break_info_fourth);
	for($kk = 0; $kk < $count_fourth; $kk++)
	{
		$fourth_id = $board_break_info_fourth[$kk][0];
		$qquu_fourth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$fourth_id' ");
		$num_fourth = mysqli_num_rows($qquu_fourth); 
		if($num_fourth == 0)
		{
			board_break_income($fourth_id,1,4);
	
			$real_par3 = get_real_parent($fourth_id);
			$board_break_info_fifth = insert_into_board_fifth($fourth_id,$real_par3,$spill,$real_par3);
			unset($_SESSION['board_fifth_breal_id']);
			$count_fifth = count($board_break_info_fifth);
			for($nk = 0; $nk < $count_fifth; $nk++)
			{
				$fifth_id = $board_break_info_fifth[$nk][0];
				board_break_income($fifth_id,1,5);
			}			
		}
		else
		{
			board_break_income($fourth_id,2,4);
		}
	}
}
//**********************************************Plan 4 **********************************//
function join_plan5($board_break_info_fifth)
{
	$count_fifth = count($board_break_info_fifth);
	for($kk = 0; $kk < $count_fifth; $kk++)
	{
		$fifth_id = $board_break_info_fifth[$kk][0];
		board_break_income($fifth_id,1,5);
	}
}
?>