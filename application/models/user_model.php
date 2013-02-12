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
	 
	 public function getCountries()
	 {
		$query = $this->db->query("SELECT * from countries order by countries_name_es asc");
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
	function get_all($limit=20,$offset=0,$shortinfo=TRUE)
	{
		$limit = ($limit > 60) ? 60 : $limit ; //let's put a hard limit to the amount of users returned
		$all_users = array();
		if ($shortinfo)
			$this->db->select('user_id, user_username, user_email, user_gender, user_birthdate, user_country_id, countries_name_es, countries_name, user_state_id, user_state_desc, zone_name');
		$this->db->from('users');	
		$this->db->join('countries', 'users.user_country_id = countries.countries_id');
		$this->db->join('geo_regions', 'users.user_state_id = geo_regions.zone_id', 'left');
		//get state
		
		$this->db->where('users.status',1);
		$this->db->order_by("user_created", "desc"); 
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$row->state_name = ($row->user_state_id > 0) ? $row->zone_name : $row->user_state_desc ;
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
}
?>