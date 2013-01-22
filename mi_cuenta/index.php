<?php 
define('IN_PHPBB', true);
include('../includes/config.php'); 
include('../includes/authlevel1.php');

session_start(); 

require_once('../includes/smarty_setup.php');
$smarty = new Smarty_su;
$smarty->compile_check = true;

	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	$sql = "SELECT * from countries order by countries_name_es asc";
	//$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) { printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}

	$countries_menu_start = "<select name=\"country\" style=\"width:160px;\">\n<option value=\"\">--".$lang['country']."--</option>\n";
	while ($row = mysql_fetch_assoc($result)) {
		if($country == $row['countries_id']) {$selected = 'selected="selected"'; }
		else { $selected = ""; }	
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

	

//--------------------- my profile views -----------------------------

$sql = "SELECT * from ".USERS_PROFILE_VIEWS_TABLE." where upv_uid=".(int)$_SESSION['user_id'];
if ( !($result = mysql_query($sql)) ) { printf('Could not select records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
if(mysql_num_rows($result) > 0 ){
	$profile_views_link = '<a href="'.PROFILE_VIEWS_PAGE_URL.'">'.LA_VIEW_PROFILE_VIEWS.'</a>';
}
//---------------------end my profile views ---------------------------
//user_seeks_gender
$profile = get_profile_info($_SESSION['user_id']);
$seeks_gender = $profile['user_seeks_gender'];

$content = '

<div id="homeLeftCol">
	  <div class="leftBox">
          <h2>'.$lang['hello'].' '. $_SESSION['username'].'</h2>
        <div class="content">
          <img src="'.get_profile_photo($profile['user_id']).'" />
		  	';
			if(!empty($profile_views_link)) $content .= '<p>'.$profile_views_link.'</p>'."\n";
			$content .= ' 
            <p><a href="'.PROFILE_DIR_URL.'/'.strtolower($_SESSION['username']).'">'.$lang['see_my_profile'].'</a></p>
                <p><a href="/profiler.php" >'.$lang['edit_profile'].'</a></p>';
            /*<p><a href="/micuenta/?p=fo">'.$lang['add_and_change_photos'].'</a></p>*/
$content .= '			<p><a href="/fotos/'.$_SESSION['username'].'">'.$lang['add_and_change_photos'].'</a></p>
            <p><a href="/micuenta/">'.$lang['my_account_settings'].'</a></p>
			<p><a href="/mi_perfil_contrasena/">'.LA_CHANGE_MY_PASSWORD.'</a></p>
		    <p><a href="/logout">Log out</a></p>';

	$MessageCount = getNewMessages();
	$msg_notice = LA_MESSAGES;
	$msg_notice = '<div style="float: left; margin-left: 2px; margin-right: 4px; margin-bottom: 10px;"><img src="/images/icon_mail.gif" border="0" /></div> '
					.str_replace('%x%', $MessageCount, $msg_notice);
	$content .= '<a href="/foros/privmsg.php?folder=inbox">'.$msg_notice.'</a> (<a href="/mensajes/?folder=inbox">'.$MessageCount.'</a>)
	       </div>
	</div>
  <div id="favorites" class="leftBox">
  <h2>'.LA_MY_FAVORITES.'</h2>';
  $sqlfav = "SELECT * from ".FAVORITE_PEOPLE_TABLE." where user_uid = ".$_SESSION['user_id']." ORDER BY date desc";
	if (!($result = mysql_query($sqlfav))){ printf('Could not select users at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql1);
	} else {
		$total_rows = mysql_num_rows($result);
		if ($total_rows > 0) {

			$favs_count = $total_rows;
			for($i=0;$i<$total_rows;$i++){
				$row = mysql_fetch_assoc($result);
				$sql2 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$row['fav_uid']." limit 1";	
				$result2 = mysql_query($sql2) or die(mysql_error());
				$profile = mysql_fetch_assoc($result2);
				$profile['user_username'] = preg_replace('/�/','n',$profile['user_username']);
				$favs .= "\n".'  <div class="favorite" id="'.$row['ufp_id'].'_fav">
				<div style="float:left; display:inline"><img src="'.get_profile_photo($profile['user_id']).'" /></div>
				<div style="float:left; display:inline; width:11px; margin-left:2px;"><a href="#" onclick="return deleteFav('.$row['ufp_id'].','.$row['fav_uid'].');"><img src="/images/icon_delete.gif" alt="'.LA_DELETE.'" border="0"></a></div>
				<div style="clear:both;"></div>
				<a href="'.PROFILE_DIR_URL.'/'.strtolower($profile['user_username']).'">'.strtolower($profile['user_username']).'</a>
				';

				//$favs .= "<br>";
				//$favs .= ucwords(strtolower($profile['user_city']));
				//$favs .= ' <br>'.db_get_user_country($profile['user_country_id']);
				$favs .= "\n".'  </div>'."\n";
			}
		}else{ //you don't have buddies yet
			$favs = '<div style="width:100%;text-align:center;"><div style="margin:20px auto;color:#ccc;font-weight:bold;">'.LA_YOU_DONT_FAVORITES.'</div></div>';
		}
		mysql_free_result($result);      
	}	

	$content .= $favs;
  $content .= '</div>
  <div style="clear:both;">&nbsp;</div>

          <div class="leftBox">
		  	<h2>'.$lang['search'].'</h2>
            <div class="content">
             <form id="form1" name="form1" method="post" action="../gente/index.php">
                <input type="checkbox" name="checkbox2" value="checkbox" />'.$lang['woman'].'
                <input type="checkbox" name="checkbox3" value="checkbox" />'.$lang['man'].'

                <p>entre:<br />
                  <input name="textfield" type="text" size="2" />
                  y 
                  <input name="textfield2" type="text" size="2" />
                  Anos</p>
                <p>Paiz:<br />
                  '.$countries_menu.'
				  </p>
                <p>Ciudad:<br />
                  <input type="text" name="user_city" />
                  </p>
                <p>
                  <input type="checkbox" name="checkbox" value="checkbox" />
                  Solo perfiles que tienen fotos</p>
               <input type="submit" value="Buscar">
              </form>            
			</div>
		</div>

		</div> <!-- end of homeLeftCol -->
		<div id="homeRightCol">

        <table width="444" border="0" class="userTable">
          <tr valign="top">
            <td class="userCellTitle">'.$lang['the_url_of_my_profile'].'</td>
            </tr>
          <tr>
            <td class="profileUrlLink"><a href="'.PROFILE_DIR_URL.'/'.$_SESSION['username'].'">'.PROFILE_DIR_URL.'/'.$_SESSION['username'].'</a></td>
            </tr>
          </table>

        <table width="444" border="0" cellspacing="0" class="userTable">
          <tr valign="top">
            <td nowrap="nowrap" class="userCellTitle">'.$lang['new_members'].'</td>
            <td nowrap="nowrap" class="userCellEdit"><a href="/gente">'.$lang['search'].'</a> </td>
          </tr>
          <tr valign="top">
            <td colspan="2">';


	$new_users = get_users(6,$seeks_gender);
	$content .= $new_users;  
	$content .= '</td>
          </tr>
          <tr valign="top">
            <td colspan="2">&nbsp;</td>
          </tr>
          </table>


      <table width="444" border="0" cellspacing="0" class="userTable">
	    <tr valign="top">
          <td nowrap="nowrap" class="userCellTitle">'.LA_MY_FRIENDS.'</td>
            <td nowrap="nowrap" class="userCellEdit"><a href="#">Editar</a></td>
          </tr>';

		  $waiting_buddies = get_waiting_buddies();
		  if($waiting_buddies != '0'){
		  	$content .= '<tr><td colspan="2" style="border: #f00 solid 1px; width 410px;" align="center">
							'.$waiting_buddies.'</td></tr>';
		 }

		

        $content .= '<tr valign="top">
          <td colspan="2">';
		  
		   $sql1 = "SELECT * from ".BUDDIES_TABLE." where user_uid = ".$_SESSION['user_id']." ORDER BY date_added desc limit 10";
	if (!($result = mysql_query($sql1))){ printf('Could not select users at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql1);
	} else {
		$total_rows = mysql_num_rows($result);
		if ($total_rows > 0) {

			$buddies_count = $total_rows;
			$buddies_on_approval = 0;
			for($i=0;$i<$total_rows;$i++){
				$buddyrow = mysql_fetch_assoc($result);
				$sql2 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$buddyrow['buddy_uid']." limit 1";	
				//echo $sql2."<br>";
				$result2 = mysql_query($sql2) or die(mysql_error());
				$profile = mysql_fetch_assoc($result2);
				$profile['user_username'] = preg_replace('/�/','n',$profile['user_username']);
				$buddies .= '<div class="usersMiniProfile" id="'.$buddyrow['buddy_uid'].'_buddy">
				<div class="miniProfileBuddyOptions" id="deleteBuddyDiv" onclick="deleteBuddy('.$buddyrow['buddy_uid'].');"><img src="/images/icon_delete.gif" border="0" /></div>
				<div class="miniProfilePhoto"><a href="'.PROFILE_DIR_URL.'/'.$profile['user_username'].'"><img src="'.get_profile_photo($profile['user_id']).'" border="0" /></a></div>
				<a href="'.PROFILE_DIR_URL.'/'.$profile['user_username'].'">'.$profile['user_username']."</a>";
				if($buddyrow['confirmed'] != 1){
					// to do: check date of when the request was made
					$buddies .= '<img style="width:15px;height:15px;" src="/images/icon_waiting.gif" width="15" height="15" />';
					$buddies_on_approval++;
					$buddies_count--;
				}
				$buddies .= "<br>";
				$buddies .= ucwords(strtolower($profile['user_city'])).' <br>';
				$buddies .= db_get_user_country($profile['user_country_id']);
				$buddies .= ' </div>'."\n";
			}

		}else{ //you don't have buddies yet
			$buddies = '<div style="width:100%;text-align:center;"><div style="margin:20px auto;color:#ccc;font-weight:bold;">'.LA_YOU_DONT_HAVE_BUDDIES.'</div></div>';
		}
		mysql_free_result($result);      
	}	

	

	$content .= $buddies;
	$content .= '           </td>
          </tr>
        <tr valign="top">
          <td colspan="2">';
		if($buddies_on_approval>0) {
		 	$content .= '<img src="/images/icon_waiting.gif" width="15" height="15" /> = '.LA_BUDDY_PENDING_APPROVAL;
		}

     $content .= '</td>
	     </tr>
        </table></td>
    </div><!--end of homeRightCol-->
	<div style="clear:both;"></div>
	';

$smarty->assign("js",'<script language="javascript" src="../js/mystart-js.php"></script>');
$smarty->assign("head",'<link href="../styles/profile.css" rel="stylesheet" type="text/css" />');
$smarty->assign("title",$lang['hello'].' '. $_SESSION['username']);
$smarty->assign("content_wide",$content);
$smarty->display('index.html');	

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

function getNewMessages() {
	$CurrentTime		= time();
	$_SESSION['User']['Messages']	= array();
	$sql = "SELECT COUNT(*) as MessageCount 
	FROM " . PRIVMSGS_TABLE . " 
	WHERE privmsgs_to_userid = ". $_SESSION['user_id']."
	AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . ", " . PRIVMSGS_UNREAD_MAIL . ")";
	$result	= @mysql_query($sql);
	if($row = @mysql_fetch_array($result)) {
		$MessageCount	= $row['MessageCount'];
		//echo "messages: $MessageCount ";
		$_SESSION['User']['Messages']['Count']	= $MessageCount;
	}


	// Set the time messages were lst checked
	$_SESSION['User']['Messages']['Time']	= $CurrentTime;
	return $MessageCount;
}