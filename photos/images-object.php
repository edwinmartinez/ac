<?php
include_once('../includes/config.php'); 


$uid = $_REQUEST['u'];

function display_object(){

	//let's get the picture
	
	$sql = "SELECT * from ".USERS_GALLERY_TABLE." where ";
	$sql .= "photo_uid='".$uid ."' ";
	$sql .= "and  use_in_profile=0 and privacy_level=0";
	
	$profile_pic_result = mysql_query($sql);
	$profile_pic_rows = mysql_num_rows($profile_pic_result);
	if($profile_pic_rows > 0){
		while($profile_pic_row = mysql_fetch_assoc($profile_pic_result)){
			$profile_pic = $profile_pic_row['photo_filename'];
		}
		
	}else{
		$content = '<p><center><strong>'.LA_USER_HAS_NO_PHOTOS.'</center></strong></p>';	
	}
}

if(!isset($content)){
	$content = '
		var JJ = {
	images : [
			
			["60.b6f382e3b0ca25984b724e19b9e5aea6_m.jpg", "Woode Wood playing guitar", "Woode Wood, River park"],
	["60.ee94ef72f2e6af7718023e3de09e0959_m.jpg", "Bat bridge in Austin", "Bridge"]
	]
};
	';
}

echo $content;	
	
?>