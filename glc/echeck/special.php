<?php
if(!empty($username)):
	$merchant_class = getInstance('Class_Merchant');
	$user_class = getInstance("Class_User");
	$payment_class = getInstance("Class_Payment");

	$checknum	= post_var('checknum');
	$routingnum	= post_var('routingnum');
	$accountnum = post_var('accountnum');
	$user_id 	= (!empty($user_id)) ? $user_id : 0;
	$mobile     = preg_replace('/[^A-Za-z0-9]/', '', $phone);

	$membership_details = $user_class->get_membership_by_membership($membership);
	$membership_details = $membership_details[0];

    $membership_details['membership'] = sprintf('Special %0.2f', $membership_amount);

	$echeck_class = getInstance("Class_Echeck");
	$echeck_class->setLogin($echeck_username, $echeck_password);

	//Set Billing Details
	$echeck_class->setCustomer($payment_fname, $payment_lname, $address1, $address2, $city, $state, $zip, $mobile, $email);
	//Set order details
	$echeck_class->setOrder($user_id, $membership_details['membership'], $membership_amount);
	//Process order
	$payment_response = $echeck_class->doSale($checknum, $routingnum, $accountnum);

	if($payment_response->checkstatus == 'Accepted'):
        // Insert user in temp_users table
        // $temuserdata = array(
        //     'parent_id'         => 0,
        //     'real_parent'       => $real_parent_id,
        //     'position'          => 0,
        //     'date'              => date('Y-m-d'),
        //     'activate_date'     => date('Y-m-d'),
        //     'time'              => $time,
        //     'f_name'            => $f_name,
        //     'l_name'            => $l_name,
        //     'user_img'          => '',
        //     'gender'            => $gender,
        //     'email'             => $email,
        //     'phone_no'          => $phone,
        //     'city'              => $city,
        //     'username'          => $username,
        //     'password'          => $password,
        //     'dob'               => $dob,
        //     'address'           => $address,
        //     'country'           => $country,
        //     'type'              => $type,
        //     'user_pin'          => $user_pin,
        //     'beneficiery_name'  => '',
        //     'ac_no'             => '',
        //     'bank'              => '',
        //     'branch'            => '',
        //     'bank_code'         => '',
        //     'payza_account'     => '',
        //     'tax_id'            => '',
        //     'pan_no'            => '',
        //     'pin_code'          => 0,
        //     'father_name'       => '',
        //     'district'          => '',
        //     'state'             => '',
        //     'provience'         => $provience,
        //     'reg_way'           => $reg_by,
        //     'paid'              => 1,
        //     'membership'        => $membership,
        //     'optin_affiliate'   => $optin_aff
        // );
        // $response = $registration->insert_temp_users($temuserdata);
        // if($response['type'] === false):
        //     $result = array('result' => 'error', 'message' => $response['message']); 
        //     die(json_encode($result));
        // endif;
        // $user_id = $response['message'];
        // $payment_class = getInstance('Class_Payment');
        // $data = array(
        //     'customername'      => $payment_response->customername,
        //     'customeraddress1'  => $payment_response->customeraddress1,
        //     'customeraddress2'  => $payment_response->customeraddress2,
        //     'customercity'      => $payment_response->customercity,
        //     'customerstate'     => $payment_response->customerstate,
        //     'customerzip'       => $payment_response->customerzip,
        //     'customerphone'     => $payment_response->customerphone,
        //     'customeremail'     => $payment_response->customeremail,
        //     'product'           => $payment_response->product,
        //     'amount'            => $payment_response->amount,
        //     'checkstatus'       => $payment_response->checkstatus,
        //     'statusmsg'         => $payment_response->statusmsg,
        //     'customerid'        => sprintf('100%d', $user_id),
        //     'transactionid'     => $payment_response->transactionid,
        //     'payment_type'      => 1,
        //     'date_created'      => date('Y-m-d H:i:s')
        // );
        // $save_echeck_payment = $payment_class->echeck_ipn($data);

        // //If company, save company name
        // if(!empty($company_name)):
        //     $user_class->glc_update_usermeta($user_id, 'company_name', $company_name);
        // endif;

        // //REGISTER USER TO WORDPRESS DATABASE
        // $user_class->wp_register_user($temuserdata, $original_regby, $membership, $pw_nohash);
        // // END OF REGISTER USER TO WORDPRESS DATABASE

        // //Insert payment details to payments table
        // $payment_data = array(
        //     'user_id'           => sprintf('100%d', $response['message']),
        //     'payment_method'    => $reg_by,
        //     'payment_type'      => 'echeck',
        //     'amount'            => $membership_amount,
        //     'date_created'      => date('Y-m-d H:i:s')
        // );
        // $payment_class->insert_payment($payment_data);

        // // Send email to user
        // $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

        // //Send email to enroller about referred user
        // $mail->new_affiliate(array('username' => $username, 'membership' => $membership, 'email_address' => $email, 'enroller' => $real_parent_id));

        // $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed!<br>A welcome email will be sent to you once your account is active. Thank you.', GLC_URL));
        // die(json_encode($result)); 

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

        $payment_class = getInstance('Class_Payment');
        $data = array(
            'customername'      => $payment_response->customername,
            'customeraddress1'  => $payment_response->customeraddress1,
            'customeraddress2'  => $payment_response->customeraddress2,
            'customercity'      => $payment_response->customercity,
            'customerstate'     => $payment_response->customerstate,
            'customerzip'       => $payment_response->customerzip,
            'customerphone'     => $payment_response->customerphone,
            'customeremail'     => $payment_response->customeremail,
            'product'           => $payment_response->product,
            'amount'            => $payment_response->amount,
            'checkstatus'       => $payment_response->checkstatus,
            'statusmsg'         => $payment_response->statusmsg,
            'customerid'        => sprintf('100%d', $user_id),
            'transactionid'     => $payment_response->transactionid,
            'payment_type'      => 1,
            'date_created'      => date('Y-m-d H:i:s')
        );
        $save_echeck_payment = $payment_class->echeck_ipn($data);

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
            'payment_type'      => 'echeck',
            'amount'            => $membership_amount,
            'date_created'      => date('Y-m-d H:i:s')
        );
        $payment_class->insert_payment($payment_data);

        // Send email to user
        $mail_result = $mail->welcome_email(array('email_address' => $email, 'fname' => $f_name, 'lname' => $l_name, 'username' => $username));

        //Send email to enroller about referred user
        $mail_result = $mail->new_affiliate(array('username' => $username, 'membership' => $wp_membership, 'email_address' => $email, 'enroller' => $real_parent_id));

        $userdata['password'] = $pw_nohash;
        glc_auto_login($user_id, $userdata);

        $result = array('result' => 'success', 'message' => sprintf('%s/glc/myhub', GLC_URL));
        die(json_encode($result));    
    else:
        //Process will go here if the edata payment did not succeed
        if(!empty($payment_response)):
            $error = json_decode($payment_response->statusmsg);
            if(is_object($error) || is_array($error)):
                $error = (array)$error->Invalid;
                foreach ($error as $key => $value) {
                    foreach ($value as $ekey => $evalue) {
                        $errormsg .= sprintf("INVALID %s\n", htmlentities($evalue));    
                    }
                }
            else:
                $errormsg = htmlentities($payment_response->statusmsg);
            endif;

            //Process will go here if the response of edata is correct
            //Insert edata details to db
            $payment_class = getInstance('Class_Payment');
            $data = array(
                'customername'      => $payment_response->customername,
                'customeraddress1'  => $payment_response->customeraddress1,
                'customeraddress2'  => $payment_response->customeraddress2,
                'customercity'      => $payment_response->customercity,
                'customerstate'     => $payment_response->customerstate,
                'customerzip'       => $payment_response->customerzip,
                'customerphone'     => $payment_response->customerphone,
                'customeremail'     => $payment_response->customeremail,
                'product'           => $payment_response->product,
                'amount'            => $payment_response->amount,
                'checkstatus'       => $payment_response->checkstatus,
                'statusmsg'         => $errormsg,
                'customerid'        => $payment_response->customerid,
                'transactionid'     => $payment_response->transactionid,
                'payment_type'      => 1,
                'date_created'      => date('Y-m-d H:i:s')
            );
            $save_echeck_payment = $payment_class->echeck_ipn($data);
            $result = array('result' => 'error', 'message' => sprintf('%s', $errormsg)); 
            die(json_encode($result));
        else:
            //Process will go here if the response of edata api is not correct
            $result = array('result' => 'error', 'message' => sprintf('%s', "There seems to be a problem with E-check processing. Please contact administrator."));
            die(json_encode($result));
        endif;
    endif;
endif;