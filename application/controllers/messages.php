<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Messages extends CI_Controller{
	
	 public function __construct()
	 {
	  parent::__construct();
		$sections = array(
		'config'  => FALSE,
		'queries' => TRUE
		);

		$this->output->set_profiler_sections($sections);
		//$this->output->enable_profiler(TRUE);
		
		if(($this->session->userdata('username')==""))
		{
			// $this->myhome();
			redirect('/', 'location');
			return;
		}
		$this->load->model('user_model');
		$this->load->model('message_model');
	 }
	 
	 public function index($thread_username='')
	 {
		 //$this->lang->load('messages');
		$data['messages'] =  empty($thread_username)? FALSE:$this->message_model->get_messages_thread($this->session->userdata('user_id'),$thread_username);
		$data['thread_username'] =  empty($thread_username)? FALSE:$thread_username;
		//$data['messages'] =  $this->message_model->get_messages();
		//$data['messages'] = $this->message_model->get_messages(3267);
		
		$data['title']= $this->lang->line('common_welcome');
		$this->load->view('header_view',$data);
		$this->load->view('messages_view.php', $data);
		$this->load->view('footer_view',$data);
	 }
}
?>