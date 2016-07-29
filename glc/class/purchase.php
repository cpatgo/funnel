<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing purchase functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Purchase extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function get_memberships()
    {
        $get_memberships = sprintf("SELECT * FROM memberships WHERE membership <> 'Free'");
        return $this->select($get_memberships);
    }

    function check_availability($membership_id)
    {
        $check_availability = sprintf("SELECT * FROM e_voucher WHERE user_id = 0 AND used_id = 0 AND mode = 1 AND voucher_type = %d", $membership_id);
        return count($this->select($check_availability));
    }

    function get_pay_modes()
    {
        $get_pay_modes = sprintf("SELECT * FROM payment_methods WHERE status = 1");
        return $this->select($get_pay_modes);
    }

    function insert_purchase($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO purchases (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function insert_purchase_details($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO purchase_details (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function buy_vouchers($purchase_id, $membership_id, $count, $user_id)
    {
        //Get available vouchers
        $get_available_vouchers = sprintf("SELECT * FROM e_voucher WHERE user_id = 0 AND used_id = 0 AND mode = 1 AND voucher_type = %d LIMIT %d", $membership_id, $count);
        $available_vouchers = $this->select($get_available_vouchers);

        foreach ($available_vouchers as $key => $value) {
            $purchased_vouchers = array(
                'purchase_id' => $purchase_id,
                'voucher_id' => $value['id']
            );
            //Insert voucher to purchase_voucher
            $sql_array = $this->array_to_sql($purchased_vouchers);
            $sql = sprintf('INSERT INTO purchase_vouchers (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
            $this->insert($sql);
            //Update voucher owner
            $this->update_voucher_user($value['id'], $user_id);
        }
    }

    function update_voucher_user($voucher_id, $user_id)
    {
        $update_sql = sprintf('UPDATE e_voucher SET user_id=%d WHERE id=%d', $user_id, $voucher_id);
        return $this->update($update_sql);
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