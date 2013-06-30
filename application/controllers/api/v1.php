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
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function user_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
 
    function users_get()
    {
    	$get_all_options['offset'] = 0;
		//$get_all_options['country_id'] = 10;
		$get_all_options['gender'] = FALSE;	
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
	
	
	
	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}