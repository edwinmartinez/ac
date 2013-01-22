<?php
require_once("includes/config.php");
require_once('includes/smarty_setup.php');
$smarty = new Smarty_su;

	mysql_connect($dbhost, $dbuser, $dbpasswd) or
	  die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);



function approve_buddy_request(){
	global $buddy_requester_username,$approved;
   $approvalcode = $_REQUEST['r'];
   	$sql1 = "SELECT * from ". BUDDIES_TABLE. " WHERE  approvalcode = ".GetSQLValueString($approvalcode, "text") . ' LIMIT 1';
	$result = mysql_query($sql1) or die(mysql_error());
	$result_rows = mysql_num_rows($result);
	if($result_rows > 0){
		$row = mysql_fetch_assoc($result);
		if($row['confirmed'] == 0){ //let's update the row if it hasn't been done before
			$sql2 = "UPDATE ". BUDDIES_TABLE . " SET confirmed=1, date_added=NOW() WHERE  approvalcode = ".GetSQLValueString($approvalcode, "text");
			$result2 = mysql_query($sql2) or die(mysql_error());
		}
		$buddy_requester_uid = $row['user_uid'];
		$buddy_requester_username = get_username($buddy_requester_uid);
		$approved = 1;
	}else{
		global $error;
		$error = LA_BUDDY_REQUEST_DOES_NOT_EXIST;
	}
}

function deny_buddy_request(){
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
	}else{
		
		global $error;
		$error = LA_BUDDY_REQUEST_DOES_NOT_EXIST;
	}
}


if ( isset($_REQUEST['r']) && isset($_REQUEST['a'])){  //requestcode and action
	switch($_REQUEST['a']) {
		case 'a'://approve
			$title = LA_BUDDY_APPROVED;
			approve_buddy_request();
			break;
		case 'd'://deny
			$title = LA_BUDDY_DENIED;
			deny_buddy_request();
			break;		
		default:
			$error = "Nothing to do";
			break;
	}
}else{
        $error = "No Pudimos encontrar esos datos";
  }


 if(isset($error)) $title= $error; 
$content = '<div style="text-align:center; width:100%;">
	<div style="margin:auto;">';
if(!isset($error)){ 
	$content .= '<h2><?php echo $title ?></h2>';
 
	if ($approved)
		$msg.= LA_BUDDY_APPROVED_MESSAGE;
	else
		$msg.= LA_BUDDY_DENIED_MESSAGE;
	$msg = str_replace('%username%', '<b><a href="'.PROFILE_DIR_URL.'/'.$buddy_requester_username.'">'.$buddy_requester_username.'</a></b>', $msg);
	$content .= $msg;
	
} else{
	$content .= '<h2>Error</h2>';
	$content .= $error;
 } 
 $content .='
	</div>
</div>';
	$smarty->assign("title",$title);
	$smarty->assign("content",$content);
	$smarty->display('index.html');	
?>

