<?php
include('../includes/config.php'); 	
include('../includes/authlevel1.php');
session_start(); 

require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;

if(isset($_GET['folder']) && $_GET['folder'] != '') { 
	$folder = $_GET['folder'];
	$userdata['user_id'] = $_SESSION['user_id'];
	//
	// SQL to pull appropriate message, prevents nosey people
	// reading other peoples messages ... hopefully!
	//
	switch( $folder )
	{
		case 'inbox':
			$l_box_name = LA_INBOX;
			$pm_sql_user = "AND pm.privmsgs_to_userid = " . $userdata['user_id'] . " 
				AND ( pm.privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
					OR pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
					OR pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " )";
			break;
		case 'outbox':
			$l_box_name = LA_OUTBOX;
			$pm_sql_user = "AND pm.privmsgs_from_userid =  " . $userdata['user_id'] . " 
				AND ( pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
					OR pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " ) ";
			break;
		case 'sentbox':
			$l_box_name = LA_SENTBOX;
			$pm_sql_user = "AND pm.privmsgs_from_userid =  " . $userdata['user_id'] . " 
				AND pm.privmsgs_type = " . PRIVMSGS_SENT_MAIL;
			break;
		case 'savebox':
			$l_box_name = LA_SAVEBOX;
			$pm_sql_user = "AND ( ( pm.privmsgs_to_userid = " . $userdata['user_id'] . "
					AND pm.privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . " ) 
				OR ( pm.privmsgs_from_userid = " . $userdata['user_id'] . "
					AND pm.privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . " ) 
				)";
			break;
		default:
			message_die(GENERAL_ERROR, $lang['No_such_folder']);
			break;
	}
	
	//
	// Major query obtains the message ...
	//
	$sql = "SELECT u.username AS username_1, u.user_id AS user_id_1, u2.username AS username_2, u2.user_id AS user_id_2,  u.user_email, u.user_viewemail,  pm.*, pmt.privmsgs_bbcode_uid, pmt.privmsgs_text
		FROM " . PRIVMSGS_TABLE . " pm, " . PRIVMSGS_TEXT_TABLE . " pmt, " . USERS_TABLE . " u, " . USERS_TABLE . " u2 
		WHERE 
			pmt.privmsgs_text_id = pm.privmsgs_id 
			$pm_sql_user 
			AND u.user_id = pm.privmsgs_from_userid 
			AND u2.user_id = pm.privmsgs_to_userid
		ORDER BY privmsgs_date desc";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query private message post information', '', __LINE__, __FILE__, $sql);
	}

	$num_rows = $db->sql_numrows($result);




$content .= 
	'
	<div id="message-nav" style="float:left; display:inline; margin-left:10px;margin-top:20px;width:140px;font-size:12px; line-height:24px;font-weight:bold;">
	<a href="?folder=inbox">'.LA_INBOX.'</a><br>
	<a href="?folder=outbox">'.LA_OUTBOX.'</a><br>
	<a href="?folder=sentbox">'.LA_SENTBOX.'</a><br>
	<a href="?folder=savebox">'.LA_SAVEBOX.'</a><br>
	</div>
	<div id="message-area" style="float:left;display:inline;margin-left:5px; width:560px; border:#000 1px solid;">
	<div style="font-weight:bold;">'.$l_box_name.'</div>'."\n";
	//$content .='<div style="float:left;display:inline;border:1px solid #ccc;">here</div>'."\n";
	  // Error check that the rows returned were what you expected
        if ( $num_rows >= 1) {
            // Fetch Array (Associative) 
            while ($mysql_array = $db->sql_fetchrow()) {
            // Print Data
			$content.= "\n";
           $content .= '<div class="message-unit" style="clear:both;border-bottom:1px solid #ccc;margin-bottom:4px;">'."\n"
		   .'<div style="width:130px;float:left;padding:4px;display:inline;border-right:1px solid #ccc;font-size:10px;">'.$mysql_array['username_1'].'</div>'."\n"
		    .'<div class="message-title" style="width:300px; overflow:hidden;float:left; font-size:12px;font-weight:bold; padding-top:4px;border-right:1px solid #ccc;">'.$mysql_array['privmsgs_subject'].'</div>'."\n"
			.'<div style="clear:both;"></div>'
		    .'</div><!--end of message unit-->'."\n\n";
    
            }
			$content .='<div style="clear:both;"></div>'."\n";
        }
	
	//$content .='<br>'.$sql."<br>".$userdata['user_id'].":
	$content .="</div>\n";
	$content .='<div style="clear:both;"></div>'."\n";
	
	$smarty->assign("my_home",LA_MY_HOME);
	$smarty->assign("title",LA_MESSAGES);
	$smarty->assign("content_wide",$content);
	
}else{

		if ( !empty($_GET[POST_USERS_URL]) ){
			$user_id = intval($_GET[POST_USERS_URL]);
			$to_username = get_username($user_id);
		}	
		
 //   echo "sid".$SID; 
 
	$form_action = append_sid("/foros/privmsg.$phpEx?");
	$content = get_send_form();
	$smarty->assign("title","Manda un Mensaje : AmigoCupido.com Buscar Pareja Latin Dating ");
	$smarty->assign("content",$content);
}

	$smarty->assign("js",$js);	
	$smarty->display('index.html');	
	

function shorten($string, $width) {
  if(strlen($string) > $width) {
    $string = wordwrap($string, $width);
    $string = substr($string, 0, strpos($string, "\n"));
  }

  return $string;
}
		
function get_send_form(){
//-------------------------------------------------------------------
	global $to_username, $form_action;
	
 $content = <<<EOF
      <h1>Enviar un nuevo mensaje privado</h1>

<script language="javascript" src="/js/messaging_phpbb.js"></script>

	  
	  <form action="$form_action" method="post" name="post" onsubmit="return checkForm(this)">
        <table border="0" cellpadding="3" cellspacing="1" width="100%">
	
	<tr>
		<td class="row1"><span class="gen"><b>Nombre de Usuario </b></span></td>
		<td class="row2"><span class="genmed"><input type="text"  class="post" name="username" maxlength="25" size="25" tabindex="1" value="$to_username" />&nbsp;<input type="submit" name="usersubmit" value="Encontrar un usuario" class="liteoption" onClick="window.open('../foros/search.php?mode=searchuser', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></span></td>
	</tr>
	<tr>
	  <td class="row1" width="22%"><span class="gen"><b>Asunto</b></span></td>
	  <td class="row2" width="78%"> <span class="gen">
		<input type="text" name="subject" size="45" maxlength="60" style="width:450px" tabindex="2" class="post" value="" />
		</span> </td>
	</tr>

	<tr>
	  <td class="row1" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		  <tr>
			<td><span class="gen"><b>Cuerpo del mensaje</b></span> </td>
		  </tr>
		  <tr>
			<td valign="middle" align="center"> <br /></td>
		  </tr>
		</table>
	  </td>
	  <td class="row2" valign="top"><span class="gen"> <span class="genmed"> </span>
		<table width="450" border="0" cellspacing="0" cellpadding="2">
		  <tr>
			<td><span class="gen">
			  <textarea name="message" rows="15" cols="35" wrap="virtual" style="width:450px" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"></textarea>
			  </span></td>
		  </tr>
		</table>
		
		</span></td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center" height="28"> <input type="hidden" name="folder" value="inbox" /><input type="hidden" name="mode" value="post" /><!--<input type="submit" tabindex="5" name="preview" class="mainoption" value="Vista Preliminar" />-->&nbsp;<input type="submit" accesskey="s" tabindex="6" name="post" class="mainoption" value="Enviar" /></td>
	</tr>
  </table>
      </form>

EOF;
	return $content;
}
?>