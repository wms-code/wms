<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$this->load->view('layouts/main');
	}

	public function login()
	{
		$this->load->view('layouts/login');

	}

	public function data()
	{
		
		$this->load->library('datatables');
		$this->datatables->select('firstname, lastname, gender');
		$this->datatables->from('persons');

		echo $this->datatables->generate('json', 'ISO-8859-1');
	}

	public function dispdata()
	{
		$this->load->view('datatable');
	}

}
