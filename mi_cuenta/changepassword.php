<?php
include('../includes/config.php'); 
include('../includes/authlevel1.php');
session_start();

$error = 0;
if(!empty($_POST['change_password'])){       	change_password();}
else{									$content =  change_password_form(); }

	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js",'<link href="/forms01.css" rel="stylesheet" type="text/css" />');	
	$smarty->assign("title",LA_CHANGE_MY_PASSWORD);
	$smarty->assign("content",$content);
	$smarty->assign("my_home",LA_MY_HOME);
	$smarty->display('index.html');	
	
function change_password_form($username_value=''){
$form ='

<div style="background-color:#EEEEEE; border:#CCCCCC solid 1px; padding:10px;">
<form method="post">

<table border="0">
<tr><td align="right">
'.LA_USERNAME.' :
</td>
<td><strong>'.$_SESSION['login'].'<strong></td>
</tr>
<tr><td align="right">
'.LA_CURRENT_PASSWORD.': 
</td>
<td>
	<input type="text" maxlength="'.PASSWORD_MAX_CHARS.'" name="current_password" value="" size="'.PASSWORD_MAX_CHARS.'" />
</td>
</tr>
<tr><td align="right">
'.LA_NEW_PASSWORD.' :
</td>
<td>
	<input type="text" maxlength="'.PASSWORD_MAX_CHARS.'" name="new_password" size="'.PASSWORD_MAX_CHARS.'" />
</td>
</tr>
<tr>
<td>
'.LA_RETYPE_PASSWORD.' :
</td>
<td>
	<input type="text" maxlength="'.PASSWORD_MAX_CHARS.'" name="confirm_password" size="'.PASSWORD_MAX_CHARS.'" />
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right">
<input type="submit" name="change_password" value="'.LA_UPDATE.'" />
</td>
</tr>

</table>

  </div>

</form>
</div>';
return $form;
}	

function change_password(){
 global $content;
 global $dbhost, $dbuser, $dbpasswd, $dbname;
	$error = 0;

	if(	$_POST['new_password'] != $_POST['new_password'] ){
		$content .= '<div class="warningBlock">
			Por favor, Asegurate que tu contrase&ntilde;a nueva esta correcta.
			</div>';
		$content .= change_password_form();
		$error=1;
	}
	//alphanumeric check
	if (ereg('[^A-Za-z0-9]', $_POST['new_password'])){
		$content .= '<div class="warningBlock">
			Por favor, Asegurate que tu contrase&ntilde;a solo contiene letras y numeros.
			</div>';
		$content .= change_password_form();
		$error=1;
	}
	if( strlen($_POST['new_password']) > PASSWORD_MAX_CHARS || strlen($_POST['confirm_password']) < PASSWORD_MIN_CHARS){
		$content .= '<div class="warningBlock">
			Por favor, Asegurate que tu contrase&ntilde;a nueva es de entre '.PASSWORD_MIN_CHARS.' y '.PASSWORD_MAX_CHARS.' caracteres.
			</div>';
		$content .= change_password_form();
		$error=1;
	}
 
 	if($error != 1){
 		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		$sql = "SELECT user_id, user_username as username, user_password from ".SITE_USERS_TABLE." ";
		$sql .= "WHERE user_id = ".$_SESSION['user_id']." AND user_password = ".GetSQLValueString(md5($_POST['current_password']),'text').' limit 1';
		//$content = $sql;
		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}else{
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				$new_password = md5(trim($_POST['new_password']));
				
				//update users table with new password and modification date.
				$sql = "UPDATE ".SITE_USERS_TABLE." set user_password = ".GetSQLValueString($new_password,'text')
						." where user_id =".$row['user_id']." limit 1";
				if ( !($result = mysql_query($sql)) ) {
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
				//$content .= '<br>'.$sql;
				//update phpbb_users table
				$sql = "UPDATE ".PHPBB_USERS_TABLE." set user_password = ".GetSQLValueString($new_password,'text')
						." where user_id =".$row['user_id']." limit 1";
				if ( !($result = mysql_query($sql)) ) {
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
				//$content .= '<br>'.$sql;
				
				
				/*// email user the new password
				$mailTo = $row['user_email'];
				$from_email = 'AmigoCupido <ayuda@amigocupido.com>';
				$msgSubject = 'Tu Contrasena de AmigoCupido';
				$msgBody = 'Hola '. $row['username'].','."\n";
				//$msgBody .='La contrase&ntilde;a asociada de esta cuenta es:'."\n".$row['username']."\n";
				//$msgBody .= 'Esperamos que esto te ayude.'."\n";
				$msgBody .= 'Aqui esta tu nueva contrasena de AmigoCupido.'."\n";
				$msgBody .= $row['new_pass']."\n";
				$msgBody .= 'Una ves hayas entrado a tu cuenta puedes cambiar tu contrasena ';
				$msgBody .= 'presionando en el enlace que dice *cambiar contrasena*'."\n";
				$msgBody .= 'http://www.amigocupido.com/mi_cuenta/'."\n";
				$msgBody .= "\nEl Equipo de AmigoCupido.com";
				$xHeaders = "From: $from_email\nX-Mailer: PHP/" . phpversion();
				
				mail ($mailTo, $msgSubject, $msgBody, $xHeaders);*/ 
				
				
				// show message that operation was succesful and to check email for password
				
				$content .= '<div class="notifyBlock">Tu contrase&ntilde;a ha sido cambiada.</div>';
				//$content .= '<div>Contrase&ntilde;a: <strong>'.$row['new_pass'].'</strong><br>'."\n";
				//$content .= '(Tambien te hemos enviado tu Contrase&ntilde;a: a tu email)<br></div>';
				$content .= change_password_form();
				
 			}else{
				//let's tell them that either the password activation is expired or it's a bad activation code
				//print the form so that they can try again
				$content .= '<div class="warningBlock">
			Por favor, Asegurate que tu contrase&ntilde;a actual este correcta.
			</div>';
			$content .= change_password_form();

			}
		}
	}
}
?>