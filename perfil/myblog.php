<?php
include('../includes/config.php'); 
session_start();
if(!isset($HTTP_SESSION_VARS['user_id'])) {
	//header('Location: ' . '/login/?redirect='.PEOPLE_SEARCH_URL);
	//exit;
	
	$login_link = '<a href="/login/?redirect=/perfil/'.$_REQUEST['username'].'">Login</a> | <a href="/registrate/">Registrate</a>';
}else{
	$login_link = '<a href="/logout">Logout</a>';
}

		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);

$username = $_REQUEST['username'];
$url_slug = $_REQUEST['url_slug']; //the ruquest of a specific blog entry
if(isset($_REQUEST['username'])){
	$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
	$sql .= "LOWER(".USERNAME_FIELD.") = '" . strtolower($username) . "'"
				   ." limit 1";
}elseif(isset($_GET['acid'])){
	$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
	$sql .= "user_id = '" . $_GET['acid'] . "'"
				   ." limit 1";
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}else{
			$total_rows = mysql_num_rows($result);
			//echo $total_rows . "|" .$username;
			//do we have a row with that username?
            if ($total_rows < 1) {
				user_does_not_exist();
			}else{
				$row = mysql_fetch_assoc($result);
			header('Location: ' . MEMBER_BLOG_DIR_URL.strtolower($row['user_username']));	
				
			}
			mysql_free_result($result);
	}
	
	exit;
}

if(!$error) {

		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		} else {
			$total_rows = mysql_num_rows($result);
			//echo $total_rows . "|" .$username;
			//do we have a row with that username?
            if ($total_rows < 1) {
				user_does_not_exist($username,$sql.' '. USERNAME_FIELD);
			}else{
				$profile = mysql_fetch_assoc($result);
			//echo $row['username'] . "|" .  htmlentities($row['username']). "\n";<br />				
				display_blog();
			}
			mysql_free_result($result);
		}
}

function user_does_not_exist($username='',$xtrainfo=''){
	echo "User: ".$username. " does not exist";
	if(!empty($xtrainfo)) echo "\n"."<br>".$xtrainfo;
}

function display_blog(){
	global $username,$profile,$url_slug;
	require (SMARTY_DIR.'Smarty.class.php');
	
	$smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/';
	$smarty->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/templates_c/';
	$smarty->caching = false;
	
	
	$smarty = new Smarty;
	
	$smarty->compile_check = true;
	
	
	
	$blog_sql = "SELECT * from ". USERS_BLOGS_TABLE. " where ub_uid = '".$profile[USER_ID_FIELD]."' and type='text'";
	$blog_sql .= " order by date desc";
	if ( !($blog_result = mysql_query($blog_sql)) ) {
			printf('Could not select user blog at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $blog_sql);
	} else {
		while($blogrow = mysql_fetch_assoc($blog_result)){
			$left_col .= '<div style="margin: 4px;">- <a href="'.MEMBER_BLOG_DIR_URL.$username.'/'.$blogrow['url_slug'].'">'.$blogrow['title'].'</a></div>';
		}
		
		//let's see if it's the owner of the blog if so then give him a link to create a new entry
		if($_SESSION['user_id'] == $profile[USER_ID_FIELD]){
			$left_col = '<div id="createNewBlogEntryLink" style="font-weight: bold; background: #ccc; border: solid 1px #999; padding: 8px; margin: 4px;"><a href="'.BLOG_ADD_URL.'">'.LA_CREATE_NEW_BLOG_ENTRY.'</a></div>'.$left_col;
		}
		
		
		$smarty->assign("left_col",$left_col);
	}
	
	$buddies = get_buddies($profile[USER_ID_FIELD]);
	foreach($buddies as $bkey=>$barray){
		$buddies_content .= "\n<br><img src=\"".$barray['image'].'" >'."<br>".$barray['username'];
	}
	
	
	if(!empty($url_slug)){ //----------------- Are we looking at a specific entry? ----------------------
		$sql = "SELECT * from ".USERS_BLOGS_TABLE." WHERE ub_uid = '".$profile[USER_ID_FIELD]."' AND url_slug= '".$url_slug."'";
	}else {   //----------ok so there's no spefic blog that is being asked for so let's display the latest
		$sql = "SELECT * from ".USERS_BLOGS_TABLE." WHERE ub_uid = '".$profile[USER_ID_FIELD]."' order by date desc";
	}
		
		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select blog detail at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		} else {
			$row = mysql_fetch_assoc($result);
			$smarty->assign('blog_title',$row['title']);
			$smarty->assign('title',$profile['user_username'].' - '.$row['title']);
			$smarty->assign('blog_content',$row['date'].'<br>'.$row['content']."\n".$buddies_content);
		}
		//let's see if it's the owner of the blog looking at his own entry
		//if it is then let's give options of editing,deleting and whether or not he/she wants comments on this entry
		if($_SESSION['user_id'] == $profile[USER_ID_FIELD]){
			$smarty->assign('blog_options',LA_EDIT." | ".LA_DELETE);
		}
		

	
	
	$smarty->display('profile_blog.html');
}
?>