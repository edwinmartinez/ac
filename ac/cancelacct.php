<?php
include('../includes/config.php'); 
//include('../includes/authlevel1.php');
session_start(); 
if(!isset($_SESSION['user_id'])) {
	header('Location: ' . '/login/' );
	exit;
}

if(isset($_POST['cancelok']) && $_POST['cancelok'] != ''){

    if(!isset($_POST['user_id'])){
		$user_id = $_SESSION['user_id'];
	}else{
	    $user_id = $_POST['user_id'];
	}
	$user = new user_info($user_id);
	$user->cancel_account(); 
	/*$content = '<strong>'.LA_CANCEL_ACCOUNT.'</strong><br />
                    Tu cuenta ha sido cancelada. <br />
                     <br />';*/
	header("Location: ".$cfgHomeUrl."/logout.php");
	exit;
	
				
}else{
 	$content =  _form();
	
}

	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js",'<link href="/forms01.css" rel="stylesheet" type="text/css" />');	
	$smarty->assign("title",LA_CANCEL_ACCOUNT);
	$smarty->assign("content",$content);
	$smarty->display('index.html');	
	
function _form(){
//-----------------------------------------------------------------------------
global $PHP_SELF;
$form ='
<div style="background-color:#EEEEEE; border:#CCCCCC solid 1px; padding:10px;">
<strong>'.LA_CANCEL_ACCOUNT.'</strong><br />
'.LA_THANK_YOU_FOR_TRYING_OUR_SERVICE.' <br />
<br />


<table border="0">
<tr><td align="right">
&nbsp; 
</td>
<td colspan=2>
<form method="post">
<input name="cancelok" type="submit" value="'.LA_CANCEL_ACCOUNT.'" />
</form>
</td>
</tr>

</table>

  </div>


</div>';
return $form;
}	
?>