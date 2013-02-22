<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Messages extends CI_Controller{
	
	 public function __construct()
	 {
	  parent::__construct();
	  if(($this->session->userdata('username')==""))
	  {
	 // $this->myhome();
	  redirect('/', 'location');
	  return;
	  }
	  $this->load->model('user_model');
	  $this->load->model('message_model');
	 }
	 
	 public function index()
	 {
		 //$this->lang->load('messages');
		 $data['messages'] = $this->message_model->get_new_messages(3267);
		
		$data['title']= $this->lang->line('common_welcome');
		$this->load->view('header_view',$data);
		$this->load->view('messages_view.php', $data);
		$this->load->view('footer_view',$data);
	 }
}
?>