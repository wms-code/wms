<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$this->load->view('layouts/main');
	}


	public function hosting()
	{
		$this->load->view('layouts/main');
	}

	public function domain()
	{
		$this->load->view('layouts/main');
	}

	

}
