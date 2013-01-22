<?php

require("includes/config.php");
session_start();

switch($_REQUEST['action']) {
	case 'checkuser':
		check_username();
		break;
	case 'regionlist':
		get_region_list();
		break;
	case 'regionmenu':
		build_region_menu();
		break;
		//for edit in place
	case 'changeinfo':
		change_info();
		break;				
		//for adding a buddy from profile
	case 'addbuddyrequest':
		add_buddy_request();
		break;
	case 'approve_buddy':
		approve_buddy();
		break;	
	case 'deny_buddy':
		deny_buddy();
		break;	
	case 'delete_buddy':
		delete_buddy();
		break;			
	case 'addfav':
		add_to_fav();
		break;			
	case 'delete_fav':
		delete_fav();
		break;
	case 'profile_comment':
		add_profile_comments();
		break;	
	case 'edit_profile_comment':
		edit_profile_comments();
		break;	
	case 'delete_profile_comment':
		delete_profile_comment();
		break;
	case 'photo_comment':
		add_photo_comments();
		break;
	case 'update_pic_title':
		update_pic_title();
		break;
	case 'update_pic_caption':
		update_pic_caption();
		break;
	case 'get_pic_info':
		get_pic_info();
		break;
	case 'delete_pic':
		delete_pic();
		break;
	case 'add_pic_tag':
		add_pic_tag();
		break;
	case 'icon_pic':
		icon_pic();
		break;
	case 'delete_pic_tag':
		delete_pic_tag();
		break;
	default:
	    echo "Nothing to do"."\n";
		//foreach($_REQUEST as $key=>$val) echo 'key:'.$key.' val:'.$val.'<br>'."\n";
		break;
}	

function get_region_list(){
	global $dbhost,$dbuser,$dbpasswd,$dbname,$phpbb_root_dir;
	if (isset($_REQUEST['countryid']) && $_REQUEST['countryid'] != '') {
		$sql = "SELECT zone_id,zone_name from geo_regions "
		       ."WHERE zone_country_id =" . $_REQUEST['countryid'] 
			   ." Order by zone_name asc";
	}
	//echo "host:" . $dbhost . " user:" . $dbuser . " dbpass:" . $dbpasswd . $phpbb_root_dir ."\n\n";
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
   		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	if ( !($result = mysql_query($sql)) )
	{
		printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}
/* 
 	while ($row = mysql_fetch_assoc($result)) {
   	  $countries_menu .= sprintf("<option value\"%s\">%s</option> \n", $row['countries_id'], $row['countries_name_es']);
	}  */
	
	$totalRows = mysql_num_rows($result);
	echo $totalRows . "\n\n";
	//echo "--" . $_REQUEST['countryid'] . "\n";
    while($row = mysql_fetch_assoc($result)){
	  echo $row['zone_id'] . "|" .  htmlentities($row['zone_name']). "\n";
	  //echo $row['zone_id'] . "|" .  $row['zone_name']. "\n";
	}
	//mysql_free_result($result);
}

function build_region_menu(){
	global $dbhost,$dbuser,$dbpasswd,$dbname,$phpbb_root_dir;
	if (isset($_REQUEST['countryid']) && $_REQUEST['countryid'] != '') {
		$sql = "SELECT zone_id,zone_name from geo_regions "
		       ."WHERE zone_country_id =" . $_REQUEST['countryid'] 
			   ." Order by zone_name asc";
			   
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		if ( !($result = mysql_query($sql)) )
		{
			printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}
		$totalRows = mysql_num_rows($result);
	}

/* 
 	while ($row = mysql_fetch_assoc($result)) {
   	  $countries_menu .= sprintf("<option value\"%s\">%s</option> \n", $row['countries_id'], $row['countries_name_es']);
	}  */
	
	
?>
		<html>
		<head>
		<!--<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type">-->
		<meta content="no-cache" http-equiv="Pragma">
		<meta content="no-cache" http-equiv="Cache-Control">
		<link href="forms01.css" rel="stylesheet" type="text/css">
		<title>Citas en Linea - Citas Con Hispanos</title>
		
		</head> 
		<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" bgcolor="WHITE">
		<script>


	function updateState(s) {
		parent.updateStateId(s.options[s.selectedIndex].value)
	}
	
	function updateStateDesc(t) {
		parent.updateStateDesc(t.value)
	}
	
	</script>

<form name="formProvincia">
<?php

	echo $header;
	
	//echo $totalRows . "\n\n";
	if($totalRows) {
		echo "<select name=\"region_id\" id=\"region_id\" onChange=\"updateState(this)\" style=\"width:225px;\">";
		echo "<option value=\"\">Estado/Provincia</option>\n";
		while($row = mysql_fetch_assoc($result)){
		  if($_REQUEST['stateid'] == $row['zone_id']) {$selected = 'selected="selected"'; }
		else { $selected = ""; }
		  echo "<option " . $selected . " value=\"". $row['zone_id'] . "\">" .  htmlentities($row['zone_name']). "</option>\n";
	
		}
		echo "</select>";
	} else {
		echo "<input type=\"text\" name=\"region_desc\" id=\"region_desc\" class=\"inputText\" size=\"18\" maxlength=\"100\" onKeyUp=\"updateStateDesc(this)\" value=\"\" />\n";
	}
	echo "</form></body></html>";
	//mysql_free_result($result);
}

function check_username(){
	global $dbhost,$dbuser,$dbpasswd,$dbname,$phpbb_root_dir;
	if (isset($_REQUEST['username']) && $_REQUEST['username'] != '') {
	
		$username = trim($_REQUEST['username']);
		// Remove doubled up spaces
		$username = preg_replace('#\s+#', ' ', $username);
		$username = preg_replace('#_+#', '_', $username);

		if (strstr($username, '"') || strstr($username, '&quot;') || strstr($username, '|') 
		|| strstr($username, ',') ||  strstr($username, '^') || strlen($username) < 4 
		|| strstr($username, '<') || strstr($username, '>') || strstr($username, '%') || strstr($username, '\'') || strstr($username, chr(160)) ){
			$error = 1;
		} else {
		
			$sql = "SELECT username from phpbb_users "
				   ."WHERE LOWER(username) = '" . strtolower($username) . "'"
				   ." limit 1";
		} // no errors
	}
	else { $error = 2; }
    
	if(!$error) {
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		if ( !($result = mysql_query($sql)) )
		{
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		} else {
			$totalRows = mysql_num_rows($result);
			echo $totalRows . "|" .$username;
			//echo "\n\n";
			$row = mysql_fetch_assoc($result);
			//echo $row['username'] . "|" .  htmlentities($row['username']). "\n";
		
			mysql_free_result($result);
		}
	}
	else {
		echo "2" . "|" . $username;
	}
}

function change_info(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	$info_to_change = $_POST['id'];
	$email = $_REQUEST['content'];
	if ($info_to_change == 'email' && check_email($email) && isset($_SESSION['user_id']) ) {
		$sql = "UPDATE phpbb_users set user_email=".GetSQLValueString($email,"text")." WHERE user_id =".$_SESSION['user_id'];
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		if ( !($result = mysql_query($sql))){
			printf('Could not update email at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		} else {
			$totalRows = mysql_num_rows($result);
			echo  "1|" .$email;
			mysql_free_result($result);
		}
				   
	}
}

function check_email($email) { 
	if( (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) || 
		(preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) { 
		return true;
	}
	return false;
}
	
//------------------------------ buddy -----------------------------------


function add_buddy_request(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
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


function delete_buddy(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
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


function add_to_fav(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	$user_id = $_REQUEST['user_id']; //user requesting the add
	$fav_id = $_REQUEST['fav_id'];
	
	if($user_id == "" || $fav_id =="") {
		 echo LA_ERROR_WHILE_PROCESSING;
		return false;
	}
	//let's check if the user has allready requested the addition
	$sql = "SELECT * from ".FAVORITE_PEOPLE_TABLE." WHERE user_uid = ".$user_id." AND fav_uid = ".$fav_id;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		echo LA_DUPLICATE_FAVORITE_REQUEST;
		return false;
	}else{
		$sql = "INSERT into ".FAVORITE_PEOPLE_TABLE." (user_uid, fav_uid, date) ".
				"VALUES(".$user_id.", ".$fav_id.", NOW())";
		if ( !($result = mysql_query($sql))){
			printf('Could not insert into favorites at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}
		$req_id = mysql_insert_id();
		
			
		echo LA_FAVORITE_ADDED_SUCCESSFULLY;	
	}

}

function delete_fav(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	$ufp_id = $_REQUEST['ufp_id']; //user requesting the deletion
	$fav_uid = $_REQUEST['fav_uid'];
	
	if($ufp_id == ""|| $fav_uid =="" ) {
		 echo LA_ERROR_WHILE_PROCESSING;
		return false;
	}
	//let's check if the user has allready requested the addition
	$sql = "DELETE from ".FAVORITE_PEOPLE_TABLE." WHERE ufp_id = ".$ufp_id." AND fav_uid = ".$fav_uid;
	if($result = mysql_query($sql)){
		echo LA_FAVORITE_DELETED_SUCCESSFULLY;
	}

}

function add_profile_comments(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	global $allowed_profile_comment_tags;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	include_once 'includes/class.inputfilter.php'; 
	
	
	//InputFilter($tagsArray, $attrArray, $tagsMethod , $attrMethod);
//Instantiate the class with your settings.
//1st (tags array):    Optional (since 1.2.0)
//2nd (attr array):    Optional
//3rd (tags method):   0 = remove ALL BUT these tags (default)
//                     1 = remove ONLY these tags
//4th (attr method):   0 = remove ALL BUT these attributes (default)
//                     1 = remove ONLY these attributes
//5th (xss autostrip): 1 = remove all identified problem tags (default)
//                     0 = turn this feature off
//
//Eg.. $myFilter = new InputFilter($tags, $attributes, 0, 0);
	
	$user_id = $_REQUEST['user_id']; //user requesting the add
	$commenter_uid = $_REQUEST['commenter_uid'];
	$comment = $_REQUEST['comment'];
	$userinfo = get_profile_info($user_id);	
	$commenterinfo = get_profile_info($commenter_uid);
	$myFilter = new InputFilter($forbiden_profile_comment_tags, $forbiden_attributes_in_profile_comments,1, 1,0); 
	$comment = $myFilter->process($comment);
	
	
	if($user_id == "" || $commenter_uid =="") {
		 echo LA_ERROR_WHILE_PROCESSING ." user_id:".$user_id." \n commenter_uid:".$commenter_uid;
		return false;
	}
	//let's check if the user has allready requested the addition
/*	$sql = "SELECT * from ".PROFILE_COMMENTS_TABLE." WHERE user_uid = ".$user_id." AND commenter_uid = ".$fav_id;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		$row = mysql_fetch_assoc($result);
		
		list($year,$month,$day) = explode("-",$row['date']);
		list($day,$time) = explode(" ",$day);
		list($hour,$min,$sec) = explode(":",$time);
		
	$age = date("Y") - $year;
	if(date("md") < $month.$day) { $age--; }
		
		
		//if comment date by the same poster is not older than 5 min
		echo LA_DUPLICATE_PROFILE_COMMENT;
		return false;
	}else{*/
	
	
		if($userinfo['who_comments_on_profile'] > 0){ // 0 = no one 1 = invited only 2 = members 3 = everyone
		//comments are being allowed then we have to check if they are 
		//allowed for members or for friends or whatever for now all members
			
			if($comment != ""){
				$sql = "INSERT into ".PROFILE_COMMENTS_TABLE." (user_id, commenter_uid, comment, ";
				if($userinfo['comments_on_profile_need_approval'] == 0)
					$sql .= " approved,";
				$sql .= " date) ";
				$sql .= "VALUES(".$user_id.", ".$commenter_uid.", ".GetSQLValueString($comment,"text").", ";
				if($userinfo['comments_on_profile_need_approval'] == 0)
					$sql .= " 1,";
				$sql .=	" NOW())";
				if ( !($result = mysql_query($sql))){
					printf('Could not insert into comments at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
				}
				$comment_id = mysql_insert_id();
				
				

				//mail ($strMailTo, $strSubject, $strBody, $strXHeaders); 
				//BUDDY_APPROVAL_URL
				
				//check if we are going to notify the user
				if($commenter_uid != $user_id){
					if($userinfo['notify_on_profile_comments'] == 1){
						$from = "From: ".SITE_NAME." <".SITE_CONTACT_EMAIL.">\nX-Mailer: PHP/" . phpversion();
						$msg = LA_COMMENTS_NOTIFICATION_MESSAGE;
						$msg = str_replace('%commenter_username%', $commenterinfo['user_username'], $msg);
						$msg = str_replace('%profile_url%' , PROFILE_DIR_URL.'/'.$userinfo['user_username'] , $msg);
						$msg = str_replace('%comment%' , $comment , $msg);
						//future
						$msg = str_replace('%approvelink%' , PROFILE_COMMENT_APPROVAL_URL.'?a=a&r='.$approvalcode , $msg);
						$msg = str_replace('%denylink%' , PROFILE_COMMENT_APPROVAL_URL.'?a=d&r='.$approvalcode, $msg);
						
						mail($userinfo['user_email'], LA_COMMENTS_NOTIFICATION_SUBJECT, $msg, $from);
						
					}
				}
				
				$commenter_box = '<a href="'.PROFILE_DIR_URL.'/'.$commenterinfo['user_username'].'">'
								.'<img src="'.get_profile_photo($commenter_uid).'" border="0"></a><br />'
								.'<a href="'.PROFILE_DIR_URL.'/'.$commenterinfo['user_username'].'">'
								.$commenterinfo['user_username'].'</a>';
				
				header('Content-type: text/xml');
				echo "<xml>\n<status>1</status>\n";
				echo "<message>".LA_COMMENT_ADDED_SUCCESSFULLY."</message>\n";
				echo "<comment_id>".$comment_id."</comment_id>\n";
				echo "<comment><![CDATA[".$comment."]]></comment>\n";
				echo "<commenter><![CDATA[".$commenter_box."]]></commenter>\n";
				echo "<datetime><![CDATA[".$commenter_box."]]></datetime>\n";
				echo "</xml>";
			}else{
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message>".LA_COMMENT_COULD_NOT_BE_ADDED."</message>\n";
				echo "<comment></comment>\n";
				echo "<commenter></commenter>\n";
				echo "<datetime></datetime>\n";
				echo "</xml>";
			}
		}else{
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message>".LA_COMMENTS_ARE_NOT_ALLOWED_BY_USER." set;".$userinfo['who_comments_on_profile']."</message>\n";
				echo "<comment></comment>\n";
				echo "<commenter></commenter>\n";
				echo "<datetime></datetime>\n";
				echo "</xml>";
		}
	/*}*/

}


function edit_profile_comments(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	global $allowed_profile_comment_tags;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	include_once 'includes/class.inputfilter.php'; 
	
	
	//InputFilter($tagsArray, $attrArray, $tagsMethod , $attrMethod);
//Instantiate the class with your settings.
//1st (tags array):    Optional (since 1.2.0)
//2nd (attr array):    Optional
//3rd (tags method):   0 = remove ALL BUT these tags (default)
//                     1 = remove ONLY these tags
//4th (attr method):   0 = remove ALL BUT these attributes (default)
//                     1 = remove ONLY these attributes
//5th (xss autostrip): 1 = remove all identified problem tags (default)
//                     0 = turn this feature off
//
//Eg.. $myFilter = new InputFilter($tags, $attributes, 0, 0);
	
	$comment_id = $_REQUEST['comment_id'];
	$comment = $_REQUEST['comment'];
	
	$myFilter = new InputFilter($forbiden_profile_comment_tags, $forbiden_attributes_in_profile_comments,1, 1,0); 
	$comment = $myFilter->process($comment);
	

			if($comment != ""){
				$sql = "UPDATE ".PROFILE_COMMENTS_TABLE." set comment=".GetSQLValueString($comment,"text");
				$sql .= " where upc_id=".GetSQLValueString($comment_id,"int") ;
				
				if ( !($result = mysql_query($sql))){
					printf('Could not update comments at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
				}
							
				
/*				header('Content-type: text/xml');
				echo "<xml>\n<status>1</status>\n";
				echo "<message><![CDATA[".LA_COMMENT_UPDATED_SUCCESSFULLY."]]></message>\n";
				echo "<comment_id>".$comment_id."</comment_id>\n";
				echo "<comment><![CDATA[".$comment."]]></comment>\n";
				echo "</xml>";*/
				
				echo $comment;
			}else{
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message><![CDATA[".LA_COMMENT_COULD_NOT_BE_UPDATED."]]></message>\n";
				echo "</xml>";
			}

}


function delete_profile_comment(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$sql = "DELETE from ".PROFILE_COMMENTS_TABLE." where upc_id=".$_REQUEST['comment_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not delete comment at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	
	header('Content-type: text/xml');
	echo "<xml>\n<status>1</status>\n";
	echo "<message>".LA_COMMENT_DELETED_SUCCESSFULLY."</message>\n";
	echo "<comment_id>".$_REQUEST['comment_id']."</comment_id>\n";
	echo "</xml>";
}




function update_pic_title(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	require 'includes/class.inputfilter.php';
	$content = $_REQUEST['content'];
	
	
	//InputFilter($tagsArray, $attrArray, $tagsMethod , $attrMethod);
	//Instantiate the class with your settings.
	//1st (tags array):    Optional (since 1.2.0)
	//2nd (attr array):    Optional
	//3rd (tags method):   0 = remove ALL BUT these tags (default)
	//                     1 = remove ONLY these tags
	//4th (attr method):   0 = remove ALL BUT these attributes (default)
	//                     1 = remove ONLY these attributes
	//5th (xss autostrip): 1 = remove all identified problem tags (default)
	//                     0 = turn this feature off
	//
	//Eg.. $myFilter = new InputFilter($tags, $attributes, 0, 0);
	$tags = array();
	$attributes = array();
	$myFilter = new InputFilter($tags, $attributes,0,0); 

	$content = $myFilter->process($content);
	
	$sql = "update ".USERS_GALLERY_TABLE." set photo_title = '".$content."' where photo_id=".$_REQUEST['picture_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	
	echo utf8RawUrlDecode($content)."\n";
}

function add_photo_comments(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	global $allowed_profile_comment_tags;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	include_once 'includes/class.inputfilter.php'; 
	
	
	//InputFilter($tagsArray, $attrArray, $tagsMethod , $attrMethod);
//Instantiate the class with your settings.
//1st (tags array):    Optional (since 1.2.0)
//2nd (attr array):    Optional
//3rd (tags method):   0 = remove ALL BUT these tags (default)
//                     1 = remove ONLY these tags
//4th (attr method):   0 = remove ALL BUT these attributes (default)
//                     1 = remove ONLY these attributes
//5th (xss autostrip): 1 = remove all identified problem tags (default)
//                     0 = turn this feature off
//
//Eg.. $myFilter = new InputFilter($tags, $attributes, 0, 0);
	
	//$user_id = $_REQUEST['user_id']; //user requesting the add
	//the difference from profile comments is that 
	// we don't know the user_id at this time because we only have the photo_id
	$sql = "SELECT * from ".USERS_GALLERY_TABLE." ";
	$sql .= "WHERE photo_id='".GetSQLValueString($_REQUEST['photo_id'],"int") ."' ";
	if ( !($result = mysql_query($sql))){
	
				header('Content-type: text/xml');
				echo "<xml>\n<status>1</status>\n";
				echo "<message>".'Could not select record at line '. __LINE__ .' file: '. __FILE__ .' <br> sql: '.$sql." user_id:".$user_id." \n commenter_uid:".$commenter_uid ." error: 2</message>\n";
				echo "<comment_id>".$comment_id."</comment_id>\n";
				echo "<comment><![CDATA[".$comment."]]></comment>\n";
				echo "<commenter><![CDATA[".$commenter_box."]]></commenter>\n";
				echo "<datetime><![CDATA[".$commenter_box."]]></datetime>\n";
				echo "</xml>";
		 //echo LA_ERROR_WHILE_PROCESSING ." user_id:".$user_id." \n commenter_uid:".$commenter_uid;
		return false;
	
	
					//printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	$detail_pic_rows = mysql_num_rows($result);
	if($detail_pic_rows > 0){
		$detail_pic_row = mysql_fetch_assoc($result);
	}
	
	
	// let's check if the photo has a title
	if($detail_pic_row['photo_title'] != ""){
		$photo_title = $detail_pic_row['photo_title'];
	}else{
		$photo_title = $detail_pic_row['orig_filename'];
	}
	$photo_id = GetSQLValueString($_REQUEST['photo_id'],"int");	
	$user_id = $detail_pic_row['photo_uid'];
	$commenter_uid = GetSQLValueString($_REQUEST['commenter_uid'],"int");
	if(empty($user_id) || empty($commenter_uid)) {
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message>".LA_ERROR_WHILE_PROCESSING  ." user_id:".$user_id." \n commenter_uid:".$commenter_uid;" error: 2</message>\n";
				echo "<comment_id>".$comment_id."</comment_id>\n";
				echo "<comment><![CDATA[".$comment."]]></comment>\n";
				echo "<commenter><![CDATA[".$commenter_box."]]></commenter>\n";
				echo "<datetime><![CDATA[".$commenter_box."]]></datetime>\n";
				echo "</xml>";
		 //echo LA_ERROR_WHILE_PROCESSING ." user_id:".$user_id." \n commenter_uid:".$commenter_uid;
		return false;
	}
	
	$comment = GetSQLValueString($_REQUEST['comment'],"text");
	$userinfo = get_profile_info($user_id);	
	$commenterinfo = get_profile_info($commenter_uid);
	$myFilter = new InputFilter($forbiden_profile_comment_tags, $forbiden_attributes_in_profile_comments,1, 1,0); 
	$comment = $myFilter->process($comment);
	//let's check to see if the comment contains content
	if(empty($_REQUEST['comment'])) {
	
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message>".LA_ERROR_WHILE_PROCESSING ." error: 1</message>\n";
				echo "<comment_id>".$comment_id."</comment_id>\n";
				echo "<comment><![CDATA[".$comment."]]></comment>\n";
				echo "<commenter><![CDATA[".$commenter_box."]]></commenter>\n";
				echo "<datetime><![CDATA[".$commenter_box."]]></datetime>\n";
				echo "</xml>";
	
		return false;
	}
	
	$photo_url = PHOTOS_URL.'/'.get_username($user_id).'/'.$photo_id;
	
	
	//let's check if the user has allready requested the addition
/*	$sql = "SELECT * from ".PROFILE_COMMENTS_TABLE." WHERE user_uid = ".$user_id." AND commenter_uid = ".$fav_id;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		$row = mysql_fetch_assoc($result);
		
		list($year,$month,$day) = explode("-",$row['date']);
		list($day,$time) = explode(" ",$day);
		list($hour,$min,$sec) = explode(":",$time);
		
	$age = date("Y") - $year;
	if(date("md") < $month.$day) { $age--; }
		
		
		//if comment date by the same poster is not older than 5 min
		echo LA_DUPLICATE_PROFILE_COMMENT;
		return false;
	}else{*/
	
	
		if($userinfo['who_comments_on_photos'] > 0){ // 0 = no one 1 = invited only 2 = members 3 = everyone
		//comments are being allowed then we have to check if they are 
		//allowed for members or for friends or whatever for now all members
		
		//---------------------what is the user name of the owner of the photo? ---------
		
		//-------------------------------------------------------------------------------
			
			if($comment != ""){
				$sql = "INSERT into ".USERS_GALLERY_COMMENTS_TABLE." (photo_id, commenter_uid, comment, ";
				if($userinfo['comments_on_photo_need_approval'] == 0)
					$sql .= " approved,";
				$sql .= " date) ";
				$sql .= "VALUES(".$photo_id.", ".$commenter_uid.", ".$comment.", ";
				if($userinfo['comments_on_photo_need_approval'] == 0)
					$sql .= " 1,";
				$sql .=	" NOW())";
				if ( !($result = mysql_query($sql))){
					printf('Could not insert into photo comments at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
				}
				$comment_id = mysql_insert_id();
				
				

				//mail ($strMailTo, $strSubject, $strBody, $strXHeaders); 
				//BUDDY_APPROVAL_URL
				
				//check if we are going to notify the user
				if($commenter_uid != $user_id){
					if($userinfo['notify_on_photo_comments'] == 1){
						$from = "From: ".SITE_NAME." <".SITE_CONTACT_EMAIL.">\nX-Mailer: PHP/" . phpversion();
						$msg = LA_PHOTO_COMMENTS_NOTIFICATION_MESSAGE;
						$msg = str_replace('%commenter_username%', $commenterinfo['user_username'], $msg);
						$msg = str_replace('%photo_title%',$photo_title,$msg);
						$msg = str_replace('%photo_url%',$photo_url,$msg);
						//$msg = str_replace('%profile_url%' , PROFILE_DIR_URL.'/'.$userinfo['user_username'] , $msg);
						//$msg = str_replace('%comment%' , $comment , $msg);
						//future
						$msg = str_replace('%approvelink%' , PROFILE_COMMENT_APPROVAL_URL.'?a=a&r='.$approvalcode , $msg);
						$msg = str_replace('%denylink%' , PROFILE_COMMENT_APPROVAL_URL.'?a=d&r='.$approvalcode, $msg);
						$subject = LA_PHOTO_COMMENTS_NOTIFICATION_SUBJECT;
						$subject = str_replace('%photo_title%',$photo_title,$subject);
						mail($userinfo['user_email'], $subject, $msg, $from);
						
					}
				}
				
				$commenter_box = '<a href="'.PROFILE_DIR_URL.'/'.$commenterinfo['user_username'].'">'
								.'<img src="'.get_profile_photo($commenter_uid).'" border="0"></a><br />'
								.'<a href="'.PROFILE_DIR_URL.'/'.$commenterinfo['user_username'].'">'
								.$commenterinfo['user_username'].'</a>';
				
				header('Content-type: text/xml');
				echo "<xml>\n<status>1</status>\n";
				echo "<message>".LA_COMMENT_ADDED_SUCCESSFULLY."</message>\n";
				echo "<comment_id>".$comment_id."</comment_id>\n";
				echo "<comment><![CDATA[".$comment."]]></comment>\n";
				echo "<commenter><![CDATA[".$commenter_box."]]></commenter>\n";
				echo "<datetime><![CDATA[".$commenter_box."]]></datetime>\n";
				echo "</xml>";
			}else{
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message>".LA_COMMENT_COULD_NOT_BE_ADDED."</message>\n";
				echo "<comment></comment>\n";
				echo "<commenter></commenter>\n";
				echo "<datetime></datetime>\n";
				echo "</xml>";
			}
		}else{
				header('Content-type: text/xml');
				echo "<xml>\n<status>0</status>\n";
				echo "<message>".LA_COMMENTS_ARE_NOT_ALLOWED_BY_USER." set;".$userinfo['who_comments_on_profile']."</message>\n";
				echo "<comment></comment>\n";
				echo "<commenter></commenter>\n";
				echo "<datetime></datetime>\n";
				echo "</xml>";
		}
	/*}*/

}

function update_pic_caption(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	require 'includes/class.inputfilter.php';
	$content = $_REQUEST['content'];
	$tags = array("br","p","u","b","i");
	$attributes = array();
	$myFilter = new InputFilter($tags, $attributes,0,0);
	$content = $myFilter->process($content);
	
	$sql = "update ".USERS_GALLERY_TABLE." set photo_caption = '".$content."' where photo_id=".$_REQUEST['picture_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	
	echo utf8RawUrlDecode($content)."\n";
	//echo urlencode($_REQUEST['content']);
}

function get_pic_info(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	$photo_id = $_REQUEST['photo_id'];
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$sql = "select * from ".USERS_GALLERY_TABLE." where photo_id=".$photo_id;
	if ( !($result = mysql_query($sql))){
					printf('Could not select at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	$row = mysql_fetch_assoc($result);
	
	$title = $row['photo_title'];
	$caption = $row['photo_caption'];
	
	$sql = "SELECT ut.tag_id, t.tag FROM `users_gallery2tag` as ut left join tags as t using(tag_ID) WHERE ut.photo_id = ".$photo_id;
	if ( !($tagresult = mysql_query($sql))){
					printf('Could not select at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	$tags = array();
	while($row = mysql_fetch_assoc($tagresult)){
		$tags[$row['tag']] = $row['tag_id'];
	}
	//echo $row['photo_title'];

	header('Content-type: text/xml');
	echo "<xml>\n<status>1</status>\n";
	echo "<title>".$title."</title>\n";
	echo "<caption><![CDATA[".$caption."]]></caption>\n";
	echo "<tags>\n";
	foreach($tags as $tagname=>$tagid){
		print '    <tag tagid="'.$tagid.'"><![CDATA['.$tagname."]]></tag>\n";
	}
	//echo "	<tag tagid=\"\"><![CDATA[".$caption."]]></tag>\n";
	echo "</tags>\n";
	echo "</xml>";
	
}

function delete_pic(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$sql = "select * from ".USERS_GALLERY_TABLE." where photo_id=".$_REQUEST['photo_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	$row = mysql_fetch_assoc($result);
	$picname = $row['photo_filename'];
	list($id,$basename,$ext) = split('[.-]',$picname);
	
	$basename = $id.'.'.$basename;
	$squarepic = $basename.'_sq.'.$ext;
	$medpic  = $basename.'_m.'.$ext;
	$smpic = $basename.'_s.'.$ext;
	$largestpic = $basename.'_l.'.$ext;
	$dirname = MEMBER_IMG_DIR_URL.'/'.$_SESSION['user_id'];
	
	$filestocheck = array($squarepic,$medpic,$smpic,$largestpic);
	foreach($filestocheck as $thispic){
		if (file_exists(MEMBER_IMG_DIR_PATH.'/'.$_SESSION['user_id'].'/'.$thispic)) {
				//echo $thispic."\n";
				unlink(MEMBER_IMG_DIR_PATH.'/'.$_SESSION['user_id'].'/'.$thispic);
		}
	}
	
	$sql = "delete from ".USERS_GALLERY_TABLE." where photo_id=".$_REQUEST['photo_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not delete from record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
   
   	$sql = "delete from ".TAGS_TO_GALLERY_TABLE." where photo_id=" .$_REQUEST['photo_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not delete from record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	echo LA_PHOTO_DELETED_SUCCESSFULLY;
}

function delete_pic_tag(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	$sql = "delete from ".TAGS_TO_GALLERY_TABLE." where tag_id=".$_REQUEST['tag_id']." and photo_id=" .$_REQUEST['photo_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}
	echo $_REQUEST['photo_id']; //let's give back the photo_id of the photo tag modified
	
}



function add_pic_tag(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	require 'includes/class.inputfilter.php';
	$tags =  stripslashes($_REQUEST['tags']);
	$photo_id = $_REQUEST['photo_id'];
	$filtertags = array();
	$attributes = array();
	$myFilter = new InputFilter(filtertags, $attributes,0,0);
	$tags = $myFilter->process($tags);
	//$tags = str_replace("\\","",$tags);
	$tagsarray = ParseTagString($tags);
	
	//$sql = "select * from ".TAGS_TO_GALLERY_TABLE." where tag_id=".$_REQUEST['tag_id']." and photo_id=" .$_REQUEST['photo_id'];
	foreach($tagsarray as $tag){
		$sql = "select * from ".TAGS_TABLE." where tag='".$tag."'";
		if ( !($result = mysql_query($sql))){
				printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				exit;
		}
		
		if(mysql_num_rows($result)==0){ //tag doesnt exists 
			$sql = "insert into ".TAGS_TABLE." (tag) values('".$tag."')"; //insert tag
			if ( !($insres = mysql_query($sql))){
				printf('Could not insert record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				exit;
			}	
			$tag_id = mysql_insert_id();
			$sql = "insert into ".TAGS_TO_GALLERY_TABLE." (tag_id,photo_id) values('".$tag_id."','".$photo_id."')"; //insert relation
			if ( !($insres = mysql_query($sql))){
				printf('Could not insert record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				exit;
			}
		}elseif(mysql_num_rows($result)>0){ //the tag exists
			$row = mysql_fetch_assoc($result);
			$tag_id = $row['tag_ID'];
			$sql = "select * from ".TAGS_TO_GALLERY_TABLE." where tag_id = '".$tag_id."' and photo_id = '".$photo_id."'";//check if relation
			if ( !($selectresult = mysql_query($sql))){
				printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				exit;
			}
			if(mysql_num_rows($selectresult) == 0){ //we don't have a relation
				$sql = "insert into ".TAGS_TO_GALLERY_TABLE." (tag_id,photo_id) values('".$tag_id."','".$photo_id."')"; //insert relation
				if ( !($insertresult = mysql_query($sql))){
					printf('Could not insert record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
				}
			}
		}	
	} //end foreach
	
	echo $photo_id;
}

function icon_pic(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	//let's change all the other pic(s) that are marked as icon into non icons  ." and use_in_profile=0"
	$sql = "select * from ".USERS_GALLERY_TABLE." where photo_id=".$_REQUEST['photo_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}	
	$row = mysql_fetch_assoc($result);
	$user_id = $row['photo_uid']; // ok so now we know the owner of the picture that will become the icon
	
	$sql = "update ".USERS_GALLERY_TABLE." set use_in_profile=0 where photo_uid=".$user_id;
	if ( !($result = mysql_query($sql))){
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}	
	
	$sql = "update ".USERS_GALLERY_TABLE." set use_in_profile=1 where photo_uid=".$user_id." and photo_id=".$_REQUEST['photo_id'];
	if ( !($result = mysql_query($sql))){
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
					exit;
	}	


	echo LA_PICTURE_ICON_CHANGED;
}

function approve_buddy(){
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


function deny_buddy(){
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



//-------------------------------------------- utilities ---------------------------------------------//
function utf8RawUrlDecode ($source) {
    $decodedStr = "";
    $pos = 0;
    $len = strlen ($source);
    while ($pos < $len) {
        $charAt = substr ($source, $pos, 1);
        if ($charAt == '%') {
            $pos++;
            $charAt = substr ($source, $pos, 1);
            if ($charAt == 'u') {
                // we got a unicode character
                $pos++;
                $unicodeHexVal = substr ($source, $pos, 4);
                $unicode = hexdec ($unicodeHexVal);
                $entity = "&#". $unicode . ';';
                $decodedStr .= utf8_encode ($entity);
                $pos += 4;
            }
            else {
                // we have an escaped ascii character
                $hexVal = substr ($source, $pos, 2);
                $decodedStr .= chr (hexdec ($hexVal));
                $pos += 2;
            }
        } else {
            $decodedStr .= $charAt;
            $pos++;
        }
    }
    return $decodedStr;
}



/**
 * Parses a String of Tags http://www.bigbold.com/snippets/posts/show/1625
 *
 * Tags are space delimited. Either single or double quotes mark a phrase.
 * Odd quotes will cause everything on their right to reflect as one single
 * tag or phrase. All white-space within a phrase is converted to single
 * space characters. Quotes burried within tags are ignored! Duplicate tags
 * are ignored, even duplicate phrases that are equivalent.
 *
 * Returns an array of tags.
 */
function ParseTagString($sTagString)
{
	$arTags = array();		// Array of Output
	$cPhraseQuote = null;	// Record of the quote that opened the current phrase
	$sPhrase = null;		// Temp storage for the current phrase we are building
	
	// Define some constants
	static $sTokens = " \r\n\t";	// Space, Return, Newline, Tab
	static $sQuotes = "'\"";		// Single and Double Quotes
	
	// Start the State Machine
	do
	{
		// Get the next token, which may be the first
		$sToken = isset($sToken)? strtok($sTokens) : strtok($sTagString, $sTokens);
		
		// Are there more tokens?
		if ($sToken === false)
		{
			// Ensure that the last phrase is marked as ended
			$cPhraseQuote = null;
		}
		else
		{		
			// Are we within a phrase or not?
			if ($cPhraseQuote !== null)
			{
				// Will the current token end the phrase?
				if (substr($sToken, -1, 1) === $cPhraseQuote)
				{
					// Trim the last character and add to the current phrase, with a single leading space if necessary
					if (strlen($sToken) > 1) $sPhrase .= ((strlen($sPhrase) > 0)? ' ' : null) . substr($sToken, 0, -1);
					$cPhraseQuote = null;
				}
				else
				{
					// If not, add the token to the phrase, with a single leading space if necessary
					$sPhrase .= ((strlen($sPhrase) > 0)? ' ' : null) . $sToken;
				}
			}
			else
			{
				// Will the current token start a phrase?
				if (strpos($sQuotes, $sToken[0]) !== false)
				{
					// Will the current token end the phrase?
					if ((strlen($sToken) > 1) && ($sToken[0] === substr($sToken, -1, 1)))
					{
						// The current token begins AND ends the phrase, trim the quotes
						$sPhrase = substr($sToken, 1, -1);
					}
					else
					{
						// Remove the leading quote
						$sPhrase = substr($sToken, 1);
						$cPhraseQuote = $sToken[0];
					}
				}
				else
					$sPhrase = $sToken;
			}
		}
		
		// If, at this point, we are not within a phrase, the prepared phrase is complete and can be added to the array
		if (($cPhraseQuote === null) && ($sPhrase != null))
		{
			$sPhrase = strtolower($sPhrase);
			if (!in_array($sPhrase, $arTags)) $arTags[] = $sPhrase;
			$sPhrase = null;
		}
	}
	while ($sToken !== false);	// Stop when we receive FALSE from strtok()
	return $arTags;
}

/**
 * Reverses ParseTagString()
 */
function CreateTagString($arTags)
{
	// Prepare each tag to be imploded
	for ($i = 0; $i < sizeof($arTags); $i++)
	{
		// Record findings
		$bContainsWhitespace = false;	// Was whitespace found?
		$cRequiredQuote = '"';			// Use double-quote by default
		$cLastChar = null;
	
		// Search the tag
		for ($j = 0; $j < strlen($arTags[$i]); $j++)
		{
			$c = $arTags[$i][$j];
			
			// If the current character is a space
			if ($c === ' ')
			{
				$bContainsWhitespace = true;
				
				// If the previous char was a double quote, we require single quotes round our phrase
				if ($cLastChar === '"')
				{
					$cRequiredQuote = "'";
					break;	// There is no more point in continuing our search, we cant handle double-mixed quotes
				}
			}
			
			// Record this char as the last char
			$cLastChar = $c;
		}
		
		// Quote if necessary
		if ($bContainsWhitespace) $arTags[$i] = $cRequiredQuote . $arTags[$i] . $cRequiredQuote;
	}
	return implode(' ', $arTags);
}

?>