<?php
ini_set("display_errors",'off');
session_start();
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/class/icontact.php");
include(dirname(__FILE__)."/function/setting.php");
include(dirname(__FILE__)."/function/functions.php");
include(dirname(__FILE__)."/function/join_plan.php");
require_once(dirname(__FILE__)."/function/formvalidator.php");
include(dirname(__FILE__)."/function/virtual_parent.php");
include(dirname(__FILE__)."/function/send_mail.php");
include(dirname(__FILE__)."/function/e_pin.php");
include(dirname(__FILE__)."/function/income.php");
include(dirname(__FILE__)."/function/u_id_par_id_pos.php");
include(dirname(__FILE__)."/function/check_income_condition.php");
include(dirname(__FILE__)."/function/direct_income.php");
require_once(dirname(__FILE__)."/function/get_parent_with_same_level.php");
require_once(dirname(__FILE__)."/function/insert_board.php");
require_once(dirname(__FILE__)."/function/insert_board_second.php");
require_once(dirname(__FILE__)."/function/insert_board_third.php");
require_once(dirname(__FILE__)."/function/insert_board_fourth.php");
require_once(dirname(__FILE__)."/function/insert_board_fifth.php");
//require_once(dirname(__FILE__)."/function/insert_board_six.php");
require_once(dirname(__FILE__)."/validation/validation.php");
require_once(dirname(__FILE__)."/function/rearrangement.php");
require_once(dirname(__FILE__)."/function/country_list1.php");
require_once(dirname(__FILE__)."/function/find_board.php");
require_once(dirname(__FILE__)."/function/export_all_database_into_sql.php");
?><?php
if(isset($_POST['q']) && isset($_POST['email']))
{
    $response       = array();
    $registration   = getInstance('Class_Registration');
    $user_class     = getInstance('Class_User');
    $mail           = getInstance('Class_Email');
    $payment_class  = getInstance('Class_Payment');
    $membership_class = getInstance('Class_Membership');

    $epin           = (isset($_REQUEST['epin']))        ? $_REQUEST['epin']         : '';
    $product_type   = (isset($_POST['product_type']))   ? $_POST['product_type']    : '';
    $year           = (isset($_POST['year']))           ? $_POST['year']            : date('Y');
    $month          = (isset($_POST['month']))          ? $_POST['month']           : date('m');
    $day            = (isset($_POST['day']))            ? $_POST['day']             : date('d');
    $gender         = (isset($_POST['gender']))         ? $_POST['gender']          : '';
    $real_parent    = ($_REQUEST['real_parent'] != "")  ? $_REQUEST['real_parent']  : "joinnow";
    $dob            = sprintf('%s-%s-%s', $year, $month, $day);

    // added payment info 
    $payment_lname = (isset($_REQUEST['payment_l_name']))      ? $_POST['payment_l_name']          : '';
    $payment_fname = (isset($_REQUEST['payment_f_name']))      ? $_POST['payment_f_name']          : '';

    // if($_POST['year'] == '0000' or $_POST['month'] == '00' or $_POST['day']== '00')
        // $error_dob = "<font color=\"#FF0000\" size=\"2\"><strong>Invalid Date of Birth</strong></font>";
    
    $position       = (isset($_REQUEST['position']))    ? $_REQUEST['position']     : '';
    $f_name         = (isset($_REQUEST['f_name']))      ? $_POST['f_name']          : '';
    $l_name         = (isset($_REQUEST['l_name']))      ? $_POST['l_name']          : '';
    $user_name      = sprintf('%s %s', $f_name, $l_name);
    $address        = (isset($_POST['address']))        ? $_POST['address']         : '';
    $address1        = (isset($_POST['address_1']))      ? $_POST['address_1']         : '';
    $address2        = (isset($_POST['address_2']))      ? $_POST['address_2']         : '';
    $city           = (isset($_POST['city']))           ? $_POST['city']            : '';
    $provience      = (isset($_POST['us_state']))       ? $_POST['us_state']        : '';
    $country        = (isset($_POST['country']))        ? $_POST['country']         : '';
    $state          = (isset($_POST['state']))          ? $_POST['state']           : '';
    $state          = (isset($_POST['us_state']))          ? $_POST['us_state']           : '';    
    $email          = (isset($_POST['email']))          ? $_POST['email']           : ''; 
    $phone          = sprintf('%s %s', isset($_POST['code']) ? $_POST['code'] : '', isset($_POST['phone']) ? $_POST['phone'] : '');
    $username       = (isset($_POST['username']))       ? $_POST['username']        : '';
    $virtual_par    = (isset($_POST['virtual_par']))    ? $_POST['virtual_par']     : '';      
    $password       = (isset($_POST['password']))       ? sha1($_POST['password'])   : '';      
    $alert          = (isset($_POST['alert']))          ? $_POST['alert']           : '';
    $liberty        = (isset($_POST['liberty']))        ? $_POST['liberty']         : '';
    $re_password    = (isset($_POST['re_password']))    ? sha1($_POST['re_password']) : '';
    $date           = $systems_date = date('Y-m-d');
    $reg_mode       = (isset($_POST['reg_mode']))       ? $_POST['reg_mode']        : '';
    $reg_amount     = (isset($_SESSION['registration_amount'])) ? $_SESSION['registration_amount'] : '';    
    $pancard_no     = (isset($_REQUEST['pancard_no']))  ? $_REQUEST['pancard_no']   : '';
    $number         = 2;
    $user_pin       = mt_rand(100000, 999999);

    $selected_payment = explode('-', $_POST['payment_method_radio_group']);
    $reg_by         = $selected_payment[0];
    $pay_type       = $selected_payment[1];

    $original_regby = (isset($_POST['reg_by']))         ? $_POST['reg_by']          : ''; 
    $time           = time();

    $company        = (isset($_POST['company']))        ? $_POST['company']          : ''; 
    $company_name   = (isset($_POST['company_name']))   ? $_POST['company_name']     : ''; 
    $zip            = (isset($_POST['zip']))            ? $_POST['zip']              : ''; 
    $fax            = (isset($_POST['fax']))            ? $_POST['fax']              : ''; 
    $website        = (isset($_POST['website']))        ? $_POST['website']          : ''; 

    $pw_nohash      = (isset($_POST['password']))       ? $_POST['password']         : '';      
    $membership_amount = 0;

    $sf      = (isset($_POST['sf']) && (int)$_POST['sf'] === 1)       ? $_POST['sf']         : '';  

    $id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$real_parent' ");
    $num = mysqli_num_rows($id_query);
    if($num == 0)
    {
        $error_real_parent = "<font color=\"#FF0000\" size=\"2\"><strong>Enter correct Sponsor Id!</strong></center></font>";
    }
    else
    {
        while($row = mysqli_fetch_array($id_query))
        {
            $real_parent_id = $row['id_user'];
        }
        
        if($reg_by == 'Cash')
        {   
            $join_type = 1;
            $epin_exist = 1;
            $type = "A";
        }
        if($reg_by == 'Paypal')
        {
            $join_type = 3;
            $epin_exist = 1;
            $type = "C";
        }
        if($reg_by == 'E-pin')
        {
            $join_type = 2;
            $query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where voucher = '$epin' and mode = 1 "); 
            $epin_exist = mysqli_num_rows($query);
            while($er = mysqli_fetch_array($query))
            {
                $plan = $er['voucher_type'];
            }
            $type = "B";
        }
        if($reg_by == 'Free' || $original_regby == 'Free')
        {
            $join_type = 4;
            $epin_exist = 1;
            $type = "F";
        }
        if($reg_by == 'Bitcoin')
        {
            $join_type = 5;
            $epin_exist = 1;
            $type = "B";
        }
        if($reg_by == 'Wire' || $reg_by == 'Bank')
        {
            $join_type = 6;
            $epin_exist = 1;
            $type = "B";
        }
        if($reg_by == 'Dwolla')
        {
            $join_type = 7;
            $epin_exist = 1;
            $type = "B";
        }
        if($reg_by == 'Payza')
        {
            $join_type = 8;
            $epin_exist = 1;
            $type = "B";
        }
        if($reg_by == 'e_data')
        {
            $join_type = 9;
            $epin_exist = 1;
            $type = "B";
        }
        if($reg_by == 'authorize_net')
        {
            $join_type = 10;
            $epin_exist = 1;
            $type = "B";
        }

        if($reg_by == 'authorize_net_2')
        {
            // var_dump($reg_by);
            $join_type = 11;
            $epin_exist = 1;
            $type = "B";
        }
        if($original_regby == 'Masters')
        {
            // Generate voucher for master member
            $voucher_class = getInstance('Class_Voucher');
            $membership_data = $membership_class->get_membership_by_name($_POST['membership']);
            $generated_pin = substr(md5(rand(0, 1000000)), 0, 10);
            $voucher_data = array(
                'generate_id' => 0,
                'epin_amount' => $membership_data[0]['amount'],
                'user_id' => 0,
                'voucher' => $generated_pin,
                'voucher_type' => $membership_data[0]['id']-1,
                'date' => date('Y-m-d'),
                'mode' => 0,
                'used_id' => 0,
                'used_date' => date('Y-m-d')
            );
            $voucher_response = $voucher_class->generate_voucher($voucher_data);
            $voucher_id = $voucher_response['message'];

            $join_type = 2;
            $type = "B";
            $epin_exist = 1;
            $plan = $membership_data[0]['id']-1;
            $reg_by = 'E-pin';
        }
        if($original_regby == 'Founder')
        {
            // Generate voucher for master member
            $voucher_class = getInstance('Class_Voucher');
            $membership_data = $membership_class->get_membership_by_name($_POST['membership']);
            $generated_pin = substr(md5(rand(0, 1000000)), 0, 10);
            $voucher_data = array(
                'generate_id' => 0,
                'epin_amount' => $membership_data[0]['amount'],
                'user_id' => 0,
                'voucher' => $generated_pin,
                'voucher_type' => $membership_data[0]['id']-1,
                'date' => date('Y-m-d'),
                'mode' => 0,
                'used_id' => 0,
                'used_date' => date('Y-m-d')
            );
            $voucher_response = $voucher_class->generate_voucher($voucher_data);
            $voucher_id = $voucher_response['message'];

            $join_type = 2;
            $type = "B";
            $epin_exist = 1;
            $plan = $membership_data[0]['id']-1;
            $reg_by = 'E-pin';
        }
        if($reg_by == 'xpressdrafts')
        {
            $join_type = 12;
            $epin_exist = 1;
            $type = "B";
        }

        if($epin_exist == 0)
        {       
            echo $error_epin = sprintf('%s/glc/login.php?msg=Enter Correct Registration e-Voucher!', GLC_URL);
        }   
        else
        {
        /*$query = mysql_query("SELECT * FROM e_voucher WHERE voucher = '$epin' and mode = 1 ");
        $chk = mysql_num_rows($query);  
            if($chk > 0)
            {*/
        /*
                if(!validateEmail($_POST['email']) || !validateUsername($real_parent) || !validateUsername($_POST['username']) ||  !validatePasswords($_POST['password'], $_POST['re_password']) )
                 {  ?>  
                <div id="error">  
                 <ul>  
                    <?php if(!validateUsername($_POST['username'])): 
                        $error_username = "<font color=\"#FF0000\" size=\"2\"><B>Invalid Username</B></font>";
                       endif?>  
                    
                     
                     
                    <?php if(!validateEmail($_POST['email'])): 
                        $error_email = "<font color=\"#FF0000\" size=\"2\"><B>Invalid E-mail</B></font>";
                       endif?>  
                    <?php if(!validatePasswords($_POST['password'], $_POST['re_password'])): 
                        $error_password = "<font color=\"#FF0000\" size=\"2\"><B>Invalid Password</B></font>";
                       endif?>   
                    </ul>  
                </div>  
          <?php }
                else
                {
                    */

                    $real_p     = $real_parent_id;
                    $username   = strtolower($username);
                    $membership = $_POST['membership'];

                    if(strtolower($membership) !== 'free'):
                        $membership_data = $membership_class->get_membership_by_name($_POST['membership']);
                        $membership_amount = $membership_data[0]['amount'];
                    endif;

                    $optin_aff  = (isset($_POST['acceptTerms1']) && $_POST['acceptTerms1'] == 'on') ? 1 : 0; 

                    //Include wordpress functions
                    include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
                    
                    if($join_type == 1) // Cash
                    {
                        $chk = user_exist1($username);
                        if($chk >0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!1', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {   
                            // Insert User to temporary user's table
                            $userdata = array(
                                'parent_id'         => 0,
                                'real_parent'       => $real_p,
                                'position'          => 0,
                                'date'              => date('Y-m-d'),
                                'activate_date'     => date('Y-m-d'),
                                'time'              => $time,
                                'f_name'            => $f_name,
                                'l_name'            => $l_name,
                                'user_img'          => '',
                                'gender'            => $gender,
                                'email'             => $email,
                                'phone_no'          => $phone,
                                'city'              => $city,
                                'username'          => $username,
                                'password'          => $password,
                                'dob'               => $dob,
                                'address'           => $address,
                                'country'           => $country,
                                'type'              => $type,
                                'user_pin'          => $user_pin,
                                'beneficiery_name'  => '',
                                'ac_no'             => '',
                                'bank'              => '',
                                'branch'            => '',
                                'bank_code'         => '',
                                'payza_account'     => '',
                                'tax_id'            => '',
                                'pan_no'            => '',
                                'pin_code'          => 0,
                                'father_name'       => '',
                                'district'          => '',
                                'state'             => '',
                                'provience'         => $provience,
                                'reg_way'           => $reg_by,
                                'paid'              => 0,
                                'membership'        => $membership,
                                'optin_affiliate'   => $optin_aff
                            );
                            $response = $registration->insert_temp_users($userdata);
                            if($response['type'] === false):
                                // Return Error
                                $result = array('result' => 'error', 'message' => $response['message']);
                                die(json_encode($result));
                            endif;

                            //REGISTER USER TO WORDPRESS DATABASE
                            $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                            // END OF REGISTER USER TO WORDPRESS DATABASE

                            //Insert payment details to payments table
                            $payment_data = array(
                                'user_id'           => sprintf('100%d', $response['message']),
                                'payment_method'    => $reg_by,
                                'payment_type'      => '',
                                'amount'            => $membership_amount,
                                'date_created'      => date('Y-m-d H:i:s')
                            );
                            $payment_class->insert_payment($payment_data);
                            
                            // Send email to user
                            $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));
                            
                            //Send email to enroller about referred user
                            $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                            $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed!', GLC_URL));
                            die(json_encode($result));
                        }
                    }
                    if($join_type == 2) // E-pin
                    {
                        $chk = user_exist($username);
                        if($chk >0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!2', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {   
                            // if($real_parent === 'joinnow'):
                                //Insert user in temp_users table
                                $userdata = array(
                                    'parent_id'         => 0,
                                    'real_parent'       => $real_parent_id,
                                    'position'          => 0,
                                    'date'              => date('Y-m-d'),
                                    'activate_date'     => date('Y-m-d'),
                                    'time'              => $time,
                                    'f_name'            => $f_name,
                                    'l_name'            => $l_name,
                                    'user_img'          => '',
                                    'gender'            => $gender,
                                    'email'             => $email,
                                    'phone_no'          => $phone,
                                    'city'              => $city,
                                    'username'          => $username,
                                    'password'          => $password,
                                    'dob'               => $dob,
                                    'address'           => $address,
                                    'country'           => $country,
                                    'type'              => $type,
                                    'user_pin'          => $user_pin,
                                    'beneficiery_name'  => '',
                                    'ac_no'             => '',
                                    'bank'              => '',
                                    'branch'            => '',
                                    'bank_code'         => '',
                                    'payza_account'     => '',
                                    'tax_id'            => '',
                                    'pan_no'            => '',
                                    'pin_code'          => 0,
                                    'father_name'       => '',
                                    'district'          => '',
                                    'state'             => '',
                                    'provience'         => $provience,
                                    'reg_way'           => $reg_by,
                                    'paid'              => 0,
                                    'membership'        => $membership,
                                    'optin_affiliate'   => $optin_aff
                                );
                                $response = $registration->insert_temp_users($userdata);

                                if($response['type'] === false):
                                    $result = array('result' => 'error', 'message' => $response['message']); 
                                    die(json_encode($result));
                                endif;
                                
                                //Update voucher for master member
                                if($original_regby == 'Masters'):
                                    $voucher_class->update_voucher_owner($voucher_id, $response['message']);
                                endif;

                                //If company, save company name
                                if(!empty($company_name)):
                                    $user_class->glc_update_usermeta($response['message'], 'company_name', $company_name);
                                endif;

                                //REGISTER USER TO WORDPRESS DATABASE
                                $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                // END OF REGISTER USER TO WORDPRESS DATABASE

                                //Insert payment details to payments table
                                $payment_data = array(
                                    'user_id'           => sprintf('100%d', $response['message']),
                                    'payment_method'    => $reg_by,
                                    'payment_type'      => '',
                                    'amount'            => $membership_amount,
                                    'date_created'      => date('Y-m-d H:i:s')
                                );
                                $payment_class->insert_payment($payment_data);

                                // Send email to user
                                // $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'membership' => $membership, 'username' => $username));
                                // Send email activatoin to user
                                // $mail_result = $mail->activation(array('email_address' => $email, 'lname' => $l_name, 'fname' => $f_name, 'username' => $username));

                                $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed!<br>A welcome email will be sent to you once your account is active. Thank you.', GLC_URL));
                                die(json_encode($result)); 
                            // else:
                            //     //Insert user to users table
                            //     $userdata = array(
                            //         'parent_id'         => 0,
                            //         'real_parent'       => $real_parent_id,
                            //         'position'          => 0,
                            //         'date'              => date('Y-m-d'),
                            //         'activate_date'     => date('Y-m-d'),
                            //         'time'              => $time,
                            //         'f_name'            => $f_name,
                            //         'l_name'            => $l_name,
                            //         'user_img'          => '',
                            //         'gender'            => $gender,
                            //         'email'             => $email,
                            //         'phone_no'          => $phone,
                            //         'city'              => $city,
                            //         'username'          => $username,
                            //         'password'          => $password,
                            //         'dob'               => $dob,
                            //         'address'           => $address,
                            //         'country'           => $country,
                            //         'type'              => $type,
                            //         'user_pin'          => $user_pin,
                            //         'beneficiery_name'  => '',
                            //         'ac_no'             => '',
                            //         'bank'              => '',
                            //         'branch'            => '',
                            //         'bank_code'         => '',
                            //         'payza_account'     => '',
                            //         'tax_id'            => '',
                            //         'pan_no'            => '',
                            //         'pin_code'          => 0,
                            //         'father_name'       => '',
                            //         'district'          => '',
                            //         'state'             => '',
                            //         'provience'         => $provience,
                            //         'optin_affiliate'   => $optin_aff,
                            //         'dwolla_id'         => ''
                            //     );
                            //     $response = $registration->insert_user($userdata);
                            //     $user_id = $response['message'];  
            
                            //     $par = get_par($user_id);
                            //     $user_pos = $par[1][0];          //user position
                            //     $users_parent_id = $par[0][1];  //parent id
                            //     $children = geting_virtual_parent($users_parent_id);
                            //     if($children < 2)
                            //     {
                            //         mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET parent_id = '$users_parent_id' , real_parent = '$real_p' , position =  '$user_pos' , f_name = '$f_name' , l_name = '$l_name' , gender = '$gender' , email = '$email' , phone_no = '$phone' , city = '$city' , password = '$password' , dob = '$dob' , address = '$address' , time = '$time' , country ='$country' , provience ='$provience', state ='$state' , user_pin = '$user_pin' , date = '$date' , activate_date = '$activate_date' , type = '$type' , pan_no = '$pancard_no', optin_affiliate = '$optin_aff' WHERE id_user = '$user_id' ");                               
                                                
                            //         $t = date('H:i:s'); 
                            //         mysqli_query($GLOBALS["___mysqli_ston"], "update e_voucher set mode = 0 , used_date = '$date' , used_id = '$user_id' WHERE voucher = '$epin' and mode = 1 ");
                                    
                            //         $spill = 0;
                                            
                            //         if($plan == 1)
                            //         {
                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';

                            //             $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                            //             join_plan1($board_break_info);
                            //             $membership_type = 2;
                            //         }
                            //         if($plan == 2)
                            //         {
                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                            //             join_plan1($board_break_info);

                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                            //             join_plan2($board_break_info);
                            //             $membership_type = 3;
                            //         }
                            //         if($plan == 3)
                            //         {
                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                            //             join_plan1($board_break_info);

                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                            //             join_plan2($board_break_info);

                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';

                            //             $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                            //             join_plan3($board_break_info);
                            //             $membership_type = 4;
                            //         }
                            //         if($plan == 4)
                            //         {
                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
                            //             join_plan1($board_break_info);

                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
                            //             join_plan2($board_break_info);
                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';

                            //             $board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
                            //             join_plan3($board_break_info);
                            //             unset($_SESSION['board_second_breal_id']);
                            //             unset($_SESSION['board_third_breal_id']);
                            //             unset($_SESSION['board_fourth_breal_id']);
                            //             unset($_SESSION['board_fifth_breal_id']);
                            //             unset($_SESSION['board_sixth_breal_id']);
                            //             unset($_SESSION['board_breal_id']);
                            //             $board_break_info = '';
                                        
                            //             $board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
                            //             join_plan4($board_break_info);
                            //             $membership_type = 5;
                            //         }
                            //         if(chk_real_forth_member($real_p))
                            //         {
                            //             $new_user_id = $real_p;
                            //             $new_real_p = get_real_parent($real_p);
                            //             mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                            //             $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                            //             join_plan1($board_break_info);
                            //             $membership_type = 2;
                            //         }

                            //         //Insert user membership
                            //         $membershipdata = array(
                            //             'user_id' => $user_id,
                            //             'payment_type' => $reg_by,
                            //             'number'    => '',
                            //             'initial' => $membership_type,
                            //             'current ' => $membership_type
                            //         );
                            //         $response = $registration->insert_membership($membershipdata);

                            //         if($response['type'] === false):
                            //             // Return Error
                            //             $result = array('result' => 'error', 'message' => $response['message']);
                            //             die(json_encode($result));
                            //         endif;

                            //         // Send email to user
                            //         $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name));
                            //         // Send email activatoin to user
                            //         $mail_result = $mail->activation(array('email_address' => $email, 'lname' => $l_name, 'fname' => $f_name, 'username' => $username));

                            //         $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed for %s Membership!', GLC_URL, $membership));
                            //         die(json_encode($result));    
                            //     }
                            //     else 
                            //     { 
                            //         $result = array('result' => 'error', 'message' => sprintf('%s/glc/login.php?err=Selected virtual parent already have two child!', GLC_URL)); 
                            //         die(json_encode($result));
                            //     }
                            // endif; 
                        }
                    }
                    if($join_type == 3) // Paypal
                    {
                        $chk = user_exist1($username);
                        if($chk > 0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!3', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {
                            $chk = user_exist($username);
                            if($chk > 0)
                            {
                                $result = array('result' => 'error', 'message' => sprintf('%s already exist!4', $username)); 
                                die(json_encode($result));
                            }
                            else
                            {
                                // Insert User to temporary user's table
                                $userdata = array(
                                    'parent_id'         => 0,
                                    'real_parent'       => $real_p,
                                    'position'          => 0,
                                    'date'              => date('Y-m-d'),
                                    'activate_date'     => date('Y-m-d'),
                                    'time'              => $time,
                                    'f_name'            => $f_name,
                                    'l_name'            => $l_name,
                                    'user_img'          => '',
                                    'gender'            => $gender,
                                    'email'             => $email,
                                    'phone_no'          => $phone,
                                    'city'              => $city,
                                    'username'          => $username,
                                    'password'          => $password,
                                    'dob'               => $dob,
                                    'address'           => $address,
                                    'country'           => $country,
                                    'type'              => $type,
                                    'user_pin'          => $user_pin,
                                    'beneficiery_name'  => '',
                                    'ac_no'             => '',
                                    'bank'              => '',
                                    'branch'            => '',
                                    'bank_code'         => '',
                                    'payza_account'     => '',
                                    'tax_id'            => '',
                                    'pan_no'            => '',
                                    'pin_code'          => 0,
                                    'father_name'       => '',
                                    'district'          => '',
                                    'state'             => '',
                                    'provience'         => $provience,
                                    'reg_way'           => $reg_by,
                                    'paid'              => 0,
                                    'membership'        => $membership,
                                    'optin_affiliate'   => $optin_aff
                                );
                                $response = $registration->insert_temp_users($userdata);

                                if($response['type'] === false):
                                    // Return Error
                                    $result = array('result' => 'error', 'message' => $response['message']);
                                    die(json_encode($result));
                                endif;

                                $new_user_id = $response['message'];

                                // Get membership amount in membership table
                                $membershipdata = $registration->get_membership_amount($membership);
                                $amount = (!empty($membershipdata)) ? $membershipdata[0]['amount'] : 0;

                                include_once __DIR__ . "/paypal/vendor/autoload.php"; //include PayPal SDK
                                include_once __DIR__ . "/paypal/functions.inc.php"; //our PayPal functions
                                
                                $item_name  = $membership." Package";
                                $item_qty   = 1;
                                $item_price = number_format($amount, 2);
                                $item_code  = $membership_type;
                                $total_amount = ($item_qty * $item_price);
                                
                                //set array of items you are selling, single or multiple
                                $items = array(
                                    array('name'=> $item_name, 'quantity'=> $item_qty, 'price'=> $item_price, 'sku'=> $item_code, 'currency'=>PP_CURRENCY)
                                );
                                
                                try { // try a payment request
                                    $result = create_paypal_payment($total_amount, PP_CURRENCY, '', $items, RETURN_URL, CANCEL_URL);
                                    //if payment method was PayPal, we need to redirect user to PayPal approval URL
                                    if($result->state == "created" && $result->payer->payment_method == "paypal"){
                                        //REGISTER USER TO WORDPRESS DATABASE
                                        $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                        // END OF REGISTER USER TO WORDPRESS DATABASE

                                        //Insert payment details to payments table
                                        $payment_data = array(
                                            'user_id'           => sprintf('100%d', $response['message']),
                                            'payment_method'    => $reg_by,
                                            'payment_type'      => '',
                                            'amount'            => $membership_amount,
                                            'date_created'      => date('Y-m-d H:i:s')
                                        );
                                        $payment_class->insert_payment($payment_data);

                                        // Send email to user
                                        $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                        //Send email to enroller about referred user
                                        $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                        $result = array('result' => 'success', 'message' => $result->links[1]->href); 
                                        die(json_encode($result));
                                    }
                                } catch(PPConnectionException $ex) {
                                    $result = array('result' => 'error', 'message' => parseApiError($ex->getData())); 
                                    die(json_encode($result));
                                } catch (Exception $ex) {
                                    $result = array('result' => 'error', 'message' => $ex->getMessage()); 
                                    die(json_encode($result));  
                                }

                            }
                        }
                    }
                    if($join_type == 4) // Free
                    {
                    //echo "1A ";
                        $membership_type = 1;
                        $chk = user_exist($email);// change username to email
                        if($chk > 0):
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!5', $email)); // change username to email
                            die(json_encode($result));
                            // echo "1b ";
                        else: 
                        // echo "1c ";
                            // If registration came from glc/registrationsf.php, give activation/access immediately
                            $date_activated = ((int)$sf === 1) ? date('Y-m-d') : '0000-00-00';

                            //Insert user to users table
                            $userdata = array(
                                'parent_id'         => 0,
                                'real_parent'       => $real_p,
                                'position'          => 0,
                                'date'              => date('Y-m-d'),
                                'activate_date'     => $date_activated,
                                'time'              => $time,
                                'f_name'            => $f_name,
                                'l_name'            => $l_name,
                                'user_img'          => '',
                                'gender'            => $gender,
                                'email'             => $email,
                                'phone_no'          => $phone,
                                'city'              => $city,
                                'username'          => $email,
                                'password'          => $password,
                                'dob'               => $dob,
                                'address'           => $address,
                                'country'           => $country,
                                'type'              => $type,
                                'user_pin'          => $user_pin,
                                'beneficiery_name'  => '',
                                'ac_no'             => '',
                                'bank'              => '',
                                'branch'            => '',
                                'bank_code'         => '',
                                'payza_account'     => '',
                                'tax_id'            => '',
                                'pan_no'            => '',
                                'pin_code'          => 0,
                                'father_name'       => '',
                                'district'          => '',
                                'state'             => '',
                                'provience'         => $provience,
                                'optin_affiliate'   => $optin_aff,
                                'dwolla_id'         => ''
                            );
                            $response = $registration->insert_user($userdata);

                            if($response['type'] === false):
                            // echo "1d ";
                                // Return Error
                                $result = array('result' => 'error', 'message' => $response['message']);
                                die(json_encode($result));
                            endif;

                            //Insert user membership
                            $membershipdata = array(
                                'user_id' => $response['message'],
                                'payment_type' => 'Free',
                                'number'    => '',
                                'initial' => $membership_type,
                                'current ' => $membership_type
                            );
                            $response = $registration->insert_membership($membershipdata);

                            if($response['type'] === false):
                            // echo "1e ";
                                // Return Error
                                $result = array('result' => 'error', 'message' => $response['message']);
                                die(json_encode($result));
                            endif;

                            //If company, save company name
                            if(!empty($company_name)):
                            //  echo "1f ";
                                $user_class->glc_update_usermeta($response['message'], 'company_name', $company_name);
                            endif;

                            //REGISTER USER TO WORDPRESS DATABASE
                            $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                            // END OF REGISTER USER TO WORDPRESS DATABASE

                            //Insert payment details to payments table
                            $payment_data = array(
                                'user_id'           => $response['message'],
                                'payment_method'    => 'Free',
                                'payment_type'      => '',
                                'amount'            => $membership_amount,
                                'date_created'      => date('Y-m-d H:i:s')
                            );
                            $payment_class->insert_payment($payment_data);

                            // Send account activation email to FREE USERS ONLY
                            // If registration came from glc/registrationsf.php, do not send activation email
                            if(empty($sf)):
                             // echo "1g ";
                                $mail_result_activate = $mail->activate_account(array('email_address' => $email, 'lname' => $l_name, 'fname' => $f_name, 'membership' => $membership, 'username' => $email, 'user_id' => $response['message'], 'pww' => $_POST['password']));
                            endif;

                            // Send email to user
                            $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $email));
                            // send email activation to FREE USERS ONLY
                            // $mail_result_activate = $mail->activation(array('email_address' => $email, 'lname' => $l_name, 'fname' => $f_name, 'membership' => $membership, 'username' => $email));

                            //Send email to enroller about referred user
                            $mail_result = $mail->new_affiliate(array('username' => $email, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                            if(!empty($sf)):
                             // echo "1h ";
                                $userdata['password'] = $pw_nohash;
                                glc_auto_login($response['message'], $userdata);

                                $result['result'] = 'success';
                                $result['message'] = sprintf('%s/myhub', GLC_URL);
                            else:
                             // echo "1i ";
                                $result['result'] = 'success';
                                $result['message'] = sprintf('%s/glc/login.php?reg=1&email=%s&pkg=%s', GLC_URL, $email, $membership);
                            endif;

                            die(json_encode($result));
                        endif;
                    }
                    if($join_type == 5) // bitcoin
                    {
                        $chk = user_exist1($username);
                        if($chk > 0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!6', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {
                            $chk = user_exist($username);
                            if($chk > 0)
                            {
                                $result = array('result' => 'error', 'message' => sprintf('%s already exist!7', $username)); 
                                die(json_encode($result));
                            }
                            else
                            {
                                // Insert User to temporary user's table
                                $userdata = array(
                                    'parent_id'         => 0,
                                    'real_parent'       => $real_p,
                                    'position'          => 0,
                                    'date'              => date('Y-m-d'),
                                    'activate_date'     => date('Y-m-d'),
                                    'time'              => $time,
                                    'f_name'            => $f_name,
                                    'l_name'            => $l_name,
                                    'user_img'          => '',
                                    'gender'            => $gender,
                                    'email'             => $email,
                                    'phone_no'          => $phone,
                                    'city'              => $city,
                                    'username'          => $username,
                                    'password'          => $password,
                                    'dob'               => $dob,
                                    'address'           => $address,
                                    'country'           => $country,
                                    'type'              => $type,
                                    'user_pin'          => $user_pin,
                                    'beneficiery_name'  => '',
                                    'ac_no'             => '',
                                    'bank'              => '',
                                    'branch'            => '',
                                    'bank_code'         => '',
                                    'payza_account'     => '',
                                    'tax_id'            => '',
                                    'pan_no'            => '',
                                    'pin_code'          => 0,
                                    'father_name'       => '',
                                    'district'          => '',
                                    'state'             => '',
                                    'provience'         => $provience,
                                    'reg_way'           => $reg_by,
                                    'paid'              => 0,
                                    'membership'        => $membership,
                                    'optin_affiliate'   => $optin_aff
                                );
                                $response = $registration->insert_temp_users($userdata);

                                if($response['type'] === false):
                                    // Return Error
                                    $result = array('result' => 'error', 'message' => $response['message']);
                                    die(json_encode($result));
                                endif;

                                $new_user_id = $response['message'];
                                
                                // Get membership amount in membership table
                                $membershipdata = $registration->get_membership_amount($membership);
                                // Temporarily commented for testing purposes
                                //$amount = (!empty($membershipdata)) ? $membershipdata[0]['amount'] : 0;                                
                                switch(strtolower(trim($membership)))
                                {
                                    case "free":
                                        $amount = 0;
                                        break;
                                    case "executive":
                                        $amount = 0.01;
                                        break;
                                    case "leadership":
                                        $amount = 0.02;
                                        break;
                                    case "professional":
                                        $amount = 0.03;
                                        break;
                                    case "masters":
                                        $amount = 0.04;
                                        break;
                                }

                                require_once("coinbase_API/Coinbase.php");
                                $coinbase = Coinbase::withApiKey($coinbaseAPIKey, $coinbaseAPISecret);
                                new Coinbase('OIA5512ezDiB9o46OKorUGg5x64Q0geumk7USytCVjap4vxS');
                                $data = array();
                                $results = '';
                                $paymentButton = $coinbase->createButton(
                                        $email." #".$new_user_id, 
                                        $amount, 
                                        "USD", 
                                        "TRACKING_CODE_1", 
                                        array(
                                            "description" => $membership." Membership"
                                        )
                                    );

                                //REGISTER USER TO WORDPRESS DATABASE
                                $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                // END OF REGISTER USER TO WORDPRESS DATABASE

                                //Insert payment details to payments table
                                $payment_data = array(
                                    'user_id'           => sprintf('100%d', $response['message']),
                                    'payment_method'    => $reg_by,
                                    'payment_type'      => '',
                                    'amount'            => $membership_amount,
                                    'date_created'      => date('Y-m-d H:i:s')
                                );
                                $payment_class->insert_payment($payment_data);

                                // Send email to user
                                $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                //Send email to enroller about referred user
                                $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                $result = array('result' => 'success', 'message' => sprintf('%s/checkouts/%s?c=%s', $coinbaseurl, $paymentButton->button->code, $new_user_id));
                                die(json_encode($result));
                            }
                        }
                    }       
                    if($join_type == 6) // bank wire
                    {
                        $chk = user_exist1($username);
                        if($chk > 0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!8', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {
                            $chk = user_exist($username);
                            if($chk > 0)
                            {
                                $result = array('result' => 'error', 'message' => sprintf('%s already exist!9', $username)); 
                                die(json_encode($result));
                            }
                            else
                            {
                                //Insert user in temp_users table
                                $userdata = array(
                                    'parent_id'         => 0,
                                    'real_parent'       => $real_p,
                                    'position'          => 0,
                                    'date'              => date('Y-m-d'),
                                    'activate_date'     => date('Y-m-d'),
                                    'time'              => $time,
                                    'f_name'            => $f_name,
                                    'l_name'            => $l_name,
                                    'user_img'          => '',
                                    'gender'            => $gender,
                                    'email'             => $email,
                                    'phone_no'          => $phone,
                                    'city'              => $city,
                                    'username'          => $username,
                                    'password'          => $password,
                                    'dob'               => $dob,
                                    'address'           => $address,
                                    'country'           => $country,
                                    'type'              => $type,
                                    'user_pin'          => $user_pin,
                                    'beneficiery_name'  => '',
                                    'ac_no'             => '',
                                    'bank'              => '',
                                    'branch'            => '',
                                    'bank_code'         => '',
                                    'payza_account'     => '',
                                    'tax_id'            => '',
                                    'pan_no'            => '',
                                    'pin_code'          => 0,
                                    'father_name'       => '',
                                    'district'          => '',
                                    'state'             => '',
                                    'provience'         => $provience,
                                    'reg_way'           => $reg_by,
                                    'paid'              => 0,
                                    'membership'        => $membership,
                                    'optin_affiliate'   => $optin_aff
                                );
                                $response = $registration->insert_temp_users($userdata);
                                if($response['type'] === false):
                                    $result = array('result' => 'error', 'message' => $response['message']); 
                                    die(json_encode($result));
                                endif;

                                $new_user_id = $response['message'];
                                $order = sprintf('%s-%s', $new_user_id, $time);

                                if($reg_by == 'Bank') {
                                    $link = "bank";
                                } else {
                                    $link = "wire";
                                }

                                //REGISTER USER TO WORDPRESS DATABASE
                                $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                // END OF REGISTER USER TO WORDPRESS DATABASE

                                //Insert payment details to payments table
                                $payment_data = array(
                                    'user_id'           => sprintf('100%d', $response['message']),
                                    'payment_method'    => $reg_by,
                                    'payment_type'      => '',
                                    'amount'            => $membership_amount,
                                    'date_created'      => date('Y-m-d H:i:s')
                                );
                                $payment_class->insert_payment($payment_data);

                                // Send email to user
                                $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                //Send email to enroller about referred user
                                $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                $result = array('result' => 'success', 'message' => sprintf('%s/glc/%s-payment.php?order=%s', GLC_URL, $link, $order));
                                die(json_encode($result));
                            }
                        }
                    }       
                    if($join_type == 7) // DWOLLA
                    {
                        $chk = user_exist1($username);
                        if($chk > 0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!10', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {
                            $chk = user_exist($username);
                            if($chk > 0)
                            {
                                $result = array('result' => 'error', 'message' => sprintf('%s already exist!11', $username)); 
                                die(json_encode($result));
                            }
                            else
                            {
                                //Insert user in temp_users table
                                $userdata = array(
                                    'parent_id'         => 0,
                                    'real_parent'       => $real_p,
                                    'position'          => 0,
                                    'date'              => date('Y-m-d'),
                                    'activate_date'     => date('Y-m-d'),
                                    'time'              => $time,
                                    'f_name'            => $f_name,
                                    'l_name'            => $l_name,
                                    'user_img'          => '',
                                    'gender'            => $gender,
                                    'email'             => $email,
                                    'phone_no'          => $phone,
                                    'city'              => $city,
                                    'username'          => $username,
                                    'password'          => $password,
                                    'dob'               => $dob,
                                    'address'           => $address,
                                    'country'           => $country,
                                    'type'              => $type,
                                    'user_pin'          => $user_pin,
                                    'beneficiery_name'  => '',
                                    'ac_no'             => '',
                                    'bank'              => '',
                                    'branch'            => '',
                                    'bank_code'         => '',
                                    'payza_account'     => '',
                                    'tax_id'            => '',
                                    'pan_no'            => '',
                                    'pin_code'          => 0,
                                    'father_name'       => '',
                                    'district'          => '',
                                    'state'             => '',
                                    'provience'         => $provience,
                                    'reg_way'           => $reg_by,
                                    'paid'              => 0,
                                    'membership'        => $membership,
                                    'optin_affiliate'   => $optin_aff
                                );
                                $response = $registration->insert_temp_users($userdata);
                                if($response['type'] === false):
                                    $result = array('result' => 'error', 'message' => $response['message']); 
                                    die(json_encode($result));
                                endif;

                                $new_user_id = $response['message'];

                                // Get membership amount in membership table
                                $membershipdata = $registration->get_membership_amount($membership);
                                $amount = (!empty($membershipdata)) ? $membershipdata[0]['amount'] : 0;
                               
                                //Dwolla API
                                require ('dwolla/vendor/autoload.php');
                                $Checkouts = new Dwolla\Checkouts();

                                $Checkouts->addToCart($membership." Membership", "Global Learning Center", $amount, 1);

                                $dwolla_link = sprintf('%s/glc/dwolla-payment.php', GLC_URL);
                                $test = $Checkouts->create(
                                    [ 'destinationId' => DWOLLA_ID ],
                                    [ 'orderId' => $new_user_id ],
                                    [ 'redirect' => $dwolla_link ]
                                );

                                if(is_array($test) && isset($test['URL'])):
                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => sprintf('100%d', $response['message']),
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => '',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));
                                    
                                    $result = array('result' => 'success', 'message' => $test['URL']);
                                else:
                                    $result = array('result' => 'error', 'message' => 'DwollaPHP: Unable to create checkout due to API error.'); 
                                endif;
                                die(json_encode($result));
                            }
                        }
                    }
                    if($join_type == 8) // payza
                    {
                        $chk = user_exist1($username);
                        if($chk > 0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {
                            $chk = user_exist($username);
                            if($chk > 0)
                            {
                                $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                                die(json_encode($result));
                            }
                            else
                            {
                                //Insert user in temp_users table
                                $userdata = array(
                                    'parent_id'         => 0,
                                    'real_parent'       => $real_p,
                                    'position'          => 0,
                                    'date'              => date('Y-m-d'),
                                    'activate_date'     => date('Y-m-d'),
                                    'time'              => $time,
                                    'f_name'            => $f_name,
                                    'l_name'            => $l_name,
                                    'user_img'          => '',
                                    'gender'            => $gender,
                                    'email'             => $email,
                                    'phone_no'          => $phone,
                                    'city'              => $city,
                                    'username'          => $username,
                                    'password'          => $password,
                                    'dob'               => $dob,
                                    'address'           => $address,
                                    'country'           => $country,
                                    'type'              => $type,
                                    'user_pin'          => $user_pin,
                                    'beneficiery_name'  => '',
                                    'ac_no'             => '',
                                    'bank'              => '',
                                    'branch'            => '',
                                    'bank_code'         => '',
                                    'payza_account'     => '',
                                    'tax_id'            => '',
                                    'pan_no'            => '',
                                    'pin_code'          => 0,
                                    'father_name'       => '',
                                    'district'          => '',
                                    'state'             => '',
                                    'provience'         => $provience,
                                    'reg_way'           => $reg_by,
                                    'paid'              => 0,
                                    'membership'        => $membership,
                                    'optin_affiliate'   => $optin_aff
                                );
                                $response = $registration->insert_temp_users($userdata);
                                if($response['type'] === false):
                                    $result = array('result' => 'error', 'message' => $response['message']); 
                                    die(json_encode($result));
                                endif;

                                $new_user_id = $response['message'];
                                $order = sprintf('%s-%s', $new_user_id, $time);

                                $payza_id = '';
                                if(trim($membership) == 'Executive') $payza_id = $payza_executive;
                                if(trim($membership) == 'Leadership') $payza_id = $payza_leadership;
                                if(trim($membership) == 'Professional') $payza_id = $payza_professional;
                                if(trim($membership) == 'Masters') $payza_id = $payza_masters;

                                $payza = array(
                                    'ap_productid'  => $payza_id,
                                    'ap_quantity'   => 1,
                                    'apc_1'         => json_encode(array('glc_temp_user_id' => $new_user_id, 'wp_user_id' => $user_id))
                                );

                                //REGISTER USER TO WORDPRESS DATABASE
                                $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                // END OF REGISTER USER TO WORDPRESS DATABASE

                                //Insert payment details to payments table
                                $payment_data = array(
                                    'user_id'           => sprintf('100%d', $response['message']),
                                    'payment_method'    => $reg_by,
                                    'payment_type'      => '',
                                    'amount'            => $membership_amount,
                                    'date_created'      => date('Y-m-d H:i:s')
                                );
                                $payment_class->insert_payment($payment_data);

                                // Send email to user
                                $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));
                                
                                //Send email to enroller about referred user
                                $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                $result = array('result' => 'success', 'message' => sprintf('%s/glc/payza-payment.php?order=%s', GLC_URL, $order));
                                die(json_encode($result));

                                // This will redirect the user to payza checkout page. But for the mean time, we will use payza payment instruction page instead.
                                // $result = array('result' => 'success', 'message' => sprintf('%s?%s', $payza_checkout, http_build_query($payza)));
                                // die(json_encode($result));
                            }
                        }
                    }
                    if($join_type == 9) // E-Data
                    {
                        $chk = user_exist($username);
                        if($chk >0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                            die(json_encode($result));
                        }
                        else
                        { 
                            if($real_parent === 'joinnow'):
                                //Process the edata payment. If there is no problem, registration of user will be executed
                                require_once(dirname(__FILE__)."/edata/process.php");

                                if($payment_error === 0):
                                    // Insert user in temp_users table
                                    $userdata = array(
                                        'parent_id'         => 0,
                                        'real_parent'       => $real_parent_id,
                                        'position'          => 0,
                                        'date'              => date('Y-m-d'),
                                        'activate_date'     => date('Y-m-d'),
                                        'time'              => $time,
                                        'f_name'            => $f_name,
                                        'l_name'            => $l_name,
                                        'user_img'          => '',
                                        'gender'            => $gender,
                                        'email'             => $email,
                                        'phone_no'          => $phone,
                                        'city'              => $city,
                                        'username'          => $username,
                                        'password'          => $password,
                                        'dob'               => $dob,
                                        'address'           => $address,
                                        'country'           => $country,
                                        'type'              => $type,
                                        'user_pin'          => $user_pin,
                                        'beneficiery_name'  => '',
                                        'ac_no'             => '',
                                        'bank'              => '',
                                        'branch'            => '',
                                        'bank_code'         => '',
                                        'payza_account'     => '',
                                        'tax_id'            => '',
                                        'pan_no'            => '',
                                        'pin_code'          => 0,
                                        'father_name'       => '',
                                        'district'          => '',
                                        'state'             => '',
                                        'provience'         => $provience,
                                        'reg_way'           => $reg_by,
                                        'paid'              => 1,
                                        'membership'        => $membership,
                                        'optin_affiliate'   => $optin_aff
                                    );
                                    $response = $registration->insert_temp_users($userdata);

                                    if($response['type'] === false):
                                        $result = array('result' => 'error', 'message' => $response['message']); 
                                        die(json_encode($result));
                                    endif;

                                    if(!empty($payment_response) && is_array($payment_response)):
                                        $user_id = $response['message'];  
                                        //Insert edata details to db
                                        $payment_data = array(
                                            'user_id' => sprintf('100%d', $user_id),
                                            'cc_fname' => $payment_fname,
                                            'cc_lname' => $payment_lname,
                                            'response' => $payment_response['response'],
                                            'responsetext' => $payment_response['responsetext'],
                                            'authcode' => (!empty($payment_response['authcode'])) ? $payment_response['authcode'] : 0,
                                            'transactionid' => $payment_response['transactionid'],
                                            'avsresponse' => $payment_response['avsresponse'],
                                            'cvvresponse' => $payment_response['cvvresponse'],
                                            'orderid' => $payment_response['orderid'],
                                            'type' => $payment_response['type'],
                                            'response_code' => $payment_response['response_code'],
                                            'date_created' => date('Y-m-d H:i:s')
                                        );
                                        $edata_ipn_id = $payment_class->edata_ipn($payment_data);
                                    endif;

                                    //If company, save company name
                                    if(!empty($company_name)):
                                        $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                    endif;

                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => sprintf('100%d', $response['message']),
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => 'creditcard',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                    $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed!<br>A welcome email will be sent to you once your account is active. Thank you.', GLC_URL));
                                    die(json_encode($result)); 
                                else:
                                    $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                                    die(json_encode($result));
                                endif;
                            else:
                                //Process the edata payment. If there is no problem, registration of user will be executed
                                require_once(dirname(__FILE__)."/edata/process.php");

                                if($payment_error === 0):
                                    //Insert user to users table
                                    $userdata = array(
                                        'parent_id'         => 0,
                                        'real_parent'       => $real_parent_id,
                                        'position'          => 0,
                                        'date'              => date('Y-m-d'),
                                        'activate_date'     => date('Y-m-d'),
                                        'time'              => $time,
                                        'f_name'            => $f_name,
                                        'l_name'            => $l_name,
                                        'user_img'          => '',
                                        'gender'            => $gender,
                                        'email'             => $email,
                                        'phone_no'          => $phone,
                                        'city'              => $city,
                                        'username'          => $username,
                                        'password'          => $password,
                                        'dob'               => $dob,
                                        'address'           => $address,
                                        'country'           => $country,
                                        'type'              => $type,
                                        'user_pin'          => $user_pin,
                                        'beneficiery_name'  => '',
                                        'ac_no'             => '',
                                        'bank'              => '',
                                        'branch'            => '',
                                        'bank_code'         => '',
                                        'payza_account'     => '',
                                        'tax_id'            => '',
                                        'pan_no'            => '',
                                        'pin_code'          => 0,
                                        'father_name'       => '',
                                        'district'          => '',
                                        'state'             => '',
                                        'provience'         => $provience,
                                        'optin_affiliate'   => $optin_aff,
                                        'dwolla_id'         => '',
                                        'description'       => ''
                                    );
                                    $response = $registration->insert_user($userdata);
                                    $user_id = $response['message'];  

                                    if(!empty($payment_response) && is_array($payment_response)):
                                        //Insert edata details to db
                                        $payment_data = array(
                                            'user_id' => $user_id,
                                            'cc_fname' => $payment_fname,
                                            'cc_lname' => $payment_lname,
                                            'response' => $payment_response['response'],
                                            'responsetext' => $payment_response['responsetext'],
                                            'authcode' => (!empty($payment_response['authcode'])) ? $payment_response['authcode'] : 0,
                                            'transactionid' => $payment_response['transactionid'],
                                            'avsresponse' => $payment_response['avsresponse'],
                                            'cvvresponse' => $payment_response['cvvresponse'],
                                            'orderid' => $payment_response['orderid'],
                                            'type' => $payment_response['type'],
                                            'response_code' => $payment_response['response_code'],
                                            'date_created' => date('Y-m-d H:i:s')
                                        );
                                        $edata_ipn_id = $payment_class->edata_ipn($payment_data);
                                    endif;
                
                                    $spill = 0;
                                    $plan = $membership_details['id']-1;
                                            
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
                                    if(chk_real_forth_member($real_p))
                                    {
                                        $new_user_id = $real_p;
                                        $new_real_p = get_real_parent($real_p);
                                        mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                                        $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                                        join_plan1($board_break_info);
                                        $membership_type = 2;
                                    }

                                    //Insert user membership
                                    $membershipdata = array(
                                        'user_id' => $user_id,
                                        'payment_type' => $reg_by,
                                        'number'    => '',
                                        'initial' => $membership_type,
                                        'current ' => $membership_type
                                    );
                                    $response = $registration->insert_membership($membershipdata);

                                    if($response['type'] === false):
                                        // Return Error
                                        $result = array('result' => 'error', 'message' => $response['message']);
                                        die(json_encode($result));
                                    endif;

                                    //If company, save company name
                                    if(!empty($company_name)):
                                        $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                    endif;

                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => $user_id,
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => 'creditcard',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                    // Send email activatoin to user
                                    // $mail_result = $mail->activation(array('email_address' => $email, 'lname' => $l_name, 'fname' => $f_name, 'membership' => $membership, 'username' => $username));

                                    $userdata['password'] = $pw_nohash;
                                    glc_auto_login($user_id, $userdata);

                                    $result = array('result' => 'success', 'message' => sprintf('%s/glc/myhub', GLC_URL));
                                    // var_dump(json_decode($result));
                                    die(json_encode($result));    
                                else:
                                    $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                                    die(json_encode($result));
                                endif;
                            endif;
                        }
                    }

                    if($join_type == 10) // Authorize.net
                    {
                        $chk = user_exist($username);
                        if($chk >0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                            die(json_encode($result));
                        }
                        else
                        { 
                            if($real_parent === 'joinnow'):
                                //Process the edata payment. If there is no problem, registration of user will be executed
                                require_once(dirname(__FILE__)."/class/process.php");

                                if($payment_error === 0):
                                    // Insert user in temp_users table
                                    $userdata = array(
                                        'parent_id'         => 0,
                                        'real_parent'       => $real_parent_id,
                                        'position'          => 0,
                                        'date'              => date('Y-m-d'),
                                        'activate_date'     => date('Y-m-d'),
                                        'time'              => $time,
                                        'f_name'            => $f_name,
                                        'l_name'            => $l_name,
                                        'user_img'          => '',
                                        'gender'            => $gender,
                                        'email'             => $email,
                                        'phone_no'          => $phone,
                                        'city'              => $city,
                                        'username'          => $username,
                                        'password'          => $password,
                                        'dob'               => $dob,
                                        'address'           => $address,
                                        'country'           => $country,
                                        'type'              => $type,
                                        'user_pin'          => $user_pin,
                                        'beneficiery_name'  => '',
                                        'ac_no'             => '',
                                        'bank'              => '',
                                        'branch'            => '',
                                        'bank_code'         => '',
                                        'payza_account'     => '',
                                        'tax_id'            => '',
                                        'pan_no'            => '',
                                        'pin_code'          => 0,
                                        'father_name'       => '',
                                        'district'          => '',
                                        'state'             => '',
                                        'provience'         => $provience,
                                        'reg_way'           => $reg_by,
                                        'paid'              => 1,
                                        'membership'        => $membership,
                                        'optin_affiliate'   => $optin_aff
                                    );
                                    $response = $registration->insert_temp_users($userdata);

                                    if($response['type'] === false):
                                        $result = array('result' => 'error', 'message' => $response['message']); 
                                        die(json_encode($result));
                                    endif;

                                    if(($tresponse != null) && ($tresponse->getResponseCode()=="1")):
                                        $user_id = $response['message'];  
                                        $payment_data = array(
                                          'user_id' => sprintf('100%d', $user_id),
                                          'cc_fname' => $payment_fname,  
                                          'cc_lname' => $payment_lname,
                                          'response' => $tresponse->getResponseCode(),
                                          'responsetext' => json_encode($tresponse->getMessages()),
                                          'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                                          'transactionid' => $tresponse->getTransId(),
                                          'avsresponse' => $tresponse->getAvsResultCode(),
                                          'cvvresponse' => $tresponse->getCvvResultCode(),
                                          'orderid' => $orderid,
                                          'type' => 'authCaptureTransaction',
                                          'response_code' => $tresponse->getResponseCode(),
                                          'amount' => $membership_details['amount'],
                                          'payment_type' => 1,
                                          'date_created' => date('Y-m-d H:i:s')
                                        );
                                        $payment_id = $payment_class->authorize_ipn($payment_data);
                                    endif;

                                    //If company, save company name
                                    if(!empty($company_name)):
                                        $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                    endif;

                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => sprintf('100%d', $response['message']),
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => 'creditcard',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                    $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed!<br>A welcome email will be sent to you once your account is active. Thank you.', GLC_URL));
                                    die(json_encode($result)); 
                                else:
                                    $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                                    die(json_encode($result));
                                endif;

                            else:
                                //Process the edata payment. If there is no problem, registration of user will be executed
                                require_once(dirname(__FILE__)."/class/process.php");

                                if($payment_error === 0):
                                    //Insert user to users table
                                    $userdata = array(
                                        'parent_id'         => 0,
                                        'real_parent'       => $real_parent_id,
                                        'position'          => 0,
                                        'date'              => date('Y-m-d'),
                                        'activate_date'     => date('Y-m-d'),
                                        'time'              => $time,
                                        'f_name'            => $f_name,
                                        'l_name'            => $l_name,
                                        'user_img'          => '',
                                        'gender'            => $gender,
                                        'email'             => $email,
                                        'phone_no'          => $phone,
                                        'city'              => $city,
                                        'username'          => $username,
                                        'password'          => $password,
                                        'dob'               => $dob,
                                        'address'           => $address,
                                        'country'           => $country,
                                        'type'              => $type,
                                        'user_pin'          => $user_pin,
                                        'beneficiery_name'  => '',
                                        'ac_no'             => '',
                                        'bank'              => '',
                                        'branch'            => '',
                                        'bank_code'         => '',
                                        'payza_account'     => '',
                                        'tax_id'            => '',
                                        'pan_no'            => '',
                                        'pin_code'          => 0,
                                        'father_name'       => '',
                                        'district'          => '',
                                        'state'             => '',
                                        'provience'         => $provience,
                                        'optin_affiliate'   => $optin_aff,
                                        'dwolla_id'         => '',
                                        'description'       => ''
                                    );
                                    $response = $registration->insert_user($userdata);
                                    $user_id = $response['message'];  

                                    if(($tresponse != null) && ($tresponse->getResponseCode()=="1")):
                                        $payment_data = array(
                                          'user_id' => $user_id,
                                          'cc_fname' => $payment_fname,  
                                          'cc_lname' => $payment_lname,
                                          'response' => $tresponse->getResponseCode(),
                                          'responsetext' => json_encode($tresponse->getMessages()),
                                          'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                                          'transactionid' => $tresponse->getTransId(),
                                          'avsresponse' => $tresponse->getAvsResultCode(),
                                          'cvvresponse' => $tresponse->getCvvResultCode(),
                                          'orderid' => $orderid,
                                          'type' => 'authCaptureTransaction',
                                          'response_code' => $tresponse->getResponseCode(),
                                          'amount' => $membership_details['amount'],
                                          'payment_type' => 1,
                                          'date_created' => date('Y-m-d H:i:s')
                                        );
                                        $payment_id = $payment_class->authorize_ipn($payment_data);
                                    endif;
                
                                    $spill = 0;
                                    $plan = $membership_details['id']-1;
                                            
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
                                    if(chk_real_forth_member($real_p))
                                    {
                                        $new_user_id = $real_p;
                                        $new_real_p = get_real_parent($real_p);
                                        mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                                        $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                                        join_plan1($board_break_info);
                                        $membership_type = 2;
                                    }

                                    //Insert user membership
                                    $membershipdata = array(
                                        'user_id' => $user_id,
                                        'payment_type' => $reg_by,
                                        'number'    => '',
                                        'initial' => $membership_type,
                                        'current ' => $membership_type
                                    );
                                    $response = $registration->insert_membership($membershipdata);

                                    if($response['type'] === false):
                                        // Return Error
                                        $result = array('result' => 'error', 'message' => $response['message']);
                                        die(json_encode($result));
                                    endif;

                                    //If company, save company name
                                    if(!empty($company_name)):
                                        $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                    endif;

                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => $user_id,
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => 'creditcard',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                    // Send email activatoin to user
                                    // $mail_result = $mail->activation(array('email_address' => $email, 'lname' => $l_name, 'fname' => $f_name, 'membership' => $membership, 'username' => $username));

                                    $userdata['password'] = $pw_nohash;
                                    glc_auto_login($user_id, $userdata);

                                    $result = array('result' => 'success', 'message' => sprintf('%s/glc/myhub', GLC_URL));
                                    die(json_encode($result));    
                                else:
                                    $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                                    die(json_encode($result));
                                endif;
                            endif;
                        }
                    }

                    if($join_type == 11) // Authorize.net (Tom Pace)
                    {
                        $chk = user_exist($username);
                        if($chk >0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                            die(json_encode($result));
                        }
                        else
                        { 
                            if($real_parent === 'joinnow'):
                                //Process the edata payment. If there is no problem, registration of user will be executed
                                require_once(dirname(__FILE__)."/class/process.php");

                                if($payment_error === 0):
                                    // Insert user in temp_users table
                                    $userdata = array(
                                        'parent_id'         => 0,
                                        'real_parent'       => $real_parent_id,
                                        'position'          => 0,
                                        'date'              => date('Y-m-d'),
                                        'activate_date'     => date('Y-m-d'),
                                        'time'              => $time,
                                        'f_name'            => $f_name,
                                        'l_name'            => $l_name,
                                        'user_img'          => '',
                                        'gender'            => $gender,
                                        'email'             => $email,
                                        'phone_no'          => $phone,
                                        'city'              => $city,
                                        'username'          => $username,
                                        'password'          => $password,
                                        'dob'               => $dob,
                                        'address'           => $address,
                                        'country'           => $country,
                                        'type'              => $type,
                                        'user_pin'          => $user_pin,
                                        'beneficiery_name'  => '',
                                        'ac_no'             => '',
                                        'bank'              => '',
                                        'branch'            => '',
                                        'bank_code'         => '',
                                        'payza_account'     => '',
                                        'tax_id'            => '',
                                        'pan_no'            => '',
                                        'pin_code'          => 0,
                                        'father_name'       => '',
                                        'district'          => '',
                                        'state'             => '',
                                        'provience'         => $provience,
                                        'reg_way'           => $reg_by,
                                        'paid'              => 1,
                                        'membership'        => $membership,
                                        'optin_affiliate'   => $optin_aff
                                    );
                                    $response = $registration->insert_temp_users($userdata);

                                    if($response['type'] === false):
                                        $result = array('result' => 'error', 'message' => $response['message']); 
                                        die(json_encode($result));
                                    endif;

                                    if(($tresponse != null) && ($tresponse->getResponseCode()=="1")):
                                        $user_id = $response['message'];  
                                        $payment_data = array(
                                          'user_id' => sprintf('100%d', $user_id),
                                          'cc_fname' => $payment_fname,  
                                          'cc_lname' => $payment_lname,
                                          'response' => $tresponse->getResponseCode(),
                                          'responsetext' => json_encode($tresponse->getMessages()),
                                          'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                                          'transactionid' => $tresponse->getTransId(),
                                          'avsresponse' => $tresponse->getAvsResultCode(),
                                          'cvvresponse' => $tresponse->getCvvResultCode(),
                                          'orderid' => $orderid,
                                          'type' => 'authCaptureTransaction',
                                          'response_code' => $tresponse->getResponseCode(),
                                          'amount' => $membership_details['amount'],
                                          'payment_type' => 1,
                                          'date_created' => date('Y-m-d H:i:s')
                                        );
                                        $payment_id = $payment_class->authorize_ipn($payment_data);
                                    endif;

                                    //If company, save company name
                                    if(!empty($company_name)):
                                        $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                    endif;

                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => sprintf('100%d', $response['message']),
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => 'creditcard',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                    $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed!<br>A welcome email will be sent to you once your account is active. Thank you.', GLC_URL));
                                    die(json_encode($result)); 
                                else:
                                    $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                                    die(json_encode($result));
                                endif;
                            else:
                                
                                //Process the edata payment. If there is no problem, registration of user will be executed
                                require_once(dirname(__FILE__)."/class/process.php");

                                if($payment_error === 0):
                                    //Insert user to users table
                                    $userdata = array(
                                        'parent_id'         => 0,
                                        'real_parent'       => $real_parent_id,
                                        'position'          => 0,
                                        'date'              => date('Y-m-d'),
                                        'activate_date'     => date('Y-m-d'),
                                        'time'              => $time,
                                        'f_name'            => $f_name,
                                        'l_name'            => $l_name,
                                        'user_img'          => '',
                                        'gender'            => $gender,
                                        'email'             => $email,
                                        'phone_no'          => $phone,
                                        'city'              => $city,
                                        'username'          => $username,
                                        'password'          => $password,
                                        'dob'               => $dob,
                                        'address'           => $address,
                                        'country'           => $country,
                                        'type'              => $type,
                                        'user_pin'          => $user_pin,
                                        'beneficiery_name'  => '',
                                        'ac_no'             => '',
                                        'bank'              => '',
                                        'branch'            => '',
                                        'bank_code'         => '',
                                        'payza_account'     => '',
                                        'tax_id'            => '',
                                        'pan_no'            => '',
                                        'pin_code'          => 0,
                                        'father_name'       => '',
                                        'district'          => '',
                                        'state'             => '',
                                        'provience'         => $provience,
                                        'optin_affiliate'   => $optin_aff,
                                        'dwolla_id'         => '',
                                        'description'       => ''
                                    );
                                    $response = $registration->insert_user($userdata);
                                    $user_id = $response['message'];  

                                    if(($tresponse != null) && ($tresponse->getResponseCode()=="1")):
                                        $payment_data = array(
                                          'user_id' => $user_id,
                                          'cc_fname' => $payment_fname,
                                          'cc_lname' => $payment_lname,
                                          'response' => $tresponse->getResponseCode(),
                                          'responsetext' => json_encode($tresponse->getMessages()),
                                          'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                                          'transactionid' => $tresponse->getTransId(),
                                          'avsresponse' => $tresponse->getAvsResultCode(),
                                          'cvvresponse' => $tresponse->getCvvResultCode(),
                                          'orderid' => $orderid,
                                          'type' => 'authCaptureTransaction',
                                          'response_code' => $tresponse->getResponseCode(),
                                          'amount' => $membership_details['amount'],
                                          'payment_type' => 1,
                                          'date_created' => date('Y-m-d H:i:s')
                                        );
                                        $payment_id = $payment_class->authorize_ipn($payment_data);
                                    endif;
                
                                    $spill = 0;
                                    $plan = $membership_details['id']-1;
                                            
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
                                    if(chk_real_forth_member($real_p))
                                    {
                                        $new_user_id = $real_p;
                                        $new_real_p = get_real_parent($real_p);
                                        mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
                                        $board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
                                        join_plan1($board_break_info);
                                        $membership_type = 2;
                                    }

                                    //Insert user membership
                                    $membershipdata = array(
                                        'user_id' => $user_id,
                                        'payment_type' => $reg_by,
                                        'number'    => '',
                                        'initial' => $membership_type,
                                        'current ' => $membership_type
                                    );
                                    $response = $registration->insert_membership($membershipdata);

                                    if($response['type'] === false):
                                        // Return Error
                                        $result = array('result' => 'error', 'message' => $response['message']);
                                        die(json_encode($result));
                                    endif;

                                    //If company, save company name
                                    if(!empty($company_name)):
                                        $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                    endif;

                                    //REGISTER USER TO WORDPRESS DATABASE
                                    $user_class->wp_register_user($userdata, $original_regby, $membership, $pw_nohash);
                                    // END OF REGISTER USER TO WORDPRESS DATABASE

                                    //Insert payment details to payments table
                                    $payment_data = array(
                                        'user_id'           => $user_id,
                                        'payment_method'    => $reg_by,
                                        'payment_type'      => 'creditcard',
                                        'amount'            => $membership_amount,
                                        'date_created'      => date('Y-m-d H:i:s')
                                    );
                                    $payment_class->insert_payment($payment_data);

                                    // Send email to user
                                    $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

                                    //Send email to enroller about referred user
                                    $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

                                    $userdata['password'] = $pw_nohash;
                                    glc_auto_login($user_id, $userdata);

                                    $result = array('result' => 'success', 'message' => sprintf('%s/glc/myhub', GLC_URL));
                                    die(json_encode($result));    
                                else:
                                    $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                                    die(json_encode($result));
                                endif;
                            endif;
                        }
                    }

                    if($join_type == 12) // E-check
                    {
                        $chk = user_exist($username);
                        if($chk >0)
                        {
                            $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                            die(json_encode($result));
                        }
                        else
                        {
                            //Process the echeck payment. If there is no problem, registration of user will be executed
                            require_once(dirname(__FILE__)."/echeck/process.php");
                            $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                            die(json_encode($result));
                        }
                    }

                //} 
            }
            /*else 
            { 
                $error_epin = "<font color=\"#FF0000\" size=\"2\"><strong>Enter Correct Register Pin !</strong></center></font>"; 
            }*/
        }   
    }   
    $curr_year = date('Y')-13;
    $curr_month = date('m');
    $curr_day = date('d');
?>