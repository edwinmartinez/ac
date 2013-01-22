<?php
include('../includes/config.php'); 
include('../includes/authlevel1.php');
session_start(); 
if(!empty($_POST['staf_send'])){
	send_to_friends();
}else {
	 html_header();
?>
<div style="margin-left:10px; width:280px;">
	<form method="post">
	<input type="hidden" name="u" value="<?php echo $_GET['u']; ?>" />
	<?php echo LA_SEND_TO_FRIEND_DEST_HEADER; ?><br />
	<textarea name="emails" style="height:100px;"  onKeyDown="limitText(this.form.emails,this.form.countdown,200);" 
onKeyUp="limitText(this.form.emails,this.form.countdown,200);"></textarea><br />
	<input readonly type="hidden" name="countdown" size="3" value="200"><br />

	<?php echo LA_SEND_TO_FRIEND_MESSAGE_HEADER; ?><br />
	<textarea name="message"><?php echo LA_SEND_TO_FRIEND_DEFAULT_MESSAGE; ?></textarea><br />
	
	<input type="submit" name="staf_send" value="<?php echo LA_SEND; ?>" />
	</form>
</div>
<?php 
	html_footer();
}

function send_to_friends(){
	$message = $_POST['message'];
	$profile_user=$_POST['u'];
	$emails = explode(",",substr($_POST['emails'],0,200));

	if(has_emailheaders($message)){
		html_header();
		echo "message error";
		html_footer();
		exit;
	}

	$email_count = 0;	
	foreach($emails as $email){
		$email = trim($email);
		if(!empty($email)){
/*			if(has_emailheaders($email)){
				$error[$email_count] = $email . " Contains critical errors";
				print_errors();
				exit; 
			}*/
			if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)) {
				html_header();
				echo $email . " ". LA_CONTAINS_ERRORS;;
				html_footer();
				$error[$email_count] = 1;
				exit;
			}
			if(empty($error[$email_count])){
				
				$mailTo = $email;
				$from_email = 'Servicio de AmigoCupido <ayuda@amigocupido.com>';
				$msgSubject = $_SESSION['login'].' Te Manda un Perfil';
				$msgBody = $_SESSION['login'] . ' quiere que veas el perfil de esta persona'."\n";
				$msgBody .= PROFILE_DIR_URL.'/'.$profile_user."\n";
				$msgBody .= "\n\nMensaje Personal:----------\n";
				$msgBody .= $message;
				
				$msgBody .="\n\n\nGracias,\n".$_SESSION['login'];

				$xHeaders = "From: $from_email\nX-Mailer: PHP/" . phpversion();
				
				mail ($mailTo, $msgSubject, $msgBody, $xHeaders); 
				
			}
			$email_count++;
		}
	}
	
	if($email_count > 0){
		html_header();
		echo "Tu mensaje a sido enviado";
		html_footer();
	}else{
		html_header();
		echo "No pudimos enviar tu mensaje ya que necesitas aunque escribir un email a quien lo diriges";
		html_footer();
	}
	
	
}

function has_emailheaders($text)
{
	if(preg_match("/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i", $text)){
   		return 1;
	}else{
		return 0;
	}
}

function print_errors(){
	global $error;
	
}

function html_header(){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Mandar a un Amigo</title>
<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>

<style>
body {
	 font-family: Verdana, Arial, Helvetica, sans-serif;
	 font-size: 12px;
	 margin:0;
	 padding:0;
}
textarea {
	width:280px;
	margin-bottom:10px;
}
</style>
</head>

<body>
<div style="padding:10px; border-bottom:#666666 solid 1px; display:block; margin-bottom:10px;">
<img src="/images/logo_sm.gif" width="195" height="25" />
</div>

<?php 
}

function html_footer(){
?>
</body>
</html>
<?php
}
?>