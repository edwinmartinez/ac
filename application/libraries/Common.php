<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * AC_common class
 *
 * Help convert between various formats such as XML, JSON, CSV, etc.
 *
 * @author  	Edwin Martinez

 */
class Common {

	public function get_user_id($username)
	{
		$CI =& get_instance();
		$CI->db->select('user_id');
		$CI->db->where('user_username', $username);
		$CI->db->from('users');
		$query = $CI->db->get();
		if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
		   return $row->user_id;
		} else {
			return FALSE;
		}
	}
	
	public function html2text($document){ 
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
		               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
		               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
		               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
		); 
		$text = preg_replace($search, '', $document); 
		return $text; 
	}
}