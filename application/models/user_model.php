<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model {
	 public function __construct()
	 {
	  parent::__construct();
	 }
	 
	 function login($login,$password)
	 {
	  $this->db->where("user_email",$login);
	  $this->db->or_where('user_username', $login); 
	  $this->db->where("user_password",$password);
	  
	
	  $query=$this->db->get("users");
	  if($query->num_rows()>0)
	  {
	   foreach($query->result() as $rows)
	   {
	    //add all data to session
	    $newdata = array(
	      'user_id'  => $rows->user_id,
	      'username'  => $rows->user_username,
	      'seeks_gender' => $rows->user_seeks_gender,
	      'user_email'    => $rows->user_email,
	      'logged_in'  => TRUE,
	    );
	   }
	   $this->session->set_userdata($newdata);
	   return true;
	  }
	  return false;
	 }
	 
	 public function add_user()
	 {
	 	$birthdate = $this->input->post('birth_year').'-'.$this->input->post('birth_month').'-'.$this->input->post('birth_day');
	 	$data=array(
		    'user_username' => $this->input->post('username'),
		    'user_email' => strtolower($this->input->post('email_address')),
		    'user_password' => md5($this->input->post('password')),
			'user_gender' => $this->input->post('gender'),
			'reg_from_ip' => $_SERVER['REMOTE_ADDR'],
			'user_birthdate' => $birthdate,
			'user_country_id' => $this->input->post('country'),
			'user_created' => date('Y-m-d H:i:s', time())
	  );
	  $this->db->insert('users',$data);
	  //get the last inserts id and insert it in gen_prefs
	  $this->db->set('user_id', $this->db->insert_id()); 
	  $this->db->insert($this->config->item('user_preferences_table')); 
	 }
	 
	 public function check_username_exists($username)
	 {
		 
		$this->db->where("user_username",trim($username));
		$this->db->from('users');
		return $this->db->count_all_results();
	 }
	 
	 public function check_email_exists($email)
	 {
	 	$this->db->where("user_email",trim($email));
		$this->db->from('users');
		return $this->db->count_all_results();
	 }
	
	public function verify_password($uid, $password)
	{
		$this->db->where("user_id",$uid);
		$this->db->where("user_password",$password);
		$this->db->from('users');
		return ($this->db->count_all_results() > 0)? TRUE : FALSE;
	}

	public function change_password($uid, $new_pass)
	{
		$data = array('user_password' => $new_pass);
		$this->db->where('user_id', $uid);
		$this->db->update('users', $data); 
		return ($this->db->affected_rows() > 0)? TRUE: FALSE;
	}
	/*
	 * Get countries - get all app approved countries
	 *
	 */
	public function getCountries()
	 {
	 	
		$this->db->where_not_in('countries_iso_code_2', $this->config->item('banned_countries'));	
		$this->db->order_by("countries_name_es", "asc"); 
		$this->db->from('countries');
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			
		}
		return $query->result_array();
	 }
	 
	 function change_email($uid, $new_email) {
		$data = array('user_email' => $new_email);
		$this->db->where('user_id', $uid);
		$this->db->update('users', $data); 
		return ($this->db->affected_rows() > 0)? TRUE: FALSE;
	 }
	 
	 function getAge($YYYYMMDD_In){
                  // Parse Birthday Input Into Local Variables
                  // Assumes Input In Form: YYYYMMDD
                  $yIn=substr($YYYYMMDD_In, 0, 4);
                  $mIn=substr($YYYYMMDD_In, 4, 2);
                  $dIn=substr($YYYYMMDD_In, 6, 2);
                
                  // Calculate Differences Between Birthday And Now
                  // By Subtracting Birthday From Current Date
                  $ddiff = date("d") - $dIn;
                  $mdiff = date("m") - $mIn;
                  $ydiff = date("Y") - $yIn;
                
                  // Check If Birthday Month Has Been Reached
                  if ($mdiff < 0) 
                  {
                        // Birthday Month Not Reached
                        // Subtract 1 Year From Age
                        $ydiff--;
                  } elseif ($mdiff==0)
                  {
                        // Birthday Month Currently
                        // Check If BirthdayDay Passed
                        if ($ddiff < 0)
                        {
                          //Birthday Not Reached
                          // Subtract 1 Year From Age
                          $ydiff--;
                        }
                  }
                  return $ydiff; 
        }

	function count_all()
	{
		$this->db->from('users');
		$this->db->where('status',1);
		return $this->db->count_all_results();
	}
	
	/*
	* Gets information about a particular user
	*/
	function get_info($user_id)
	{
		$this->db->from('users');	
		$this->db->join('countries', 'users.user_country_id = countries.countries_id');
		$this->db->where('users.user_id',$user_id);
		$this->db->where('users.status',1);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	* Returns an array of information about users
	*/
	//function get_all($limit=20,$offset=0,$onlywithphotos=0,$shortinfo=TRUE)
	function get_all($options=array())
	{
		$default_options = array (
			'limit' => 20,
			'offset' => 0,
			'gender' => FALSE, // FALSE, 1 = male or 2 = female
			'onlywithphotos' => 0,
			'shortinfo' => TRUE,
			'country_id' => FALSE,
			'state_id' => FALSE,
			'last_login' => FALSE,
			'status' => 1,
			'group'=> FALSE // groups: newregs
		);
		$options = array_merge($default_options,$options);
		$options['limit'] = ($options['limit'] > 60) ? 60 : $options['limit'] ; //let's put a hard limit to the amount of users returned
		$all_users = array();
		$select_rows = array(
			'user_id',
			'user_username',
			//'user_email',
			'user_gender', // 1 = male, 2 = female
			'user_birthdate',
			'user_country_id',
			'countries_name_es',
			'countries_name',
			'user_state_id',
			'user_state_desc',
			'zone_name',
			'user_last_login'
		 );
		 
		 if($options['onlywithphotos']) {
		 	$select_rows[] = 'photo_filename';
		 } else {
			 $select_rows[] = '(SELECT photo_filename FROM users_gallery WHERE users_gallery.photo_uid = users.user_id AND users_gallery.use_in_profile = 1) AS photo_filename';
		 }
		 $select = join(', ', $select_rows);
		 
		if ($options['shortinfo'])
			$this->db->select($select);
			$this->db->from('users');	
			$this->db->join('countries', 'users.user_country_id = countries.countries_id');
			$this->db->join('geo_regions', 'users.user_state_id = geo_regions.zone_id', 'left');
		if($options['onlywithphotos']) {
			$this->db->join('users_gallery', 'users.user_id = users_gallery.photo_uid', 'left');
			$this->db->where('users_gallery.use_in_profile',1);
		}
		if (!empty($options['gender'])) {
			$this->db->where("users.user_gender",$options['gender']);
		}
		//pull either state or country id
		if(!empty($options['state_id'])) {
			$this->db->where("users.user_state_id",$options['state_id']);
		} elseif (!empty($options['country_id'])) {
			$this->db->where("users.user_country_id",$options['country_id']);
		}
		if($options['status'] != 1) { //if it's other than 1
			$this->db->where('users.status',$options['status']);
		} else {
				$this->db->where('users.status',1);
		}	
		if(empty($options['group']) || $options['group'] == 'newregs') {
			$this->db->order_by("user_created", "desc");
		} 
		$this->db->limit($options['limit'], $options['offset']);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$row->state_name = ($row->user_state_id > 0) ? $row->zone_name : $row->user_state_desc ;
			$row->profile_img = ($row->photo_filename != null)? $this->get_profile_photo_url($row->photo_filename) : '';
			$row->profile_img = $this->get_profile_photo_url($row->photo_filename,'square',$row->user_gender);
			$row->age = $this->age_from_dob($row->user_birthdate);
			unset($row->user_state_id);
			unset($row->user_state_desc);
			unset($row->zone_name);
			unset($row->photo_filename);
			$all_users[]=$row;
		}
		
		if($query->num_rows()>0)
		{
			return $all_users;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	Perform a search on customers
	*/
	function search($search)
	{
		$this->db->from('users');
		$this->db->join('countries', 'users.user_country_id = countries.countries_id');
		//$this->db->join('people','customers.person_id=people.person_id');		
		$this->db->where("(user_first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		user_last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		user_email LIKE '%".$this->db->escape_like_str($search)."%' or 
		user_city LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`user_first_name`,' ',`user_last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and status=1");		
		$this->db->order_by("user_id", "desc");

		return $this->db->get();	
	}
	
	/*
	 * Get Profile photo from a user ids
	 */
	function get_profile_photo($user_id,$bigpic=0){
			
		//lets get the picture
		$this->db->select('user_id, user_username, user_gender, photo_filename');
		$this->db->from('users');
		$this->db->join('users_gallery', 'users.user_id = users_gallery.photo_uid and users_gallery.use_in_profile = 1', 'left');
		$this->db->where('users.status',1);
		$this->db->where('users.user_id',$user_id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0) 
		{
			foreach ($query->result() as $row) {
			    $this->profile_pic =  $this->get_profile_photo_url($row->photo_filename,'square',$row->user_gender);
			}
			return $this->profile_pic;
		} else {
			return base_url()."images/nofoto_m.jpg";
		}	
	}

	// format can be suare or large
	public function get_profile_photo_url($filename_in_db='',$format='square', $gender='')
	{
		if(!empty($filename_in_db)) {
		list($uid,$imgname,$extention) = explode(".", $filename_in_db);
		$basefilename = $uid.".".$imgname;
		
		switch ($format) {
	    case "large":
	        $profile_pic = $basefilename."_l.".$extention;
	        break;
	    case "square":
	        $profile_pic = $basefilename."_sq.".$extention;
	        break;
		}
		
			$member_img_dir_url = base_url().$this->config->item('member_images_dir');
			return $member_img_dir_url."/".$uid."/".$profile_pic;
		}
		
		else {
			if (!empty($gender)) { // if we have a gender specified (most likely)
				return ($gender == 1)? base_url().$this->config->item('application_images_dir')."/nofoto_m.jpg" : base_url().$this->config->item('application_images_dir')."/nofoto_f.jpg"; 
			}
			else {
				return base_url()."images/nofoto_m.jpg";
			}
		}
		 
	}
	
	
	function add_to_fav($user_id,$fav_uid){

		if($user_id == "" || $fav_uid =="") {
			echo $this->lang->line('error_error_while_processing');
			return false;
		}
		$fav_users_table = $this->config->item('favorite_people_table');
		//let's check if the user has allready requested the addition
		
		$this->db->from($fav_users_table);	
		$this->db->where('user_id',$user_id);
		$this->db->where('fav_uid',$fav_uid);
		$query = $this->db->get();
		
		if($query->num_rows() == 0)
		{
			$sql = "INSERT into ".$fav_users_table." (user_uid, fav_uid, date) ".
					"VALUES(".$user_id.", ".$fav_uid.", NOW())";
			$this->db->query($sql);
			return $this->db->affected_rows();
		}
		else
		{
			echo $this->lang->line('error_allready_added_to_fav');
			return FALSE;
		}
	
	}
	
	function delete_fav($user_id, $fav_uid){

		if($user_id == "" || $fav_uid =="") {
			echo $this->lang->line('error_error_while_processing');
			return false;
		}
		$fav_users_table = $this->config->item('favorite_people_table');
		$sql = "INSERT into ".$fav_users_table." (user_uid, fav_uid, date) ".
				"VALUES(".$user_id.", ".$fav_uid.", NOW())";
		$sql = "DELETE from ".$fav_users_table." WHERE user_uid = ".$user_id." AND fav_uid = ".$fav_uid;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	function age_from_dob($dob) {

		list($y,$m,$d) = explode('-', $dob);
		if (($m = (date('m') - $m)) < 0) {
		    $y++;
		} elseif ($m == 0 && date('d') - $d < 0) {
		    $y++;
		}
		return date('Y') - $y;
	}
	
	//-------------------------------------------------------------------------
	public function get_statusfeed($my_uid, $feeds_per_page=10, $offset=0) {
		$this->my_user_id = $my_uid;
		
		/*if(empty($my_username) ) {
			if($this->session->userdata('user_id') == '') {
				echo 'no for username provided';
				return FALSE;
			} else {
				$this->my_user_id = $this->session->userdata('user_id');
			}
		} else {
			$this->my_user_id = $this->common->get_user_id($my_username);
		}
		*/
		
		$select_rows = array(
			'users.user_username as status_username',
			'user_gender',
			'photo_filename',
			'status_id',
			'status_uid',
			'status_text',
			'status_date',
		);
		$select = join(', ', $select_rows);
		
		$this->db->select($select);
		$this->db->from('users_status');
		$this->db->join('buddies','buddies.buddy_uid = status_uid');
		$this->db->join('users','users.user_id = status_uid');
		$this->db->join('users_gallery', 'users.user_id = users_gallery.photo_uid and users_gallery.use_in_profile = 1', 'left');
		$this->db->where('buddies.confirmed',1);
		$this->db->where('buddies.user_uid',$this->my_user_id);
		$this->db->where('users.status',1);
		//$this->db->where('status_uid','buddies.buddy_uid');
		$this->db->order_by("status_date", "desc");
		$this->db->limit(10, $offset);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$this->results = array();	
			foreach($query->result() as $row)
			{
				//$row->from_username_img_url = $this->profile_images[$row->status_uid];
				$row->profile_pic =  $this->get_profile_photo_url($row->photo_filename,'square',$row->user_gender);
				unset($row->photo_filename);
				$this->results[] = $row;
				
			}
		}
		return($this->results);
		//return array_reverse($this->results);
	}


	public function new_statuspost($from_username = '', $to_username = FALSE, $status_text, $status_visibility = '')
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
			$this->from_user_id = $this->common->get_user_id($from_username);
		}
		if(!empty($to_username)) {
			$this->to_user_id = $this->common->get_user_id($to_username);
		} 
		
		$this->status_text = $status_text;
		
	
		$data=array(
		    'status_uid' => $this->from_user_id,
		    'status_text' => mysql_real_escape_string(strip_tags($this->status_text)),
		    'status_visibility' => 1,
			//'status_date' => time(),
			'status_ip_address' => ip2long($this->input->ip_address()) //using the new field
		);
		if(!empty($this->to_user_id)){
			$data['status_to_uid'] = $this->to_user_id;
		}
		$this->db->insert('users_status',$data);
		//get the last inserts id and insert it in gen_prefs
		$this->status_id = $this->db->insert_id();
		
			
		$out = array(
			'status_id' => $this->status_id,
			'status_date' => time(),
			'status_text' => $this->status_text,
			'from_username' => $from_username,
			'from_username_img_url' => $this->get_profile_photo($this->from_user_id),
			'to_username' => $to_username
			
		);
		
		
		return $out;
	}
	
}
?>