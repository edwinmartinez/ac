<?php
include('../includes/config.php'); 
	/**
	 * This file saves the edited text in the database/file
	 */
	if ($_REQUEST['id'] == 'user_email' ) { update_email();	}
	elseif ($_REQUEST['action'] == 'update_profile_item') { update_profile_item(); }

function update_email(){
	global $dbhost, $dbuser, $dbpasswd, $dbname,$lang;
	
	$email = $_REQUEST['content'];
	if(validate_email($email)){
		$sql = "UPDATE " . SITE_USERS_TABLE ." SET ";
		$sql .= "user_email = " . GetSQLValueString($email,"text");
		$sql .= " WHERE user_id=" . $_REQUEST['uid'] . " LIMIT 1";
		
		$sql_phpbb = "UPDATE " . PHPBB_USERS_TABLE ." SET ";
		$sql_phpbb .= "user_email = ". GetSQLValueString($email,"text");
		$sql_phpbb .= " WHERE user_id=" . $_REQUEST['uid'] . " LIMIT 1";
		
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
	
		if ( !mysql_query($sql) ) {
			//message_die(CRITICAL_ERROR, 'Error updating users table', '', __LINE__, __FILE__, $sql);
			echo "Critical Error updating users table on line ". __LINE__ ."<br> in file: ". __FILE__ . "<br>statement: ". $sql ;
			die(mysql_error());
		} 
		
		if ( !mysql_query($sql_phpbb) ) {
			//message_die(CRITICAL_ERROR, 'Error updating users table', '', __LINE__, __FILE__, $sql);
			echo "Critical Error updating users table on line ". __LINE__ ."<br> in file: ". __FILE__ . "<br>statement: ". $sql_phpbb ;
			die(mysql_error());
		}		
		
		echo $email;
	//		if(fwrite($fp, $content)) {
	//			echo $content;
	//		}
	//		else {
	//			echo "Failed to update the text";
	//		}
	} else {
		echo $lang['invalid_email'];
	}
}

function update_profile_item() {
	global $dbhost, $dbuser, $dbpasswd, $dbname,$lang;
	//echo $_REQUEST['value'];
	
	
	$sql = "UPDATE " . SITE_USERS_TABLE ." SET ";
		$sql .= $_REQUEST['item']." = " . GetSQLValueString($_REQUEST['value'],"text");
		$sql .= " WHERE user_id=" . $_REQUEST['uid'] . " LIMIT 1";
		
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
		if ( !mysql_query($sql) ) {
			//message_die(CRITICAL_ERROR, 'Error updating users table', '', __LINE__, __FILE__, $sql);
			echo "Critical Error updating users table on line ". __LINE__ ."<br> in file: ". __FILE__ . "<br>statement: ". $sql ;
			die(mysql_error());
		}
		
	if($_REQUEST['item'] == 'user_country_id'){ echo db_get_user_country($_REQUEST['value']); }	
}

function validate_email($email) {

   // Create the syntactical validation regular expression
   $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

   // Presume that the email is invalid
   $valid = 0;

   // Validate the syntax
   if (eregi($regexp, $email))
   {
      list($username,$domaintld) = split("@",$email);
      // Validate the domain
      if (getmxrr($domaintld,$mxrecords))
         $valid = 1;
   } else {
      $valid = 0;
   }

   return $valid;

}


?>