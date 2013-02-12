<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile extends CI_Controller{
	
	public function __construct()
	 {
	  parent::__construct();
	  $this->load->model('profile_model');
	 }
	 
	 public function index($profileid='')
	 {
	 	$this->userprofile($profileid);
	 }
	 
	 public function myprofile()
	 {
		echo 'show my profile'; 
	 }
	 
	 public function userprofile($profileid='')
	 {
		echo "userprofile of ";	
		echo $profileid; 
	 }
	 
	 
}
?>