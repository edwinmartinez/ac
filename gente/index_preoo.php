<?php 
include('../includes/config.php'); 
//include('../includes/authlevel1.php');
session_start(); 

if(PEOPLE_SEARCH_REQUIRE_LOGIN == 1){
	if(!isset($HTTP_SESSION_VARS['user_id'])) {
		header('Location: ' . '/login/?redirect='.PEOPLE_SEARCH_URL);
		exit;
	}
}

if($_REQUEST['action'] == 'mini_profiles') {  mini_profiles(); }
else {                                        print_page();  }

	
		//lets get the picture	
function get_profile_pic($uid){
//--------------------------------------------------------------------------------------------------
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	//$uid = USER_ID_FIELD;
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$sql = "SELECT * from ".USERS_GALLERY_TABLE." where ";
	$sql .= "photo_uid='".$uid."' ";
	$sql .= "and  use_in_profile=1 limit 1";
	
	$profile_pic_result = mysql_query($sql);
	$profile_pic_rows = mysql_num_rows($profile_pic_result);
	if($profile_pic_rows){
		$profile_pic_row = mysql_fetch_assoc($profile_pic_result);
		$profile_pic = MEMBER_IMG_DIR_URL . "/tb_".$profile_pic_row['photo_filename'];
		
	}else{
		$user_gender = get_user_gender($uid);
		if($user_gender == 1) { $profile_pic = "/images/nofoto_m.jpg";   }
		else{                                   $profile_pic = "/images/nofoto_f.jpg";   }	
	}
	return $profile_pic;
}	
	



function get_user_gender($uid){
//--------------------------------------------------------------------------------------------------
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	if(!$uid) $uid = $HTTP_SESSION_VARS['user_id'];
	
	$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
	$sql .= USER_ID_FIELD." = '" . $uid . "'"
				   ." limit 1";
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	if ( !($result = mysql_query($sql)) ) {
		printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$total_rows = mysql_num_rows($result);
		if ($total_rows < 1) {
			user_does_not_exist();
		}else{
			$profile = mysql_fetch_assoc($result);
			//display_profile();
			
		}
		mysql_free_result($result);
		return $profile['user_gender'];
	}

}
	
function mini_profiles(){
//--------------------------------------------------------------------------------------------------
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	global $lang,$profile;
	
	//Build the sql statement
	
	if (!empty($_REQUEST['min_age'])) { 
		$min_bday = date('Y-m-d',mktime(0, 0, 0, date("m"),  date("d")-1,  date("Y")-($_REQUEST['min_age'])));
	}	
	if (!empty($_REQUEST['max_age'])) { 
		$max_bday = date('Y-m-d',mktime(0, 0, 0, date("m"),  date("d")-1,  date("Y")-($_REQUEST['max_age']+1)));
	}
//	list($year,$month,$day) = explode("-",$profile['user_birthdate']);
//	$age = date("Y") - $year;
//	if(date("md") < $month.$day) { $age--; }
	
	//define the results per page
	if(!empty($_GET['rpp'])) {
		$rpp = $_GET['rpp'];
	}else{
		$rpp = PEOPLE_SEARCH_RPP;
	}
	
	$sqlstart  = "SELECT * from ".SITE_USERS_TABLE." ";
	$sqlcount = "SELECT count(*) as total_count from ".SITE_USERS_TABLE." ";
	if (!empty($_GET['photo_only'])) { 
		$sql = "join ".USERS_GALLERY_TABLE." ";
	}
	if (!empty($_REQUEST['user_country_id'])) { 
		$sql .= "WHERE  user_country_id=".$_REQUEST['user_country_id']." "; 
		$use_and = 1;
	}
	if(!(!empty($_GET['m']) && !empty($_GET['f']))){
		if (!empty($_GET['m'])) {
			$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
			$sql .= $and."user_gender=1 ";
			$use_and = 1;
		}
		if (!empty($_GET['f'])) {
			$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
			$sql .= $and."user_gender=2 ";
			$use_and = 1;
		}
	}
	if (!empty($_REQUEST['min_age'])) {
		$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
		$sql .= $and." user_birthdate < '".$min_bday."'";
		$use_and = 1;		
	}
	if (!empty($_REQUEST['max_age'])) {
		$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
		$sql .= $and." user_birthdate > '".$max_bday."'";
		$use_and = 1;		
	}
	
	if (!empty($_GET['photo_only'])) { 
		$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
		$sql .= $and."photo_uid=user_id and use_in_profile=1 ";
		$use_and = 1;
	}
	
	if (!empty($_REQUEST['username'])) { 
		$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
		$sql .= $and."user_username LIKE '%".$_REQUEST['username']."%' ";
		$use_and = 1;
	}
	
	if (!empty($_REQUEST['user_city'])) { 
		$and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
		$sql .= $and."user_city LIKE '%".$_REQUEST['user_city']."%' ";
		$use_and = 1;
	}
	
	//mod for cancelled accounts
	    $and = ( $use_and == 1 ) ? 'AND ' : 'WHERE ';
		$sql .= $and."status = 1 ";
		$use_and = 1;
	
	$sqlorderby = " ORDER BY ".USER_LAST_LOGIN_FIELD." desc";
	$sqlend .= " limit ".$rpp;
	
	if(!empty($_REQUEST['p'])) {
		$sqlend .= " OFFSET ".(($_REQUEST['p']-1) * $rpp);
	}
	$sqlcount = $sqlcount . $sql;
	$sql = $sqlstart . $sql . $sqlorderby . $sqlend;
	
	
	header('Content-type: text/xml');
	$xml = '<?xml version="1.0" ?>'."\n";
	$xml .= "<xml>\n";
	//$xml .= "<statement>".'mnb'.$min_bday.'mxb'.$max_bday.":".$sql."</statement>\n";
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
		die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	if ( !($countresult = mysql_query($sqlcount)) ) {
		printf('Could do a selectcount at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$count_rows = mysql_num_rows($countresult);
		$countrow = mysql_fetch_assoc($countresult);
		$totalcount = $countrow['total_count']; 
	}
	mysql_free_result($countresult);
	
	if ( !($result = mysql_query($sql)) ) {
		printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$total_rows = mysql_num_rows($result);
		//do we have a row with that username?
		if ($total_rows < 1) {
		//There are no results for this search
			$xml .= "<status>2</status>\n";
			$xml .= "<users>".$sql."\n";
			
			//header("Location: ".$cfgHomeUrl."/logout.php");
		}else{
			$xml .= "<status>1</status>\n";
			$xml .= "<totalcount>".$totalcount."</totalcount>\n";
			$xml .= "<users>\n";
			for($i=0;$i<$total_rows;$i++){
				$profile = mysql_fetch_assoc($result);
				$profile['user_username'] = preg_replace('/ñ/','n',$profile['user_username']);

				$xml .= '    <user ';
				$xml .= 'user_id="'.$profile['user_id'].'" ';
				$xml .= 'user_username="'.htmlentities($profile['user_username']).'" ';
				$xml .= 'country="'.get_user_country($profile['user_country_id']).'" ';
				$xml .= 'user_city="'.urlencode(ucwords(strtolower($profile['user_city']))).'" ';
				$xml .= 'photo="'.get_profile_photo($profile['user_id']).'" ';
				$xml .= ' />'."\n";
			}			
			
		}
		mysql_free_result($result);
	}
	$xml .= "</users>\n";
	//$xml .= "<sql>".$sql."</sql>\n";
	$xml .= "</xml>";
	echo $xml;
	//echo "\n".$sql."\n".$sqlcount;
}


function print_page(){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	global $lang, $top_countries;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	$sql = "SELECT * from countries order by countries_name_es asc";
	$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) { printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
	
	$countries_menu_start = "<select id=\"country\" name=\"country\" style=\"width:160px;\">\n<option value=\"\">--".$lang['all_countries']."--</option>\n";
	while ($row = mysql_fetch_assoc($result)) {
		if(isset($_COOKIE['country'])){
			if($_COOKIE['country'] == $row['countries_id']) {$selected = 'selected="selected"'; }
			else { $selected = ""; }	
		}else { $selected = ""; }	
		if(in_array($row['countries_iso_code_2'],$top_countries)){
			$countries_menu_top .= sprintf("<option value=\"%s\">%s</option>\n",
				$row['countries_id'],$row['countries_name_es']);
		}
		$countries_menu_bot .= sprintf("<option %s value=\"%s\">%s</option> \n", $selected,$row['countries_id'], $row['countries_name_es']);  
		
		$menu_div = "<option value=\"\">---------------</option>\n";
		$countries_menu = $countries_menu_start . $countries_menu_top . $menu_div . $countries_menu_bot;
	} 
	$countries_menu .= "</select>\n";
	mysql_free_result($result);
	
	//--------------------------------------------------------------
	
	require (SMARTY_DIR.'Smarty.class.php');

	$smarty = new Smarty;
	$smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/';
	$smarty->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/templates_c/';
	$smarty->caching = false;
	$smarty->compile_check = true;
	
	$smarty->assign("title", $lang['people_search']);
	
	$top_loads = <<<EOF

	<script language="javascript" src="../js/prototype.js"></script>
	<script src="../js/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	<script language="javascript" src="../js/people_search-js.php"></script>
	<link href="../styles/profile.css" rel="stylesheet" type="text/css" />

EOF;

	$smarty->assign("js_top_load",$top_loads);
	
	$content = '

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td width="180">
	 <div id="searchBox" class="leftBox">
            <h2>'. $lang['people_search'].'<a href="#"></a></h2>
			
		<div class="leftBoxSearchContent">
			<form id="form1" name="form1" method="post">
			<input type="hidden" id="p" value="1" />
			<div class="searchItem">
				<strong>'.$lang['gender'].':</strong><br />
                <input id="f" name="f" type="checkbox" value="1" 
				';
				
	if ($_COOKIE['f'] == 1 || !isset($_COOKIE['f'])) $content .= 'checked="checked"';
	$content .=' />
                '.$lang['woman'].' 
                           
                <input id="m" name="m" type="checkbox" value="1" ';
	if ($_COOKIE['m'] == 1 || !isset($_COOKIE['m'])) $content .= 'checked="checked"';
	$content .= ' />'. $lang['man'].'
			</div>
			
			<div class="searchItem"> 
				<strong>'.$lang['age'].':</strong><br />';
				
				
				if (!empty($_REQUEST['min_age'])){ $min_age = $_REQUEST['min_age']; }
				else{ $min_age = SEARCH_MIN_AGE_DEFAULT;}
				if (!empty($_REQUEST['max_age'])){ $max_age = $_REQUEST['max_age']; }
				else{ $max_age = SEARCH_MAX_AGE_DEFAULT;}
				
	$content .= $lang['between'].' <strong><span id="min_age_txt">'.$min_age.'</span></strong> '.$lang['and']; 
	$content .= ' <strong><span id="max_age_txt">'.$max_age.'</span></strong> ';
	$content .= '
                <input id="min_age" name="min_age" type="hidden" value="'.$min_age.'" />
			  	<input id="max_age" name="max_age" type="hidden" value="'.$max_age.'" />
				 
              Anos
		  
				<div id="track6" style="width:200px;background-color:#aaa;height:5px;position:relative; margin-top:6px; margin-bottom:14px">
					<div id="handle6-1" style="position:absolute;top:0;left:0;width:11px;height:18px;background-image:url(../images/horizontal_handle.gif); background-position:0px -3px;"> </div>
					<div id="handle6-2" style="position:absolute;top:0;left:0;width:11px;height:18px;background-image:url(../images/horizontal_handle.gif); background-position:0px -3px;"> </div>
				</div>
			</div>
			
			<div class="searchItem">
				<strong>'.$lang['country'].'</strong><br />
					'. $countries_menu.'
			</div>
	
			<div class="searchItem">
					<input id="photo_only" type="checkbox" name="photo_only" value="1" ';
					 if ($_COOKIE['ponly'] == 1) $content .= 'checked="checked"';
					 
	$content .= '
				 />
				'. LA_ONLY_PROFILES_WITH_PHOTOS.'<br />
			</div>
			
			<div class="searchItem">	
				  '. LA_RESULTS_PER_PAGE.'
				  <select id="rpp" name="rpp">
				  
				 <script language="javascript"> 
				 if (get_cookie("rpp")!="" && get_cookie("rpp")== 20) 
				 	document.write(\'<option value="20" selected="selected" >20</option>\');
				else document.write(\'<option value="20" >20</option>\');
				if (get_cookie("rpp")!="" && get_cookie("rpp")== 40) 
				 	document.write(\'<option value="40" selected="selected" >40</option>\');
				else document.write(\'<option value="40" >40</option>\');	
				if (get_cookie("rpp")!="" && get_cookie("rpp")== 60) 
				 	document.write(\'<option value="60" selected="selected" >60</option>\');
				else document.write(\'<option value="60" >60</option>\');
				</script>
				</select><br />
			</div>
	
				</form>
		</div>
		
		<h2>'. LA_BY_CITY.'</h2>
		<div class="leftBoxSearchContent" style="text-align:center;">    
			<div><input id="user_city" name="user_city" type="text"  /></div>
			<div>
			<input id="search_button" type="button" name="Submit" value="'. $lang['search'].'" />
			</div>
		</div>

		
		<h2>'.LA_BY_USERNAME.'</h2>
		<div class="leftBoxSearchContent" style="text-align:center;">    
			<div><input id="user_username" name="user_username" type="text"  /></div>
			<div align="center">
				<input id="search_username_button" type="button" name="Submit" value="'. $lang['search'].'" />
			</div>		
		</div>

	</div>	
			
	</td>
	
    <td width="6"><img src="../images/spacer.gif" width="6" height="10" /></td>
	
    <td>
	<table width="476" border="0" cellspacing="0" class="userTable">
        <tr valign="top">
          <td nowrap="nowrap" class="userCellTitle">'. LA_OUR_MEMBERS.'!</td>
          <td nowrap="nowrap" class="userCellEdit" id="status">&nbsp;</td>
        </tr>
        <tr valign="top">
          <td colspan="2" id="usersCell">
              <div style="width:100%;text-align:center;margin-top:20px;"><img src="../images/icon_spin_32.gif" /></div>
         </td>
        </tr>
        <tr valign="top">
          <td colspan="2">&nbsp;<div id="statusBottom">Area Status</div></td>
        </tr>
      </table></td>
  </tr>
  <tr valign="top">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</div>
';
//if(isset($_COOKIE['min_age'])) echo $_COOKIE['min_age']; else echo $min_age; 
if(isset($_COOKIE['min_age'])) $min_age = $_COOKIE['min_age']; else $min_age = $min_age;
if(isset($_COOKIE['max_age'])) $max_age = $_COOKIE['max_age']; else $max_age = $max_age;

$content .= <<<EOF

		<script type="text/javascript" language="javascript">
	var f = document.form1;
	var theValues = new Array();
	for(var i=18;i<=89;i++){
		theValues.push(i);
	}
	var slider6 = new Control.Slider(['handle6-1','handle6-2'],'track6',{
			
			sliderValue:[
				'$min_age',
				'$max_age'],
			range:\$R(18,89),
			values:theValues,
			restricted:true,
			handleImage:['/images/horizontal_handle.gif','/images/horizontal_handle.gif'],
			onSlide:function(v){
				//$('debug6').innerHTML='slide: '+v.inspect();  
				slide_update(v);
			},
			onChange:function(v){
				//$('debug6').innerHTML='changed! '+v.inspect();
				show_page(1);
			}
		});
		
	function slide_update(v){
		var temp = new Array();
		var numbers = v.toString();
		temp = numbers.split(',');
		f.min_age.value = temp[0];
		f.max_age.value = temp[1];
		$('min_age_txt').innerHTML= temp[0];
		$('max_age_txt').innerHTML= temp[1];
	}
	</script>

EOF;

	$smarty->assign("content_wide",$content);
	$smarty->display('index.html');
} 

?>