<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	
	 public function __construct() {
        parent::__construct();
        $this->load->library('bcrypt');
		
    }
    public function index()
    {
    	 $this->load->view('login');
    }
    
	public function auth()
	{

		if ($this->input->post('email')) {

			//validate form input
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == FALSE)
	        {
	                $this->load->view('login');
	        }
	        else
	        {
	        	
	        	$this->db->where('email',$_POST['email']);
	        	$password=$_POST['password'];
	        	$query=$this->db->get('admins');
	        	if ($query->num_rows()==1) {
	        		foreach ($query->result() as $row)
	        		{
	        			$dbpassword= $row->password;
	        		}

	        		if ($this->bcrypt->verify($password,$dbpassword)==1) {
	        			echo "success";
	        		}
	        		else
	        		{
	        			$this->load->view('loginfail');
	        		}
	        	
	        	}
	        	else
	        	{
	        		 $this->load->view('loginfail');
	        	}
	        	
	        	

	        }
		}
		else
		{
			$this->load->view('login');

		}
		


		/*$password="admin";
		$dbpassword='$2y$07$YtTsbiCiHw6FsaaErRzQau.Lim5sWlsI2yzdY..P12i6Y7OIiyVr6  ';
		echo $identity=$this->gen_uid().'-'.$this->gen_uid().'-'.rand(000,999);

		echo "password:".$this->bcrypt->hash($password)."<br>";
		//echo "salt:".$this->bcrypt->getSalt()."<br>";
		echo $this->bcrypt->verify($password,$dbpassword);
*/
		
	}

	function gen_uid($l=4)
	{
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $l);
	}



}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */