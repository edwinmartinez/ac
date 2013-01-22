<?php
include('../includes/config.php'); 
ob_start();
session_start(); 
$error = 0;
$username = $_POST['username'];
if(!empty($_GET['activation'])){       password_activation();
}elseif(!empty($_POST['username'])){
	if (strstr("'", $username) || strstr(' ',$username)
		|| strlen($username) < USERNAME_MIN_CHARS 
		|| strlen($username) > USERNAME_MAX_CHARS) {
		 $content .= '<div style="display:block; background-color:#FF0000; color:#fff; padding:5px; font-weight:bold;">
		 No parece ser un apodo valido</div>'."\n";
		 $error = 1;
	}
	if (!$_SESSION['CAPTCHAString'] == strtoupper($_POST['captchastring'])){
		$content .= '<div style="display:block; background-color:#FF0000; color:#fff; padding:5px; font-weight:bold;"> La respuesta a la Verificacion de caracteres no es correcta. Por favor trata otra vez.</div>';
		$content .= forgot_password_form($_POST['email']);
		$error = 1;
		
	}
	if(!$error){
		mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		$sql = "SELECT user_id, user_username as username, user_email from ".SITE_USERS_TABLE." where user_username = ".GetSQLValueString(trim($username),'text') . ' LIMIT 1';
		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}else{
			
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				
				$activation_code = md5($row['username'].rand(1,999));
				$new_pass = substr($activation_code,0,6);
				$activation_link = PASSWORD_ACTIVATION_BASE_URL.$activation_code;
				
			$content .= '<div class="notifyBlock">
			Dentro de unos segundos vas a recibir un email con el codigo para la activacion de una nueva contrase&ntilde;a para este apodo.
			</div>';
			
				
				$sql = "INSERT INTO ".USER_PASS_RESET_TABLE." (user_id,new_pass,pass_activation) values ";
				$sql .= " (".$row['user_id'].",'$new_pass','$activation_code')";
				if ( !($result = mysql_query($sql)) ) {
					printf('Could not insert record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
				
				//$content .= '<br>'. $row['username'];
				
				$mailTo = $row['user_email'];
				$from_email = 'AmigoCupido <ayuda@amigocupido.com>';
				//$msgSubject = 'Tu Contrase&ntilde;a de AmigoCupido.com';
				$msgSubject = 'Tu Activacion de Contrasena';
				$msgBody = 'Hola '. $row['username'].','."\n";
				//$msgBody .='La contrase&ntilde;a asociada de esta cuenta es:'."\n".$row['username']."\n";
				//$msgBody .= 'Esperamos que esto te ayude.'."\n";
				$msgBody .= 'Por favor presiona en el siguiente enlace (link) para activar una nueva contrasena.'."\n";
				$msgBody .= 'Cuando presionas este enlace, apruevas la activacion de una nueva contrasena que por siguiente se te mandara a este correo.'."\n";
				$msgBody .= $activation_link."\n";
				
				
				$msgBody .= "\nEl Equipo de AmigoCupido.com";
				$xHeaders = "From: $from_email\nX-Mailer: PHP/" . phpversion();
				
				mail ($mailTo, $msgSubject, $msgBody, $xHeaders); 
				
				
			}else{
				//We didn't find any username associated with the email. 
				$content .= '<div class="warningBlock">
			Disculpanos pero parece ser que no existe ningurn usuario con ese apodo.
			</div>';
			}
			$content .= forgot_password_form();
		}
	 }
}else{
 	$content =  forgot_password_form();
}

	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js",'<link href="/forms01.css" rel="stylesheet" type="text/css" />');	
	$smarty->assign("title","Olvide mi Contrase&ntilde;a");
	$smarty->assign("content",$content);
	$smarty->display('index.html');	
	
function forgot_password_form($username_value=''){
$form ='
<div style="background-color:#EEEEEE; border:#CCCCCC solid 1px; padding:10px;">
<form method="post">
<table border="0">
<tr><td align="right">
'.LA_USERNAME.': 
</td>
<td colspan=2>
<input type="text" maxlength="50" name="username" value="'.$username_value.'" size="30">
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
<input type="submit" value="'.LA_EMAIL_MY_PASSWORD.'"></td>
</tr>
</table>

  </div>

</form>
</div>';
return $form;
}	

function password_activation(){
 global $content;
 global $dbhost, $dbuser, $dbpasswd, $dbname;

 
 mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
		$sql = "SELECT u.user_username as username, ac.* from ".USER_PASS_RESET_TABLE." ac, ".SITE_USERS_TABLE." u ";
		$sql .= "WHERE u.user_id = ac.user_id AND pass_activation = ".GetSQLValueString(trim($_GET['activation']),'text') . ' and activation_date = \'0000-00-00 00:00:00\' LIMIT 1';
		if ( !($result = mysql_query($sql)) ) {
			printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}else{
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				$new_password = md5($row['new_pass']);
				//let's update the reset table with the activation date
				$sql = "UPDATE ".USER_PASS_RESET_TABLE." set activation_date = now() where pass_activation = "
				       .GetSQLValueString($_GET['activation'],'text').' LIMIT 1';
				if ( !($result = mysql_query($sql)) ) {
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
				
				//update users table with new password and modification date.
				$sql = "UPDATE ".SITE_USERS_TABLE." set user_password = ".GetSQLValueString($new_password,'text')
						." where user_id =".$row['user_id']." limit 1";
				if ( !($result = mysql_query($sql)) ) {
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
				
				//update phpbb_users table
				$sql = "UPDATE ".PHPBB_USERS_TABLE." set user_password = ".GetSQLValueString($new_password,'text')
						." where user_id =".$row['user_id']." limit 1";
				if ( !($result = mysql_query($sql)) ) {
					printf('Could not update record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
				}
				
				// email user the new password
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
				
				mail ($mailTo, $msgSubject, $msgBody, $xHeaders); 
				
				
				// show message that operation was succesful and to check email for password
				
				$content .= '<div class="notifyBlock" >'.LA_HERE_IS_YOUR_NEW_PASSWORD.'</div>';
				$content .= '<div>Contrase&ntilde;a: <strong>'.$row['new_pass'].'</strong><br>'."\n";
				$content .= '(Tambien te hemos enviado tu Contrase&ntilde;a: a tu email)<br></div>';
				
				
 			}else{
				//let's tell them that either the password activation is expired or it's a bad activation code
				//print the form so that they can try again
				$content .= '<div class="warningBlock">
			Disculpanos, pero este codigo de activacion esta expirado o no existe.
			</div>';
			$content .= forgot_password_form();

			}
		}
}
?>