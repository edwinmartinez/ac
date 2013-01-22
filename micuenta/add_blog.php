<?
include('../includes/config.php'); 
//include('../includes/authlevel1.php');
session_start(); 
if(!isset($HTTP_SESSION_VARS['user_id'])) {
	header('Location: ' . '/login/?redirect=/micuenta/add_blog.php');
	exit;
}


	

	if (isset($_REQUEST['submit_blog_entry'])){
		if(!add_blog()){
			$page_content .= "Ha ocurrido un error. No pudimos guardar tu entrada de tu diario. Disculpanos"."\n";
		}/*else {
			$content .= "Tu Entrada en tu diario a sido publicada" . "\n";
		}*/	
	}else{
		print_form();
	}
	require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	//menu items
	$smarty->assign("my_account_settings",$lang['my_account_settings']);
	$smarty->assign("my_profile",$lang['my_profile']);
	$smarty->assign("my_pictures",LA_MY_PICTURES);
	$smarty->assign("my_home",LA_MY_HOME);
	$smarty->assign("js",$js);
	$smarty->assign("content",$page_content);
	$smarty->assign("title",LA_BLOG_ENTRY_ADD);
	$smarty->display('index.html');	
	
	
	
function stringToUrlSlug($string){
        $unPretty = array('/�/','/�/','/!/','/�/', '/�/', '/�/', '/�/', '/�/', '/�/', '/�/', '/\s?-\s?/', '/\s?_\s?/', '/\s?\/\s?/', '/\s?\\\s?/', '/\s/', '/"/', '/\'/');
        $pretty   = array('n','N','','ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', '-', '-', '-', '-', '-', '', '');
		
		//let's check if the slug is not being used allready
		//save to database
		$url_slug = strtolower(urlencode(preg_replace($unPretty, $pretty, $string)));
		$sql = "SELECT url_slug from ".USERS_BLOGS_TABLE." WHERE url_slug = ".GetSQLValueString($url_slug,"text");
		$sql .= " AND ub_uid = ".$_SESSION['user_id'];
		$result = mysql_query($sql);
		$no_rows = mysql_num_rows($result);
		for($suffix = 1;!empty($no_rows) || $suffix >=5;$suffix++){
			$url_slug = strtolower(urlencode(preg_replace($unPretty, $pretty, $string)))."-".$suffix;
			$sql = "SELECT url_slug from ".USERS_BLOGS_TABLE." WHERE url_slug = ".GetSQLValueString($url_slug,"text");
			$sql .= " AND ub_uid = ".$_SESSION['user_id'];
			//echo $sql.'<br>';
			$result = mysql_query($sql);
			$no_rows = mysql_num_rows($result);
		}
		
		
        return $url_slug;
}
	
function add_blog(){
//----------------------------------------------------------------------------------------------
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	global $allowed_profile_comment_tags;
	global $page_content, $title, $url_slug;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	include_once '../includes/class.inputfilter.php'; 	
	
	$forbiden_blog_tags = array();
	$forbiden_blog_tags_attributes = array();
	
	$user_id = $_REQUEST['user_id']; //user requesting the add
	$blog_content = $_REQUEST['content'];
	$title = trim($_REQUEST['title']);
	$type = $_REQUEST['type'];
	$userinfo = get_profile_info($user_id);	
	$username = $userinfo[USERNAME_FIELD];
	$myFilter = new InputFilter(array('b','strong','i','font','a','span'), array('color','style','face','href','target','title','name','id'),0, 0); 
	$blog_content = $myFilter->process($blog_content);
	$myTitleFilter = new InputFilter(array('i'), array(),0, 0); 
	$title = $myTitleFilter->process($title);
	$url_slug = stringToUrlSlug($title);
	
	
	if(empty($user_id)) {
		 echo LA_ERROR_WHILE_PROCESSING ." user_id:".$user_id."\n";
		return false;
	}else{
		//save to database
		$sql = "INSERT into ".USERS_BLOGS_TABLE." (ub_uid, title, content, type, url_slug, date) ".
				"VALUES(".$user_id.", ".GetSQLValueString($title,"text").", ".GetSQLValueString($blog_content,"text").", ".GetSQLValueString($type,"text").", ".GetSQLValueString($url_slug,"text").", NOW())";
		if ( !($result = mysql_query($sql))){
			printf('Could not insert into blog at line %s file: %s <br> sql: %s',  __LINE__, __FILE__, $sql);
			return false;
		}
		$req_id = mysql_insert_id();
		
		header('Location: ' . MEMBER_BLOG_DIR_URL.$username.'/'.$url_slug);
		exit;
		//return result and option to see entry 
		//return true;
	}
}

function print_form(){
//----------------------------------------------------------------------------------------------
	global $page_content, $js;
	$js = "
	<script type=\"text/javascript\" src=\"/js/fckeditor/fckeditor.js\"></script>"."\n";	
	$js .= '<script type="text/javascript" src="/js/add_blog.js"></script>'."\n";
	
	$page_content = "\n".'
		<form enctype="multipart/form-data" method="post" name="form1" onsubmit="return check_and_submit();">
		<input name="user_id" type="hidden" value="'.$_SESSION['user_id'].'" />
		<input name="type" type="hidden" value="text" />	
	<div class="content">
		<div class="centered" style="background:#ccc; padding:10px;">
				
			'.LA_BLOG_ENTRY_TITLE."<br><input type=\"text\" name=\"title\" size=\"50\" style=\"margin:10px 0;\"><br>
			".LA_BLOG_ENTRY."<!--<br><textarea name=\"content\"  style=\"margin:10px 0;\"  cols=\"40\" rows=\"8\"></textarea>--><br>"."
			
			<script language=\"javascript\" >
<!--
var sBasePath = document.location.pathname.substring(0,document.location.pathname.lastIndexOf('_samples')) ;

var oFCKeditor = new FCKeditor( 'content' ) ;
oFCKeditor.BasePath = '/js/fckeditor/' ;
oFCKeditor.Height	= 300 ;
oFCKeditor.Value	= '' ;
oFCKeditor.ToolbarSet = 'MyToolbar' ;
oFCKeditor.Config['LinkBrowser'] = false;
oFCKeditor.Config['ImageBrowser'] = false;
oFCKeditor.Config['FlashBrowser'] = false;
oFCKeditor.Config['LinkUpload'] = false;
oFCKeditor.Create() ;

//-->
</script>"."\n".'

<input type="submit" name="submit_blog_entry" value="'.LA_BLOG_ENTRY_SUBMIT.'"  />'
.'<input type="submit" name="cancel" value="'.LA_CANCEL.'" onclick="javascript:window.location=(\''.LOGIN_REDIRECT_DEFAULT.'\'); return false;" >'."			
		</div>
	</div>
	</form>";
} /*print_form end*/
?>