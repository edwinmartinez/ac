<?php
require_once '../includes/config.php'; 
//require_once '../includes/user.class.php';
@session_start();

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


$username = $_GET['username'];
//var_dump($_GET);
if(!empty($username)){
	$username = mysql_real_escape_string($username);
	$sql = "SELECT * from ".SITE_USERS_TABLE."   WHERE LOWER(".USERNAME_FIELD.") = '" . strtolower($username) . "'"
				   ." AND status=1 limit 1";
}elseif(isset($_GET['acid'])){
	$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
	$sql .= "user_id = '" . $_GET['acid'] . "'"
				   ." AND status=1 limit 1";
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
			header('Location: ' . PROFILE_DIR_URL.'/'.strtolower($row['user_username']));	
				
			}
			mysql_free_result($result);
	}
	
	exit;
}

if(empty($error)) {

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
				display_profile();
			}
			mysql_free_result($result);
		}
}

function user_does_not_exist(){
	global $username;
	require (SMARTY_DIR.'Smarty.class.php');

	$smarty = new Smarty;
	$smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/';
	$smarty->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/templates_c/';
	$smarty->caching = false;
	
	$smarty->compile_check = true;
	// LA_USER.": <strong>".$username. "</strong> ".LA_DOES_NOT_EXIST
	$content = '<div style="text-align:left;margin-bottom:20px;font-size:12px;"><a href="/gente/"><img src="/images/back_arrow_20x20.gif" border="0" align="middle" /></a>
  <a href="/gente/">Mirar Otros Perfiles</a>
  </div> ';
	$content .= LA_USER.": <strong>".$username. "</strong> ".LA_DOES_NOT_EXIST;
	
	$smarty->assign("content",$content);
	$smarty->display('index.html');
}

function display_profile(){
	global $username, $login_link, $profile,$profile_pic,
	$lang,$lang_gender,$lang_relation_type,$lang_marital_status,$lang_race,$lang_religion,$lang_drink_habit,
	$lang_smoke_habit,$lang_have_kids,$lang_want_kids,$lang_income,$lang_education,$lang_exercise_freq,$lang_employment_status,
	$lang_occupational_area,$lang_height_cm,$lang_eyes_color,$lang_hair_color,$lang_body_type,$lang_languages;

	
	//let's get the picture
	
	
	
	$uid = USER_ID_FIELD;

	$profile_pic = get_profile_photo($profile[$uid]);

	//let's figure out who can comment on this profile
	
	//first we get the preferences to see if the user allows comments on his profile
	//if the 
	
	$sql = "SELECT * from ".USER_PREFERENCES_TABLE." where user_id=".$profile[$uid]." limit 1";
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$prefrow = mysql_fetch_assoc($result);
		$pref_comments_on_profile = $prefrow['who_comments_on_profile'];
		//let's remember
		// 0 = no one can comment
		// 1 = only those that have invitation (will be implemented later)
		// 2 = friends only
		// 3 = members only
		// 4 = everyone
		
	}
	

//if($row['DOBPublic']    == 1) {
	list($year,$month,$day) = explode("-",$profile['user_birthdate']);
	$age = date("Y") - $year;
	if(date("md") < $month.$day) { $age--; }
	//$age = $age. " Years Old";
//}

	require (SMARTY_DIR.'Smarty.class.php');
	

	$smarty = new Smarty;
	$smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/';
	$smarty->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/templates_c/';
	$smarty->caching = false;
	
	$smarty->compile_check = true;
	//$smarty->debugging = true;
	$head = "
	  <script src=\"../js/prototype.js\" type=\"text/javascript\"></script>
	  <script src=\"../js/scriptaculous/scriptaculous.js\" type=\"text/javascript\"></script>
	  <script src=\"../js/scriptaculous/unittest.js\" type=\"text/javascript\"></script>
	  <script language=\"javascript\" src=\"../js/profile-js.php\"></script>
	  ";
	
	$smarty->assign("head",$head);

/*-------------------------------------Comments -------------------------------------*/
//$smarty->assign("comments",LA_COMMENTS);
if($prefrow['show_profile_comments']=1){
	$smarty->assign("comments",LA_COMMENTS);
	$comments_array = array();
	$sql = "SELECT * from ".PROFILE_COMMENTS_TABLE." where user_id=".$profile[USER_ID_FIELD]." and approved = 1 order by date asc limit 20";
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		while($commentrow = mysql_fetch_assoc($result)){
			$commenter_username = get_username($commentrow['commenter_uid']);
			
			
			if($profile['user_id'] == $_SESSION['user_id']){ //owner of profile
				
				array_push($comments_array,array(
							'commenter'=>$commenter_username,
							'comment'=>$commentrow['comment'],
							'photo'=>get_profile_photo($commentrow['commenter_uid']),
							'commenter_profile_url'=>PROFILE_DIR_URL.'/'.$commenter_username,
							'comment_id'=>$commentrow['upc_id'],
							'datetime'=>$commentrow['date'],
							'edit_link'=>'<a href="#" id="edit_control_'.$commentrow['upc_id'].'" ><img src="/images/icon_edit.png" border="0" style="margin-left:5px;" />'.LA_EDIT.'</a>',
							'delete_link'=>'<a href="#" onClick="delete_comment('.$commentrow['upc_id'].');return false;"><img src="/images/icon_cross.png" border="0" style="margin-left:5px;" /></a> <a href="#" onClick="delete_comment('.$commentrow['upc_id'].');return false;">'.LA_ERASE.'</a>',
							'edit_control'=> "
							<script>
							makeEditable(".$commentrow['upc_id'].");
							</script>"
						));
			}else{
				array_push($comments_array,array(
							'commenter'=>$commenter_username,
							'comment'=>$commentrow['comment'],
							'photo'=>get_profile_photo($commentrow['commenter_uid']),
							'commenter_profile_url'=>PROFILE_DIR_URL.'/'.$commenter_username,
							'comment_id'=>$commentrow['upc_id'],
							'datetime'=>$commentrow['date']
												));
			}
				
		}
	}

}

$smarty->assign('profile_comments',$comments_array);

//let's check if we have a gallery of pictures
$sql = "SELECT count(*) as total from ".USERS_GALLERY_TABLE." where photo_uid=".$profile[USER_ID_FIELD]." AND use_in_profile = 0  ";
if ( !($result = mysql_query($sql)) ) {
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$row = mysql_fetch_assoc($result);
		if($row['total'] > 0){
		 $smarty->assign('gallery','<a href="'.PHOTOS_URL.'/'.$username.'"><img src="/images/icon_camera2.gif" border="0" width="16" height="16"></a> <a href="'.PHOTOS_URL.'/'.$username.'">'.LA_LOOK_AT_MY_PHOTOS.'</a>');
		}
	
	}


switch($pref_comments_on_profile){
	case '2': //friends only
		//check if friends
		$smarty->assign("add_your_comment",LA_ADD_YOUR_COMMENT);
		break;
	case '3': //members only
		//check if member
		if(isset($_SESSION['user_id'])){
			$smarty->assign("add_your_comment",LA_ADD_YOUR_COMMENT);
			$smarty->assign("commenter_uid",$_SESSION['user_id']);
		}
		break;
	case '4': //everyone
		$smarty->assign("add_your_comment",LA_ADD_YOUR_COMMENT);
		break;
}

/*-------------------------------------Comments end -------------------------------------*/

/*------------------------------------- Blog Stuff --------------------------------------*/

$blog_sql = "SELECT * from ". USERS_BLOGS_TABLE. " where ub_uid = ".$profile[USER_ID_FIELD]." and type='text'";
	if ( !($blog_result = mysql_query($blog_sql)) ) {
			printf('Could not select user blog at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		while($blogrow = mysql_fetch_assoc($blog_result)){
			$blog_content .= '<div style="margin: 4px;">- '.$blogrow['title'].'</div>';
		}
		$smarty->assign("blog_content",$blog_content);
	}

/*------------------------------------- Blog end ----------------------------------------*/


/*------------------------------------- Friends Stuff --------------------------------------*/
if($friends = get_buddies($profile[USER_ID_FIELD],'approved',4)){
    $smarty->assign("friends_count", get_buddy_count($profile[USER_ID_FIELD]));
	$smarty->assign("friends",$friends);
	$smarty->assign("my_friends_label",LA_MY_BUDIES);
}
/*------------------------------------- Friends end --------------------------------------*/


/*-------------------------------------Astro --------------------------------------------*/
require("../includes/class.astro.php");
$person = new astro($profile['user_birthdate']);
$zodiac_sign = $person->chaldeanSign;
$chinese_sign = $person->chineseSign;
$smarty->assign("zodiac_sign_label",LA_ZODIAC_SIGN);
$smarty->assign("zodiac_sign",translate_sign($zodiac_sign));
$smarty->assign("chinese_sign_label",LA_CHINESE_YEAR_ANIMAL);
$smarty->assign("chinese_sign",translate_sign($chinese_sign));
/*-------------------------------------Astro end ----------------------------------------*/
$smarty->assign("login",$login_link);
$smarty->assign("age_label",$lang['age']);
$smarty->assign("age",$age . " " .$lang['years_old']);

$smarty->assign("friends_of",$lang['friends_of']);
$smarty->assign("details_of",$lang['details_of']);
$smarty->assign("profilepic",$profile_pic);
if($bigprofilepic = get_profile_photo($profile[$uid],1))
	$smarty->assign("bigprofilepic",$bigprofilepic);

//check to see if we are signed on
if(isset($_SESSION['user_id'])) {
$send_me_a_message = '<a href="/mensajes/?u='
	.$profile['user_id'].'" >'.$lang['send_me_a_message'].'</a>';
	$smarty->assign("add_to_buddies",'<a href="#" onClick="add_to_buddies('.$_SESSION['user_id'].','.$profile[$uid].');return false;">'.LA_ADD_ME_AS_MALE_BUDDY.'</a>');
	if ($profile['user_gender'] == 2) //female
		$smarty->assign("add_to_buddies",'<a href="#" onClick="add_to_buddies('.$_SESSION['user_id'].','.$profile[$uid].');return false;">'.LA_ADD_ME_AS_FEMALE_BUDDY.'</a>');
	$smarty->assign("send_to_friend",'<a href="javascript:staf_pop(\''.strtolower($profile['user_username']).'\')">'.LA_SEND_TO_FRIEND.'</a>');	
	$smarty->assign("add_to_favorites",'<a href="#" onClick="addfav('.$_SESSION['user_id'].','.$profile[$uid].');return false;">'.LA_ADD_TO_FAVORITES.'</a>');
}else{
	$please_login_onclick = 'onClick="alert(\''.LA_PLEASE_LOGIN_TO_PERFORM_THIS_ACTION.'\');return false;"';
	$send_me_a_message = '<a href="#" '.$please_login_onclick.'>'.$lang['send_me_a_message'].'</a>';
	$smarty->assign("add_to_buddies",'<a href="#" '.$please_login_onclick.'>'.LA_ADD_ME_AS_MALE_BUDDY.'</a>');
	if ($profile['user_gender'] == 2) //female
		$smarty->assign("add_to_buddies",'<a href="#" '.$please_login_onclick.'>'.LA_ADD_ME_AS_FEMALE_BUDDY.'</a>');
	$smarty->assign("send_to_friend",'<a href="#" '.$please_login_onclick.'>'.LA_SEND_TO_FRIEND.'</a>');	
	$smarty->assign("add_to_favorites",'<a href="#" '.$please_login_onclick.'>'.LA_ADD_TO_FAVORITES.'</a>');
}
if($_SESSION['user_id'] == $profile[$uid]){
	$smarty->assign("upload_pics_link_label",LA_UPLOAD_PICTURES);
	$smarty->assign("upload_pics_link",GALLERY_UPLOAD_URL);
}

$smarty->assign("send_me_a_message",$send_me_a_message);
$smarty->assign("profile_uid",$profile[$uid]);

$smarty->assign("username",$profile['user_username']);
$smarty->assign("title",$profile['user_username']." - ".truncate_words($profile['about_me']));
$smarty->assign("FirstName",$profile['user_first_name']);
$smarty->assign("LastName",$profile['user_first_name']);
$smarty->assign("about_me",$profile['about_me']);
$smarty->assign("my_ideal_mate",$profile['my_ideal_mate']);
$smarty->assign("interests",$lang['interests']);
$smarty->assign("details_label",$lang['details']);

$smarty->assign("country_label",$lang['country']);
$smarty->assign("country", get_user_country($profile['user_country_id']));

$smarty->assign("state_label",$lang['state']);
$smarty->assign("state", db_get_user_state($profile['user_state_id']));

$smarty->assign("city_label",$lang['city']);
$smarty->assign("city", $profile['user_city']);

$smarty->assign("ocupational_area",$lang_occupational_area[$profile['occupational_area']]);

$smarty->assign("gender_label", $lang['gender']); 
$smarty->assign("gender", $lang_gender[$profile['user_gender']]); 

$smarty->assign("race_label", $lang['race']); 
$smarty->assign("race", $lang_race[$profile['race']]); 

$smarty->assign("seeks_gender_label", $lang['seeks_gender']); 
$smarty->assign("seeks_gender", $lang_gender[$profile['user_seeks_gender']]); 

$smarty->assign("turn_ons_label", $lang['turn_ons']); 
$smarty->assign("turn_ons", $profile['turn_ons']); 

$smarty->assign("turn_offs_label", $lang['turn_offs']); 
$smarty->assign("turn_offs", $profile['turn_offs']); 

$smarty->assign("fav_music_label", $lang['fav_music']); 
$smarty->assign("fav_music", $profile['fav_music']);

$smarty->assign("relation_type_label", $lang['relationship_type']); 
$smarty->assign("relation_type", $lang_relation_type[$profile['relation_type']]); 

$smarty->assign("body_type_label", $lang['body_type']); 
if($profile['body_type'] > 0) $smarty->assign("body_type", $lang_body_type[$profile['body_type']]); 

$smarty->assign("height_label", $lang['height']); 
if($profile['height_cm'] > 0) $smarty->assign("height", $lang_height_cm[$profile['height_cm']]); 

$smarty->assign("marital_status_label", $lang['marital_status']); 
if($profile['marital_status'] > 0) $smarty->assign("marital_status", $lang_marital_status[$profile['marital_status']]); 

$smarty->assign("have_kids_label", $lang['children']); 
if($profile['have_kids'] > 0) $smarty->assign("have_kids", $lang_have_kids[$profile['have_kids']]); 

$smarty->assign("want_kids_label", $lang['want_children']); 
if($profile['want_kids'] > 0) $smarty->assign("want_kids", $lang_want_kids[$profile['want_kids']]); 

$smarty->assign("hair_label", $lang['hair']); 
if($profile['hair'] > 0) $smarty->assign("hair", $lang_hair_color[$profile['hair_color']]); 

$smarty->assign("eyes_label", $lang['eyes']); 
if($profile['eyes_color'] > 0) $smarty->assign("eyes", $lang_eyes_color[$profile['eyes_color']]); 

$smarty->assign("drink_habit_label", $lang['drink_habit']); 
if($profile['drink_habit'] > 0) $smarty->assign("drink_habit", $lang_drink_habit[$profile['drink_habit']]); 

$smarty->assign("smoke_habit_label", $lang['smoke_habit']); 
if($profile['smoke_habit'] > 0) $smarty->assign("smoke_habit", $lang_smoke_habit[$profile['smoke_habit']]);

$smarty->assign("income_label", $lang['income']); 
if($profile['income'] > 0) $smarty->assign("income", $lang_income[$profile['income']]);

$smarty->assign("education_label", $lang['education']); 
if($profile['education'] > 0) $smarty->assign("education", $lang_education[$profile['education']]);

$smarty->assign("exercise_freq_label", $lang['exercise_freq']); 
if($profile['exercise_freq'] > 0) $smarty->assign("exercise_freq", $lang_exercise_freq[$profile['exercise_freq']]);

//interests
$smarty->assign("last_reading_label", $lang['last_reading']); 
$smarty->assign("last_reading", $profile['last_reading']); 

$smarty->assign("religion_label", $lang['religion']); 
$smarty->assign("religion", $lang_religion[$profile['religion']]); 

$smarty->assign("lang_spoken_label", $lang['lang_spoken']); 
$langs = explode(',',$profile['lang_spoken']);
$sep = '';
foreach($langs as $lang_spoken) {
	$languages .= $sep.$lang_languages[$lang_spoken];
	$sep = ', ';
}
$smarty->assign("lang_spoken", $languages); 



// assign an associative array of data


//$stack = array("orange", "banana");
//array_push($stack, "apple", "raspberry");

$profile_array = array(
	//array('label' => $lang['country'], 'detail' => get_user_country($profile['user_country_id']).", ". get_user_state($profile['user_state_id'])),
	);
	
	if($profile['relationship_type'])
		array_push($profile_array,array('label' =>  $lang['relationship_type'], 'detail' => $lang_relation_type[$profile['relation_type']]));
	//array_push($profile_array,array('label' => $lang['gender'], 'detail' => $lang_gender[$profile['user_gender']]));
	//if($age)
		//array_push($profile_array,array('label' => $lang['age'], 'detail' => $age . " " .$lang['years_old']));
	if($profile['marital_status'])
		array_push($profile_array,array('label' => $lang['marital_status'], 'detail' => $lang_marital_status[$profile['marital_status']]));
	if($profile['height'])
		array_push($profile_array,array('label' => $lang['height'], 'detail' =>  $lang_height_cm[$profile['height_cm']]));
	if($profile['hair'])
		array_push($profile_array,array('label' => $lang['hair'], 'detail' =>  $lang_hair_color[$profile['hair_color']]));
	if($profile['eyes'])
		array_push($profile_array,array('label' => $lang['eyes'], 'detail' =>  $lang_eyes_color[$profile['eyes_color']]));
	if($profile['body_type'])
		array_push($profile_array,array('label' => $lang['body_type'], 'detail' => $lang_body_type[$profile['body_type']]));
	if($profile['race'])
		array_push($profile_array,array('label' => $lang['race'], 'detail' => $lang_race[$profile['race']]));
	array_push($profile_array,array('label' =>  $lang['seeks_gender'], 'detail' => $lang_gender[$profile['user_seeks_gender']]));
	if($profile['drink_habit'])
		array_push($profile_array,array('label' =>  $lang['drink_habit'], 'detail' => $lang_drink_habit[$profile['drink_habit']]));
	if($profile['smoke_habit'])
		array_push($profile_array,array('label' =>  $lang['smoke_habit'], 'detail' => $lang_smoke_habit[$profile['smoke_habit']]));
	if($profile['have_kids'])
		array_push($profile_array,array('label' =>   $lang['children'], 'detail' => $lang_have_kids[$profile['have_kids']]));
	if($profile['want_kids'])
		array_push($profile_array,array('label' =>  $lang['want_children'], 'detail' => $lang_want_kids[$profile['want_kids']]));
	if($profile['income'])
		array_push($profile_array,array('label' =>  $lang['income'], 'detail' => $lang_income[$profile['income']]));
	if($profile['education'])	
		array_push($profile_array,array('label' =>  $lang['education'], 'detail' => $lang_education[$profile['education']]));
	if($profile['exercise_freq'])
		array_push($profile_array,array('label' =>  $lang['exercise_freq'], 'detail' => $lang_exercise_freq[$profile['exercise_freq']]));
	if($profile['my_ideal_mate'] != "")
		array_push($profile_array,array('label' => $lang['describe_what_you_looking_for'], 'detail' => $profile['my_ideal_mate']));
	if($profile['turn_ons'] != "")	
		array_push($profile_array,array('label' => $lang['turn_ons'], 'detail' => $profile['turn_ons']));	
	if($profile['turn_offs'] != "")
		array_push($profile_array,array('label' => $lang['turn_offs'], 'detail' => $profile['turn_offs']));
	
	
	update_users_profile_views();

$smarty->assign('users', $profile_array);
$smarty->display('profile.html');
}


function update_users_profile_views(){
	global $profile;
	if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $profile[USER_ID_FIELD]) {
		$sql = 'SELECT upv_uid from '.USERS_PROFILE_VIEWS_TABLE.' WHERE upv_uid = '.$profile[USER_ID_FIELD]. ' and viewer_uid = '.$_SESSION['user_id'];
		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}
		if(mysql_num_rows($result) > 0){
			$viewer_uid = $_SESSION['user_id'];
			$sql = 'update '.USERS_PROFILE_VIEWS_TABLE.' set viewer_ip= \''.$_SERVER['REMOTE_ADDR'].'\', datetime=now()';
			$sql .= ' WHERE upv_uid = '.$profile[USER_ID_FIELD].' and viewer_uid = '.$_SESSION['user_id'];
			if ( !($result = mysql_query($sql)) ) {
				printf('Could not insert record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			}else{
				return 1;
			}
		}else{
			$viewer_uid = $_SESSION['user_id'];
			$sql = 'insert into '.USERS_PROFILE_VIEWS_TABLE.' (upv_uid,viewer_uid,viewer_ip,datetime) values('.$profile[USER_ID_FIELD].',';
			$sql .= ' '. $_SESSION['user_id'].', \''.$_SERVER['REMOTE_ADDR'].'\', now())';
			if ( !($result = mysql_query($sql)) ) {
				printf('Could not insert record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			}else{
				return 1;
			}
		}
	}
	return 0;

}


?>