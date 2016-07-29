<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing payza functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Payza extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function insert_payza_ipn($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO payza_ipn (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function get_batchnumbers($from = '', $to = '')
    {
        $where_date = (!empty($from) && !empty($to)) ? sprintf("AND DATE(ap_transactiondate) BETWEEN '%s' AND '%s'", $from, $to) : '';
        $payza_history_sql = sprintf("SELECT DISTINCT(ap_batchnumber), ap_transactiondate FROM payza_ipn WHERE ap_transactiontype = 'masspay' %s ORDER BY ap_transactiondate desc", $where_date);
        return $this->select($payza_history_sql);
    }

    function get_batch_transaction($batch_no, $from = '', $to = '')
    {
        $where_date = (!empty($from) && !empty($to)) ? sprintf("AND DATE(pi.ap_transactiondate) BETWEEN '%s' AND '%s'", $from, $to) : '';
        $payza_history_sql = sprintf("SELECT * FROM payza_ipn pi LEFT JOIN users u ON pi.ap_mpcustom = u.id_user WHERE pi.ap_transactiontype = 'masspay' AND pi.ap_batchnumber = '%s' %s ORDER BY ap_mpcustom desc", $batch_no, $where_date);
        return $this->select($payza_history_sql);
    }

    function get_pending_payments($from = '', $to = '')
    {
        $where_date = (!empty($from) && !empty($to)) ? sprintf("AND DATE(pu.request_date) BETWEEN '%s' AND '%s'", $from, $to) : '';
        $get_pending_payments = sprintf("SELECT u.username, u.payza_account, pu.* FROM paid_unpaid pu LEFT JOIN users u ON pu.user_id = u.id_user WHERE pu.paid = 0 %s ORDER BY pu.request_date desc", $where_date);
        return $this->select($get_pending_payments);
    }

    function get_user_via_paid_unpaid_id($id)
    {
        $get_user = sprintf("SELECT * FROM users u INNER JOIN paid_unpaid pu ON u.id_user = pu.user_id WHERE pu.id = %d AND pu.paid = 0", (int)$id);
        return $this->select($get_user);
    }

    function get_all_user_via_paid_unpaid_id()
    {
        $get_users = sprintf("SELECT * FROM users u INNER JOIN paid_unpaid pu ON u.id_user = pu.user_id WHERE pu.paid = 0");
        return $this->select($get_users);
    }

    function update_paid_unpaid_user($id)
    {
        $update_sql = sprintf("UPDATE paid_unpaid set paid_date = '%s', paid = 1 WHERE id = %d", date('Y-m-d'), $id);
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