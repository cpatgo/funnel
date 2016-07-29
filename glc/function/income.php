<?php
//level - level
//type - first time cycle(2) or infinity(1)
//board_type - advanced to level

function board_break_income($id,$type,$levels)
{ 
    include("setting.php");
    require_once(dirname(dirname(__FILE__))."/config.php");
    $income_class = getInstance('Class_Income');

    $date = date('Y-m-d');
    $types = get_type($id);
    $time = time();
    $datetime = date('Y-m-d H:i:s');
    //var_dump($types);
    if($types != 'F')
    {   
        if($type > 1)
            // Check if the user has enough enrollees and if the user is qualified
            $child = is_user_qualified($id);
        else
            $child = 2; 
        
        /*$query = mysql_query("select * from income where user_id = '$id' and level = '$levels' ");
        $num = mysql_num_rows($query);
        if($num == 0)
            $income = $board_income[$levels][1];
        else*/
        /* Addon by Virginia - double check do not pay someone commision the first time that cycle*/
        $pay_commission = false;
        $advanced = false;
        switch ($levels) {
            case 1:
                $query_2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_second WHERE user_id = '$id'");  
                $num_of_cycle_2 = mysqli_num_rows($query_2);
                $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE pos1 = '$id' AND mode = 0");   
                $num_of_cycle = mysqli_num_rows($query);
                //If user is already in board_break_second and already completed 2 pay cycles, pay commission true
                if($num_of_cycle_2 > 0 && $num_of_cycle > 1) $pay_commission = true;
                //If user is not yet in board_break_second table, advance is true
                if($num_of_cycle_2 == 0) $advanced = true;
                break;
            case 2:
                $query_3 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_third WHERE user_id = '$id'");   
                $num_of_cycle_3 = mysqli_num_rows($query_3);
                $query_2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE pos1 = '$id' AND mode = 0");  
                $num_of_cycle_2 = mysqli_num_rows($query_2);
                if($num_of_cycle_3 > 0 && $num_of_cycle_2 > 1) $pay_commission = true;
                if($num_of_cycle_3 == 0) $advanced = true;
                break;
            case 3:
                $query_4 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_fourth WHERE user_id = '$id'");  
                $num_of_cycle_4 = mysqli_num_rows($query_4);
                $query_3 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE pos1 = '$id'  AND mode = 0");  
                $num_of_cycle_3 = mysqli_num_rows($query_3);
                if($num_of_cycle_4 > 0 && $num_of_cycle_3 > 1) $pay_commission = true;
                if($num_of_cycle_4 == 0) $advanced = true;
                break;
            case 4:
                $query_5 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_fifth WHERE user_id = '$id'");   
                $num_of_cycle_5 = mysqli_num_rows($query_5);
                $query_4 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fourth WHERE pos1 = '$id'  AND mode = 0"); 
                $num_of_cycle_4 = mysqli_num_rows($query_4);    
                if($num_of_cycle_5 > 0 && $num_of_cycle_4 > 1) $pay_commission = true;
                if($num_of_cycle_5 == 0) $advanced = true;
                break;
            case 5:
                $query_5 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fifth WHERE pos1 = '$id' AND mode = 0");   
                $num_of_cycle_5 = mysqli_num_rows($query_5);    
                if($num_of_cycle_5 > 1) $pay_commission = true;
                break;
        } 
        //var_dump("adv->".$advanced);
        if($advanced)
        {
            board_break_point($id,$levels);
        } else {
            $income = $board_income[$levels][$type];
            $reenter = $board_reenter[$levels];
            // $co_comm = $board_cocomm[$levels];

            //Total = pos4 + pos5 + pos6 + pos7
            //Co-Comm = Total - recycle on same level - advance or commission
            $total_income = get_co_commission($id, $levels);
            $co_comm = (float)$total_income - (float)$reenter - (float)$income;
            
            //$income > 0 and 
            $other = 0;
            $other_type = "";
            //check member type
            
            // $rule30day = get_membership_type($id, $levels);
            
            //if(!$rule30day && $child < 2) 
            if($child < 2) //Remove grace periods and make all packages required to have 2 enrollees on the first qualification month
            {
                $other = $income;
                $income = 0;
                $other_type = "less than 2 qp";
            }

            if($types == 'D' || $types == 'C')
            {
                $other = $income;
                $income = 0;
                $other_type = "blocked member";  
            } 
            //if($pay_commission)
            //{
            
            //Check if the user has 2nd step commission, if he has, then the amount for step 3 should be the remaining partial amount
            $has_second_step_commission = $income_class->has_second_step_commission($id, $levels);
            if(!empty($has_second_step_commission)) $income = glc_option(sprintf('third_step_income_%s', $levels));

            //Insert commission to income table                                        
            $commission_data = array(
                'user_id'       => $id,
                'amount'        => $income,
                'other'         => $other,
                'other_type'    => $other_type,
                'reenter'       => $reenter,
                'co_comm'       => $co_comm,
                'admin_tax'     => 0,
                'left_income'   => 0,
                'type'          => $income_type[1],
                'date'          => $date,
                'time'          => $time,
                'level'         => $levels,
                'board_type'    => $type,
                'approved'      => 0
            );
            $income_id = $income_class->insert_commission($commission_data);

            //Update income relation of step 2 and 3
            if(!empty($has_second_step_commission)) $income_class->update_income_relation($has_second_step_commission[0]['id'], $income_id['message']);

            // less than 2 enroller and block ang member
            if($other_type === "" && $income > 0):
                $income_id = $income_id['message'];
                //Save rolling reserve
                rolling_reserve($income_id, $income, $datetime);

                //Send email to user about completed cycle
                send_step3_cycle_completed_email($id, $levels);
            else:
                //Send email to user about completed cycle
                send_cycle_completed_email($id, $levels);
            endif;
                
            insert_into_wallet($id,$income,$income_type[1]);
            if($income > 0){
                $w_bal = get_wallet_balance($id);
                //$ac_desc[1]
                insert_wallet_account($id , $id , $income , $date , $ac_type[3], "cycles on level ".$levels , 1 , $w_bal);
            }
        }
        return true;
    }       
}

function is_user_qualified($user_id)
{
    require_once(dirname(dirname(__FILE__))."/config.php");
    $user_class = getInstance('Class_User');
    $membership_class = getInstance('Class_Membership');

    $user = $user_class->get_user($user_id);
    $is_qualified = $membership_class->is_qualified($user_id, $user[0]['time'], true);

    return ($is_qualified) ? 2 : 0;
}

function send_step3_cycle_completed_email($user_id, $level)
{
    require_once(dirname(dirname(__FILE__))."/config.php");

    $email_class = getInstance('Class_Email');
    $user_class = getInstance('Class_User');

    $user = $user_class->get_user($user_id);
    $email_class->icontact_step3_cycle_completed($user[0], $level);
    return true;
}

function send_cycle_completed_email($user_id, $level)
{
    require_once(dirname(dirname(__FILE__))."/config.php");

    $email_class = getInstance('Class_Email');
    $user_class = getInstance('Class_User');

    $user = $user_class->get_user($user_id);
    $email_class->icontact_cycle_completed($user[0], $level);
    return true;
}

function rolling_reserve($income_id, $income, $datetime)
{
    require_once(dirname(dirname(__FILE__))."/config.php");

    $income_class = getInstance('Class_Income');
    $percentage = glc_option('reserve_percentage');
    //Compute income less reserve
    $reserve = (float)$income * ((float)$percentage / 100);

    $data = array(
        'income_id' => $income_id,
        'income' => $income,
        'reserve' => $reserve,
        'reserve_percentage' => $percentage,
        'date_created' => $datetime
    );
    $response = $income_class->insert_rolling_reserve($data);
    if($response) return true;
    return false;
}

function get_co_commission($id, $level)
{
    require_once(dirname(dirname(__FILE__))."/config.php");
    include("setting.php");
        
    $board_class = getInstance('Class_Boards');
    $board_table = $board_class->get_latest_board($id, $level);

    if(!empty($board_table)):
        $total_income = 0;
        $board_table = $board_table[0];

        //Get users in positions 4 - 7 to check if they are new users or recycled users
        $positions = array(
            $board_table['pos4'],
            $board_table['pos5'],
            $board_table['pos6'],
            $board_table['pos7']
        );

        //Check user one by one and determine the total income amount
        foreach ($positions as $key => $value) {
            $new_user = $board_class->is_new_user($value, $level);
            $board_fee = $board_cocomm[$level];
            //If user is new, the board fee should be the amount for new user 
            if($new_user) $board_fee = $board_cocomm_cylcle1[$level];
            //If user is not new, the board fee should be the actual board amount
            $total_income += $board_fee;
        }
        return $total_income;
    endif;
    return 0;
}

function get_type($user_id)  //getting type
{
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$user_id' ");
    while($row = mysqli_fetch_array($query))
    {
        $type = $row['type'];
    }
    return $type;
}
//checks if member is leadership of proffesional and has registered in last 30 days
function get_membership_type($user_id)  //getting type
{
    $sql = sprintf('select time, initial 
                        from users u INNER JOIN user_membership um ON um.user_id = u.id_user 
                            where id_user = %d and u.type="B" LIMIT 1', $user_id);
    $query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

    $num = mysqli_num_rows($query);
    if($num > 0){   
        $row = mysqli_fetch_array($query);
        $membership = $row[1]; //initial
        $professional_masters = strtotime("+1 month", $row[0]); //time
        $leadership = strtotime("+15 days", $row[0]);
        $now = time();

        // Checks if the membership = 2,3,4 or 5 AND current date is within days after the activation of membership
        if((int)$membership == 2){ // Executive
            return false;
        } else if((int)$membership == 3 && $now < $leadership){ //Leadership
            return true;
        } else if(((int)$membership == 4 || (int)$membership == 5) && $now < $professional_masters){ //Professional and Masters
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function board_income($board_b_level)
{
    include("setting.php");
    $income = $board_break_income[$board_b_level]; 
    return $income; 
}   

function insert_into_wallet($id,$income,$inc_type)
{   
    include("setting.php");
    $amount = 0;
    $q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
    while($row = mysqli_fetch_array($q))
    {
        $amount = $row['amount'];
    }   
    $date = date('Y-m-d');
    $total_income = $income+$amount;
    mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = '$total_income' , date = '$date' where id = '$id' ");
    
    if($inc_type == 1)
        $inc_type_log = "Board Break Income";
    elseif($inc_type == 2)  
        $inc_type_log = "Board Point";
        
    $user_income_log = $income;
    $wallet_amount_log = $amount;
    $total_wallet_amount_log = $total_income;
    $log_username = get_user_name($id);
    include("logs_messages.php");
    data_logs($id,$data_log[5][0],$data_log[5][1],$log_type[5]);
}       

function board_break_point($id,$type)
{ 
    include("setting.php");
    require_once(dirname(dirname(__FILE__))."/config.php");
    
    $date = date('Y-m-d');
    $types = get_type($id);
    $time = time();
    $level = $type;
    $other_type = "advanced comm";
    
    switch ($type) {
        case 1:
            $membership = "free";
            $new_membership = "executive";
            break;
        case 2:
            $membership = "executive";
            $new_membership = "leadership";
            break;
        case 3:
            $membership = "leadership";
            $new_membership = "professional";
            break;
        case 4:
            $membership = "professional";
            $new_membership = "masters";
            break;
        case 5:
            $membership = "vip";
            $new_membership = "masters";
            break;
    }
    
    //update current membership
    mysqli_query($GLOBALS["___mysqli_ston"], "update user_membership set current = '$level' where used_id = '$id'");
    
    //register a user into the wordpress database
    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
    $income_class = getInstance('Class_Income');

    $current_user = wp_get_current_user();
    $current_user->remove_role($membership);
    $current_user->add_role($new_membership);
    
    //if($types == 'B')
    //{
    if($types == 'D') $other_type = "blocked member";

    $income = $board_income[$level][1];
    $other = $board_income[$level][2];
    $reenter = $board_reenter[$level];

    //Total = pos4 + pos5 + pos6 + pos7
    //Co-Comm = Total - recycle on same level - advance or commission
    $total_income = get_co_commission($id, $level);
    $co_comm = (float)$total_income - (float)$reenter - (float)$other;

    //Check if the user has 2nd step commission, if he has, then the amount for step 3 should be the remaining partial amount
    $has_second_step_commission = $income_class->has_second_step_commission($id, $level);
    if(!empty($has_second_step_commission)) $other = glc_option(sprintf('third_step_income_%s', $level));

    //Insert commission to income table                                        
    $commission_data = array(
        'user_id'       => $id,
        'amount'        => $income,
        'other'         => $other,
        'other_type'    => $other_type,
        'reenter'       => $reenter,
        'co_comm'       => $co_comm,
        'admin_tax'     => 0,
        'left_income'   => 0,
        'type'          => $income_type[2],
        'date'          => $date,
        'time'          => $time,
        'level'         => $level,
        'board_type'    => $type,
        'approved'      => 0
    );
    $income_id = $income_class->insert_commission($commission_data);

    //Update income relation of step 2 and 3
    if(!empty($has_second_step_commission)) $income_class->update_income_relation($has_second_step_commission[0]['id'], $income_id['message']);

    if($income > 0) {
        $w_bal = get_wallet_balance($id);
        //$ac_desc[3]
        insert_wallet_account($id , $id , $income , $date , $ac_type[3] , "advanced to level ".$level , 1 , $w_bal);        
    }       
}

function insert_into_point_wallet($id,$income)
{   
    include("setting.php");
    $q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from point_wallet where user_id = '$id' ");
    while($row = mysqli_fetch_array($q))
    {
        $user_point = $row['user_point'];
    }   
    $date = date('Y-m-d');
    $total_income = $income+$user_point;
    mysqli_query($GLOBALS["___mysqli_ston"], "update point_wallet set user_point = '$total_income' where user_id = '$id' ");
    
    $inc_type_log = "Board Point";
        
    $user_income_log = $income;
    $wallet_amount_log = $amount;
    $total_wallet_amount_log = $total_income;
    $log_username = get_user_name($id);
    include("logs_messages.php");
    data_logs($id,$data_log[5][0],$data_log[5][1],$log_type[5]);
}       

?>