<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('string');

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->helper('language');

		$this->data['pageTitle'] = $this->lang->line('login_page_title');
	}

	//log the user in
	function login()
	{
		if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), 1))
		{
			die(json_encode(array('response_code' => 1)));
		}
		else
		{
			die(json_encode(array('response_code' => 2)));
		}
	}

	function update()
	{
		if($_POST['token'] === 'kkEoms9yo4IcFonWmWgZ'):
			$update = $this->ion_auth->update($_POST['userID'], array('email'=>$_POST['email'], 'password'=>$_POST['password']));
			die(json_encode($update));
		endif;
	}
}
