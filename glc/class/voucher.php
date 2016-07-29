<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');


class Class_Voucher extends Class_Database
{
    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function generate_voucher($data)
    {
        $sql_array = $this->array_to_sql($data);
        $sql = sprintf('INSERT INTO e_voucher (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
        return $this->insert($sql);
    }

    function update_voucher_owner($voucher_id, $user_id)
    {
        $update_sql = sprintf("UPDATE e_voucher set used_id = %d WHERE id = %d", $user_id, $voucher_id);
        return $this->update($update_sql);
    }

    function update_voucher_used_id($old_id, $new_id)
    {
        $update_sql = sprintf("UPDATE e_voucher set used_id = %d WHERE used_id = %d", $new_id, $old_id);
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