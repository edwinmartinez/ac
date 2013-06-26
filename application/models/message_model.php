<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_Model {

	protected $privmsgs_read_mail = 0;
	protected $privmsgs_new_mail = 1;
	protected $privmsgs_sent_mail = 2;
	protected $privmsgs_saved_in_mail = 3;
	protected $privmsgs_saved_out_mail = 4;
	protected $privmsgs_unread_mail = 5;
	
	 public function __construct()
	 {
		parent::__construct();
		$this->load->helper('text');
	 }

	public function get_messages($user_id = '',$limit=10,$offset=0)
	{
		return $this->get_inbox($user_id,$limit,$offset);
	}

/*
 * Gets messages for the provided user_id 
 * if no user_id is provided it will try to user the session user_id
 */
	function get_inbox($user_id = '',$limit=10,$offset=0) {

		if(empty($user_id) ) {
			if($this->session->userdata('user_id') == '') {
				echo 'no user provided';
				return FALSE;
			} else {
				$user_id = $this->session->userdata('user_id');
			}
		}
		
		//return FALSE;
	
		
		$this->db->select('privmsgs_id as msg_id, user_gender,
			privmsgs_type as msg_type_code,
			users.user_id as from_uid, 
			users.user_username as from_username,
			count(*) as count,
			LEFT(privmsgs_text,100) as msg_text, 
			FROM_UNIXTIME(privmsgs_date) as msg_date,
			privmsgs_date as msg_timestamp',FALSE);
		$this->db->from('users');
		$this->db->join('(select * from phpbb_privmsgs m2 group by m2.privmsgs_date order by m2.privmsgs_date desc) phpbb_privmsgs', 'users.user_id = phpbb_privmsgs.privmsgs_from_userid');
		$this->db->join('phpbb_privmsgs_text', 'phpbb_privmsgs.privmsgs_id = phpbb_privmsgs_text.privmsgs_text_id');
		$this->db->where("privmsgs_to_userid",$user_id);
		$this->db->where("users.status",1);
		$this->db->where('privmsgs_type IN('.$this->privmsgs_read_mail.','.$this->privmsgs_new_mail.', '.$this->privmsgs_unread_mail.')');
	 	$this->db->group_by('from_username');
		$this->db->order_by("msg_timestamp", "desc"); 
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		
		if($query->num_rows() > 0)
		{
			$results = array();	
			foreach($query->result() as $row)
			{
				$row->msg_thread_username = $row->from_username;
				unset($row->from_username);
				$row->msg_text = character_limiter($row->msg_text,32,'...');
				$row->msg_thread_username_img_url = $this->user_model->get_profile_photo($row->from_uid);
				$row->msg_type = $this->get_message_type($row->msg_type_code);
				unset($row->msg_type_code);
				$results[] = $row;
			}
		}
		 
		 //return $query->result_array();  
		 return $results;
		

	}
	
	function get_new_messages_count(){
		$sql = "SELECT COUNT(*) as MessageCount 
		FROM " . PRIVMSGS_TABLE . " 
		WHERE privmsgs_to_userid = ". $_SESSION['user_id']."
		AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . ", " . PRIVMSGS_UNREAD_MAIL . ")";
	}
	
	public function update_message_status($message_id,$status_type)
	{
		$this->db->set('privmsgs_type', $status_type);
		$this->db->where("privmsgs_id",$message_id);
		$query=$this->db->update("phpbb_privmsgs");
		return TRUE;
	}

	public function get_messages_thread($user_id='', $username_thread='', $limit=20, $offset=0) 
	{
		if(empty($user_id) ) {
			if($this->session->userdata('user_id') == '') {
				echo 'no user provided';
				return FALSE;
			} else {
				$user_id = $this->session->userdata('user_id');
			}
		}
		//if(empty($user_thread)) return FALSE;
		$this->uid_thread = $this->get_user_id($username_thread);
		$this->load->model('user_model');
		$this->profile_images = array();
		
		$this->profile_images[$user_id] = $this->user_model->get_profile_photo($user_id);
		$this->profile_images[$this->uid_thread] = $this->user_model->get_profile_photo($this->uid_thread);
		
		//$username_thread_profile_img_url = $this->user_model->get_profile_photo($this->uid_thread);
		

		$this->db->select('privmsgs_id as msg_id, 
			privmsgs_type as msg_type,
			users.user_id as from_uid, 
			users.user_username as from_username,
			u2.user_username as to_username,
			privmsgs_text as msg_text, 
			FROM_UNIXTIME(privmsgs_date) as msg_date,
			privmsgs_date as msg_timestamp',FALSE);
		$this->db->from('users');
		$this->db->join('phpbb_privmsgs', 'users.user_id = phpbb_privmsgs.privmsgs_from_userid');
		$this->db->join('users u2', 'u2.user_id = phpbb_privmsgs.privmsgs_to_userid');
		$this->db->join('phpbb_privmsgs_text', 'phpbb_privmsgs.privmsgs_id = phpbb_privmsgs_text.privmsgs_text_id');
		$where = "privmsgs_to_userid = '".$user_id."' AND privmsgs_from_userid = '".$this->uid_thread."' OR privmsgs_to_userid= '".$this->uid_thread."' AND privmsgs_from_userid = '".$user_id."'";
		$this->db->where($where);
		$this->db->group_by('msg_date'); // TODO: will need to update db to get rid of dups because old system puts a dup row with a privmsgs type 2 (sent email)
		$this->db->order_by("msg_timestamp", "desc");
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$this->results = array();	
			foreach($query->result() as $row)
			{
				$row->from_username_img_url = $this->profile_images[$row->from_uid];
				$this->results[] = $row;
			}
		}
		//return $query->result_array();
		return array_reverse($this->results);
	}
	
	public function get_message_type($msg_type_code)
	{
		switch ($msg_type_code) {
					case $this->privmsgs_read_mail:
						$this->msg_type = 'read';
						break;
					case $this->privmsgs_new_mail:
						$this->msg_type = 'new';
						break;
					case $this->privmsgs_sent_mail:
						$this->msg_type = 'sent';
						break;
					case $this->privmsgs_saved_in_mail:
						$this->msg_type = 'savedin';
						break;
					case $this->privmsgs_saved_out_mail:
						$this->msg_type = 'savedout';
						break;
					case $this->privmsgs_unread_mail:
						$this->msg_type = 'unread';
						break;
					default:
						$this->msg_type = 'unknown';
						break;
				}
		return $this->msg_type;
	}
	
	public function new_message($from_username='', $to_username, $msg_text, $msg_type='')
	{
		// let's take care of the from_user_id
		if(empty($from_username) ) {
			if($this->session->userdata('user_id') == '') {
				echo 'no from username provided';
				return FALSE;
			} else {
				$this->from_user_id = $this->session->userdata('user_id');
			}
		} else {
			$this->from_user_id = $this->get_user_id($from_username);
		}
		if(empty($to_username)) {
			echo 'no to_username provided';
			die;
		}

		$this->to_user_id = $this->get_user_id($to_username);
		
		$this->msg_text = $msg_text;
		
		$this->msg_type = (empty($msg_type))? $this->privmsgs_unread_mail:$msg_type;
	
		$data=array(
		    'privmsgs_from_userid' => $this->from_user_id,
		    'privmsgs_to_userid' => $this->to_user_id,
		    'privmsgs_subject' => 'msg',
		    'privmsgs_type' => $msg_type,
			'privmsgs_date' => time(),
			'privmsgs_ip_address' => ip2long($this->input->ip_address()) //using the new field
		);
		$this->db->insert('phpbb_privmsgs',$data);
		//get the last inserts id and insert it in gen_prefs
		$this->msg_id = $this->db->insert_id();
		$data = array();
		$data = array(
			'privmsgs_text_id' => $this->msg_id,
			'privmsgs_text' => $msg_text
		);
		$this->db->insert('phpbb_privmsgs_text',$data);

		$this->load->model('user_model');
		
		
			
		$message = array(
			'msg_id' => $this->msg_id,
			'msg_date' => time(),
			'msg_text' => $this->msg_text,
			'from_username' => $from_username,
			'from_username_img_url' => $this->user_model->get_profile_photo($this->from_user_id),
			'to_username' => $to_username
			
		);
		
		
		return $message;
	}
	
	public function get_user_id($username)
	{
		$this->db->select('user_id');
		$this->db->where('user_username', $username);
		$this->db->from('users');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
		   return $row->user_id;
		} else {
			return FALSE;
		}
	}
	
	
}

?>