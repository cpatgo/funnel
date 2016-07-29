<?php
if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
/**
* Class for performing registration functions
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Boards extends Class_Database
{
    private $board_table = array(1 => 'board', 2 => 'board_second', 3 => 'board_third', 4 => 'board_fourth', 5 => 'board_fifth');

    function __construct($db_con)
    {
        parent::__construct($db_con);
    }

    function get_latest_board($id, $level)
    {
        $get_board = sprintf("SELECT * FROM %s WHERE pos1 = %d AND mode = 0 ORDER BY board_id DESC LIMIT 1", $this->board_table[$level], $id);
        return $this->select($get_board);
    }

    function is_new_user($id, $level)
    {
        $get_board = sprintf("SELECT * FROM %s WHERE pos1 = %d AND mode = 0", $this->board_table[$level], $id);
        $boards = $this->select($get_board);
        if(count($boards) === 0) return true;
        return false;
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