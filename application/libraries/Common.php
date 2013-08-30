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
	
	public function gen_rand_string($hash){
		$chars = array( 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	
		$max_chars = count($chars) - 1;
		srand( (double) microtime()*1000000);
		
		$rand_str = '';
		for($i = 0; $i < 8; $i++)
		{
			$rand_str = ( $i == 0 ) ? $chars[rand(0, $max_chars)] : $rand_str . $chars[rand(0, $max_chars)];
		}
	
		return ( $hash ) ? md5($rand_str) : $rand_str;
	}
}