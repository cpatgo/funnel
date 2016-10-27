<?php
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
?>
<?php
if(isset($_POST['q']) && isset($_POST['username']))
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
        
        if($reg_by == 'authorize_net' || $reg_by == 'authorize_net_2')
        {
            $join_type = 10;
            $epin_exist = 1;
            $type = "B";
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
                $real_p         = $real_parent_id;
                $username       = strtolower($username);
                $membership     = glc_option('aem_special_matrix_membership');
                $wp_membership  = glc_option('aem_special_wp_membership');
                $membership_amount = glc_option('aem_special_registration');

                $optin_aff  = (isset($_POST['acceptTerms1']) && $_POST['acceptTerms1'] == 'on') ? 1 : 0; 

                //Include wordpress functions
                include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
                
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
                            require_once(dirname(__FILE__)."/class/subscription.php");

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

                                if(($apiresponse != null) && ($apiresponse->getMessages()->getResultCode() == "Ok")):
                                    $user_id = $response['message'];  
                                    $tresponseMessages = $apiresponse->getMessages()->getMessage();
                                    $payment_data = array(
                                      'user_id' => sprintf('100%d', $user_id),
                                      'cc_fname' => $payment_fname,  
                                      'cc_lname' => $payment_lname,
                                      'response' => $tresponseMessages[0]->getCode(),
                                      'responsetext' => $tresponseMessages[0]->getText(),
                                      'authcode' => $tresponseMessages[0]->getCode(),
                                      'transactionid' => $tresponseMessages->getSubscriptionId(),
                                      'avsresponse' => 0,
                                      'cvvresponse' => 0,
                                      'orderid' => $orderid,
                                      'type' => 'subscription',
                                      'response_code' => $tresponseMessages[0]->getCode(),
                                      'amount' => $membership_amount,
                                      'payment_type' => 3,
                                      'date_created' => date('Y-m-d H:i:s')
                                    );
                                    $payment_id = $payment_class->authorize_ipn($payment_data);
                                endif;

                                //If company, save company name
                                if(!empty($company_name)):
                                    $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
                                endif;

                                //REGISTER USER TO WORDPRESS DATABASE
                                $user_class->wp_register_user($userdata, $original_regby, $wp_membership, $pw_nohash);
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
                            require_once(dirname(__FILE__)."/class/subscription.php");

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

                                if(($apiresponse != null) && ($apiresponse->getMessages()->getResultCode() == "Ok")):
                                    $tresponseMessages = $apiresponse->getMessages()->getMessage();
                                    $payment_data = array(
                                      'user_id' => sprintf('100%d', $user_id),
                                      'cc_fname' => $payment_fname,  
                                      'cc_lname' => $payment_lname,
                                      'response' => $tresponseMessages[0]->getCode(),
                                      'responsetext' => $tresponseMessages[0]->getText(),
                                      'authcode' => $tresponseMessages[0]->getCode(),
                                      'transactionid' => $tresponseMessages->getSubscriptionId(),
                                      'avsresponse' => 0,
                                      'cvvresponse' => 0,
                                      'orderid' => $orderid,
                                      'type' => 'subscription',
                                      'response_code' => $tresponseMessages[0]->getCode(),
                                      'amount' => $membership_amount,
                                      'payment_type' => 3,
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
                                $user_class->wp_register_user($userdata, $original_regby, $wp_membership, $pw_nohash);
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

                // if($join_type == 12) // E-check
                // {
                //     $chk = user_exist($username);
                //     if($chk >0)
                //     {
                //         $result = array('result' => 'error', 'message' => sprintf('%s already exist!', $username)); 
                //         die(json_encode($result));
                //     }
                //     else
                //     {
                //         //Process the echeck payment. If there is no problem, registration of user will be executed
                //         require_once(dirname(__FILE__)."/echeck/special.php");
                //         $result = array('result' => 'error', 'message' => sprintf('There is a problem with your payment. Please contact administrator.')); 
                //         die(json_encode($result));
                //     }
                // }
            }
        }   
    }   
$curr_year = date('Y')-13;
$curr_month = date('m');
$curr_day = date('d');