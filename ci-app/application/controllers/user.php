<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller{
	
	 public function __construct()
	 {
	  parent::__construct();
	  $this->load->model('user_model');
	 }
	 
 	/*
	Determines if user is loged in 
	*/
	 public function index()
	 {
	  if(($this->session->userdata('user_name')!=""))
	  {
	   $this->welcome();
	  }
	  else{
	  	$this->registration_form();
	  }
	 }
	 
	 public function welcome()
	 {
	  $data['title']= 'Welcome';
	  $this->load->view('header_view',$data);
	  $this->load->view('welcome_view.php', $data);
	  $this->load->view('footer_view',$data);
	 }
	 
	 public function login()
	 {
	  $email=$this->input->post('email');
	  $password=md5($this->input->post('pass'));
	
	  $result=$this->user_model->login($email,$password);
	  if($result) $this->welcome();
	  else        $this->index();
	 }
	 
	 public function registration_form()
	 {
	   $data['title']= 'Home';
	   $data['countries'] = $this->user_model->getCountries();
	   $this->load->view('header_view',$data);
	   $this->load->view("registration_view.php", $data);
	   $this->load->view('footer_view',$data);
	 }
	 
	 public function thank()
	 {
	  $data['title']= 'Thank';
	  $this->load->view('header_view',$data);
	  $this->load->view('thanku_view.php', $data);
	  $this->load->view('footer_view',$data);
	 }
	 
	 public function registration()
	 {
	  $this->load->library('form_validation');
	  // field name, error message, validation rules
	  $this->form_validation->set_rules('sex', 'Sexo', 'trim|required|max_length[1]');
	  $this->form_validation->set_rules('country', 'lang:users_country', 'trim|required|max_length[25]');
	  $this->form_validation->set_rules('birth_day', 'lang:users_day', 'trim|required|max_length[2]');
	  $this->form_validation->set_rules('birth_month', 'lang:users_month', 'trim|required|max_length[2]');
	  $this->form_validation->set_rules('birth_year', 'lang:users_year', 'trim|required|max_length[2]');
	  $this->form_validation->set_rules('username', 'lang:users_username', 'trim|required|min_length[4]|max_length[25]|xss_clean|alpha_dash|is_unique[users.user_username]');
	  $this->form_validation->set_rules('email_address', 'lang:users_email', 'trim|required|valid_email');
	  $this->form_validation->set_rules('password', 'lang:users_password', 'trim|required|min_length[6]|max_length[32]');
	  $this->form_validation->set_rules('confirm_password', 'lang:users_confirm_password', 'trim|required|matches[password]');
	  
	
	  if($this->form_validation->run() == FALSE)
	  {
	   $this->registration_form();
	  }
	  else
	  {
	   $this->user_model->add_user();
	   $this->thank();
	  }
	 }
	 
	 public function logout()
	 {
	  $newdata = array(
	  'user_id'   =>'',
	  'user_name'  =>'',
	  'user_email'     => '',
	  'logged_in' => FALSE,
	  );
	  $this->session->unset_userdata($newdata );
	  $this->session->sess_destroy();
	  $this->index();
	 }
}
?>