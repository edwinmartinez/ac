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
	* Gets an array of information about users
	*/
	function get_all($limit=20,$offset=0,$onlywithphotos=0,$shortinfo=TRUE)
	{
		$limit = ($limit > 60) ? 60 : $limit ; //let's put a hard limit to the amount of users returned
		$all_users = array();
		$select_rows = array(
			'user_id',
			'user_username',
			'user_email',
			'user_gender',
			'user_birthdate',
			'user_country_id',
			'countries_name_es',
			'countries_name',
			'user_state_id',
			'user_state_desc',
			'zone_name'
		 );
		 
		 if($onlywithphotos) {
		 	$select_rows[] = 'photo_filename';
		 } else {
			 $select_rows[] = '(SELECT photo_filename FROM users_gallery WHERE users_gallery.photo_uid = users.user_id AND users_gallery.use_in_profile = 1) AS photo_filename';
		 }
		 $select = join(', ', $select_rows);
		 
		if ($shortinfo)
			$this->db->select($select);
		$this->db->from('users');	
		$this->db->join('countries', 'users.user_country_id = countries.countries_id');
		$this->db->join('geo_regions', 'users.user_state_id = geo_regions.zone_id', 'left');
		if($onlywithphotos) {
			$this->db->join('users_gallery', 'users.user_id = users_gallery.photo_uid', 'left');
			$this->db->where('users_gallery.use_in_profile',1);
		}
		$this->db->where('users.status',1);
		$this->db->order_by("user_created", "desc"); 
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$row->state_name = ($row->user_state_id > 0) ? $row->zone_name : $row->user_state_desc ;
			$row->profile_img = ($row->photo_filename != null)? $this->getProfilePhotoUrl($row->photo_filename) : '';
			$row->profile_img = $this->getProfilePhotoUrl($row->photo_filename,'square',$row->user_gender);
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
	Preform a search on customers
	*/
	function search($search)
	{
		$this->db->from('users');
		$this->db->join('countries', 'users.user_country_id = countries.countries_id');
		//$this->db->join('people','customers.person_id=people.person_id');		
		$this->db->where("(user_first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		user_first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
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
		$this->db->join('users_gallery', 'users.user_id = users_gallery.photo_uid', 'left');
		$this->db->where('users_gallery.use_in_profile',1);
		$this->db->where('users.status',1);
		$this->db->where('users_gallery.photo_uid',$user_id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows()>0) 
		{
			foreach ($query->result() as $row)
			{
			    $profile_pic =  $this->getProfilePhotoUrl($row->photo_filename,'square',$row->user_gender);
			}
			
		} 		
		return $profile_pic;
	}

	// format can be suare or large
	public function getProfilePhotoUrl($filename_in_db='',$format='square', $gender='')
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
}
?>