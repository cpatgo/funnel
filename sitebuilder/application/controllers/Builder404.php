<?php 
class Builder404 extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct(); 
    } 

    public function index() 
    { 
    	$data['return_link'] = $_SERVER['HTTP_REFERER'];
        $this->output->set_status_header('404'); 
        $this->load->view('sites/builder404', $data);
    } 
} 
?> 