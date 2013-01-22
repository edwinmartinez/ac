<?php

/**
 * Description of user
 *
 * @author Edwin Martinez
 */
class User {
    protected $db;
    protected $uid;

    function __construct($uid = NULL) {
        if(!empty($uid)){
            $this->uid = $uid;
        } 
        elseif (isset($HTTP_SESSION_VARS['user_id'])) {
            $this->uid = $HTTP_SESSION_VARS['user_id'];
        }    

        require_once("db_settings.php");
        $this->db = new MySQL();
        //echo DBMS." $dbname, $dbhost, $dbuser, $dbpasswd";
        if (! $this->db->Open(DBNAME, DBHOST, DBUSER, DBPASS)) {
            $this->db->Kill();
        }
        $this->sql = "SET SESSION SQL_BIG_SELECTS=1"; //sets the session for big selects/joins
        if (!$this->db->Query($this->sql)) {
            $this->db->Kill();
        }
    }
    
    function getUserCountry($country_id){
		$this->sql = "SELECT ".COUNTRY_NAME_FIELD." from ".COUNTRY_TABLE." WHERE ".COUNTRY_ID_FIELD."=".$country_id;
		if ( !($this->result = $this->db->Query($this->sql)) ) {
	            printf('Could not select country at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
	            $this->db->Kill();
		}else{
	            $this->country = mysql_fetch_assoc($this->result);
			return $this->country[COUNTRY_NAME_FIELD];
		}
	    }
	   
	    function getUsername(){
	        $this->sql = "SELECT user_username from ".SITE_USERS_TABLE." WHERE user_id = ".$this->uid ." limit 1";	
		if ( !($this->result = $this->db->Query($this->sql)) ) {
						printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql1);
						exit;
		}
		$this->row = mysql_fetch_assoc($this->result);
		return $this->row['user_username'];
    }
    
    function getUserState(){
        $this->sql = "SELECT ".STATE_NAME_FIELD." from users u LEFT JOIN ".STATE_TABLE." r on r.".STATE_ID_FIELD." = u.user_state_id"
                ." WHERE u.user_id = ".$this->uid;
		if ( !($this->result = $this->db->Query($this->sql)) ) {
						printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql1);
						exit;
		}
        $this->state = mysql_fetch_assoc($this->result);
		return $state[STATE_NAME_FIELD];
    }
}

?>
