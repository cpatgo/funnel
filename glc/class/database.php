<?php
/**
* Base class for accessing the database
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class Class_Database 
{
    private $db;
    private $data;
 
    function __construct($db_con)
    {
        $this->db = $db_con;
    }

    protected function query($query){
        $result = $this->db->query($query);
        if(!$result) return $this->response(false, $this->db->error);
        return $result;
    }

    protected function select($query){
        $this->data = array();
        $result = $this->db->query($query);
        if(!$result) return $this->response(false, $this->db->error);
        if($result->num_rows < 1) return array();
        while($row = $result->fetch_assoc()) {
            $this->data[] = $row;
        }
        return $this->data;
    }

    protected function insert($query){
        if(!$this->db->query($query)) return $this->response(false, $this->db->error);
        return $this->response(true, $this->db->insert_id);
    }

    protected function update($query){
        if(!$this->db->query($query)) return $this->response(false, $this->db->error);
        return $this->response(true, true);
    }

    protected function delete($query){
        if(!$this->db->query($query)) return $this->response(false, $this->db->error);
        return $this->response(true, true);
    }

    protected function response($type, $message)
    {
        $type = (!$type) ? false : true;
        return array('type' => $type, 'message' => $message);
    }
}