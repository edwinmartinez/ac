<?php 
require_once('includes/config.php');
require_once("includes/mysql.class.php");
require_once('includes/class.paginator.php');
require_once('includes/smarty_setup.php');
require_once ('includes/acgui.class.php');
//require_once('admin_model.php');

session_start();

//let's see if ita a new reg or updating profile
//if(isset($HTTP_SESSION_VARS['user_id'])) {
//	header('Location: '.MEMBER_ACCOUNT_URL);
//	exit;
//}

$gui = new acgui();



	$smarty = new Smarty_su;
	$smarty->compile_check = true;
        $smarty->assign("countries_menu", $gui->getCountrySelectMenu(223));
	//$smarty->assign("js",$js);	
	//$smarty->assign("title","AmigoCupido.com - Buscar Pareja ,Latin Dating, Citas en Linea : Citas con hispanos solteros:");
	//$smarty->assign("content",'test');

	$smarty->display('homev2.tpl');	

?>