<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
	}


	public function index()
	{
		if ($this->input->post('email') && $this->input->post('password'))
		{

	 		$identity=$this->gen_uid().'-'.rand(1000,9999).'-'.$this->gen_uid(3);
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			$data['first_name'] = $this->input->post('first_name');
			$data['last_name'] = $this->input->post('last_name');
			$data['company'] = $this->input->post('company');
			$data['phone'] = $this->input->post('phone');
			$data['zip_code'] = $this->input->post('zip_code');
			$data['address1'] = $this->input->post('address1');
			$data['address2'] = $this->input->post('address2');
			$data['city'] = $this->input->post('city');
			$data['state'] = $this->input->post('state');
			$data['country'] = $this->input->post('country');
			$data['admin_id']=$_SESSION['user_id'];
			$addclient=$this->ion_auth->register($identity, $password, $email, $data);
			if ($addclient== FALSE ) {

				log_message('error',"Unable to create user from admin:$addclient");
				
			}
		}


		$this->db->where('admin_id', $_SESSION['user_id']);
		$data['query']=$this->db->get('users');


		$this->load->view('layouts/customer',$data);
	}
	public function del()
	{
			//$this->load->view('layouts/customer');
					echo "<pre>";
			print_r($_SESSION);
			echo "</pre>";

	}

	public function edit()
	{
		if ($this->uri->segment(3)) {

		$identity=$this->uri->segment(3);

		$this->db->where('identity', $identity);
		$data['user']=$this->db->get('users')->row();

		echo "<pre>";
		print_r($data['user']);
			
				
		}
				

	}

	function gen_uid($l=4)
	{
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $l);
	}
	

}
