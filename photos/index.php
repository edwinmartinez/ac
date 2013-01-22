<?php 
include('../includes/config.php'); 
session_start();
if(!isset($HTTP_SESSION_VARS['user_id'])) {
	//header('Location: ' . '/login/?redirect='.PEOPLE_SEARCH_URL);
	//exit;
	
	$login_link = '<a href="/login/?redirect=/perfil/'.$_REQUEST['username'].'">Login</a> | <a href="/registrate.php">Registrate</a>';
}else{
	$login_link = '<a href="/logout">Logout</a>';
}

$username = strtolower($_REQUEST['username']);
if(isset($_REQUEST['username'])){
	$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
	$sql .= "LOWER(".USERNAME_FIELD.") = '" . $username . "'"
				   ." limit 1";


	if(!$error) {
			mysql_connect($dbhost, $dbuser, $dbpasswd) or
				die("Could not connect: " . mysql_error());
			mysql_select_db($dbname);
			if ( !($result = mysql_query($sql)) ) {
				printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			} else {
				$total_rows = mysql_num_rows($result);
				//echo $total_rows . "|" .$username;
				//do we have a row with that username?
				if ($total_rows < 1) {
					user_does_not_exist();
				}else{
					$profile = mysql_fetch_assoc($result);
				//echo $row['username'] . "|" .  htmlentities($row['username']). "\n";<br />				
					display_photos();
				}
				mysql_free_result($result);
			}
	}

}

function user_does_not_exist(){
	echo "User: ".$username. " does not exist";
}


function display_photos(){
	global $profile;

	//let's get the picture
	
	$sql = "SELECT * from ".USERS_GALLERY_TABLE." where ";
	$sql .= "photo_uid='".$profile[USER_ID_FIELD]."' ";
	$sql .= "and  use_in_profile=0 and privacy_level=0";
	
	$profile_pic_result = mysql_query($sql);
	$profile_pic_rows = mysql_num_rows($profile_pic_result);
	if($profile_pic_rows > 0){
		while($profile_pic_row = mysql_fetch_assoc($profile_pic_result)){
			$profile_pic = MEMBER_IMG_DIR_URL . $profile_pic_row['photo_filename'];
		}
		
	}else{
		$content = '<p><center><strong>'.$sql.'</center></strong></p>';	
	}


	

	$js = '
	<script type="text/javascript" src="images-object.php"></script>	
	<script type="text/javascript" src="/js/jas-gallery-js.php?u='.$profile[USER_ID_FIELD].'"></script>
	<style type="text/css">
		@import url("/photos/css/jas.css");
	</style>
	<!--[if lt IE 7]>
		<link rel="stylesheet" href="css/ie.css" type="text/css">
	<![endif]-->';


	if(!isset($content)){
	$content = '
	<div id="content" style="margin:10px;"><h2>Fotos de '.$_REQUEST['username'].'</h2>
	</div>
	<div id="galleryContainer">
		
		<div id="jas-frame">
		
		<ul id="navigation-controls">
			<li><a id="previous-image" href="index.htm">'.LA_PREVIOUS.'</a></li>
			<li id="image-counter">1 / 12</li>
			<li><a id="next-image" href="index.htm">'.LA_NEXT.'</a></li>
			<li class="slideshow-item">
				<a id="start-slideshow" href="index.htm">'.LA_START_SLIDESHOW.'</a>
				<a id="stop-slideshow" href="index.htm">'.LA_STOP_SLIDESHOW.'</a>
			</li>
		</ul>
		

			<div id="jas-container">
				<h2 id="jas-image-text">Fotos</h2>
				<img id="jas-image" src="images/1.jpg" alt="">
				
			</div>
		</div>
		

		
		<div id="jas-thumbnails"></div>
		<div id="jas-tags">
			<h3>Tags:</h3>
			<p>
				<input type="checkbox" id="jas-select-all-tags" checked="checked">
				<label for="jas-select-all-tags">Select all</label>
			</p>
		</div>
				
	</div>
	<div style="clear:both;">&nbsp;</div>
	';
	}
	
	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js",$js);	
	$smarty->assign("title","Fotos");
	$smarty->assign("content_wide",$content);
	$smarty->display('index.html');	
}