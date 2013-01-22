<?php


include('../includes/config.php'); 
//include('../includes/authlevel1.php');
session_start(); 
if(!isset($_SESSION['user_id'])) {
	header('Location: ' . '/login/?redirect=/micuenta?p='.$_REQUEST['p']);
	exit;
}

$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
$sql .= USER_ID_FIELD." = '" . $_SESSION['user_id'] . "'" ." limit 1";


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
		echo "User: the user with id=".$_SESSION['user_id']. " does not exist anymore";
		header("Location: ".$cfgHomeUrl."/logout.php");
	}else{
		$profile = mysql_fetch_assoc($result);				
		//display_profile();
	}
	mysql_free_result($result);
}

if($_REQUEST['p'] != 'photoup' && $_REQUEST['p'] != 'fo') {
	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	//menu items
	$smarty->assign("my_account_settings",$lang['my_account_settings']);
	$smarty->assign("my_profile",$lang['my_profile']);
	$smarty->assign("my_pictures",LA_MY_PICTURES);
	$smarty->assign("my_home",LA_MY_HOME);
}


if ($_REQUEST['p'] == 'ac')     	show_account();
elseif($_REQUEST['p'] == 'pr')  	show_profile();
elseif($_REQUEST['p'] == 'pv')		show_profile_views();
elseif($_REQUEST['p'] == 'fo')  	/*show_photos();*/ header('Location: '."/fotos/".get_username($_SESSION['user_id']).'/'); 
elseif($_REQUEST['p'] == 'fo-form') show_photo_upload_form();
elseif($_REQUEST['p'] == 'photoup')  	show_upload_photo();
else show_account();
	
function show_account(){
	global $lang, $profile, $smarty;
	
	$smarty->assign("acc_tab_class","On");
	
	$js = <<<EOD
<script language="JavaScript" type="text/JavaScript">
<!--
Event.observe(window, 'load', init, false)
	function init(){ 
		makeEditable('user_email','text');
	}

function confirmCancel()
   {
		var answer = confirm('Estas segur@ que quieres cancelar tu cuenta?')
		if (answer){
			alert('Gracias por probar nuestro servicio. Ahora cancelaremos tu cuenta.')
			window.location = '/ac/cancelacct.php';
		}
   }
//-->
</script>
EOD;
	

	
	$content = "\n".'
	
		<form enctype="multipart/form-data">	
	<div class="content">
		<div class="centered">
<!--				<h2>'.LA_BUDDY_ICON.' </h2>
			<div id="images">
				<div id="image"><img src="'. get_profile_photo($_SESSION['user_id']).'" /></div>
			</div>
				<a class="simulatedLink" id="change_link" href="#" onClick="Element.show(\'photo_fields\');Element.hide(\'change_link\'); return false;">'.LA_CHANGE.'</a><br />-->
			
			<!--<div id="photo_fields" style="display:none;">
				<div id="iframe">
				<iframe src="'.$cfgHomeUrl.ACCOUNT_DIR_URL.'/upload_iframe.php" frameborder="0"></iframe>
				</div>
			</div>-->
			
		</div>
	</div>
	</form>'."	
	
	
	<form><input id=\"user_id\" type=\"hidden\" value=\"".$_SESSION['user_id']."\" /></form>\n";
	$content .= '<span class="fieldname">'.$lang['username'] .":</span> <div id=\"username\" >". $profile['user_username'] . "</div><br>";
	$content .= '<span class="fieldname">'.$lang['email'] .":</span>  <div id=\"user_email\" class=\"editableArea\">". $profile['user_email'] . "</div>";
	$content .= "<div id=\"user_email_status\">(Clickea sobre el email para cambiarlo)</div><br>\n";
    $content .= <<<EOD
	 <span class="fieldname">Cancelar Cuenta:</span> <a href="javascript:void(0)" onClick="confirmCancel()">Cancelar mi cuenta</a>
	 <br />
	 <br />
EOD;
	
	$smarty->assign("js",$js);
	$smarty->assign("content",$content);
	$smarty->assign("title",$lang['my_account_settings']);
	$smarty->display('index.html');	
}
//-------------------------- Profile Edit ----------------------------------------------
function show_profile(){
	global $profile, $smarty, $dbname;
	global $lang,$lang_gender,$lang_relation_type,$lang_marital_status,$lang_race,$lang_religion,$lang_drink_habit,
	$lang_smoke_habit,$lang_have_kids,$lang_want_kids,$lang_income,$lang_education,$lang_exercise_freq,$lang_employment_status,
	$lang_occupational_area,$lang_height_cm,$lang_eyes_color,$lang_hair_color,$lang_body_type,$lang_languages;
	
	$birth_date_array = array();
	
	$birth_date_array = explode("-",$profile['user_birthdate']);
	$birth_year = $array[0];
	$birth_month = $birth_date_array[1];
	$birth_date = $birth_date_array[2];
	
	// similar to registrate.php function but different
	$top_countries = array("US","CA","ES","MX","GT","SV","HN","NI","CR","PA","DO","CU","CL","PR","PE","EC","VE","BO","UY","PY","AR","BR");
	
	mysql_select_db($dbname) or die(mysql_error());

	$sql = "SELECT * from countries order by countries_name_es asc";
	
	$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}

	while ($row = mysql_fetch_assoc($result)) {
	//echo $country .":". $row['countries_id']."<br>";
//		if($country == $row['countries_id']) {$selected = 'selected="selected"'; }
//		else { $selected = ""; }	
		if(in_array($row['countries_iso_code_2'],$top_countries)){
			$countries_menu_top .= sprintf("[%s,'%s'],",
				$row['countries_id'],$row['countries_name_es']);
		}
		$countries_menu_bot .= sprintf("[%s,'%s'],", $row['countries_id'], $row['countries_name_es']); 
		
		//$menu_div = "<option value=\"\">---------------</option>\n";
		$countries_menu = rtrim($countries_menu, ","); //remove the last comma
		$countries_menu = $countries_menu_top . $menu_div . $countries_menu_bot;
	} 
	mysql_free_result($result);
	
	//we need to create the ajsx menu here
			//we need to create the ajsx menu here
	foreach ($lang_race as $key=>$value) {
		$race_menu .= sprintf("[%s,'%s'],",$key,$value);
	}
	$race_menu = rtrim($race_menu, ","); //remove the last comma
	
	foreach ($lang_drink_habit as $key=>$value) {
		$drink_habit_menu .= sprintf("[%s,'%s'],",$key,$value);
	}
	$drink_habit_menu = rtrim($drink_habit_menu, ","); //remove the last comma
	
		//we need to create the ajsx menu here
	foreach ($lang_smoke_habit as $key=>$value) {
		$smoke_habit_menu .= sprintf("[%s,'%s'],",$key,$value);
	}
	$smoke_habit_menu = rtrim($smoke_habit_menu, ","); //remove the last comma
	
	//collection: [[1,'".$lang['man']."'],[2,'".$lang['woman']."'],[3,'".$lang['man_or_woman']."']],
	
	$smarty->assign("pr_tab_class","On");
	
	$content = "<script src=\"../js/scriptaculous/scriptaculous.js\" type=\"text/javascript\"></script>
  <script src=\"../js/scriptaculous/unittest.js\" type=\"text/javascript\"></script>";
	$content .= '
	<style>
	iframe {
	border-width: 0px;
	height: 52px;
	width: 600px;
	/*border: #CCCCCC solid 1px;*/
	}
	iframe.hidden {
		visibility: hidden;
		width:0px;
		height:0px;
	}
	</style>'."\n\n";	
	
	//$content .= rtrim($countries_menu, ","); //remove the last comma
	//$content .= 'uid:----------------------'.$_SESSION['user_id'];
	$content .= "Valores del Perfil de <strong>". $profile['user_username'] . "</strong><br />&nbsp;<br />\n";
	
	$content .= ''.$lang['buddy_icon'].' 
		<div id="images">
		<div id="image"><img src="'. get_profile_photo($_SESSION['user_id']).'" /></div>
		</div>
		
		<a class="simulatedLink" id="change_link" onClick="Element.show(\'photo_fields\');Element.hide(\'change_link\');">'.$lang['edit'].'</a><br />
	
		<div id="photo_fields" style="display:none;">
			<div id="iframe">
			<iframe src="'.$cfgHomeUrl.ACCOUNT_DIR_URL.'/upload_iframe.php" frameborder="0"></iframe>
			</div>
		</div>
		<br />
	';
	
	$content .= '<div class="section">'.$lang['personal_info'].'</div>'."\n";
	
	$content .= "
	<div class=\"editProfileItem\">
	<span class=\"label\">Nombre:</span><br />
	<span id=\"user_first_name\">".$profile['user_first_name']."</span> 
	&nbsp; <a id=\"user_first_nameEditControl\" href=\"#\">cambiar</a>
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">Apellido:</span><br />
	<span id=\"user_last_name\">".$profile['user_last_name']."</span> 
	&nbsp; <a id=\"user_last_nameEditControl\" href=\"#\">cambiar</a>
	</div>
	
		<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['email'].":</span><br />
	<span id=\"user_email\">".$profile['user_email']."</span> 
	&nbsp; <a id=\"user_emailEditControl\" href=\"#\">cambiar</a>
	</div>

			<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['birth_date'].":</span><br />
	<span id=\"birthDate\">".$birth_date."</span> 
	&nbsp; <a id=\"birth_dateEditControl\" href=\"#\">cambiar</a>
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['my_gender_is'].":</span><br />
	<span id=\"user_gender\">".$lang_gender[$profile['user_gender']]."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['seeks_gender'].":</span><br />
	<span id=\"user_seeks_gender\">".$lang_gender[$profile['user_seeks_gender']]."</span> 
	</div>		
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['country_of_residence'].":</span><br />
	<span id=\"user_country\">".db_get_user_country($profile['user_country_id'])."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['state'].":</span><br />
	<span id=\"user_state\">".db_get_user_state($profile['user_state_id'])."</span> 
	</div>		
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['city'].":</span><br />
	<span id=\"user_city\">". $profile['user_city']."</span> 
	</div>

	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['postal_code'].":</span><br />
	<span id=\"user_city\">". $profile['user_postal_code']."</span> 
	</div>
	
			
	";
	
	$content .= '<div class="section">'.$lang['basic_info'].'</div>'."\n";
	
	$content .= "<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['describe_personality'].":</span><br />
	<span id=\"about_me\">".$profile['about_me']."</span> 
	</div>

	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['describe_what_you_looking_for'].":</span><br />
	<span id=\"my_ideal_mate\">".$profile['my_ideal_mate']."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['turn_ons'].":</span><br />
	<span id=\"turn_ons\">".$profile['turn_ons']."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['turn_offs'].":</span><br />
	<span id=\"turn_offs\">".$profile['turn_offs']."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['relationship_type'].":</span><br />
	<span id=\"relation_type\">".$lang_relation_type[$profile['relation_type']]."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['marital_status'].":</span><br />
	<span id=\"marital_status\">".$lang_marital_status[$profile['marital_status']]."</span> 
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['race'].":</span><br />
	<span id=\"race\">".$lang_race[$profile['race']]."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['religion'].":</span><br />
	<span id=\"religion\">".$lang_religion[$profile['religion']]."</span> 
	</div>	
	
	";
	
	$content .= '<div class="section">'.$lang['lifestyle'].'</div>'.
	
	"
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['drink_habit'].":</span><br />
	<span id=\"drink_habit\">".$lang_drink_habit[$profile['drink_habit']]."</span> 	
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['smoke_habit'].":</span><br />
	<span id=\"smoke_habit\">".$lang_smoke_habit[$profile['smoke_habit']]."</span>
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['children'].":</span><br />
	<span id=\"smoke_habit\">".$lang_have_kids[$profile['have_kids']]."</span>
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['want_children'].":</span><br />
	<span id=\"smoke_habit\">".$lang_want_kids[$profile['want_kids']]."</span>
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['income'].":</span><br />
	<span id=\"smoke_habit\">".$lang_income[$profile['income']]."</span>
	</div>	
		
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['education'].":</span><br />
	<span id=\"education\">".$lang_education[$profile['education']]."</span> 
	</div>	
		
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['employment_status'].":</span><br />
	<span id=\"employment_status\">".$lang_employment_status[$profile['employment_status']]."</span> 
	</div>	
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['occupation'].":</span><br />
	<span id=\"employment_status\">".$lang_occupational_area[$profile['occupational_area']]."</span> 
	</div>
	
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['exercise_freq'].":</span><br />
	<span id=\"exercise_freq\">".$lang_exercise_freq[$profile['exercise_freq']]."</span> 
	</div>	
	
	";
	$content .= '<div class="section">'.$lang['interests'].'</div>'."\n";
	
	$content .= "
	<div class=\"editProfileItem\">
	<span class=\"label\">".$lang['last_reading'].":</span><br />
	<span id=\"last_reading\">".$profile['last_reading']."</span> 
	</div>	
	
	";
	
	$langs = explode(',',$profile['lang_spoken']);
	$sep = '';
//	foreach($langs as $lang_spoken) {
//		$languages .= $sep.$lang_languages[$lang_spoken];
//		$sep = '| ';
//	}

	
	$content .= '
	
	 <div class="editProfileItem">
	 <span class="label">'. $lang['lang_spoken'].'</span>
<table border="0">
<tr><td valign="top" width="250">
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="es" ';
	 if(in_array('es',$langs)) $content .= 'checked="checked"'; $content .= ' /> Espa�ol</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="en" ';
	if(in_array('en',$langs)) $content .= 'checked="checked"'; $content .= ' /> Ingl�s</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="de" ';
	if(in_array('de',$langs)) $content .= 'checked="checked"'; $content .= ' /> Alem�n</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ar" ';
	if(in_array('ar',$langs)) $content .= 'checked="checked"'; $content .= ' /> Arabe</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ca" ';
	if(in_array('ca',$langs)) $content .= 'checked="checked"'; $content .= ' /> Catalan</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="zh" ';
	if(in_array('zh',$langs)) $content .= 'checked="checked"'; $content .= ' /> Chino</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ko" ';
	if(in_array('ko',$langs)) $content .= 'checked="checked"'; $content .= ' /> Coreano</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="fi" ';
	if(in_array('fi',$langs)) $content .= 'checked="checked"'; $content .= ' /> Finland�s</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="fr" ';
	if(in_array('fr',$langs)) $content .= 'checked="checked"'; $content .= ' /> Franc�s</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="gl" ';
	if(in_array('gl',$langs)) $content .= 'checked="checked"'; $content .= ' /> Ga�lico</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="he" ';
	if(in_array('he',$langs)) $content .= 'checked="checked"'; $content .= ' /> Hebreo</div>
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="nl" ';
	if(in_array('nl',$langs)) $content .= 'checked="checked"'; $content .= ' /> Holand�s</div>		
</td>
<td valign="top" width="250">
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="hi" ';
	if(in_array('hi',$langs)) $content .= 'checked="checked"'; $content .= ' /> Ind�</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="it" ';
	if(in_array('it',$langs)) $content .= 'checked="checked"'; $content .= ' /> Italiano</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ja" ';
	if(in_array('ja',$langs)) $content .= 'checked="checked"'; $content .= ' /> Japon�s</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="no" ';
	if(in_array('no',$langs)) $content .= 'checked="checked"'; $content .= ' /> Noruego</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="pl" ';
	if(in_array('pl',$langs)) $content .= 'checked="checked"'; $content .= ' /> Polaco</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="pt" ';
	if(in_array('pt',$langs)) $content .= 'checked="checked"'; $content .= ' /> Portugu�s</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ru" ';
	if(in_array('ru',$langs)) $content .= 'checked="checked"'; $content .= ' /> Ruso</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="sv" ';
	if(in_array('sv',$langs)) $content .= 'checked="checked"'; $content .= ' /> Sueco</div>			
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="tg" ';
	if(in_array('tg',$langs)) $content .= 'checked="checked"'; $content .= ' /> Tagalog</div>		
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="tr" ';
	if(in_array('tr',$langs)) $content .= 'checked="checked"'; $content .= ' /> Turco</div>	
	<div><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ot" ';
	if(in_array('ot',$langs)) $content .= 'checked="checked"'; $content .= ' /> Otro</div>
	</td><td>&nbsp;
	</td></tr>
	</table>
	</div>';
	
	$content .= '<div class="section">'.$lang['physical_appearance'].'</div>'."\n";
	
	$content .= "<script>
new Ajax.InPlaceEditor($('user_first_name'), 'remote_edit.php', {
    externalControl: 'user_first_nameEditControl',
    ajaxOptions: {method: 'get'}, //override so we can use a static for the result
	cancelText: 'Cancelar',
	callback: function(form, value) { return 'item=123&field=description&myparam=' + escape(value) }
    });
	
new Ajax.InPlaceEditor($('user_last_name'), 'remote_edit.php', {
    externalControl: 'user_last_nameEditControl',
    ajaxOptions: {method: 'get'}, //override so we can use a static for the result
	cancelText: 'Cancelar',
	callback: function(form, value) { return 'item=123&field=description&myparam=' + escape(value) }
    });	


new Ajax.InPlaceEditor($('user_email'), 'remote_edit.php', {
    externalControl: 'user_emailEditControl',
    ajaxOptions: {method: 'get'}, //override so we can use a static for the result
	cancelText: 'Cancelar',
	callback: function(form, value) { return 'item=123&field=description&myparam=' + escape(value) }
    });	

//new Ajax.InPlaceCollectionEditor(
//  'birthDate', 'remote_edit.php', {
//  collection: [[0,'one'],[1,'two'],[2,'three']],
//  value: 0,
//  ajaxOptions: {method: 'get'} //override so we can use a static for the result
//});

new Ajax.InPlaceCollectionEditor(
  'user_gender', 'remote_edit.php', {
  collection: [[1,'".$lang['man']."'],[2,'".$lang['woman']."']],
  cancelText: 'Cancelar',
  value: ".$profile['user_gender']."
});

new Ajax.InPlaceCollectionEditor(
  'user_seeks_gender', 'remote_edit.php', {
  collection: [[1,'".$lang['man']."'],[2,'".$lang['woman']."'],[3,'".$lang['man_or_woman']."']],
  cancelText: 'Cancelar',
  value: ".$profile['user_seeks_gender']."
});


//new Ajax.InPlaceCollectionEditor(
//  'user_country', 'remote_edit.php?action=update_profile_item&item=user_country_id&uid=".$_SESSION['user_id']."', {
//  collection: [".$countries_menu."],
//  cancelText: 'Cancelar',
//  savingText: 'Guardando...',
//  value: ".$profile['user_country_id']."
//  //callback: function(form, value) { return 'action=update_profile&user_country_id=' + escape(value) },
//  //ajaxOptions: {method: 'get'} //override so we can use a static for the result
//});

new Ajax.InPlaceCollectionEditor(
  'race', 'remote_edit.php?action=update_profile_item&item=drink_habit&uid=".$_SESSION['user_id']."', {
  collection: [".$race_menu."],
  cancelText: 'Cancelar',
  savingText: 'Guardando...',
  value: ".$profile['race']."
});

new Ajax.InPlaceCollectionEditor(
  'drink_habit', 'remote_edit.php?action=update_profile_item&item=drink_habit&uid=".$_SESSION['user_id']."', {
  collection: [".$drink_habit_menu."],
  cancelText: 'Cancelar',
  savingText: 'Guardando...',
  value: ".$profile['drink_habit']."
});

new Ajax.InPlaceCollectionEditor(
  'smoke_habit', 'remote_edit.php?action=update_profile_item&item=smoke_habit&uid=".$_SESSION['user_id']."', {
  collection: [".$smoke_habit_menu."],
  cancelText: 'Cancelar',
  savingText: 'Guardando...',
  value: ".$profile['smoke_habit']."
});

new Ajax.InPlaceEditor($('about_me'), '_ajax_inplaceeditor_result.html', {
    rows:5,
    ajaxOptions: {method: 'get'} //override so we can use a static for the result
    });
</script>

";
	
	$smarty->assign("content",$content);
	$smarty->display('index.html');	
}

function show_profile_views(){
//-----------------------------------------------------------------------------
	global $lang, $profile, $smarty;
	
	$sql = "SELECT viewer_uid, UNIX_TIMESTAMP(datetime) as view_date from ".USERS_PROFILE_VIEWS_TABLE." v left join ".SITE_USERS_TABLE." u ";
	$sql .= " on v.viewer_uid = u.user_id ";
	$sql .= "where v.upv_uid=".(int)$_SESSION['user_id'];
	$sql .= " and  u.user_id is not NULL";
	$sql .= " order by v.datetime desc";
	$sql .= " limit 30";

	if ( !($result = mysql_query($sql)) ) { printf('Could not select records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
	if(mysql_num_rows($result) > 0 ){
		while($row = mysql_fetch_assoc($result)){
			$viewer_username = get_username($row['viewer_uid']);
			$time_ago =   timeDiff($row['view_date']);
			$viewers	.= '<div class="profileView"><div class="profileViewImg">'
						.'<a href="/perfil/'.$viewer_username.'"><img src="'.get_profile_photo($row['viewer_uid']).'" border="0" /></a></div>'
						.'<div class="profileViewLink"><a href="/perfil/'.$viewer_username.'">'.$viewer_username.'</a></div>'
						.'<div class="profileViewTime">'.$time_ago.'</div></div>'."\n";
		
		}
	}else{
		$viewers = "<div>".LA_THERE_ARE_NO_PROFILE_VIEWERS."</div>";
	}
	
	$content = "\n".'
	<div class="content">
		<div class="centered">

'.$viewers.'
		<div style="clear:both"></div>
		</div>
	</div>'."\n";
	
	
	$smarty->assign("content",$content);
	$smarty->assign("title",LA_PROFILE_VIEWS);
	$smarty->display('index.html');	
}

function timeDiff($timestamp,$detailed=false, $max_detail_levels=8, $precision_level='second'){
    $now = time();

    #If the difference is positive "ago" - negative "away"
    ($timestamp >= $now) ? $action = 'away' : $action = 'ago';
  
    # Set the periods of time
    //$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$periods = array("segundo","minuto","hora","dia","semana","mes","a&ntilde;o","decada");
    $lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);

    $diff = ($action == 'away' ? $timestamp - $now : $now - $timestamp);
  
    $prec_key = array_search($precision_level,$periods);
  
    # round diff to the precision_level
    $diff = round(($diff/$lengths[$prec_key]))*$lengths[$prec_key];
  
    # if the diff is very small, display for ex "just seconds ago"
    if ($diff <= 10) {
        $periodago = max(0,$prec_key-1);
        $agotxt = $periods[$periodago].'s';
        return "just $agotxt $action";
    }
  
    # Go from decades backwards to seconds
    $time = "";
    for ($i = (sizeof($lengths) - 1); $i>0; $i--) {
        if($diff > $lengths[$i-1] && ($max_detail_levels > 0)) {        # if the difference is greater than the length we are checking... continue
            $val = floor($diff / $lengths[$i-1]);    # 65 / 60 = 1.  That means one minute.  130 / 60 = 2. Two minutes.. etc
            $time .= $val ." ". $periods[$i-1].($val > 1 ? 's ' : ' ');  # The value, then the name associated, then add 's' if plural
            $diff -= ($val * $lengths[$i-1]);    # subtract the values we just used from the overall diff so we can find the rest of the information
            if(!$detailed) { $i = 0; }    # if detailed is turn off (default) only show the first set found, else show all information
            $max_detail_levels--;
        }
    }
 
    # Basic error checking.
    if($time == "") {
        return "Error-- Unable to calculate time.";
    } else {
		$timetxt = ($action == 'away' ? preg_replace("/%time%/",$time,LA_TIME_AWAY) : preg_replace("/%time%/",$time,LA_TIME_AGO));
        //return $time.$action;
		return $timetxt;
    }
}


function show_photos(){
//-------------------------- Photos ----------------------------------------------
	global $smarty;
		
	$sql = "SELECT * from ".USERS_GALLERY_TABLE." where ";
	$sql .= "photo_uid='".$_SESSION['user_id'] ."' ";
	//$sql .= "and  use_in_profile=0 "
	$sql .= "and privacy_level=0 ";
	$sql .= "ORDER BY uploaded_date desc";
	
	$result = mysql_query($sql);
	$pic_rows = mysql_num_rows($result);
	if($pic_rows > 0){
		$curimage = 0;
		while($pic_row = mysql_fetch_assoc($result)){
			$picname = $pic_row['photo_filename'];
			list($id,$basename,$ext) = split('[.-]',$picname);
			//check if medium size exists
			$basename = $id.'.'.$basename;
			$squarepic = $basename.'_sq.'.$ext;
			$medpic  = $basename.'_m.'.$ext;
			$smpic = $basename.'_s.'.$ext;
			$largestpic = $basename.'_l.'.$ext;
			$dirname = MEMBER_IMG_DIR_URL.'/'.$_SESSION['user_id'];
			
			//are we gonna display the medium picture?
			if (file_exists(MEMBER_IMG_DIR_PATH.'/'.$_SESSION['user_id'].'/'.$medpic)) {
				$displaypic = $medpic;
			}else{
				$displaypic = $largestpic;
			}
			if($curimage == 0){
				 				 
				$firstpicture = '<h2><div 
				onclick="editTitle('.$pic_row['photo_id'].');" 
				onmouseover="showAsEditable(\'pic_title_'.$pic_row['photo_id'].'\');"
				onmouseout="showAsEditable(\'pic_title_'.$pic_row['photo_id'].'\',true);"
				id="pic_title_'.$pic_row['photo_id'].'">'.$pic_row['photo_title'].'</div></h2>'."\n"
				                .'<img src="'.$dirname.'/'.$displaypic.'">';
								
				$firstpicture = "<img src=\"/images/loading.gif\" /> Espera un momento..";
			}
			
			$mygallery .= '<div class="myPicturesDisplayImg">'
						  .'<a href="#"  onClick="return modifyimage(\'mainPic\','.$curimage.'); return false;"><img src="'.$dirname.'/'.$squarepic.'" border="0"></a>'
						  .'</div>'."\n";	
			//$files[$curimage++] = $dirname.'/'.$file;
			$js_array .= 'dynimages['.$curimage++.']=["'.$pic_row['photo_id'].'", "'.$dirname.'/'.$displaypic.'", "'.$pic_row['photo_title'].'",""]'."\n";

		}
		
	}else{
		$mygallery = '&nbsp;';	
		$firstpicture = '<div class="noContentText"><center><strong>'.LA_YOU_DONT_HAVE_PHOTOS.'</center></strong></div>';
		$taghide = '<script language="javascript">obj = $(\'add_tag_link\'); Element.hide(obj);</script>';
	}

	
	$js = '
	<script language="javascript">
	var dynimages=new Array()
	'.$js_array.'
	</script>
	<script language="javascript" src="/js/tinyxmldom.js"></script>
	<script language="javascript" src="/js/gallery-myphotos-js.php"></script>
	<script language="javascript">
	Event.observe(window, \'load\', init, false);
	</script>
	';
	
	
	$content = '
	<style>
	iframe {
	border-width: 0px;
	height: 52px;
	width: 600px;
	/*border: #CCCCCC solid 1px;*/
	}
	iframe.hidden {
		visibility: hidden;
		width:0px;
		height:0px;
	}
	
	</style>
	
	<div class="content">
	<h2>'.LA_MY_PICTURES.'</h2>
		<div class="myPicturesFrame">
			<div class="myPicturesDisplayPic"  id="mainPic">
				'.$firstpicture.'			
			</div>
		
			<div id="myPicturesNarrowCol">
				<div class="myPicturesUploadLink"><a href="'.GALLERY_UPLOAD_URL.'">'.LA_UPLOAD_PICTURES.'</a></div>
				<div class="myPicturesThumbs">
					'.$mygallery.'
				</div>
				<div style="clear:both">&nbsp;</div>

				<form name="tagform" id="tagform"><input type="hidden" name="picid" id="picid" value=""></form>
				<div class="myPicturesTagsHead">'.LA_TAGS.'</div>
				<div class="myPicturesTags" id="tags_area"></div>
				<div class="myPicturesTagsHead" id="add_tag_link">
					<a href="#" onclick="showTagField(); return false;">'.LA_ADD_TAG.'</a>
				</div>
				'.$taghide.'
				<div class="smallTextGray" style="margin-top:20px;" id="tag_help">'.LA_ADD_TAG_HELP.'</div>
			</div>
		</div>	
	</div>
	
	<div style="clear:both">&nbsp;</div>
	
	
	

	';
	$content .= '<script type="text/javascript" language="javascript">'."\n";
	$content .= "// Event.observe('change_link', 'click', function(){Element.show('photo_fields');Element.hide('change_link')}, false);\n".'</script>'."\n";
	
	
	$smarty->assign("fo_tab_class","On");
	$smarty->assign("js",$js);
	$smarty->assign("content_wide",$content);
	$smarty->display('index.html');	
}

/*--------------------------------------foto area --------------------------*/

function show_photo_upload_form() {
	global $lang, $profile, $smarty;
	
	$foto_form = get_photo_form();
	
	$smarty->assign("fo_tab_class","On");
	$smarty->assign("content",$foto_form);
	$smarty->display('index.html');	
}

function get_photo_form() {
global $lang;

	if (!isset($number_of_fields)) $number_of_fields = 5;
  // Lets build the Image Uploading fields
		 for($counter=1; $counter <= $number_of_fields;$counter++) {
		   $photo_upload_fields .= <<<__HTML_END
		<div class="optional">
			<label>Foto {$counter}:</label>
		 	<input name="photo_filename[]" type="file" />
		</div>
		
__HTML_END;
	 }
	 
	 $content = ' 
<script type="text/javascript">
function continueform() {
	var f = document.form1;
	f.submit();
}

</script>';

	$content .= '
	<div class="content">
		<form action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data" name="form1">
	
		<div class="submit" style="line-height:30px;">
		
		 <input type="hidden" name="p" value="photoup" />
			<strong>'.LA_FIND_THE_IMAGES_ON_YOUR_COMPUTER.'</strong>
			'.$photo_upload_fields.'	
		   <br />    
			  <input type="button" class="buttons" value="'.LA_UPLOAD.' &raquo;" onClick="continueform()" />
		  </div>
		  
		  <br />
		  <div>
		  '.LA_OR.', <a href="'.MY_PICTURES_URL.'">'.LA_CANCEL_AND_RETURN_TO_YOUR_PHOTOS.'</a>
		  </div>
		  
		</form>
	</div>
	';
	
	return $content;
 
}



function show_upload_photo(){
/*---------------------------------------------------------------------------------------*/
	global $smarty;
	
	$foto_form = get_photo_form();
	$result = upload_photo();
	
	
	//----------------------------Let's see if we have a foto icon, if not let's select the earliest pic -----
	
	$sql2 = "SELECT photo_id,use_in_profile,uploaded_date from ".USERS_GALLERY_TABLE." where photo_uid=".$_SESSION['user_id'];
	$found_icon = 0;
	if ( !($result2 = mysql_query($sql2)) ){printf('Could not select records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql2);}


		$num_pics = mysql_num_rows($result2);
		if($num_pics > 0){ //------------ there are pictures uploaded but did any actually made it in?
			while($row2 = mysql_fetch_assoc($result2)){
				if($row2['use_in_profile'] == 1){
					$found_icon = 1;
					$icon_count++;
				}
			}
			if(!$found_icon){ 
				$sql3 = "UPDATE  ".USERS_GALLERY_TABLE." set use_in_profile=1 where photo_uid=".$_SESSION['user_id']." order by photo_id asc limit 1";
				if ( !($result2 = mysql_query($sql3)) ){printf('Could not update records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql3);}
			}
		}
	
	//----------------------------End of picture upload ----------------------------
	if(is_array($result)){
		if($result){
			foreach($result as $key => $resultrow) {
				if($resultrow[0] > 0) { //success
					$content .= $resultrow[1];
					$upload_error = 0;
				}else { 
					$upload_error =  "<p class=\"error\">".$resultrow[1]."</p>"; //failure
					}
			}
		}
	}else {
		$content = $result;
	}
	
	$content .= '<br>
	 <a href="'.MY_PICTURES_URL.'" >'.LA_RETURN_TO_MY_PICTURES.'</a>';
	$content = '<div class="content">'.$content.'</div>';
	
	if(!$upload_error) {
		header('Location: '.PHOTOS_URL.'/'.get_username($_SESSION['user_id']));
//		
//	require_once('../includes/smarty_setup.php');
//	//require (SMARTY_DIR.'Smarty.class.php');
//	//$smarty = new Smarty;
//	$smarty = new Smarty_su;
//	$smarty->compile_check = true;
//	
//	$smarty->assign("my_account_settings",$lang['my_account_settings'];
//	$smarty->assign("my_profile",$lang['my_profile']);
//	$smarty->assign("my_pictures",LA_MY_PICTURES);
//	$smarty->assign("my_home",LA_MY_HOME);
//	$smarty->assign("fo_tab_class","On");
//	$smarty->assign("content_wide",$content);
//	$smarty->display('index.html');			
		
	}else{
		echo $upload_error;
		show_photo_upload_form();
	}
	

	

}

function upload_photo() {
//-----------------------------------------------upload photo --------------------------------------------

	// initialization
	$result_final = "";
	$counter = 0;
	global $dbname, $dbhost, $dbuser, $dbpasswd, $lang, $upload_error;
	$images_dir = MEMBER_IMG_DIR_PATH.'/'.$_SESSION['user_id'];
	
	if (is_dir($images_dir)) { 
		//ok directory exists
		if (!is_writable($images_dir)) {
			chmod($images_dir,0666);
		}
	}else {
		mkdir($images_dir);
		chmod($images_dir,0777);
	}
	
	// List of our known photo types
	$known_photo_types = array( 
						'image/pjpeg' => 'jpg',
						'image/jpeg' => 'jpg',
						'image/gif' => 'gif',
						//'image/bmp' => 'bmp',
						'image/x-png' => 'png'
					);
	
	// GD Function List
	$gd_function_suffix = array( 
						'image/pjpeg' => 'JPEG',
						'image/jpeg' => 'JPEG',
						'image/gif' => 'GIF',
						//'image/bmp' => 'WBMP',
						'image/x-png' => 'PNG'
					);

	// Fetch the photo array sent by the form
	$photos_uploaded = $_FILES['photo_filename'];
	// Fetch the photo caption array
	$photo_caption = $_POST['photo_caption'];

	while( $counter <= count($photos_uploaded) ) {
		if($photos_uploaded['size'][$counter] > 0) {
			if(!array_key_exists($photos_uploaded['type'][$counter], $known_photo_types)){
				// we will return an array where the first item $result_final[0] will be the status of the operation
				$result_final .= $lang['file'] . " ".($counter+1). " " . $lang['is_not_a_photo'] . "<br />";
				$result[$counter] = array(0,$result_final);
				
			}
			else {
				
				//--------------- filenames -----------------------------
				$filetype = $photos_uploaded['type'][$counter];
				$extention = $known_photo_types[$filetype];
				$basefilename = $_SESSION['user_id'].".".gen_rand_string(time());
				$filename = $basefilename.".".$extention;
				$largefilename = $basefilename."_l.".$extention;
				$medfilename = $basefilename."_m.".$extention;
				$smfilename = $basefilename."_s.".$extention;
				$squarefilename = $basefilename."_sq.".$extention;
				$orig_name = $photos_uploaded['name'][$counter];
				
				//---------------filenames end -----------------------------
				
				// Store the orignal file with predefined maximun dimensions
				$width = IMG_MAX_WIDTH;
				$height = IMG_MAX_HEIGHT;
				
				list($width_orig, $height_orig) = getimagesize($photos_uploaded["tmp_name"][$counter]);
				
				// Build Thumbnail with GD 1.x.x, you can use the other described methods too
				$function_suffix = $gd_function_suffix[$filetype];
				$function_to_read = "ImageCreateFrom".$function_suffix;
				$function_to_write = "Image".$function_suffix;				
				
				if($width_orig > $height_orig){
					$iswide = 1;
				}else{
					$iswide = 0;
				}
				
				// is it bigger than our set max width and max height?
				// else keep the same dimention
				if($width_orig > $width || $height_orig > $height) {
					if ($width && $iswide==0) {
					  $width = ($height / $height_orig) * $width_orig;
					} else {
					  $height = ($width / $width_orig) * $height_orig;
					}
				} else {
					$width = $width_orig;
					$height = $height_orig;
				}
				
				
				$image_location = $images_dir."/".$largefilename;
				// Read the source file
				$source_handle = $function_to_read ($photos_uploaded['tmp_name'][$counter]); 
				
				//copy($photos_uploaded['tmp_name'][$counter], $image_location);
				
				
				if($source_handle){
					if(function_exists("imagecreatetruecolor") && $filetype != 'image/gif') {
						$new_dest_handle = imagecreatetruecolor($width,$height);
					}else{
						$new_dest_handle = imagecreate($width,$height);
					}
					if(function_exists("imagecopyresampled")){
					  imagecopyresampled($new_dest_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}else{
					  imagecopyresized  ($new_dest_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}
				}
				// Let's save the image
				$function_to_write( $new_dest_handle, $image_location);
				ImageDestroy($new_dest_handle);
				chmod($image_location, 0666);
				
				//check if the copied image exists, else let's skip writtin to the db because something happened
				

				if (file_exists($image_location)) {
 
					mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
					mysql_select_db($dbname);
					
					$title = substr_replace($orig_name,'',strlen($orig_name)-4);
					if ($title{strlen($title) -1} == ".")
						$title = substr_replace($orig_name,'',strlen($orig_name)-1);
					
					$sql = "INSERT INTO " . USERS_GALLERY_TABLE 
					." (photo_uid, photo_filename, photo_title, photo_category, orig_filename,use_in_profile)"
					." VALUES('".$_SESSION['user_id']."', '".addslashes($filename)."', '".$title."', 0, '".$orig_name."',0)" ;
					
					if ( !($res = mysql_query($sql)) ) {
						return 'Could not select username at line '.__LINE__ .'file: '. __FILE__.' <br> sql:'. $sql;
						//exit;
					} 
					
					$new_id = mysql_insert_id();
					
					
					
					
					/*------------------medium image -----------------------*/
					
					$width = IMG_MED_MAX_WIDTH;
					$height = IMG_MED_MAX_HEIGHT;
					$med_image_location = $images_dir."/".$medfilename;
					
					if($width_orig >= IMG_MED_MAX_WIDTH || $height_orig >= IMG_MED_MAX_HEIGHT){
						$ok_to_create_med = 1;
						//if is wide we just have to calculate the new height if its tall we calc the new width
						if($iswide == 1){  $height = ($width / $width_orig) * $height_orig;
						}else{             $width = ($height / $height_orig) * $width_orig;
						}
					}else {
						$ok_to_create_med = 0;
					}
					
					if(STORE_MED_IMAGE && $ok_to_create_med){	
						if($source_handle){
							if(function_exists("imagecreatetruecolor") && $filetype != 'image/gif') {
								$new_dest_handle = imagecreatetruecolor($width,$height);
							}else{
								$new_dest_handle = imagecreate($width,$height);
							}
							if(function_exists("imagecopyresampled")){
							  imagecopyresampled($new_dest_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
							}else{
							  imagecopyresized  ($new_dest_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
							}
						}
						// Let's save the image
						$function_to_write( $new_dest_handle, $med_image_location);
						ImageDestroy($new_dest_handle );
						chmod($med_image_location, 0666);
					}
					
					/*------------------small image -----------------------*/
					include_once('../includes/thumbnail.inc.php');
					
					if($iswide == 1 && $width_orig >= IMG_SM_MAX_WIDTH){
						$ok_to_create_sm = 1;
					}elseif($iswide == 0 && $height_orig >= IMG_SM_MAX_HEIGHT){
						$ok_to_create_sm = 1;
					}else {
						$ok_to_create_sm = 0;
					}
					
					if(STORE_SM_IMAGE && $ok_to_create_sm){
						$thumb = new Thumbnail;
						$thumb->quality = 80;
						$thumb->fileName = $image_location;
						$thumb->init();
						
						$thumb->percent = 0;
						if($iswide){
							$thumb->maxWidth = IMG_SM_MAX_WIDTH;
						}else{
							$thumb->maxHeight = IMG_SM_MAX_HEIGHT;
						}
						$thumb->resize();
						
		
						$thumb->save($images_dir."/".$smfilename);
						$thumb->destruct();
					}	
								
					/*------------------square image -----------------------*/
					
					//if its wide and its height is at least as tall as the square
					if($iswide == 1 && $height_orig >= SQUARE_MAX_SIZE){
						$bigger_than_sq = 1;
					//if its tall and its width is as wide as the square
					}elseif($iswide == 0 && $width_orig >= SQUARE_MAX_SIZE){
						$bigger_than_sq = 1;
					}else {
						$bigger_than_sq = 0;
					}
					
					if(STORE_SQUARE_IMAGE && $bigger_than_sq == 1){
						$thumb = new Thumbnail;
						$thumb->quality = 100;
						$thumb->fileName = $image_location;
						$thumb->init();
						$thumb->percent = 0;
						if($iswide){
							//is wide so make as tall as square_max_size
							$thumb->maxHeight = SQUARE_MAX_SIZE;
						}else{
							$thumb->maxWidth = SQUARE_MAX_SIZE;
						}
						//$thumb->maxHeight = SQUARE_MAX_SIZE;
						$thumb->resize();
						
						$thumb->cropSize = SQUARE_MAX_SIZE;
						$thumb->cropX = ($thumb->getCurrentWidth()/2)-($thumb->cropSize/2);
						$thumb->cropY = ($thumb->getCurrentHeight()/2)-($thumb->cropSize/2);
						$thumb->crop();
		
						$thumb->save($images_dir."/".$squarefilename);
						$thumb->destruct();
						
					}elseif(STORE_SQUARE_IMAGE && $bigger_than_sq == 0){ //the image is narrower than square size
						
						//$bg_image  = imagecreate(SQUARE_MAX_SIZE, SQUARE_MAX_SIZE);
						$bg_image  = imagecreatetruecolor(SQUARE_MAX_SIZE, SQUARE_MAX_SIZE);
						$bgcolor     = imagecolorallocate($bg_image, 255, 255, 255);					
						
						$sq_dest_x = SQUARE_MAX_SIZE/2 - $width_orig/2;
						$sq_dest_y = SQUARE_MAX_SIZE/2 - $height_orig/2;
						imagecopy($bg_image,$source_handle,$sq_dest_x,$sq_dest_y,0,0,$width_orig,$height_orig);
						imagefill($bg_image, 0, 0, $bgcolor);
						
						// Ok kids it time to draw a border
						// save that thing and we outta here
						$cColor=imagecolorallocate($bg_image,233,233,233);
						
							// first coodinates are in the first half of image
							$x0coordonate = 0;
							$y0coordonate = 0;
							// second coodinates are in the second half of image
							$x1coordonate = 0;
							$y1coordonate = SQUARE_MAX_SIZE;
							imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
							
							$x0coordonate = 0;
							$y0coordonate = SQUARE_MAX_SIZE-1;
							// second coodinates are in the second half of image
							$x1coordonate = SQUARE_MAX_SIZE-1;
							$y1coordonate = SQUARE_MAX_SIZE-1;
							imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
						
							$x0coordonate = SQUARE_MAX_SIZE-1;
							$y0coordonate = SQUARE_MAX_SIZE-1;
							// second coodinates are in the second half of image
							$x1coordonate = SQUARE_MAX_SIZE-1;
							$y1coordonate = 0;
							imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
							
							$x0coordonate = SQUARE_MAX_SIZE-1;
							$y0coordonate = 0;
							// second coodinates are in the second half of image
							$x1coordonate = 0;
							$y1coordonate = 0;
							imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );	
						
						$sqfile_loc = $images_dir."/".$squarefilename;
						switch ($extention) {
							case 'jpg': imagejpeg($bg_image,$sqfile_loc); break;
							case 'png':  imagepng($bg_image,$sqfile_loc);  break;
							case 'gif':  imagegif($bg_image,$sqfile_loc);  break;
							//default:     imagepng($img);  break;
						}
						imagedestroy($bg_image);
					}
					
					/*---------------for calculating a small image -----------------*/
					$thumbnail_width = THUMB_MAX_WIDTH;
					$thumbnail_height = THUMB_MAX_HEIGHT;	
	//							
	//				
	//				if ($thumbnail_width && ($width_orig < $height_orig)) {
	//				  $thumbnail_width = ($thumbnail_height / $height_orig) * $width_orig;
	//				} else {
	//				  $thumbnail_height = ($thumbnail_width / $width_orig) * $height_orig;
	//				}
					
					if($width_orig > SQUARE_MAX_SIZE && $height_orig > SQUARE_MAX_SIZE)
						$displayimg = $squarefilename;
					else $displayimg = $largefilename;
					
					$result_final = "
					<div style=\"width: 322px; margin-top:20px;\">
					<img src='". MEMBER_IMG_DIR_URL ."/".$_SESSION['user_id'].'/'.$displayimg."' />"
					.'</div><div>'.$lang['image']." ".($counter+1)." ".$lang['added_fem']."</div>"."\n";
					$result[$counter] = array(1,$result_final);
				} // end of if (file_exists($image_location))
			}
		}
	$counter++;
	}
	return $result;
}// end of upload photo


//may be used in future------------------------------
//is it a valid image resource?
function is_gd_handle($var) {
   ob_start();
       imagecolorallocate($var, 255, 255, 255);
       $error = ob_get_contents();
   ob_end_clean();
   if(preg_match('/not a valid Image resource/',$error)) {
       return false;
   } else {
       return true;
   }
}


?>