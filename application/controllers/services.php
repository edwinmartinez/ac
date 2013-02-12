<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Services extends CI_Controller{
	
	public function __construct()
	 {
	  parent::__construct();
	 }
	 
	 public function index()
	 {
	 	echo 'services';
	 }
	 
	 public function users($user_id=-1)
	 {
		$this->load->model('user_model');
		
		if($user_id != -1) { //if not -1 then retun the specified user
			$data['json'] = '{"user":'.json_encode($this->user_model->get_info($user_id)).'}';
			$this->load->view('json_view', $data);
			return;
		}
		else // get all users
		{
			$config['base_url'] = site_url('?offset=0&limit=20');
			$this->load->view('json_view', $data);
			return;
		}
	
	 }
	
}
