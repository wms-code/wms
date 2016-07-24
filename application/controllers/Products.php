<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$this->db->where('admin_id', $_SESSION['user_id']);
		$query=$this->db->get('products');
		$html='';
                if ($query->num_rows() > 0)
                {
                   foreach ($query->result() as $row)
                   {
                    $html.="<tr>";
                    $html.="<td> $row->order_id </td>";
					$html.="<td>$row->domain_name</td>";
					$html.="<td> $row->created_on </td>";
					$html.= "<td><span class='label label-warning'> pending </td></span>";
                    $this->db->select('product_name,product_category');
				    $this->db->where('package_id',$row->package_id);
					$user=$this->db->get('packages');
					$usrow = $user->row();
					$html.="<td> $usrow->product_category - $usrow->product_name </td>";
					$html.="</tr>";
					}
				}
			$data['html']=$html;

		$this->load->view('layouts/order',$data);
	}
	public function domains()
	{
		$this->db->where('admin_id', $_SESSION['user_id']);
		$query=$this->db->get('products');
		$html='';
                if ($query->num_rows() > 0)
                {
                   foreach ($query->result() as $row)
                   {
                    $html.="<tr>";
                    $html.="<td> $row->order_id </td>";
                    $this->db->select('invoice_id,order_status');
				    $this->db->where('order_id',$row->order_id);
					$html.="<td>$row->domain_name</td>";
					$html.="<td> $row->created_on </td>";
					$user=$this->db->get('orders');
					$usrow = $user->row();
					$html.= "<td><span class='label label-warning'> $usrow->order_status</td></span>";
					$html.="<td> Bacon ipsum dolor sit fatback doner. </td>";
					$html.="</tr>";
					}
				}
			$data['html']=$html;

		$this->load->view('layouts/order',$data);
	}
	

}
