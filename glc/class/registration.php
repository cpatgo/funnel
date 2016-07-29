<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing registration functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Registration extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function insert_temp_users($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO temp_users (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
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

    function get_membership_amount($membership)
    {
        $sql = sprintf('SELECT amount FROM memberships WHERE membership = "%s"', $membership);
        return $this->select($sql);
    }

    function redirect($uri)
    {
        printf('<script type="text/javascript">window.location = "%s/%s"</script>', parent::$glc_url, $uri);
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