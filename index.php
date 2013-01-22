<?php 
require_once 'includes/config.php';
require_once 'includes/db_settings.php';
require_once 'includes/mysql.class.php';
require_once 'includes/acgui.class.php';
require_once 'includes/useraccess.class.php';

session_start();

//
//let's see if ita a new reg or my page
//if(isset($HTTP_SESSION_VARS['user_id'])) {
//	header('Location: '.MEMBER_ACCOUNT_URL);
//	exit;
//}

$uri = $_SERVER['REQUEST_URI'];
list($uri,$datastream) = explode('?', $uri,2);

if ($uri == '/index.php' || $uri == '/' || $uri == '') {
	$acgui = new acgui();
	$user = new userAccess();
	if (!$user->is_loaded())
		$acgui->homePage();
	else
		$acgui->printMemberHome();
} elseif ($uri == '/gente/' ){
	$acgui = new acgui();
	$acgui->printMembersPage($datastream);
} elseif ($uri == '/login/'){  //--------------------- login

	$acgui = new acgui();
	$acgui->fillRequest($datastream);
	
	$user = new userAccess();

	$user->checkIn();
		//var_dump($user->userData);
	
	
//substr because the $uri may contain /xmlminiprofiles/?action=mini_profiles	
} elseif ($uri == '/logout/'){
	$acgui = new acgui();
	$acgui->fillRequest($datastream);
	
	$user = new userAccess();
	$user->logout("/");
} elseif (substr($uri, 0,17) == '/xmlminiprofiles/'){  
		$acgui = new acgui();
		echo $acgui->xmlMiniProfiles($datastream);
} elseif ($uri == '/jsonfriends/'){
	$acgui = new acgui();
	$confirmed = $_POST['confirmed']; //confirmed options confirmed=only confirmed, unconfirmed=only non-confirmed and all=all
	echo $acgui->jsonFriends($_SESSION[SESSION_VARIABLE],'',$confirmed);
	
} elseif ($uri == '/jsonnewmessagecount/'){
	$acgui = new acgui();
	echo $acgui->getNewMessageCount($_SESSION[SESSION_VARIABLE]);
} elseif ($uri == '/jsonmessages/'){
	$acgui = new acgui();
	echo $acgui->getJsonMessages($_SESSION[SESSION_VARIABLE],$_POST['type']);
} elseif ($uri == '/msgs/'){
	$acgui = new acgui();
	echo $acgui->getMessages($_SESSION[SESSION_VARIABLE],'new');
}
else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>AmigoCupido</h1>Page Not Found<br>Page '.$uri.' is not found</body></html>';
}




?>
