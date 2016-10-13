<?php
ini_set("display_errors",'off');
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing user functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_User extends Class_Database
{
    private $usermeta_fields = array('company_name', 'company_address1', 'company_address2', 'company_city', 'company_state', 'company_country', 'company_zip', 'company_phone', 'company_tin', 'zip');

    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function payza_approve_user($data)
    {
        include("../function/functions.php");
        include("../function/join_plan.php");
        include("../function/virtual_parent.php");
        include("../function/send_mail.php");
        include("../function/income.php");;
        require_once("../function/get_parent_with_same_level.php");
        require_once("../function/insert_board.php");
        require_once("../function/insert_board_second.php");
        require_once("../function/insert_board_third.php");
        require_once("../function/insert_board_fourth.php");
        require_once("../function/insert_board_fifth.php");
        require_once("../function/find_board.php"); 

        $time = time();
        $payza = $data['payza'];

        //Select the temporary user from temp_users table
        $select_temp_user = sprintf('SELECT * FROM temp_users tu INNER JOIN memberships m ON m.membership = tu.membership WHERE id_user = %d', $data['user_id']);
        $temp_user = $this->select($select_temp_user);
        $temp_user = $temp_user[0];

        if(!empty($temp_user)):
            //insert user into users table
            $insert_user_sql = $this->array_to_sql(array(
                'parent_id'         => $temp_user['parent_id'],
                'real_parent'       => $temp_user['real_parent'],
                'position'          => $temp_user['position'],
                'date'              => $temp_user['date'],
                'activate_date'     => date('Y-m-d'),
                'time'              => $temp_user['time'],
                'f_name'            => $temp_user['f_name'],
                'l_name'            => $temp_user['l_name'],
                'user_img'          => $temp_user['user_img'],
                'gender'            => $temp_user['gender'],
                'email'             => $temp_user['email'],
                'phone_no'          => $temp_user['phone_no'],
                'city'              => $temp_user['city'],
                'username'          => $temp_user['username'],
                'password'          => sha1($temp_user['password']),
                'dob'               => $temp_user['dob'],
                'address'           => $temp_user['address'],
                'country'           => $temp_user['country'],
                'type'              => $temp_user['type'],
                'user_pin'          => $temp_user['user_pin'],
                'beneficiery_name'  => $temp_user['beneficiery_name'],
                'ac_no'             => $temp_user['ac_no'],
                'bank'              => $temp_user['bank'],
                'branch'            => $temp_user['branch'],
                'bank_code'         => $temp_user['bank_code'],
                'payza_account'     => $temp_user['payza_account'],
                'tax_id'            => $temp_user['tax_id'],
                'pan_no'            => $temp_user['pan_no'],
                'pin_code'          => $temp_user['pin_code'],
                'father_name'       => $temp_user['father_name'],
                'district'          => $temp_user['district'],
                'state'             => $temp_user['state'],
                'provience'         => $temp_user['provience'],
                'optin_affiliate'   => $temp_user['optin_affiliate'],
                'dwolla_id'         => ''
            ));
            $sql = sprintf('INSERT INTO users (%s) VALUES (%s)', $insert_user_sql['keys'], $insert_user_sql['values']);
            $insert_user = $this->insert($sql);

            //Set user_id and real_p variables
            $user_id    = $insert_user['message'];
            $real_p     = $temp_user["real_parent"];

            //insert user membership in user_membership table
            $user_membership_sql = $this->array_to_sql(array(
                'user_id'       =>  $user_id,
                'payment_type'  =>  $temp_user['reg_way'],
                'number'        =>  '',
                'initial'       =>  $temp_user['id'],
                'current'       =>  $temp_user['id']
            ));
            $insert_membership = sprintf('INSERT INTO user_membership (%s) VALUES (%s)', $user_membership_sql['keys'], $user_membership_sql['values']);
            $this->insert($insert_membership);

            //process board and join plans
            switch ($temp_user["id"]) {
                case '2':
                    $plan = 1;
                    break;
                case '3':
                    $plan = 2;
                    break;
                case '4':
                    $plan = 3;
                    break;                  
                case '5':           
                    $plan = 4;  
                    break;
                case '6':
                    $plan = 5;        
                    break;
            }

            $spill = 0;
                                        
            if($plan == 1)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
                $membership_type = 2;
            }
            if($plan == 2)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                //var_dump($board_break_info);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                //var_dump($board_break_info);
                join_plan2($board_break_info);
                $membership_type = 3;
            }
            if($plan == 3)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                $membership_type = 4;
            }
            if($plan == 4)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
                $membership_type = 5;
            }
            if($plan == 5)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fifth($user_id,$real_p,$spill,$real_p);
                join_plan5($board_break_info);
                $membership_type = 6;
            }
            if(chk_real_forth_member($real_p))
            {
                $new_user_id = $real_p;
                $new_real_p = get_real_parent($real_p);
                mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                join_plan1($board_break_info);
                $membership_type = 2;
            }

            //Delete temporary user in temp_users table
            $delete_temp_user_sql = sprintf('DELETE FROM temp_users WHERE id_user = %d', $payza['apc_1']->glc_temp_user_id);
            $this->delete($delete_temp_user_sql);

            //Update user_id of user in apc_1 field in payza_ipn table
            $payza_user_ids = array(
                'glc_user_id'  => $user_id,
                'wp_user_id'   => $payza['apc_1']->wp_user_id
            );
            $update_payza_sql = sprintf("UPDATE payza_ipn SET apc_1 = '%s' WHERE id = '%d'", json_encode($payza_user_ids), $payza['id']);
            $this->update($update_payza_sql);
        endif;
    }

    function update_membership($user_id, $new_membership)
    {
        $update = sprintf("UPDATE user_membership SET initial = %d, current = %d WHERE user_id = %d", $new_membership, $new_membership, $user_id);
        $this->update($update);
    }

    function get_membership_by_membership($membership_name)
    {
        $get_membership = sprintf("SELECT * FROM memberships WHERE membership = '%s'", $membership_name);
        return $this->select($get_membership);  
    }

    function get_membership($membership_id)
    {
        $get_membership = sprintf("SELECT * FROM memberships WHERE id = %d", $membership_id);
        $membership = $this->select($get_membership);  
        return $membership[0]['membership'];
    }

    function search_username($username)
    {
        $search_username = sprintf("SELECT username FROM users WHERE username LIKE '%%%s%%'", $username);
        return $this->select($search_username);   
    }

    function get_by_username($username)
    {
        $search_username = sprintf("SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id WHERE u.username = '%s'", $username);
        return $this->select($search_username);   
    }

    function get_users()
    {
        $get_users = sprintf("SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id ");
        return $this->select($get_users);
    }

    function get_users_ids($ids)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        $users = array(); global $wpdb;
        $where = (!empty($ids)) ? sprintf('WHERE u.id_user IN(%s)', implode(',', $ids)) : '';
        $get_users = sprintf("SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id %s", $where);

        //Attach wp password to user's data
        foreach ($this->select($get_users) as $key => $value) {
            //Get user's wp password
            $get_wp_pass = $wpdb->get_row(sprintf('SELECT user_pass FROM %s WHERE user_email="%s"', $wpdb->users, $value['email']));
            $get_parent = $this->select(sprintf('SELECT username FROM users WHERE id_user = %d', $value['real_parent']));
            $users[$key] = $value;
            $users[$key]['real_parent'] = $get_parent[0]['username'];
            $users[$key]['wp_password'] = $get_wp_pass->user_pass;

            //Get user's meta fields
            foreach ($this->usermeta_fields as $ukey => $uvalue) {
                $users[$key][$uvalue] = $this->glc_usermeta($value['id_user'], $uvalue);
            }
        }
        return $users;
    }

    function get_user($id)
    {
        $get_user = sprintf("SELECT * FROM users WHERE id_user = %d", $id);
        return $this->select($get_user);
    }

    function user_membership($user_id)
    {
        $get_user_membership = sprintf("SELECT * FROM user_membership um INNER JOIN memberships m ON um.initial = m.id WHERE user_id = %d", $user_id);
        return $this->select($get_user_membership);
    }


    function update_enroller($id, $enroller)
    {
        $enroller = $this->get_by_username($enroller);
        $update_enroller = sprintf("UPDATE temp_users SET real_parent = %d WHERE id_user = %d", $enroller[0]['id_user'], $id);
        $this->update($update_enroller);
    }

    function import_user($data)
    {
        require_once(dirname(dirname(__FILE__)).'/function/functions.php');
        require_once(dirname(dirname(__FILE__)).'/function/join_plan.php');
        require_once(dirname(dirname(__FILE__)).'/function/virtual_parent.php');
        require_once(dirname(dirname(__FILE__)).'/function/income.php');
        require_once(dirname(dirname(__FILE__)).'/function/get_parent_with_same_level.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_second.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_third.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_fourth.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_fifth.php');
        require_once(dirname(dirname(__FILE__)).'/function/find_board.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

        $data = $data[0];
        if(!user_exist($data[14]) && !user_exist1($data[14]) && !useremail_exist($data[11]) && !useremail_exist1($data[11]) && !empty($data[14]) && !empty($data[11])):
            
            //Check if user exists in wordpress.
            global $wpdb;
            $check_user = $wpdb->get_row(sprintf('SELECT * FROM %s WHERE user_login = "%s"', $wpdb->users, $data[14]));

            //If user is not in wordpress database, register the user
            $master_membership = $data[41];
            if((int)$data[41] === 6):
                $master_membership = 5;
            endif;
            if(null === $check_user):
                //Register the user in wordpress
                $userdata = array(
                    'user_login'  =>  $data[14],
                    'user_email'  =>  $data[11],
                    'user_pass'   =>  $data[15],  // When creating an user, `user_pass` is expected.
                    'first_name'  =>  $data[7],
                    'last_name'   =>  $data[8],
                    'role'        =>  trim(strtolower(get_user_membership($master_membership)))
                );
                $wp_user_id = wp_insert_user($userdata);
                add_user_meta($wp_user_id, 'membership', get_user_membership($data[41]));
                //Update user's wp password and put back the pw from the excel file
                if(!empty($data[43])) $wpdb->query(sprintf('UPDATE %s SET user_pass = "%s" WHERE ID = %d', $wpdb->users, $data[43], $wp_user_id));
            endif;

            //Insert user to glc users table
            $parent_details = $this->get_by_username($data[2]);
            $data[2] = $parent_details[0]['id_user'];
            $data[1] = ((int)$data[1] !== 0) ? $data[1] : 0;
            $userdata = array(
                'parent_id'         => $data[1],
                'real_parent'       => $data[2],
                'position'          => $data[3],
                'date'              => $data[4],
                'activate_date'     => $data[5],
                'time'              => $data[6],
                'f_name'            => $data[7],
                'l_name'            => $data[8],
                'user_img'          => $data[9],
                'gender'            => $data[10],
                'email'             => $data[11],
                'phone_no'          => $data[12],
                'city'              => $data[13],
                'username'          => $data[14],
                'password'          => $data[15],
                'dob'               => $data[16],
                'address'           => $data[17],
                'country'           => $data[18],
                'type'              => $data[19],
                'user_pin'          => $data[20],
                'beneficiery_name'  => $data[21],
                'ac_no'             => $data[22],
                'bank'              => $data[23],
                'branch'            => $data[24],
                'bank_code'         => $data[25],
                'payza_account'     => $data[26],
                'tax_id'            => $data[27],
                'pan_no'            => $data[28],
                'pin_code'          => $data[29],
                'father_name'       => $data[30],
                'district'          => $data[31],
                'state'             => $data[32],
                'provience'         => $data[33],
                'optin_affiliate'   => $data[34],
                'dwolla_id'         => $data[35],
                'description'       => $data[36]
            );

            $response = $this->insert_user($userdata);

            if($response['type'] === false):
                // Return Error
                $result = array('result' => 'error', 'message' => sprintf('GLC insert: %s', $response['message']));
                die(json_encode($result));
            endif;

            $user_id = $response['message'];
            $real_p = $data[2];

            //Insert user membership
            $membershipdata = array(
                'user_id'       => $user_id,
                'payment_type'  => $data[39],
                'number'        => $data[40],
                'initial'       => $data[41],
                'current '      => $data[42]
            );
            $response = $this->insert_membership($membershipdata);

            if($response['type'] === false):
                // Return Error
                $result = array('result' => 'error', 'message' => sprintf('WP insert: %s', $response['message']));
                die(json_encode($result));
            endif;

            //Insert usermetas
            foreach ($this->usermeta_fields as $ukey => $uvalue) {
                $meta_value = "";
                if($uvalue == 'company_name') $meta_value = $data[44];
                if($uvalue == 'company_address1') $meta_value = $data[45];
                if($uvalue == 'company_address2') $meta_value = $data[46];
                if($uvalue == 'company_city') $meta_value = $data[47];
                if($uvalue == 'company_state') $meta_value = $data[48];
                if($uvalue == 'company_country') $meta_value = $data[49];
                if($uvalue == 'company_zip') $meta_value = $data[50];
                if($uvalue == 'company_phone') $meta_value = $data[51];
                if($uvalue == 'company_tin') $meta_value = $data[52];
                if($uvalue == 'zip') $meta_value = $data[53];
                $this->glc_update_usermeta($user_id, $uvalue, $meta_value);
            }

            //process board and join plans
            switch ($data[41]) {
                case '2':
                    $plan = 1;
                    break;
                case '3':
                    $plan = 2;
                    break;
                case '4':
                    $plan = 3;
                    break;                  
                case '5':           
                    $plan = 4;          
                    break;
                case '6':
                    $plan = 5;        
                    break;
            }

            $spill = 0;
                                        
            if($plan == 1)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
                $membership_type = 2;
            }
            if($plan == 2)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
                $membership_type = 3;
            }
            if($plan == 3)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                $membership_type = 4;
            }
            if($plan == 4)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
                $membership_type = 5;
            }
            if($plan == 5)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fifth($user_id,$real_p,$spill,$real_p);
                join_plan5($board_break_info);
                $membership_type = 6;
            }
            if(chk_real_forth_member($real_p))
            {
                $new_user_id = $real_p;
                $new_real_p = get_real_parent($real_p);
                mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                join_plan1($board_break_info);
                $membership_type = 2;
            }
        endif;
        return false;
    }

    function upgrade_user($user, $membership, $upgrade_to_level)
    {
        require_once(dirname(dirname(__FILE__)).'/function/functions.php');
        require_once(dirname(dirname(__FILE__)).'/function/join_plan.php');
        require_once(dirname(dirname(__FILE__)).'/function/virtual_parent.php');
        require_once(dirname(dirname(__FILE__)).'/function/income.php');
        require_once(dirname(dirname(__FILE__)).'/function/get_parent_with_same_level.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_second.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_third.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_fourth.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_fifth.php');
        require_once(dirname(dirname(__FILE__)).'/function/find_board.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

        $user_id = $user['id_user'];
        $real_p = $user['real_parent'];
        $current_membership = $membership['initial'];
        $new_membership = $upgrade_to_level;

        //process board and join plans
        switch ($new_membership) {
            case '2':
                $plan = 1;
                break;
            case '3':
                $plan = 2;
                break;
            case '4':
                $plan = 3;
                break;                  
            case '5':           
                $plan = 4;          
                break;
            case '6':
                $plan = 5;        
                break;
        }

        $spill = 0;

        if($plan == 1)
        {
            //Insert user to Stage 1
            if((int)$current_membership <= 1):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
            endif;
        }

        if($plan == 2)
        {
            //Insert user to Stage 1
            if((int)$current_membership <= 1):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
            endif;

            //Insert user to Stage 2
            if((int)$current_membership <= 2):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
            endif;
        }
        if($plan == 3)
        {
            //Insert user to Stage 1
            if((int)$current_membership <= 1):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
            endif;

            //Insert user to Stage 2
            if((int)$current_membership <= 2):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
            endif;

            //Insert user to Stage 3
            if((int)$current_membership <= 3):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
            endif;
        }
        if($plan == 4)
        {
            //Insert user to Stage 1
            if((int)$current_membership <= 1):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
            endif;
            
            //Insert user to Stage 2
            if((int)$current_membership <= 2):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
            endif;

            //Insert user to Stage 3
            if((int)$current_membership <= 3):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
            endif;

            //Insert user to Stage 4
            if((int)$current_membership <= 4):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
            endif;
        }
        if($plan == 5)
        {
            //Insert user to Stage 1
            if((int)$current_membership <= 1):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
            endif;
            
            //Insert user to Stage 2
            if((int)$current_membership <= 2):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
            endif;

            //Insert user to Stage 3
            if((int)$current_membership <= 3):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
            endif;

            //Insert user to Stage 4
            if((int)$current_membership <= 4):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
            endif;

            //Insert user to Stage 5
            if((int)$current_membership <= 5):
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fifth($user_id,$real_p,$spill,$real_p);
                join_plan5($board_break_info);
            endif;
        }
        if(chk_real_forth_member($real_p))
        {
            $new_user_id = $real_p;
            $new_real_p = get_real_parent($real_p);
            mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
            $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
            join_plan1($board_break_info);
        }
        return true;
    }

    function insert_edata_user($data)
    {
        require_once(dirname(dirname(__FILE__)).'/function/functions.php');
        require_once(dirname(dirname(__FILE__)).'/function/join_plan.php');
        require_once(dirname(dirname(__FILE__)).'/function/virtual_parent.php');
        require_once(dirname(dirname(__FILE__)).'/function/income.php');
        require_once(dirname(dirname(__FILE__)).'/function/get_parent_with_same_level.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_second.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_third.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_fourth.php');
        require_once(dirname(dirname(__FILE__)).'/function/insert_board_fifth.php');
        require_once(dirname(dirname(__FILE__)).'/function/find_board.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

        if(!user_exist($data['username']) && !user_exist1($data['username']) && !useremail_exist($data['email']) && !useremail_exist1($data['email']) && !empty($data['username']) && !empty($data['email'])):
            
            //Check if user exists in wordpress.
            global $wpdb;
            $check_user = $wpdb->get_row(sprintf('SELECT * FROM %s WHERE user_login = "%s"', $wpdb->users, $data['username']));

            //If user is not in wordpress database, register the user
            if(null === $check_user):
                //Register the user in wordpress
                $master_membership = $data['initial'];
                if((int)$data['initial'] === 6):
                    $master_membership = 5;
                endif;
                $userdata = array(
                    'user_login'  =>  $data['username'],
                    'user_email'  =>  $data['email'],
                    'user_pass'   =>  $data['password'],  // When creating an user, `user_pass` is expected.
                    'first_name'  =>  $data['f_name'],
                    'last_name'   =>  $data['l_name'],
                    'role'        =>  trim(strtolower(get_user_membership($master_membership)))
                );
                $wp_user_id = wp_insert_user($userdata);
                add_user_meta($wp_user_id, 'membership', get_user_membership($data['initial']));
            endif;

            $parent_details = $this->get_by_username($data['real_parent']);
            //Insert user to glc users table
            $userdata = array(
                'parent_id'         => $data['parent_id'],
                'real_parent'       => $parent_details[0]['id_user'],
                'position'          => $data['position'],
                'date'              => $data['date'],
                'activate_date'     => date('Y-m-d'),
                'time'              => $data['time'],
                'f_name'            => $data['f_name'],
                'l_name'            => $data['l_name'],
                'user_img'          => $data['user_img'],
                'gender'            => $data['gender'],
                'email'             => $data['email'],
                'phone_no'          => $data['phone_no'],
                'city'              => $data['city'],
                'username'          => $data['username'],
                'password'          => sha1($data['password']),
                'dob'               => $data['dob'],
                'address'           => $data['address'],
                'country'           => $data['country'],
                'type'              => $data['type'],
                'user_pin'          => $data['user_pin'],
                'beneficiery_name'  => $data['beneficiery_name'],
                'ac_no'             => $data['ac_no'],
                'bank'              => $data['bank'],
                'branch'            => $data['branch'],
                'bank_code'         => $data['bank_code'],
                'payza_account'     => $data['payza_account'],
                'tax_id'            => $data['tax_id'],
                'pan_no'            => $data['pan_no'],
                'pin_code'          => $data['pin_code'],
                'father_name'       => $data['father_name'],
                'district'          => $data['district'],
                'state'             => $data['state'],
                'provience'         => $data['provience'],
                'optin_affiliate'   => $data['optin_affiliate'],
                'dwolla_id'         => $data['dwolla_id'],
                'description'       => $data['description']
            );

            $response = $this->insert_user($userdata);

            if($response['type'] === false):
                // Return Error
                return array('result' => 'error', 'message' => sprintf('GLC insert: %s', $response['message']));
            endif;

            $user_id = $response['message'];
            $real_p = $data['real_parent'];

            //Insert user membership
            $membershipdata = array(
                'user_id'       => $user_id,
                'payment_type'  => $data['payment_type'],
                'number'        => $data['number'],
                'initial'       => $data['initial'],
                'current '      => $data['current']
            );
            $response = $this->insert_membership($membershipdata);

            if($response['type'] === false):
                // Return Error
                return array('result' => 'error', 'message' => sprintf('WP insert: %s', $response['message']));
            endif;

            //process board and join plans
            switch ($data['initial']) {
                case '2':
                    $plan = 1;
                    break;
                case '3':
                    $plan = 2;
                    break;
                case '4':
                    $plan = 3;
                    break;                  
                case '5':           
                    $plan = 4;          
                    break;
                case '6':
                    $plan = 5;        
                    break;
            }

            $spill = 0;
                                        
            if($plan == 1)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);
                $membership_type = 2;
            }
            if($plan == 2)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
                $membership_type = 3;
            }
            if($plan == 3)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                $membership_type = 4;
            }
            if($plan == 4)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
                $membership_type = 5;
            }
            if($plan == 5)
            {
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                join_plan1($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';
                
                $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                join_plan2($board_break_info);

                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                join_plan3($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                join_plan4($board_break_info);
                unset($_SESSION['board_second_breal_id']);
                unset($_SESSION['board_third_breal_id']);
                unset($_SESSION['board_fourth_breal_id']);
                unset($_SESSION['board_fifth_breal_id']);
                unset($_SESSION['board_sixth_breal_id']);
                unset($_SESSION['board_breal_id']);
                $board_break_info = '';

                $board_break_info = insert_into_board_fifth($user_id,$real_p,$spill,$real_p);
                join_plan5($board_break_info);
                $membership_type = 6;
            }
            if(chk_real_forth_member($real_p))
            {
                $new_user_id = $real_p;
                $new_real_p = get_real_parent($real_p);
                mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                join_plan1($board_break_info);
                $membership_type = 2;
            }
            return true;
        endif;
        return false;
    }

    function insert_user($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO users (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function insert_membership($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO user_membership (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function activate_user($user_id)
    {
        $update_user = sprintf("UPDATE users SET activate_date = '%s' WHERE id_user = '%d'", date('Y-m-d'), $user_id);
        $this->update($update_user);
    }

    function redirect($uri)
    {
        printf('<script type="text/javascript">window.location = "%s/%s"</script>', parent::$glc_url, $uri);
    }

    function glc_usermeta($user_id, $meta_name)
    {
        $data = '';
        $sql = sprintf("SELECT meta_value FROM usermeta WHERE meta_name ='%s' AND user_id = %d", $meta_name, $user_id);
        $result = $this->select($sql);
        if(count($result) < 1) return '';
        return $result[0]['meta_value'];
    }

    function glc_update_usermeta($user_id, $meta_name, $meta_value)
    {
        $check_option = sprintf("SELECT * FROM usermeta WHERE meta_name='%s' AND user_id = %d", $meta_name, $user_id);
        $result = $this->select($check_option);
        if(count($result) < 1):
            $data = array('user_id' => $user_id, 'meta_name' => $meta_name, 'meta_value' => $meta_value);
            $sql_array = $this->array_to_sql($data);
            $sql = sprintf('INSERT INTO usermeta (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
            return $this->insert($sql);
        else:
            $sql = sprintf("UPDATE usermeta SET meta_value='%s' WHERE meta_name='%s' AND user_id = %d", $meta_value, $meta_name, $user_id);
            return $this->update($sql);
        endif;
    }

    function update_user_meta_id($old_user_id, $new_user_id)
    {
        $sql = sprintf("UPDATE usermeta SET user_id = %d WHERE user_id = %d AND meta_name = 'company_name'", $new_user_id, $old_user_id);
        return $this->update($sql);
    }

    function get_affiliates($user_id)
    {
        $sql = sprintf("SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id WHERE u.real_parent = %d AND um.payment_type <> 'Free'", $user_id);
        return $this->select($sql);
    }

    function check_user($user_id, $username)
    {
        $get_user = sprintf("SELECT * FROM users WHERE username = '%s' AND id_user = %d", $username, $user_id);
        return $this->select($get_user);   
    }

    function user_login_check($username, $password)
    {
        $get_user = sprintf("SELECT * FROM users WHERE username = '%s' AND password = '%s'", $username, sha1($password));
        return $this->select($get_user);
    }

    function temp_user_login_check($username, $password, $paid)
    {
        $get_user = sprintf("SELECT * FROM temp_users WHERE username = '%s' AND password = '%s' AND paid = %d", $username, sha1($password), $paid);
        return $this->select($get_user);
    }

    function wp_membership()
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        return get_user_meta(get_current_user_id(), 'membership', true);
    }

    function wp_register_user($userdata, $original_regby, $membership, $pw_nohash)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

        //REGISTER USER TO WORDPRESS DATABASE
        //If membership is Founder, register to wordpress as Masters to give access to all glc libraries
        $role_wp = ($original_regby === 'Founder') ? 'Masters' : $membership;
        $userdata_wp = array(
            'user_login'  => $userdata['username'],
            'user_email'  => $userdata['email'],
            'user_pass'   => $pw_nohash, 
            'first_name'  => $userdata['f_name'],
            'last_name'   => $userdata['l_name'],
            'role'        => trim(strtolower($role_wp))
        );

        $wp_id = wp_insert_user( $userdata_wp );
        if (is_wp_error($wp_id)) {
            $result = array('result' => 'error', 'message' => $wp_id->get_error_message()); 
            die(json_encode($result));
        }

        add_user_meta($wp_id, 'membership', $membership);
        $user_id_role = new WP_User($wp_id);
        $user_id_role->add_role('wpas_user');

        //Enroll user to LifterLMS
        $this->lms_enroll_student($wp_id, $membership);

        //Register user to aem
        $this->aem_registration($userdata, $pw_nohash, $membership);        
        
        return true;
    }

    function aem_registration($userdata, $pw_nohash, $membership)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');

        $params = array(
            'api_user'     => aem_username,
            'api_pass'     => aem_password,
            'api_action'   => 'user_add',
            'api_output'   => 'serialize',
        );

        $aem_settings = array(
            'Free'          => 'aem_free_id',
            'Executive'     => 'aem_executive_id', 
            'Leadership'    => 'aem_leadership_id', 
            'Professional'  => 'aem_professional_id',
            'Masters'       => 'aem_masters_id',
            'Founder'       => 'aem_founder_id'
        );
        $aem_group_id = glc_option($aem_settings[$membership]);

        // here we define the data we are posting in order to perform an update
        $post = array(
            'username' => $userdata['username'],
            'password'   => $pw_nohash,
            'password_r' => $pw_nohash, 
            'email'      => $userdata['email'],
            'first_name'  => $userdata['f_name'],
            'last_name'   => $userdata['l_name'],
            'group' => $aem_group_id, 
        );

        $this->curl_request($params, $post);
    }

    function lms_enroll_student($user_id, $membership)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/lifterlms/includes/class.llms.student.php');

        //Find the membership's ID
        $lms_membership = get_page_by_title($membership, 'ARRAY_A', 'llms_membership');
        $student = new LLMS_Student( $user_id );
        $student->enroll($lms_membership['ID']);
    }

    function wp_update_membership($membership, $old_membership, $user_email)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        $user = get_user_by('email', $user_email);
        $user_id = $user->ID;

        $update = update_user_meta($user_id, 'membership', $membership);
        $user_id_role = new WP_User($user_id);
        $user_id_role->remove_cap($old_membership);

        if($membership === 'Founder') $membership = "Masters";
        $user_id_role->add_cap($membership);
        $user_id_role->add_cap('wpas_user');

        //Enroll user to LifterLMS
        $this->lms_enroll_student($user_id, $membership);
        return $update;
    }

    function get_user_documents($user_id)
    {
        $sql = sprintf("SELECT * FROM documents WHERE user_id = %d", $user_id);
        return $this->select($sql);
    }

    function insert_document($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO documents (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function is_company_document_approved($user_id, $country)
    {
        $doctype = ($country == 'United States' or $country == 'US') ? 4 : 3;
        $sql = sprintf("SELECT * FROM documents WHERE user_id = %d AND (doctype = 3 OR doctype = 4) AND approved = 1", $user_id);
        return $this->select($sql);
    }

    function insert_builder($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO builder (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function sitebuilder_user_login($email, $password)
    {
        // login to sitebuilder
        $url = sprintf('%s/authlogin', glc_option('sitebuilder_domain'));
        $data = array(
            'identity'     => $email,
            'password'     => $password,
            'remember'     => true
        );
        return $this->sitebuilder_curl($url, $data);
    }

    function sitebuilder_user_update($email, $password)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');

        define('ENVIRONMENT', 'production');
        define('BASEPATH', 'abc');
        require_once($_SERVER['DOCUMENT_ROOT'].'/sitebuilder/application/config/database.php');

        $class_user = getInstance('Class_User');
        $users = $class_user->get_users();

        //Connect to sitebuilder's db
        $sitebuilder_con = mysqli_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);

        // Get user information based on email address
        $sql_user = mysqli_query($sitebuilder_con, sprintf('SELECT * FROM users WHERE email = "%s"', $email));
        while($row = $sql_user->fetch_assoc()) {
            $user = $row;
        }

        // Setup data
        $url = sprintf('%s/cwp', glc_option('sitebuilder_domain'));
        $data = array(
            'userID'    => $user['id'],
            'email'     => $email,
            'password'  => $password,
            'token'     => 'kkEoms9yo4IcFonWmWgZ'
        );

        //Login the user again
        $this->sitebuilder_user_login($email, $password);

        // Send update password request to sitebuilder
        return $this->sitebuilder_curl($url, $data);
    }   

    function sitebuilder_curl($url, $data)
    {
        $request = curl_init($url); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
        $response = (string)curl_exec($request); // execute curl fetch and store results in $response
        curl_close($request); // close curl object
        return json_decode($response);
    }

    function curl_request($params, $post)
    {
        require_once(dirname(dirname(__FILE__)).'/config.php');
        $url = sprintf('%s/aem', GLC_URL);

        // This section takes the input fields and converts them to the proper format
        $query = "";
        foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';
        $query = rtrim($query, '& ');

        // This section takes the input data and converts it to the proper format
        $data = "";
        foreach( $post as $key => $value ) $data .= $key . '=' . urlencode($value) . '&';
        $data = rtrim($data, '& ');

        // clean up the url
        $url = rtrim($url, '/ ');

        // define a final API request - GET
        $api = $url . '/manage/awebdeskapi.php?' . $query;

        $request = curl_init($api); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data

        $response = (string)curl_exec($request); // execute curl post and store results in $response
        curl_close($request); // close curl object

        if ( !$response ) {
            return array('result_code' => 0, 'result_message' => 'Nothing was returned. Do you have a connection to Email Marketing server?');
        }

        $result = unserialize($response);
    }

    function array_to_sql($data)
    {
        $count = count($data); $values = ''; $flag = 0;
        $keys = implode(',', array_keys($data));
        foreach ($data as $key => $value) {
            $values .= sprintf('"%s"%s', $value, ($flag < $count-1) ? ',' : '');
            $flag++;
        }
        return array('keys' => $keys, 'values' => $values);
    }
}