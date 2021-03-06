<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class V1 extends REST_Controller
{
	public function __construct()
	 {
	  parent::__construct();
	  $this->load->model('user_model');
	 }
	 
	function user_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
    	$users = array(
			1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
		);
		
    	$user = @$users[$this->get('id')];
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
    function user_post()
    {
        //$this->user_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function user_delete()
    {
    	//$this->user_model->deleteuser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
 
    function users_get()
    {
    	$page = $this->get('page');
		$results_per_page = $this->get('rpp');
		//$get_all_options['country_id'] = 10;
		$get_all_options['gender'] = $this->session->userdata('seeks_gender');
		$get_all_options['limit'] = 12;
        $users = $this->user_model->get_all($get_all_options);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    function usersfinder_get()
    {
    	$page = $this->get('page');
		$results_per_page = $this->get('rpp');
		//$get_all_options['country_id'] = 10;
		$get_all_options['gender'] = $this->session->userdata('seeks_gender');
		$get_all_options['country_id'] = $this->session->userdata('country_id');
		$get_all_options['limit'] = 20;
        $users = $this->user_model->get_all($get_all_options);
        
        if($users)
        {	
            $this->response($users, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
	
    function messagethread_get()  // read
    {
		$this->load->model('message_model');
    	$this->load->helper('text');
        // TODO: check for login
		$messages = $this->message_model->get_messages_thread($this->session->userdata('user_id'),$this->get('thread_username'));

        if($messages)
        {
            $this->response($messages, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any messages!'), 404);
        }
    }
	
	function messagethread_post() //create
    {
        $this->load->model('message_model');
        $this->load->library('form_validation');
		$this->msg_text = $this->post('msg_text',TRUE);
		/*
		if ( ! is_array($str)) {
            return (trim($str) == '') ? FALSE : TRUE;
        } else {
            return ( ! empty($str));
        }*/
		$this->message = $this->message_model->new_message($this->session->userdata('username'), $this->post('to_username'), $this->msg_text);
		
		

        //$message = array('id' => $this->get('id'), 'msg_text' => $this->post('msg_text',TRUE).' '.$this->input->ip_address(), 'msg_date'=>'2013-03-03 18:43:29', 'message' => 'ADDED!');
        
        $this->response($this->message, 200); // 200 being the HTTP response code
    }

    function messages_get()  // read
    {
		$this->load->model('message_model');
    	$this->load->helper('text');
		$messages = $this->message_model->get_messages($this->session->userdata('user_id')); // TODO: fix this

        if($messages)
        {
            $this->response($messages, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any messages!'), 404);
        }
    }

    function messages_post() //create
    {
        //$this->message_model->updateUser($this->session->userdata('user_id'), $this->post('to_username'), $this->post('msg_reply_text') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
	
	
	public function messages_put() //update
	{
		var_dump($this->put('message_id'));
	}
	
	public function statusfeed_get_sample()
	{
		$out = array('id' => '43', 'statusfeed_username' => 'someuser', 'statusfeed_content' => 'some content here');
        
        $this->response($out, 200); // 200 being the HTTP response code
	}
	
	function statusfeed_get()  // read --------------------------------------------------------
    {
		$this->load->model('user_model');
    	$this->load->helper('text');
		$user_id = $this->session->userdata('user_id');
        // TODO: check for login
        if(!empty($user_id)) {
        	$feeds_per_page = $this->get('feeds_per_page');
			$offset = $this->get('offset');
			if(empty($feeds_per_page)) { $feeds_per_page = 10; }
			$offset = ($offset >= 0)? $offset:0;
			$ret_array = $this->user_model->get_statusfeed($user_id,$feeds_per_page,$offset);
	
	        if($ret_array)
	        {
	            $this->response($ret_array, 200); // 200 being the HTTP response code
	        }
	        else
	        {
	            $this->response(array('error_id'=>2,'error' => 'No feeds to show!'), 404);
	        }
		} else { //user not logged
			$this->response(array('error_id'=>1,'error' => 'You need to log in!'), 404);
		}
    }
	
	public function statusfeed_post() // new --------------------------------------
	{
		$this->load->model('user_model');
        $this->load->library('form_validation');
		$user_id = $this->session->userdata('user_id');
		if(!empty($user_id)) {
			$this->status_text = $this->post('status_text',TRUE);
			$this->statuspost = $this->user_model->new_statuspost($this->session->userdata('username'), $this->post('to_username'), $this->status_text);
			$this->response($this->statuspost,200);
		} else { //user not logged
			$this->response(array('error_id'=>1,'error' => 'You need to log in!'), 404);
		}
	}
	
	public function emailchange_post() {
		//$this->load->helper(array('form', 'url'));
		$this->load->helper('email');
		$this->load->model('user_model');
		$user_id = $this->session->userdata('user_id');
		$new_email = $this->post('user_email');
		$new_email = $this->security->xss_clean($new_email);
		$new_email = str_replace('[removed]', '', $new_email);
		if(!empty($user_id)) {
			//if ($this->form_validation->run() === FALSE) {
			if (valid_email($new_email)){
				if($this->user_model->change_email($user_id, $new_email)) {
					$out = array('success'=> 'true', 'message' => $this->lang->line('common_email_has_been_changed'), 'new_email' => $new_email);
				} else {
					$out = array('success' => 'false', 'message'=> $this->lang->line('common_could_not_update_db'));
				}
			} else {
				$out = array('success' => FALSE, 'message'=> $this->lang->line('common_enter_a_valid_email'));
			}
        	$this->response($out, 200); // 200 being the HTTP response code
		} else {
			$out = array('success' => 'false','message' => 'not logged in');
		}
		
	}
	
	public function cancelaccount_post() {

		$user_id = $this->session->userdata('user_id');
		if(!empty($user_id)) {
			$cancel_reason = substr($this->post('cancel_account_reason'),0,255); //get first 255 characters
			$cancel_reason = $this->common->html2text($cancel_reason);
			$this->load->model('user_model');
			if($this->user_model->cancel_user($user_id, $cancel_reason)) {
				$out = array('success'=> 'true', 'message' => $this->lang->line('common_cancel_account_success'),'reason'=>$cancel_reason);
			} else {
				$out = array('success' => 'false', 'message'=> $this->lang->line('common_could_not_update_db'));
			}
		} else {
			$out = array('success' => 'false','message' => 'not logged in' );
		}
		$this->response($out, 200); // 200 being the HTTP response code
	}
	
	public function changepassword_post()
	{
		$user_id = $this->session->userdata('user_id');
		if(!empty($user_id)) {
			$this->load->model('user_model');
			$old_pass = md5($this->post('old_password'));
			$new_pass = md5($this->post('new_password'));
			$password_ok = $this->user_model->verify_password($user_id, $old_pass);
			if($password_ok){
				if($old_pass == $new_pass) {
					$out = array('success' => 'false', 'message'=> $this->lang->line('common_password_could_not_be_changed'));
				} else {
					if($this->user_model->change_password($user_id, $new_pass)) {
						$out = array('success'=> 'true', 'message' => $this->lang->line('common_password_has_been_changed'));
					} else {
						$out = array('success' => 'false', 'message'=> $this->lang->line('common_could_not_update_db'));
					}
				}
			} else {
				$out = array('success' => 'false', 'message' => $this->lang->line('common_wrong_password') );
			}
		} else {
			$out = array('success' => 'false','message' => 'not logged in' );
		}
		$this->response($out, 200); // 200 being the HTTP response code
	}
	
	
	function statuscomment_post() //create
    {
        $out = $this->user_model->new_status_comment('', $this->post('status_id'), $this->post('comment_text') );
        //$out = array('uid'=>$this->session->userdata('user_id'), 'status_id'=>$this->post('status_id'), 'comment'=>$this->post('comment_text') );
        //$comment = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($out, 200); // 200 being the HTTP response code
    }
	
	public function checkusername_get()
	{
		//$out = array('username' => FALSE);
		 //$this->response($out, 200); // 200 being the HTTP response code
		 $exists = $this->user_model->check_username_exists($this->get('username'));
		 echo ($exists == TRUE)? 'false':'true'; //if it exist then we echo false -- that's what jquery validator expects
	}
	
	public function checkemail_get()
	{
		//$out = array('username' => FALSE);
		 //$this->response($out, 200); // 200 being the HTTP response code
		 $exists = $this->user_model->check_email_exists($this->get('email_address'));
		 echo ($exists == TRUE)? 'false':'true'; //if it exist then we echo false -- that's what jquery validator expects
	}
	
	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}