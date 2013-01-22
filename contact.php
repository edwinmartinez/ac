<?php
if($_POST['sendit'] == 1) {
# Ultimate Form Mail v1.7
#
# Copyright 2003 Jack Born (c) All rights reserved.
# Created 06/29/03 Last Modified 9/17/2003
//The $recipient variable is the 
//ONLY one you NEED to change
//Simply put a comma delimited list
//of all email addresses to notify
/*example: "contact@martinmedia.com, you@earthlink.net"; */
//-------------------------------------
$recipient = "martinmedia@yahoo.com";
//-------------------------------------
//Everything below this line is optional
//-------------------------------------
//-------------------------------------
//Set website to www.yoursite.com
//This will give added security to the script
//But it is optional
//-------------------------------------
$website = "";
//-------------------------------------
//Set testing to zero for normal operation
//Set to one for testing
//-------------------------------------
$testing = 0;
//-------------------------------------
//$subject determines the subject on 
//notification email
//-------------------------------------
//$subject = "Message from your website $website";
$subject = "AmigoCupido.com Contacto";
//-------------------------------------
/*redirect determines the page where visitors go
after submitting form
feel free to change to thanks.htm or whatever you want
but file must exist
Or leave it as is... blank 
Can be "/thanks.htm/" or 
"http://www.yoursite.com/whatever/thanks.php"
Either format works fine*/
//-------------------------------------
$redirect = "";
//-------------------------------------
/* banned emails, these will be email addresses of people
who are blocked from using the script (requested) */
//-------------------------------------
$banlist = array ('*@somedomain.com', 'user@domain.com', 'etc@domains.com', '*@www.beyondclouds.com');
//-------------------------------------
/* leave blank if you don't have a header file */
/* example: $header_file = "/folder/header.php"; */
//-------------------------------------
$header_file = "";
//-------------------------------------
/* leave blank if you don't have a footer file */
/* example: $footer_file = "/folder/footer.php"; */
//-------------------------------------
$footer_file = "";
//-------------------------------------
/*leave blank if you don't have a css file */
/* example: $css_file = "file.css";  */
//-------------------------------------
$css_file = "";
//-------------------------------------
/*This pipe delimited list determines which
files you will accept as attachments
if your form is set up to take attachments
Reccomended that you leave as is.*/
//-------------------------------------
$banned_ext="php|phtml|cgi|pl|asp|jsp|c|cfm|shtml|exe|bat|com|";
//-------------------------------------
/*$max_size determines the max file size of
files you will accept as attachments
if your form is set up to take attachments
Reccomended that you leave as is.*/
//-------------------------------------
$max_size=500;//Max size of file in Kilobytes
//-------------------------------------
/*$missing_fields_redirect should be left alone
unless you want to redirect users to a different 
page if they fill out the form incorrectly.
Recommended to leave as is.*/
//-------------------------------------
$missing_fields_redirect = "";//optional error page for missing fields.  Leave blank if unsure
//-------------------------------------
/*$env_report sends info on the user's IP and browser
Send environment variables 1=on  0=off*/
//-------------------------------------
$env_report = 1;
//-------------------------------------
/*Autoresponder... Send instant replies!*/
//-------------------------------------
//To turn on, set $autoresponder = 1;
//-------------------------------------
$autoresponder = 0;
//-------------------------------------
/*To change the From in the reply just change
"contact@" to "whatever@"
Example: "sales@" or "mail@"
//-------------------------------------*/
$auto_from = str_replace("www.", "contact@", $_SERVER['HTTP_HOST']);
//-------------------------------------
/*
To edit content of reply, edit $auto_content
Be sure to keep the same format given
Just change what's inside quotations.
Important... if you are using " or ' in your message
Then you need to proceed it with \
Example: 
$auto_content .= "Welcome to Bob\'s Petshop. Where we say \"Nobody Beats Our Prices\".";
*/
//-------------------------------------	
if($first_name != "") {
$auto_content = "$first_name,\n";
}
$auto_subject = "Gracias por Contactarnos";
$auto_content .= "Tu mensaje ha sido recibido y te contestaremos pronto.\r\n";
$auto_content .= "\r\n
";/* Line breaks made by r\n\ */
$auto_content .= "\r\n
";
$auto_content .= "Thanks for visiting $website.";
$mime_email_code = 0;
//-------------------------------------
//do NOT change this variable
//-------------------------------------
$configured = 1;
//-------------------------------------
//END of configuration... here's the code
//-------------------------------------
//-------------------------------------
//First, set error reporting to NONE
//If you want ALL errors, change 0 to E_ALL
//-------------------------------------
error_reporting(0);
if($website != "") { $referers[0] = $website; }
//checkfile function
//--------------------------------------------------------------------------------------------
function checkfileatt($fname,$fsize){
  global $max_size;
  global $banned_ext;
  $err="No file attached";
  //Checking file type in or out of banned file extensions list
  $pos1=strrchr($fname,".");
  $ftype=str_replace(".","",$pos1);
  $blist=explode("|",$banned_ext);
  for($i=0;$i<sizeof($blist)-1;$i++){
    if($ftype==$blist[$i]) $err="ERROR: Your file extension (<b>*.$ftype</b>) is not be accepted.";
  }
  //Check file size
  if(round($fsize/1024)>$max_size) $err="ERROR: Your file size (<b>" .round($fsize/1024) ."</b> Kb) is too large. We only accept <b>$max_size</b> Kb.";
  //Return the value
  return $err;
} //end of checkfileatt
//error handling function...
//--------------------------------------------------------------------------------------------
function print_error($reason,$type = 0) {
  global $version, $header_file, $footer_file, $testing, $post_info, $key;
  global $val, $_POST, $recipient;
// for missing required data
 // if ($type == "missing") {
html_header("error");
?>
<p>&nbsp;</p>
<div align="center">
<table width="500" border="0" cellpadding="10" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td><strong>The form was not submitted because it contained the following reasons:</strong><br>
	<ul>
	<?=$reason?>
	</ul>
    <p>Please use your browser's back button to return to the form and try again.</p>
	</td>
  </tr>
</table></div>
<?php
    if($testing == 1) {
      $post_info = $_POST;
      print("<p><strong>This script is in testing mode:</strong> <br> Here are the post variables:</p><br>");
      foreach($post_info as $key=>$val) { print("$key: $val<br>");}
      print("<br>And the email would have been sent to: $recipient"); 
    }
  html_footer();
  exit; 
}// end error handling function
// function to check the banlist
//--------------------------------------------------------------------------------------------
function check_banlist($banlist, $email) {
  if (count($banlist)) {
    $allow = true;
    foreach($banlist as $banned) {
      $temp = explode("@", $banned);
      if ($temp[0] == "*") {
        $temp2 = explode("@", $email);
        if (trim(strtolower($temp2[1])) == trim(strtolower($temp[1])))
          $allow = false;
        } 
		else {
        if (trim(strtolower($email)) == trim(strtolower($banned)))
          $allow = false;
        }
      }
    }
  if (!$allow) {
    print_error("You are using from a <b>banned email address.</b>");
  }
}//end check_banlist function
function check_sender_email_injection($from){
   //$from = $_POST["sender"];
   $from = urldecode($from);
   if (eregi("\r",$from) || eregi("\n",$from)){ return 0; }
   if (!has_no_emailheaders($email)){ return 0; }
   return 1;
}
function has_no_emailheaders($text)
{
   return preg_match("/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i", $text) == 0;
}
// function to check the referer for security reasons.
//--------------------------------------------------------------------------------------------
function check_referer($referers) {
  if (count($referers)) {
    $found = false;
    $temp = explode("/",getenv("HTTP_REFERER"));
    $referer = $temp[2];
    for ($x=0; $x < count($referers); $x++) {
      if (eregi ($referers[$x], $referer)) $found = true;
    }
    if (!getenv("HTTP_REFERER")) $found = false;
    if (!$found){
      print_error("You are coming from an <b>unauthorized domain.</b>");
      error_log("Illegal Referer. (".getenv("HTTP_REFERER").")", 0);
    }
    return $found;
    } else {
      return true; // not a good idea, if empty, it will allow it.
    }
  }
  if ($referers) check_referer($referers);
  if ($banlist) check_banlist($banlist, $email);
// parse the form and create the content string which we will send
//--------------------------------------------------------------------------------------------
function parse_form($array) {
// build reserved keyword array
//Anything put in here will not show up in your email when form is processed
  $reserved_keys[] = "MAX_FILE_SIZE";
  $reserved_keys[] = "required";
  $reserved_keys[] = "redirect";
  //$reserved_keys[] = "email";
  $reserved_keys[] = "require";
  $reserved_keys[] = "path_to_file";
  $reserved_keys[] = "recipient";
  $reserved_keys[] = "subject";
  $reserved_keys[] = "bgcolor";
  $reserved_keys[] = "text_color";
  $reserved_keys[] = "link_color";
  $reserved_keys[] = "vlink_color";
  $reserved_keys[] = "alink_color";
  $reserved_keys[] = "title";
  $reserved_keys[] = "missing_fields_redirect";
  $reserved_keys[] = "env_report";
  $reserved_keys[] = "Submit";
  $reserved_keys[] = "submit";
  //$reserved_keys[] = "name";
  $reserved_keys[] = "Name";
  $reserved_keys[] = "submit_x";
  $reserved_keys[] = "submit_y";
  $reserved_keys[] = "sendit";
  if (count($array)) {
    while (list($key, $val) = each($array)) {
//check for email injection
		if(!has_no_emailheaders($val)){
			print_error("Please don't spam");
		}
		
// exclude reserved keywords
      $reserved_violation = 0;
      for ($ri=0; $ri<count($reserved_keys); $ri++) {
        if ($key == $reserved_keys[$ri]) $reserved_violation = 1;
      }
// prepare content
      if ($reserved_violation != 1) {
	// let's check to see if they are check boxes
        if (is_array($val)) {
          for ($z=0; $z<count($val); $z++) {
            $nn=$z+1;
            $content .= "$key #$nn: $val[$z]\n";
          }
        }
	  // if the values contains nothing do nothing then
	  // don't add it to the content)
        elseif($val != "") $content .= "$key: $val\n";
      }
    } // end of while
return $content;
  }
}
//replace line breaks with <br>
//This is to format the html email we're about to send to you
//--------------------------------------------------------------------------------------------
function formathtml($text){
  $text=stripslashes($text);
  $text=str_replace("\r\n","<BR>\n",$text);
  $text=str_replace("\n","<BR>\n",$text);
  //$text="<html><body>".$text."</body></html>";
return $text;
}
function html_header($title){
//--------------------------------------------------------------------------------------------
global $bgcolor,$bgcolor, $text_color, $link_color, $vlink_color, $alink_color, $style_sheet;
global $header_file;
  if($header_file != "") {
    include $_SERVER['DOCUMENT_ROOT']."$header_file"; 
  }
  else {
    if (!$bgcolor)     $bgcolor = "#FFFFFF";
    if (!$text_color)  $text_color = "#000000";
    if (!$link_color)  $link_color = "#0000FF";
    if (!$vlink_color) $vlink_color = "#FF0000";
    if (!$alink_color) $alink_color = "#000088";
    if ($background)   $background = "background=\"$background\"";
	  
	echo "<html><head>\n";
	if ($style_sheet) echo "<LINK rel=STYLESHEET href=\"$style_sheet\" Type=\"text/css\">\n";
    if ($title)        echo "<title>$title</title>\n";
    echo "<body bgcolor=\"$bgcolor\" text=\"$text_color\" link=\"$link_color\" vlink=\"$vlink_color\" ";
	echo "alink=\"$alink_color\" $background>\n\n";
    }
  if($testing == 1) {
      print("<h1>Your form is in test mode.</h1>\n");
      echo "<p>To turn off test mode, you need to change your config file.";
  }
}
function html_footer() {
//--------------------------------------------------------------------------------------------
  if($footer_file != "") {
    include $_SERVER['DOCUMENT_ROOT']."$footer_file"; 
  }
  else {
    print("\n</body></html>");
  }
}
// program starts here
//--------------------------------------------------------------------------------------------
// check for a recipient email address and check the validity of it
  $recipient_in = split(',',$recipient);
  for ($i=0;$i<count($recipient_in);$i++) {
  $recipient_to_test = trim($recipient_in[$i]);
//if (!eregi("^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,3}$", $recipient_to_test)) {
	if (!eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $recipient_to_test)) {
  		print_error("<b>I NEED VALID RECIPIENT EMAIL ADDRESS ($recipient_to_test) TO CONTINUE</b>");
	}
}
/*Anything in your form that you want the user to be forced to fill out
Put a hidden tag in your form that is a comma separated list of items
You want your visitor to have to fill out
Email definitely should be required */
if ($required)
$require = $required;
// handle the required fields
if ($require) {
// seperate at the commas
$require = ereg_replace( " +", "", $require);
$required = split(",",$require);
for ($i=0;$i<count($required);$i++) {
$string = trim($required[$i]);
// check if they exsist
if((!(${$string})) || (!(${$string}))) {
// if the missing_fields_redirect option is on: redirect them
if ($missing_fields_redirect) {
header ("Location: $missing_fields_redirect");
exit;
}
$require;
$missing_field_list .= "<b>Missing: $required[$i]</b><br>\n";
}
}
// send error to our mighty error function
if ($missing_field_list)
print_error($missing_field_list,"missing");
}
// check the email fields for validity
if (($email) || ($EMAIL)) {
	$email = trim($email);
	if ($EMAIL){	$email = trim($EMAIL); }
	if(check_sender_email_injection($email)){
		if (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $email)) {
			print_error("your <b>email address</b> is invalid");
		}
	}else{
		//failed at the injection check
		print_error("Please don't spam");
	}
	$EMAIL = $email;
}
// check zipcodes for validity
if (($ZIP_CODE) || ($zip_code)) {
$zip_code = trim($zip_code);
if ($ZIP_CODE)
$zip_code = trim($ZIP_CODE);
if (!ereg("(^[0-9]{5})-([0-9]{4}$)", trim($zip_code)) && (!ereg("^[a-zA-Z][0-9][a-zA-Z][[:space:]][0-9][a-zA-Z][0-9]$", trim($zip_code))) && (!ereg("(^[0-9]{5})", trim($zip_code)))) {
print_error("your <b>zip/postal code</b> is invalid");
}
}
// check phone for validity
if (($PHONE_NO) || ($phone_no)) {
$phone_no = trim($phone_no);
if ($PHONE_NO)
$phone_no = trim($PHONE_NO);
if (!ereg("(^(.*)[0-9]{3})(.*)([0-9]{3})(.*)([0-9]{4}$)", $phone_no)) {
print_error("your <b>phone number</b> is invalid");
}
}
// check phone for validity
if (($FAX_NO) || ($fax_no)) {
$fax_no = trim($fax_no);
if ($FAX_NO)
$fax_no = trim($FAX_NO);
if (!ereg("(^(.*)[0-9]{3})(.*)([0-9]{3})(.*)([0-9]{4}$)", $fax_no)) {
print_error("your <b>fax number</b> is invalid");
}
}
// prepare the content
$content = parse_form($_POST);

// Lets compose the $mailfrom variable so that it shows the name of the sender
  if ($first_name != "") {
    $mailfrom = trim($first_name) . " " . trim($last_name) . " <" . $email . ">";
  }
  elseif ($name != "") {
    $mailfrom = trim($name) . " <" . $email . ">";
  }
  else {
    $mailfrom = $email;
  }



// if the env_report option is on: get eviromental variables
if ($env_report) {
$content .= "\n------ eviromental variables ------\n";
$content .= "REQUEST_URI: ".$_SERVER["REQUEST_URI"]."\n";
$content .= "REMOTE ADDR: ". $_SERVER["REMOTE_ADDR"]."\n";
$content .= "BROWSER: ". $_ENV["HTTP_USER_AGENT"]."\n";
}
if($testing == 1) {
  $email_message=formathtml($content);
?>
<h1>You are in testing mode</h1>
<p>Your email would have been successfully sent</p>
<p>To: <?php print $recipient; ?><br>
  Subject: <?php print $subject; ?><br>
  Message: <br>
  <?php print $email_message; ?></p>
<p>&nbsp;</p>
<?php
  $post_info = $_POST;
  print("Here are the post variables:<br><br>");
  foreach($post_info as $key=>$val) { print("$key: $val<br>"); }
  exit;
}
// if the subject option is not set: set the default
if (!$subject) $subject = "Form submission";

if($mime_email_code) {
//Start MIME email code
//This produces the html email and makes it possible to include an attachment
  $real_email_message = $content;
                //BEGIN //----------------------------------------------
                      $email_message=formathtml($real_email_message);
                      $chked=0;
                      if ($upfile == "") $chked=1;
                      if (checkfileatt($upfile_name,$upfile_size)!="No file attached") $chked=1;
                      if($chked==0) {
                    	  copy($upfile, $upfile_name);
                        $fileatt=$upfile_name;
                        $fileatt_type=$upfile_type;
                        $fileatt_name=$upfile_name;
                        $file = fopen($fileatt,'rb');
                        $data = fread($file,filesize($fileatt));
                        fclose($file);
                      }
                      $headers = "From: ".$mailfrom;
                      $semi_rand = md5(time());
                      $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                      $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/alternative; " . " boundary=\"{$mime_boundary}\";\n";
                      $email_message = "\n\n" . "--{$mime_boundary}\n";
					  $email_message .= "Content-Type: text/plain; charset=us-ascii\n\n";
					  $email_message .= $content."\n";
					  $email_message .= "--{$mime_boundary}\n";
					  $email_message .= "Content-Type: text/html; charset=us-ascii\n\n" . $email_message . "\n";
					  $email_message .= "--{$mime_boundary}\n";
					  
                      $data = chunk_split(base64_encode($data));
                      if($chked == 0){
                        $email_message .= "Content-Type: {$fileatt_type};\n" . " name=\"{$fileatt_name}\"\n" . "Content-Disposition: attachment;\n" . " filename=\"{$fileatt_name}\"\n" . "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" ."--{$mime_boundary}--\n";
                      }
                      $sending_ok = @mail($recipient, $subject,$email_message,$headers);
                      if($chked==0) unlink($upfile_name);
//end MIME email code
} else { //else we are going to send a plain mail of the form.
  # Ok let's send the email
  mail($recipient,$subject,$content,"From: ".$mailfrom);
}
//autoresponder message
if($autoresponder == 1){//Then we're sending the visitor a response immediately
  if(!$auto_from){
    $auto_from = explode(",", $recipient);
    $auto_from = $auto_from[0];/*Uses the first email in your recipient list in the config file */
  }
  if($name){/*If you have a field labeled 'name' then we can personalize the email */
    $auto_subject = $name . ", " . $auto_subject;
    $auto_content = "Hello $name,\r\n\r\n" . $auto_content;
  }
  if($first_name){/*If you have a field labeled 'first_name' then we can personalize the email */
    $auto_subject = $first_name . ", " . $auto_subject;
    $auto_content = "Hello $first_name,\r\n\r\n" . $auto_content;
  }
  mail($email, $auto_subject, $auto_content, "From: $auto_from");
}
// if the redirect option is set: redirect them
if ($redirect) {
  header ("Location: $redirect");
  exit;
} else {
  //print "Thank you for your submission\n";
  
  $content =  "<p>Gracias por contactarnos. Te responderemos lo mas pronto possible.</p>";
  $content .= "<br><br>\n";
  display_content('contacto',$content);
  exit;
}
} else {
	
	$content = <<<EOF
	
		<h1>Contactanos</h1>
		
		<div class="helpLinks" style="width:80%;margin:20px 0;">
			<strong>En que te podemos ayudar?</strong>
			<ul>
			  <li>Se te olvido tu clave? Puedes crear una nueva <a href="http://www.amigocupido.com/olvide_contrasena/">aqui</a>.</li>
			  <li>Se te olvido tu apodo con el que te registraste? Te lo mandamos a tu email <a href="/olvide_apodo/">aqui</a></li>
			  <li>Quieres contactar a un miembro? Ve a la pagina de su perfil y alli abajo de la seccion de la fotografia existe un enlace que dice <strong>Mandame un mensaje</strong>. Haz click alli y te habrira una forma para mandarle un mensaje
			  <img src="/images/contact_member.jpg" /></li>
			</ul>
		
		</div>
		
		<strong>Quieres mandar un mensaje al equipo de AmigoCupido?</strong>
		<br />
		<br />
		<form method="POST">
		    Nombre:<br /> 
	      <input type="text" name="name"><br>  Email:<br /> 
	      <input type="text" name="email"><br>
		  Mensaje:<br />
		  <label>
		  <textarea name="message" rows="5"></textarea>
		  </label>
		  <br>  
		  <input type="hidden" name="sendit" value="1">
		  <input name="required" type="hidden" id="required" value="name,email,message">
		  <br>  <input type="submit" value="Mandar Email"> 
	    </form>
EOF;
display_content('contacto',$content);
}
function display_content($title,$content){
	global $smarty;
	require_once('includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	//$smarty->assign("js",$js);	
	$smarty->assign("title",$title);
	$smarty->assign("content",$content);
	$smarty->display('index.html');	
	
}