<?php 
include('../includes/config.php'); 
require_once"../includes/db_settings.php";
require_once '../includes/mysql.class.php';
require_once '../includes/acgui.class.php';

//include('../includes/authlevel1.php');
session_start(); 


if(PEOPLE_SEARCH_REQUIRE_LOGIN == 1){
	if(!isset($HTTP_SESSION_VARS['user_id'])) {
		header('Location: ' . '/login/?redirect='.PEOPLE_SEARCH_URL);
		exit;
	}
}

if(@$_REQUEST['action'] == 'mini_profiles') {
	$acgui = new acgui();
	echo $acgui->xmlMiniProfiles();
} else {
	$acgui = new acgui();
	$acgui->printMembersPage();
}


?>