<?php
/*

Important: You need to create database connection and select database before creating object!

*/

class usersOnline {

	var $timeout = 600; //10 minutes
	var $count = 0;
	var $error;
	var $i = 0;
	
	public function __construct($uid) {
	    $this->uid = $uid;
		$this->timestamp = time();
		$this->ip = $this->ipCheck();
		$this->delete_inactive_users();
		$this->update_user();
	}
	
	
	public function new_user() {
	    //$this->uid = $uid;
		$nu_sql = "INSERT INTO users_online(uid, timestamp, ipn) VALUES ('$this->uid', '$this->timestamp', INET_ATON('$this->ip'))";
		//echo $nu_sql;
		$insert = mysql_query ($nu_sql);
		if (!$insert) {
			$this->error[$this->i] = "Unable to record new visitor\r\n";			
			$this->i ++;
		}
	}
	
	public function update_user(){
	    $this->now = time();
		$this->ssql = "SELECT count(*) as count from users_online where uid = '$this->uid'";
		$this->sres = mysql_query($this->ssql) or die(mysql_error());
		$this->row = mysql_fetch_array($this->sres);
		$this->ucount = $this->row['count'];
		//echo $this->ssql."\n<br>";
		//echo 'count:'.$this->ucount."\n<br>";
		if($this->ucount > 0){
			//echo 'updating...'."\n<br>";
		    $this->update_sql = "UPDATE users_online set timestamp = '$this->now' where uid = $this->uid";
		}else{
		    $this->new_user($this->uid);
		}
		/*$update = mysql_query($this->update_sql);
		echo "\n<br>".$this->update_sql . "<br>";
		$this->ar  = mysql_affected_rows();
		echo 'affected_rows:'.$this->ar."\n<br>";
		if(!$this->ar){
            $this->new_user($this->uid);
        }*/		
	}
	
	public function check_in(){
	    $this->update_user();
		$this->friends = $this->check_online_friends();
		return $this->friends;
	}
	
	public function check_online_friends(){
	    // let's check on the online status of the friends
		$this->fonline = array();
		$friends_online_sql = "SELECT uo.uid as uid, u.user_username as username
								FROM buddies b 
								INNER JOIN users_online uo ON  b.buddy_uid = uo.uid
								INNER JOIN users u ON uo.uid = u.user_id
								WHERE b.user_uid = '$this->uid'
								AND b.confirmed =1";
							
								
		$this->fres = mysql_query($friends_online_sql);
		if(mysql_num_rows($this->fres) > 0){
			while ($row = mysql_fetch_array($this->fres)) {
				$this->fonline[] = array('uid'=>$row['uid'],'username'=>$row['username']);
			}
			return $this->fonline;
		}else{
			return 0;
		}
		
	}
	
	public function delete_inactive_users() {
		$delete = mysql_query ("DELETE FROM users_online WHERE timestamp < ($this->timestamp - $this->timeout)");
		if (!$delete) {
			$this->error[$this->i] = "Unable to delete visitors";
			$this->i ++;
		}
	}
	
	public function count_users() {
		if (count($this->error) == 0) {
			$count = mysql_num_rows ( mysql_query("SELECT DISTINCT uid FROM users_online"));
			return $count;
		}
	}
	
	
	public function ipCheck() {
	/*
	This function will try to find out if user is coming behind proxy server. Why is this important?
	If you have high traffic web site, it might happen that you receive lot of traffic
	from the same proxy server (like AOL). In that case, the script would count them all as 1 user.
	This function tryes to get real IP address.
	Note that getenv() function doesn't work when PHP is running as ISAPI module
	*/
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

}

?>