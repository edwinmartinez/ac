<?php
include('../includes/config.php'); 
ob_start();
session_start(); 
$error = 0;

if(isset($_POST['email']) && $_POST['email'] != ''){
	if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($_POST['email']))) {
		 $content .= '<div style="display:block; background-color:#FF0000; color:#fff; padding:5px; font-weight:bold;">No es un email valido</div>'."\n";
		 $error = 1;
	}
	if (!$_SESSION['CAPTCHAString'] == strtoupper($_POST['captchastring'])){
		$content .= '<div style="display:block; background-color:#FF0000; color:#fff; padding:5px; font-weight:bold;"> La respuesta a la Verificacion de caracteres no es correcta. Por favor trata otra vez.</div>';
		$content .= forgot_username_form($_POST['email']);
		$error = 1;
		
	}
	if(!$error){
		
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		$sql = "SELECT user_username as username, user_email from ".SITE_USERS_TABLE." where user_email = ".GetSQLValueString(trim($_POST['email']),'text') . ' LIMIT 1';
		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select country at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}else{
			$content .= '<div style="display:block; border:#00CC00 2px solid; padding: 5px;">
			Vas a recibir un email con el nombre del usuario asociado a este email si hay uno.
			</div>';
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				//$content .= '<br>'. $row['username'];
				
				$mailTo = $row['user_email'];
				$from_email = 'ayuda@amigocupido.com';
				$msgSubject = 'Tu Apodo en AmigoCupido.com';
				$msgBody = 'Hola '. $row['user_email'].','."\n";
				$msgBody .='El apodo asociado con este email es:'."\n".$row['username']."\n";
				$msgBody .= 'Esperamos que esto te ayude.'."\n\nEl Equipo de AmigoCupido.com";
				$xHeaders = "From: $from_email\nX-Mailer: PHP/" . phpversion();
				
				mail ($mailTo, $msgSubject, $msgBody, $xHeaders); 
				
				
			}else{
				//We didn't find any username associated with the email. 
			}
			$content .= forgot_username_form();
		}
	 }
}else{
 	$content =  forgot_username_form();
}

	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js",'<link href="/forms01.css" rel="stylesheet" type="text/css" />');	
	$smarty->assign("title","Olvide mi Apodo");
	$smarty->assign("content",$content);
	$smarty->display('index.html');	
	
function forgot_username_form($email_value=''){
$form ='
<div style="background-color:#EEEEEE; border:#CCCCCC solid 1px; padding:10px;">
<strong>Se te olvido tu apodo?</strong><br />
No hay problema! Solamente proveenos el email con el cual hiciste la cuenta
y la verificacion de caracteres que aparece y nosotros te manderemos tu apodo. <br />
<br />


<form method="post">
<table border="0">
<tr><td align="right">
'.LA_EMAIL.': 
</td>
<td colspan=2>
<input type="text" maxlength="50" name="email" value="'.$email_value.'" size="30">
</td>
</tr>
<tr>
<td align="right">
'.LA_VERIFICATION_CODE.':
		
</td>
<td width="100">
<input type="text" name="captchastring" size="10">
</td>
<td>
<img src="/includes/captcha/captcha.php" alt="CAPTCHA" />
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan=2>
<small>Escribe las letras y numeros que ves en la imagen de la derecha</small><br>
<input type="submit" value="'.LA_EMAIL_MY_USERNAME.'"></td>
</tr>
</table>

  </div>

</form>
</div>';
return $form;
}	
?>