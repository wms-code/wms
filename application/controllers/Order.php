<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$this->db->where('admin_id', $_SESSION['user_id']);
		$query=$this->db->get('orders');
		$html='';
                if ($query->num_rows() > 0)
                {
                   foreach ($query->result() as $row)
                   {
                    $html.="<tr>";
                    $html.="<td> $row->order_id </td>";
                    $this->db->select('first_name,last_name');
				    $this->db->where('id',$row->user_id);
					$user=$this->db->get('users');
					$usrow = $user->row();
					$html.= "<td> $usrow->first_name $usrow->last_name </td>";
					$html.="<td> $row->created_on </td>";
					$html.="<td><span class='label label-warning'>$row->order_status</span></td>";
					$html.="<td> Bacon ipsum dolor sit fatback doner. </td>";
					$html.="</tr>";
					}
				}
			$data['html']=$html;

		$this->load->view('layouts/order',$data);
	}
	function getusername($value=FALSE)
	{
		
	}

	public function add()
	{
	
		if($this->input->post('user')) 
		{
			if($this->input->post('domain')) 
			{
				//add invoice
				$user=$this->input->post('user');
				$domain=$this->input->post('domain');
				$product=$this->input->post('product');
				$type=$this->input->post('type');
				$newinvoice['user_id']=$user;
				$invoice=$this->db->insert('invoices',$newinvoice);
				$invoice_id = $this->db->insert_id();
				//add order
				$order['user_id']=$user;
				$order['invoice_id']=$invoice_id;
				$order['admin_id']=$_SESSION['user_id'];
				$orders=$this->db->insert('orders',$order);
				$order_id=$this->db->insert_id();
				if($type=='bundle'||$type!='hosting') 
				{
					if($product!='none')
					{
					//add hosting
					$hosting['order_id']=$order_id;
					$hosting['admin_id']=$_SESSION['user_id'];
					$hosting['package_id']=$product;
					$hosting['domain_name']=$domain;
					$hosting['username']=substr($domain, 0, 8);
					$hosting['password']=$this->gen_uid();
					$products=$this->db->insert('products', $hosting);
					//add item1
					$this->db->where('package_id',$product);
					$this->db->limit(1); 
					$query=$this->db->get('packages');
					$row = $query->row_array();
					$item['invoice_id']=$invoice_id;
					$item['description']=$row['product_category'].' - '.$row['product_name'];
					$item['amount']=$row['product_price'];
					$invoice_item=$this->db->insert('invoice_items', $item);
					}

				}
				if($type=='bundle'||$type=='domain') 
				{

					$domains['order_id']=$order_id;
					$domains['admin_id']=$_SESSION['user_id'];
					$domains['domain_name']=$domain;
					$domains=$this->db->insert('domains', $domains);
					//add items2
					$items['invoice_id']=$invoice_id;
					$items['description']="Domain Registration:".$domain;
					$items['amount']="500";
					$invoice_items=$this->db->insert('invoice_items', $items);
				}

			}	
		

		}

		$this->db->where('admin_id', $_SESSION['user_id']);
		$data['user']=$this->db->get('users');

		$this->db->where('admin_id', $_SESSION['user_id']);
		$data['plans']=$this->db->get('packages');

		$this->load->view('layouts/order',$data);


	}

	function gen_uid($l=8)
	{
    return substr(str_shuffle("124578ABDEHLRS@-&=?abcdefghjklmnpqrstuwxyz"), 0, $l);
	}

}
