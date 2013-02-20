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
	 public function index($topmessage='')
	 {
	  if(($this->session->userdata('username')!=""))
	  {
	  $this->myhome();
	  // redirect('/users/myhome', 'location');
	  }
	  else{
	  	$this->registration_form($topmessage);
	  }
	 }
	 
	 public function myhome()
	 {
		$this->load->helper('text');
		
		$data['title']= $this->lang->line('common_welcome');
		$this->load->view('header_view',$data);
		$this->load->view('myhome_view.php', $data);
		$this->load->view('footer_view',$data);
	 }
	 
	 public function login()
	 {
	 	$login=$this->input->post('login');
		$password=md5($this->input->post('pass'));
	 	if(!empty($login) && !empty($password) ){

		  $result=$this->user_model->login($login,$password);
		  if($result) $this->myhome();
		  
		  //if($result)  redirect('/index_v1.php', 'refresh'); //TODO: fix this line to use the internal login
		  //if($result) redirect($this->config->item.'/micuenta?p='.$_REQUEST['p']);
		  else        $this->index();
		}
		else{
			$this->index('Empty Login Vars');
		}
	 }
	 
	 public function userinfo(){
	 	/*
		 * usually we dont echo stuff from the controller
		 */
	 	echo "<h1>userdata</h1>\n";
	 	echo 'username:'.$this->session->userdata('username')."<br>\n";
		echo 'user_id:'.$this->session->userdata('user_id')."<br>\n";
		echo 'user_email:'.$this->session->userdata('user_email')."<br>\n";
		echo 'logged_id:'.$this->session->userdata('logged_in')."<br>\n";
		
	 }
	 
	 /*
	  * Set the session vars to empty and return to index
	  */
	 public function logout()
	 {
	 
	  $newdata = array(
	  'user_id'   =>'',
	  'username'  =>'',
	  'user_email'     => '',
	  'logged_in' => FALSE,
	  );
	  $this->session->unset_userdata($newdata );
	  $this->session->sess_destroy();
	  $this->index('logged out');

	 }
	 
	 public function registration_form($topmessage='')
	 {
	   $data['title'] = $this->lang->line('common_home_page_title');
	   $data['countries'] = $this->user_model->getCountries();
	   $data['topmessage'] = $topmessage;
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
		 
		  $this->form_validation->set_rules('country', 'lang:users_country', 'trim|required|max_length[4]');
		  $this->form_validation->set_rules('birth_day', 'lang:users_day', 'trim|required|max_length[2]');
		  $this->form_validation->set_rules('birth_month', 'lang:users_month', 'trim|required|max_length[2]');
		  $this->form_validation->set_rules('birth_year', 'lang:users_year', 'trim|required|max_length[4]|callback_age_check');
		  $this->form_validation->set_rules('gender', 'lang:users_gender', 'trim|required|max_length[1]');
		  $this->form_validation->set_rules('username', 'lang:users_username', 'trim|required|min_length[4]|max_length[25]|xss_clean|alpha_dash|is_unique[users.user_username]');
		  $this->form_validation->set_rules('email_address', 'lang:users_email', 'trim|required|valid_email|is_unique[users.user_email]');
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
	 
	 public function age_check()
	 {
	 	$min_age = 18;
		
		$age = $this->user_model->getAge($this->input->post('birth_year').$this->input->post('birth_month').$this->input->post('birth_day'));	
		
		if ($age < $min_age)
		{
			$this->form_validation->set_message('age_check', $this->lang->line('users_min_age_notice'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function editaccount () 
	{ 
		if(($this->session->userdata('username')!= ''))
		{
			$data['user_info'] = $this->user_model->get_info($this->session->userdata('user_id'));
			var_dump($data['user_info'] );
			$data['title']= $this->lang->line('common_my_account_settings');
			
			$this->load->view('header_view',$data);
			$this->load->view('editaccount_view.php', $data);
			$this->load->view('footer_view',$data);
		}
	}
	
	 
}
?>