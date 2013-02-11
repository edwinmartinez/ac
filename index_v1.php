<?php 
include('includes/config.php');
require_once 'includes/db_settings.php';
require_once 'includes/mysql.class.php';
require_once 'includes/acgui.class.php';

session_start();

//let's see if ita a new reg or my page
/*if(isset($HTTP_SESSION_VARS['user_id'])) {
	header('Location: '.MEMBER_ACCOUNT_URL);
	exit;
}*/


$uri = $_SERVER['REQUEST_URI'];
list($uri,$datastream) = explode('?', $uri,2);

if ($uri == '/index.php' || $uri == '/index_v1.php' || $uri == '/' || $uri == '') {
	$acgui = new acgui();
	if(isset($_SESSION['user_id'])) {
			header('Location: ' . '/login/?redirect=/micuenta?p='.$_REQUEST['p']);
			exit;
	}
	$acgui->homePage();
} elseif ($uri == '/gente/' ){
	$acgui = new acgui();
	$acgui->printMembersPage($datastream);
	
//substr because the $uri may contain /xmlminiprofiles/?action=mini_profiles	
} elseif (substr($uri, 0,17) == '/xmlminiprofiles/'){  
		$acgui = new acgui();
		echo $acgui->xmlMiniProfiles($datastream);
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>AmigoCupido</h1>Page Not Found<br>Page '.$uri.' is not found</body></html>';
}




?>