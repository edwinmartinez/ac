<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Friend_model extends CI_Model {
	 public function __construct()
	 {
	  parent::__construct();
	 }
//------------------------------ buddy -----------------------------------
	
	
	public function add_friend_request(){

		$user_id = $_REQUEST['user_id']; //user requesting the add
		$buddy_id = $_REQUEST['buddy_id'];
		
		if($user_id == "" || $buddy_id =="") {
			 echo LA_BAD_BUDDY_REQUEST;
			return false;
		}
		//let's check if the user has allready requested the addition
		$sql = "SELECT * from ".BUDDIES_TABLE." WHERE user_uid = ".$user_id." AND buddy_uid = ".$buddy_id;
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0){
			//echo LA_ERROR_WHILE_PROCESSING;
			echo LA_BUDDY_RELATION_EXISTS;
			return false;
		}else{
			$secretstr = "secret";
			$approvalcode = md5($secretstr.$user_id.$buddy_id);
			$sql = "INSERT into ".BUDDIES_TABLE." (user_uid, buddy_uid, approvalcode, buddy_request_date) ".
					"VALUES(".$user_id.", ".$buddy_id.", ".GetSQLValueString($approvalcode, "text").", NOW())";
			if ( !($result = mysql_query($sql))){
				printf('Could not update buddies at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			}
			$req_id = mysql_insert_id();
			
			//lets recrprocrate the buddy request if so is set
			if(RECIPROCRATE_BUDDY_ON_APPROVAL){
				$sql = "INSERT into ".BUDDIES_TABLE." (user_uid, buddy_uid, approvalcode, buddy_request_date) ".
						"VALUES(".$buddy_id.", ".$user_id.", ".GetSQLValueString($approvalcode, "text").", NOW())";
				if ( !($result = mysql_query($sql))){
					printf('Could not update buddies at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
			}
			
			//let's get the email for the potential buddy
			$sql = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id =".$buddy_id." limit 1";
			if ( !($res = mysql_query($sql))){
				printf('Could not select user at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				exit;
			}
			$buddyrow = mysql_fetch_array($res);
			
			$buddy_email = $buddyrow['user_email'];
			$buddy_username = $buddyrow['user_username'];
			$buddy_first_name = $buddyrow['user_first_name'];
			$buddy_last_name = $buddyrow['user_last_name'];
			
			//let's get the username of the requester
			$sql = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id =".$user_id." limit 1";
			if ( !($res2 = mysql_query($sql))){
				printf('Could not select user at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			}
			$userrow = mysql_fetch_array($res2);
			
			$username = $userrow['user_username'];
			
			
			//mail ($strMailTo, $strSubject, $strBody, $strXHeaders); 
			//BUDDY_APPROVAL_URL
			$from = "From: ".SITE_NAME." <".SITE_CONTACT_EMAIL.">\nX-Mailer: PHP/" . phpversion();
			$msg = LA_BUDDY_REQUEST_MSG;
			$msg = str_replace('%1%', $buddy_username, $msg);
			$msg = str_replace('%2%', $username , $msg);
			$msg = str_replace('%profileurl%' , PROFILE_DIR_URL.'/'.$username , $msg);
			$msg = str_replace('%approvelink%' , BUDDY_APPROVAL_URL.'?a=a&r='.$approvalcode , $msg);
			$msg = str_replace('%denylink%' , BUDDY_APPROVAL_URL.'?a=d&r='.$approvalcode, $msg);
			mail($buddy_email, LA_BUDDY_REQUEST_SUBJECT, $msg, $from);
				
			echo LA_SUCCESSFUL_BUDDY_REQUEST;	
		}
	
	}
	
	
	public function delete_friend(){
		
		if(isset($_SESSION['user_id'])){
		    $user_id = $_SESSION['user_id'];
		    $buddy_id = $_POST['buddy_id'];
			if($buddy_id > 0){
			// we will remove the link from the user to the buddy as well as from the buddy to the user
				$sql = "DELETE from ".BUDDIES_TABLE." WHERE (user_uid = ".$user_id." AND buddy_uid = ".$buddy_id.") OR (user_uid = ".$buddy_id." AND buddy_uid = ".$user_id.") LIMIT 2";
				//echo $sql;
				
				if($result = mysql_query($sql)){
				   echo LA_BUDDY_DELETED;
				}
			}
		}else{
		    // the behavior will be that the miniprofile dissapears but your friend has 
			// not dissapeared fro the list. The next time you login you will see your
			// buddy in your buddy list
		    echo LA_PLEASE_LOGIN_TO_PERFORM_THIS_ACTION;
		}
	}

		
	public function approve_friend(){
		global $dbhost,$dbuser,$dbpasswd,$dbname;
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
				die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		$buddy_id = $_REQUEST['buddy_id'];
		$approval_code = $_REQUEST['approval_code'];
		
		$sql = "SELECT * from ".BUDDIES_TABLE." WHERE user_uid = ".$buddy_id." AND approvalcode = '".$approval_code."' AND confirmed=1";
		$result = mysql_query($sql);
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0){
			echo LA_BUDDY_APPROVED_ALLREADY;
			return false;
		}else{
			$sql = "UPDATE ".BUDDIES_TABLE." set confirmed=1 WHERE user_uid = ".$buddy_id." AND approvalcode = '".$approval_code."'";
			if ( !($updateres = mysql_query($sql))){
			
				printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				exit;
			}
			echo LA_BUDDY_APPROVED_MESSAGE;
		}
	
	}
	
	
	public function deny_friend(){
		global $dbhost,$dbuser,$dbpasswd,$dbname;
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
				die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		global $buddy_requester_username,$approved;
	   $approvalcode = $_REQUEST['r'];
	   	$sql1 = "SELECT * from ". BUDDIES_TABLE. " WHERE  approvalcode = ".GetSQLValueString($approvalcode, "text") . ' LIMIT 1';
		$result = mysql_query($sql1) or die(mysql_error());
		$result_rows = mysql_num_rows($result);
		if($result_rows > 0){
		    $row = mysql_fetch_assoc($result);
				$sql2 = "DELETE from ". BUDDIES_TABLE . " WHERE  approvalcode = ".GetSQLValueString($approvalcode, "text");
	      $result2 = mysql_query($sql2) or die(mysql_error());	
		  $buddy_requester_uid = $row['user_uid'];
		  $buddy_requester_username = get_username($buddy_requester_uid);
		  $approved = 0;
		  echo 1;
		}else{
			
			global $error;
			$error = LA_BUDDY_REQUEST_DOES_NOT_EXIST;
		}
	}
	
	
		
		
		
	function get_buddy_count($of_user_id,$confirmed=1){
	
			$sqlcount = "SELECT count(*) as count from ".BUDDIES_TABLE." where user_uid=".$of_user_id." and confirmed=1";
			$result = mysql_query($sqlcount) or die(mysql_error());
			$row = mysql_fetch_row($result); 
			return $row[0];
	}
	
	// in progress -------
	function get_buddies($of_user_id,$buddie_types='approved',$limit='',$get_pic=1){ //buddy_types 'all' or 'approved' or 'waiting'
		global $dbhost,$dbuser,$dbpasswd,$dbname;
		$buddies = array();
		$sql = "SELECT * from ".BUDDIES_TABLE." where user_uid=".$of_user_id;
		if($buddie_types == 'approved'){
			$sql .= " and confirmed=1";
		}else if ($buddie_types == 'waiting'){
			$sql .= " and confirmed=0";
		}else{
			//else is all so we don't need to add to the sql
		}
		$sql .= " order by buddy_request_date";
		if (!empty($limit)){
			$sql .= " limit ".$limit;
		}
		$result = mysql_query($sql) or die(mysql_error());
		if ( !($result = mysql_query($sql)) ) { printf('Could not select friends at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
		$num_rows = mysql_num_rows($result);
	
		if($num_rows > 0){
			while($buddyrow=mysql_fetch_assoc($result)){
					$sql2 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$buddyrow['user_uid']." limit 1";	
					//echo $sql2."<br>";
					$result2 = mysql_query($sql2) or die(mysql_error());
					$profile = mysql_fetch_assoc($result2);
					$buddy_username = preg_replace('/&#241;/','n',get_username($buddyrow['buddy_uid']));
					array_push($buddies,array(
											'username'=> $buddy_username,
											'buddy_uid'=> $buddyrow['buddy_uid'],
											'confirmed'=>$buddyrow['confirmed'],
											'profile_link'=>PROFILE_DIR_URL.'/'.$buddy_username,
											'image' =>get_profile_photo($buddyrow['buddy_uid'])
											)
							 );
				
			}
			return $buddies;
		}else{
			return 0;
		}
	}
	
	function get_waiting_buddies(){
		$sql = "SELECT * from ".BUDDIES_TABLE." where buddy_uid=".$_SESSION['user_id']." and confirmed=0 order by buddy_request_date";
		$result = mysql_query($sql) or die(mysql_error());
		if ( !($result = mysql_query($sql)) ) { printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
		$num_rows = mysql_num_rows($result);
	
		if($num_rows > 0){
			$waiting_buddies = '<div class="waitingBuddyHead">'.LA_PEOPLE_THAT_WANT_TO_ADD_YOU_AS_BUDDY.'</div>';
			while($buddyrow=mysql_fetch_assoc($result)){
					$sql2 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$buddyrow['user_uid']." limit 1";	
					//echo $sql2."<br>";
					$result2 = mysql_query($sql2) or die(mysql_error());
					$profile = mysql_fetch_assoc($result2);
					
					$profile['user_username'] = preg_replace('/�/','n',$profile['user_username']);
					$waiting_buddies .= '<div class="waitingBuddy" id="'.$buddyrow['user_uid'].'_waiting">';
					$waiting_buddies .= '<img src="/images/icon_smile_gray.gif" border="0" /> <a href="'.PROFILE_DIR_URL.'/'.$profile['user_username'].'">'.$profile['user_username']."</a>";
					$waiting_buddies .= "<br>";
					$waiting_buddies .= '<a class="approveBuddyLink" href="#" onclick="approveBuddy('.$buddyrow['user_uid'].',\''.$buddyrow['approvalcode'].'\'); return false;">'.LA_APPROVE.'</a> ';
					$waiting_buddies .= '| <a class="approveBuddyLink" href="#" onclick="denyBuddy('.$buddyrow['user_uid'].',\''.$buddyrow['approvalcode'].'\'); return false;">'.LA_DENY.'</a>';
					$waiting_buddies .= ' </div>'."\n";
			}
			return $waiting_buddies;
		}else{
			return 0;
		}
	}
	
		
}
?>	