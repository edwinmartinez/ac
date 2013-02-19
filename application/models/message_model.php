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
	 }

	function get_new_messages() {
		$this->db->where("privmsgs_to_userid",$login);
		$this->db->where('privmsgs_type IN('.$this->privmsgs_new_mail.', '.$this->privmsgs_unread_mail.')'); 

		$query=$this->db->get("phpbb_privmsgs");
		return $query->result_array();
		if($query->num_rows()>0)
		{
			foreach($query->result() as $rows)
			{
				
			}
		}	  
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
}
?>