<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing payment functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Payment extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function get_authorize_data($user_id, $amount)
    {
        $ipn = sprintf("SELECT * FROM authorize_ipn WHERE user_id = %d AND amount = '%0.2f' AND response_code = 1", $user_id, $amount);
        return $this->select($ipn);
    }

    function insert_payment($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO payments (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function update_payment_user_id($old_user_id, $new_user_id)
    {
        $update_sql = sprintf("UPDATE payments set user_id = %d WHERE user_id = 100%d", $new_user_id, $old_user_id);
        return $this->update($update_sql);
    }

    function update_paid_unpaid_user($data)
    {
        $data = $data[0];
        $update_sql = sprintf("UPDATE paid_unpaid set paid_date = '%s', paid = 1 WHERE id = %d", date('Y-m-d', strtotime($data[20])), $data[15]);
        return $this->update($update_sql);
    }

    function get_all_paid_users()
    {
        $sql = "SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id WHERE um.initial > 1";
        return $this->select($sql);
    }

    function authorize_payments($user_id, $from = '', $to = '')
    {
        $where_date = (!empty($from) && !empty($to)) ? sprintf("AND DATE(date_created) BETWEEN '%s' AND '%s'", $from, $to) : '';
        $authorize_payments = sprintf("SELECT * FROM authorize_ipn WHERE response_code = 1 %s ORDER BY paid_date desc", $where_date);
        return $this->select($authorize_payments);
    }

    function get_payments($from = '', $to = '')
    {
        $where_date = (!empty($from) && !empty($to)) ? sprintf("AND DATE(paid_date) BETWEEN '%s' AND '%s'", $from, $to) : '';
        $payza_history_sql = sprintf("SELECT * FROM paid_unpaid WHERE paid = 1 %s ORDER BY paid_date desc", $where_date);
        return $this->select($payza_history_sql);
    }

    function edata_ipn($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO edata_ipn (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function authorize_ipn($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO authorize_ipn (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function get_credit_card_payments($merchant_slug)
    {
        $payments = sprintf("SELECT * FROM user_membership um INNER JOIN users u ON um.user_id = u.id_user WHERE um.payment_type = '%s' ORDER BY u.id_user DESC", $merchant_slug);
        return $this->select($payments);
    }

    function get_merchant_name($merchant_slug)
    {
        $ipn = sprintf("SELECT * FROM merchants WHERE slug = '%s'", $merchant_slug);
        $merchant = $this->select($ipn);
        return $merchant[0]['merchant'];
    }

    function get_authorize_ipn($user_id, $start_date, $end_date, $payment_type)
    {
        $where = (!empty($start_date) && !empty($end_date)) ? sprintf("AND date_created BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $ipn = sprintf("SELECT * FROM authorize_ipn WHERE user_id = %d AND response_code = 1 AND payment_type = %d %s", $user_id, $payment_type, $where);
        return $this->select($ipn);
    }

    function get_edata_ipn($user_id, $start_date, $end_date)
    {
        $where = (!empty($start_date) && !empty($end_date)) ? sprintf("AND date_created BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $ipn = sprintf("SELECT * FROM edata_ipn WHERE user_id = %d AND response = 1 %s", $user_id, $where);
        return $this->select($ipn);
    }

    function get_echeck_ipn($user_id, $start_date, $end_date, $payment_type)
    {
        $where = (!empty($start_date) && !empty($end_date)) ? sprintf("AND date_created BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $ipn = sprintf("SELECT * FROM echeck_ipn WHERE customerid = %d AND checkstatus = 'Accepted' AND payment_type = %d %s", $user_id, $payment_type, $where);
        return $this->select($ipn);
    }

    function authorize_update_user_id($old_user_id, $new_user_id)
    {
        $update_sql = sprintf("UPDATE authorize_ipn set user_id = %d WHERE user_id = 100%d", $new_user_id, $old_user_id);
        return $this->update($update_sql);
    }

    function edata_update_user_id($old_user_id, $new_user_id)
    {
        $update_sql = sprintf("UPDATE edata_ipn set user_id = %d WHERE user_id = 100%d", $new_user_id, $old_user_id);
        return $this->update($update_sql);
    }

    function echeck_update_user_id($old_user_id, $new_user_id)
    {
        $update_sql = sprintf("UPDATE echeck_ipn set customerid = %d WHERE customerid = 100%d", $new_user_id, $old_user_id);
        return $this->update($update_sql);
    }

    function echeck_ipn($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO echeck_ipn (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function upgrade_authorize_ipn($start_date, $end_date, $payment_type)
    {
        $where = (!empty($start_date) && !empty($end_date)) ? sprintf("AND date_created BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $ipn = sprintf("SELECT * FROM authorize_ipn WHERE response_code = 1 AND payment_type = %d %s", $payment_type, $where);
        return $this->select($ipn);
    }

    function upgrade_edata_ipn($start_date, $end_date)
    {
        $where = (!empty($start_date) && !empty($end_date)) ? sprintf("AND date_created BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $ipn = sprintf("SELECT * FROM edata_ipn WHERE response = 1 %s", $where);
        echo $ipn;
        return $this->select($ipn);
    }

    function upgrade_echeck_ipn($start_date, $end_date, $payment_type)
    {
        $where = (!empty($start_date) && !empty($end_date)) ? sprintf("AND date_created BETWEEN '%s' AND '%s'", $start_date, $end_date) : '';
        $ipn = sprintf("SELECT * FROM echeck_ipn WHERE checkstatus = 'Accepted' AND payment_type = %d %s", $payment_type, $where);
        return $this->select($ipn);
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