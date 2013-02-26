<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

	function get_new_messages($user_id,$limit=5,$offset=0) {

		if(empty($user_id)) return FALSE;
	
		
		$this->db->select('privmsgs_id as msg_id, 
			privmsgs_type as msg_type_code,
			users.user_id as from_uid, 
			user_username as from_username,
			count(*) as count,
			LEFT(privmsgs_text,100) as msg_text, 
			FROM_UNIXTIME(privmsgs_date) as msg_date,
			privmsgs_date as msg_timestamp',FALSE);
		$this->db->from('users');
		$this->db->join('(select * from phpbb_privmsgs m2 order by m2.privmsgs_date desc) phpbb_privmsgs', 'users.user_id = phpbb_privmsgs.privmsgs_from_userid');
		$this->db->join('phpbb_privmsgs_text', 'phpbb_privmsgs.privmsgs_id = phpbb_privmsgs_text.privmsgs_text_id');
		$this->db->where("privmsgs_to_userid",$user_id);
		$this->db->where("users.status",1);
		//$this->db->where('privmsgs_type IN('.$this->privmsgs_new_mail.', '.$this->privmsgs_unread_mail.')');
	 	$this->db->group_by('from_username');
		$this->db->order_by("msg_timestamp", "desc"); 
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		
		if($query->num_rows() > 0)
		{
			$results = array();	
			foreach($query->result() as $rows)
			{
				//$rows[] = array('from_photo' => 'photo');
				//echo $this->user_model->get_profile_photo($rows->from_uid)."<br>";
				$rows->msg_thread_username = $rows->from_username;
				unset($rows->from_username);
				$rows->msg_text = character_limiter($rows->msg_text,32,'...');
				$rows->msg_thread_username_img_url = $this->user_model->get_profile_photo($rows->from_uid);
				switch ($rows->msg_type_code) {
					case $this->privmsgs_read_mail:
						$rows->msg_type = 'read';
						break;
					case $this->privmsgs_new_mail:
						$rows->msg_type = 'new';
						break;
					case $this->privmsgs_sent_mail:
						$rows->msg_type = 'sent';
						break;
					case $this->privmsgs_saved_in_mail:
						$rows->msg_type = 'savedin';
						break;
					case $this->privmsgs_saved_out_mail:
						$rows->msg_type = 'savedout';
						break;
					case $this->privmsgs_unread_mail:
						$rows->msg_type = 'unread';
						break;
					default:
						$rows->msg_type = 'unknown';
						break;
				}
				unset($rows->msg_type_code);
				$results[] = $rows;
				//var_dump($rows);
				//echo "<br />";
			}
		}
		 
		 //return $query->result_array();  
		 return $results;
		//$CurrentTime		= time();

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

	public function get_messages_thread($user_id, $uid_thread='') 
	{
		if(empty($user_id)) return FALSE;
		//if(empty($user_thread)) return FALSE;
		
		
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
		$where = "privmsgs_to_userid = '".$user_id."' AND privmsgs_from_userid = '".$uid_thread."' OR privmsgs_to_userid= '".$uid_thread."' AND privmsgs_from_userid = '".$user_id."'";
		$this->db->where($where);
		$this->db->group_by('msg_date'); // TODO: will need to update db to get rid of dups because old system puts a dup row with a privmsgs type 2 (sent email)
		//$this->db->limit($limit,$offset);
		$query = $this->db->get();
		return $query->result_array();
	}
}
?>