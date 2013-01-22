<?php 
include('../../includes/config.php');
session_start();

display_main_page();

function display_main_page(){

	require (SMARTY_DIR.'Smarty.class.php');
	$smarty = new Smarty;
	$smarty->compile_check = true;
	
	$smarty->assign('content', "this is my content");
	$smarty->display('index.html');
}

?>