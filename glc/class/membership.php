<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');


class Class_Membership extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function get_memberships()
    {
        $sql = sprintf("SELECT * FROM memberships");
        return $this->select($sql);
    }

    function is_advancing($user_id, $level)
    {
        $level_table = array(
            1 => 'board_break_second',
            2 => 'board_break_third',
            3 => 'board_break_fourth',
            4 => 'board_break_fifth'
        );
        $sql = sprintf("SELECT * FROM %s WHERE user_id = %d", $level_table[$level], $user_id);
        $is_advancing = $this->select($sql);
        if(count($is_advancing) > 0) return false;
        return true;
    }

    function get_membership($membership_id)
    {
        $sql = sprintf("SELECT * FROM memberships WHERE id = %d", $membership_id);
        return $this->select($sql);
    }

    function get_membership_by_name($membership_name)
    {
        $sql = sprintf("SELECT * FROM memberships WHERE membership = '%s'", $membership_name);
        return $this->select($sql);   
    }

    function get_membership_id($membership_name) {
        $sql = sprintf("SELECT id FROM memberships WHERE membership = '%s'", $membership_name);
        return $this->select($sql);   
    }

    function get_membership_amount($membership_id)
    {
        $sql = sprintf("SELECT amount FROM memberships WHERE id = %s", $membership_id);
        return $this->select($sql);
    }

    function get_membership_name($membership_id) {
        $sql = sprintf("SELECT membership FROM memberships WHERE id = %s", $membership_id);
        return $this->select($sql);
    }

    function get_registration_time_last_enrollee($user_id)
    {
        $sql = sprintf('SELECT time FROM users WHERE real_parent = %d AND type <> "F" ORDER BY time DESC LIMIT 1', $user_id);
        $enrollee = $this->select($sql);
        return $enrollee[0]['time'];
    }

    function get_qualification_month()
    {
        $qualification = $this->select(sprintf("SELECT q_time FROM setting WHERE id = 1"));
        return $qualification[0]['q_time'];
    }

    function remaining_qualification_period($user_id, $registration_time)
    {
        //Compute remaining qualification period
        $enrollee_registration_time = $this->get_registration_time_last_enrollee($user_id);
        $q_days = 0;
        $qmonth = $this->get_qualification_month();
        if(!empty($enrollee_registration_time)):
            //If user has enrollee, we will start the qualification time from the date the enrollee registered
            $datediff = strtotime(sprintf('+%d months', $qmonth), $enrollee_registration_time) - time();
            $q_days = floor($datediff/(60*60*24));
        elseif(empty($enrollee_registration_time) && strtotime(sprintf('+%d months', $qmonth), $registration_time) > time()):
            //If user does not have enrollee yet, we will base on the registration time of the user
            $datediff = strtotime(sprintf('+%d months', $qmonth), $registration_time) - time();
            $q_days = floor($datediff/(60*60*24));
        endif;
        return $q_days;
    }

    function is_qualified($user_id, $registration_time, $bool = false)
    {
        $bool_qualified = false;
        $qualified = '<span class="label label-warning yellow-non-qualified">non-Qualified</span>';
        $qmonth = $this->get_qualification_month();
        $referrals = $this->get_num_referrals($user_id);
        $required_referrals = $this->get_required_referrals();

        //If it's the user's first 6 months, user should enroll 2 users right away. After 6 months, 1 user every 6 months
        $registration_plus_qualification_time = strtotime(sprintf('+%d months', $qmonth), $registration_time);
        if(time() < $registration_plus_qualification_time) {
            //If referral is 2 or more on the first qualification months, return qualified
            if($referrals[0]['referrals'] >= (int)$required_referrals): 
                $qualified = '<span class="label label-primary green-qualified">Qualified</span>'; 
                $bool_qualified = true;
            endif;
        } else {
            //Base the number of referrals on the qualification time the user has. If qualification days > 0, user is still qualified
            $enrollee_registration_time = $this->get_registration_time_last_enrollee($user_id);
            $q_days = 0;
            if(!empty($enrollee_registration_time) && $referrals[0]['referrals'] >= (int)$required_referrals):
                //If user has enrollee, we will start the qualification time from the date the enrollee registered
                $datediff = strtotime(sprintf('+%d months', $qmonth), $enrollee_registration_time) - time();
                $q_days = floor($datediff/(60*60*24));
                //If user still has qualification days remaining, return qualified
                if((int)$q_days > 0):
                    $qualified = '<span class="label label-primary green-qualified">Qualified</span>';
                    $bool_qualified = true;
                endif;
            endif;
        }
        return ($bool) ? $bool_qualified : $qualified;
    }

    function second_step_commission($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO income_second_step (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function get_enrollees($user_id, $effectiveDate)
    {
        $sql = sprintf('SELECT count(id_user) as referrals FROM users WHERE real_parent = %d AND type <> "F" AND time > %d', $user_id, $effectiveDate);
        return $this->select($sql);
    }

    function get_num_referrals($user_id)
    {
        $sql = sprintf('SELECT count(id_user) as referrals FROM users WHERE real_parent = %d AND type <> "F"', $user_id);
        return $this->select($sql);
    }

    function get_enroller($user_id)
    {
        $sql = sprintf("SELECT * FROM users WHERE id_user = %d", $user_id);
        return $this->select($sql);
    }

    function check_qualification($user_id)
    {
        $user = $this->get_enroller($user_id);
        if($user[0]['type'] == 'D')
        {
            return '<span class="label label-danger">Blocked</span>';
        } else {
            //Select q_time in settings table : 6months
            $months = $this->get_qualification_month();
            $required_referrals = $this->get_required_referrals();

            //Deduct 6 months from current time
            $effectiveDate = strtotime("-".$months." months", time());

            //Select referrals of the user where the date registered is greater than the effective date
            $enrollees = $this->get_enrollees($user_id, $effectiveDate);
            if($enrollees[0]['referrals'] > (int)$required_referrals) {
                return '<span class="label label-primary green-qualified">Qualified</span>';
            } else {
                return '<span class="label label-warning yellow-non-qualified">non-Qualified</span>';
            }
        }
        return '';
    }

    function get_required_referrals()
    {
        $qualification = $this->select(sprintf("SELECT min_q_referrals FROM setting WHERE id = 1"));
        return $qualification[0]['min_q_referrals'];
    }

    function remaining_grace_period($user)
    {
        // Compute remaining grace period
        $grace_period = 0;
        if((int)$user['initial'] === 3):
            $datediff = strtotime('+15 days', $user['time']) - time();
            $grace_period = floor($datediff/(60*60*24));
        elseif((int)$user['initial'] === 4 || (int)$user['initial'] === 5):
         $datediff = strtotime('+30 days', $user['time']) - time();
         $grace_period = floor($datediff/(60*60*24));
        endif;
        return $grace_period;
    }

    function insert_upgrade_membership($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO membership_upgrade (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function get_pending_memberships()
    {
        $sql = sprintf('SELECT * FROM membership_upgrade WHERE status = 0');
        return $this->select($sql);
    }

    function get_membership_upgrades() {
        $sql = sprintf('SELECT * FROM membership_upgrade WHERE status = 1');
        return $this->select($sql);
    }

    function update_upgrade_membership($upgrade_id)
    {
        $sql = sprintf("UPDATE membership_upgrade SET status = 1, upgraded_date = '%s' WHERE id = %d", date('Y-m-d H:i:s'), $upgrade_id);
        $this->update($sql);
    }

    function upgrade_membership($user_id, $level)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

        $user_class = getInstance('Class_User');
        $membership_class = getInstance('Class_Membership');

        $user = $user_class->get_user($user_id);
        $membership = $user_class->user_membership($user_id);
        $new_membership = $membership_class->get_membership($level);

        //Upgrade user
        $upgrade_user = $user_class->upgrade_user($user[0], $membership[0], $level);
        $update_membership = $user_class->update_membership($user_id, $level);

        //Update wordpress membership
        $upgrade_wp_membership = $user_class->wp_update_membership($new_membership[0]['membership'], $membership[0]['membership'], $user[0]['email']);

        //Enroll user to LifterLMS
        $user_class->lms_enroll_student(get_current_user_id(), $new_membership[0]['membership']);
    }

    function upgrade_special_membership($user_id, $level)
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

        $user_class = getInstance('Class_User');
        $membership_class = getInstance('Class_Membership');

        $user = $user_class->get_user($user_id);
        $membership = $user_class->user_membership($user_id);
        $new_membership = $membership_class->get_membership(2);

        //Upgrade user
        $upgrade_user = $user_class->upgrade_user($user[0], $membership[0], 2);
        $update_membership = $user_class->update_membership($user_id, 2);

        //Update wordpress membership
        $new_membership = glc_option('aem_special_wp_membership');
        $upgrade_wp_membership = $user_class->wp_update_membership($new_membership, $membership[0]['membership'], $user[0]['email']);

        //Enroll user to LifterLMS
        $user_class->lms_enroll_student(get_current_user_id(), $new_membership);
    }

    function array_to_sql($data)
    {
        $count = count($data); $values = ''; $flag = 0;
        $keys = implode(',', array_keys($data));
        foreach ($data as $key => $value) {
            if($key === 'apc_1'):
                $values .= sprintf("'%s'%s", $value, ($flag < $count-1) ? ',' : '');
            else:
                $values .= sprintf('"%s"%s', $value, ($flag < $count-1) ? ',' : '');
            endif;
            $flag++;
        }
        return array('keys' => $keys, 'values' => $values);
    }
}