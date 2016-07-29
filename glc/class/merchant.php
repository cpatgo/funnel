<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing merchant functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Merchant extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function get_one($merchant_id)
    {
        $sql = sprintf("SELECT * FROM merchants WHERE id = %d", $merchant_id);
        return $this->select($sql);
    }

    function get_merchant_name($merchant_id) 
    {
        $sql = sprintf("SELECT merchant, slug FROM merchants WHERE id = %d", $merchant_id);
        return $this->select($sql);
    }

    function get_all()
    {
        $sql = sprintf("SELECT * FROM merchants");
        return $this->select($sql);
    }

    function get_merchant_by_slug($slug)
    {
        $sql = sprintf("SELECT * FROM merchants WHERE slug = '%s'", $slug);
        return $this->select($sql);
    }

    function get_payment_methods($merchant_id)
    {
        $sql = sprintf("SELECT * FROM merchant_payment_methods WHERE merchant_id = %d", $merchant_id);
        return $this->select($sql);
    }

    function get_active_merchant($package_id) {
        $sql = sprintf("SELECT * FROM merchant_packages WHERE membership_id = %d AND status = 1", $package_id);
        return $this->select($sql);
    }

    function get_default_merchant_provider() {
        $sql = sprintf("SELECT option_value FROM options WHERE option_name = '%s'", "default_merchant_provider");
        return $this->select($sql);
    }

    function get_default_merchant_environment() {
        $sql = sprintf("SELECT option_value FROM options WHERE option_name = '%s'", "default_merchant_environment");
        return $this->select($sql);   
    }

    // return all settings based on merchant_id
    function get_merchant_environments($merchant_id){
        $sql = sprintf("SELECT * FROM merchant_settings WHERE merchant_id = %d", $merchant_id);
        return $this->select($sql);   
    }

    function get_distinct_environments() {
        $sql = sprintf("SELECT DISTINCT environment FROM merchant_settings");
        return $this->select($sql);         
    }

    function get_active_payment_methods($merchant_id)
    {
        $sql = sprintf("SELECT * FROM merchant_payment_methods WHERE merchant_id = %d AND status = 1", $merchant_id);
        return $this->select($sql);
    }

    function get_all_active_payment_methods() {
        $sql = sprintf("SELECT * from merchant_payment_methods AS tbl1 WHERE tbl1.status = 1 AND tbl1.merchant_id IN (SELECT tbl2.id FROM merchants AS tbl2 WHERE tbl2.status = 1) ORDER BY id DESC;");
        return $this->select($sql);
    }

    function get_packages($merchant_id)
    {
        $sql = sprintf("SELECT mp.*, m.membership, m.amount FROM merchant_packages mp INNER JOIN memberships m ON mp.membership_id = m.id WHERE mp.merchant_id = %d", $merchant_id);
        return $this->select($sql);
    }

    function get_selected_merchant_settings($merchant_id, $environment) 
    { 
        $sql = sprintf("SELECT * FROM merchant_settings WHERE merchant_id = %d AND environment = '%s';", $merchant_id, $environment);
        return $this->select($sql); 
    }

    function update_selected_methods($merchant_id, $methods)
    {
        if($methods === 'all'):
            $this->update(sprintf("UPDATE merchant_payment_methods SET status = 0 WHERE merchant_id = %d", $merchant_id));
        else:
            $methods = implode(',', $methods);
            $this->update(sprintf("UPDATE merchant_payment_methods SET status = 0 WHERE merchant_id = %d AND id NOT IN(%s)", $merchant_id, $methods));
            $this->update(sprintf("UPDATE merchant_payment_methods SET status = 1 WHERE merchant_id = %d AND id IN(%s)", $merchant_id, $methods));
        endif;
    }

    function update_selected_packages($merchant_id, $packages)
    {
        if($packages === 'all'):
            $this->update(sprintf("UPDATE merchant_packages SET status = 0 WHERE merchant_id = %d", $merchant_id));
        else:
            $packages = implode(',', $packages);
            $this->update(sprintf("UPDATE merchant_packages SET status = 0 WHERE merchant_id = %d AND id NOT IN(%s)", $merchant_id, $packages));
            $this->update(sprintf("UPDATE merchant_packages SET status = 1 WHERE merchant_id = %d AND id IN(%s)", $merchant_id, $packages));   
        endif;
    }

    function update_merchant_status($merchant_id, $status)
    {
        $status = ($status === 'true') ? 1 : 0;
        return $this->update(sprintf("UPDATE merchants SET status = %d WHERE id = %d", $status, $merchant_id));
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