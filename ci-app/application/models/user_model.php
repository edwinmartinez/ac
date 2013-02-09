<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model {
	 public function __construct()
	 {
	  parent::__construct();
	 }
	 
	 function login($email,$password)
	 {
	  $this->db->where("user_email",$email);
	  $this->db->where("user_password",$password);
	
	  $query=$this->db->get("users");
	  if($query->num_rows()>0)
	  {
	   foreach($query->result() as $rows)
	   {
	    //add all data to session
	    $newdata = array(
	      'user_id'  => $rows->id,
	      'user_name'  => $rows->username,
	      'user_email'    => $rows->email,
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
	  $data=array(
	    'user_username'=>$this->input->post('username'),
	    'user_email'=>$this->input->post('email_address'),
	    'user_password'=>md5($this->input->post('password')),
		 'user_gender' => $this->input->post('sex')
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
		/*
			while ($row = mysql_fetch_assoc($result)) {
			//echo $country .":". $row['countries_id']."<br>";
				if($country == $row['countries_id']) {$selected = 'selected="selected"'; }
				else { $selected = ""; }	
				if(in_array($row['countries_iso_code_2'],$top_countries)){
					$countries_menu_top .= sprintf("<option value=\"%s\">%s</option>\n",
						$row['countries_id'],$row['countries_name_es']);
				}
				//check for banned countries
				if(!in_array($row['countries_iso_code_2'],$banned_countries)){
					$countries_menu_bot .= sprintf("<option %s value=\"%s\">%s</option> \n", $selected,$row['countries_id'], $row['countries_name_es']);  
				}
				
				$menu_div = "<option value=\"\">---------------</option>\n";
				$countries_menu = $countries_menu_top . $menu_div . $countries_menu_bot;
			} 
		 */
	 }
}
?>