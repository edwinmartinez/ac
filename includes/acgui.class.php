<?php

/**
 * Description of acgui
 *
 * @author Edwin Martinez
 */
class acgui {
    protected $db;
    
    function __construct() {   

        require_once("db_settings.php");
        $this->db = new MySQL();
        //echo DBMS." $dbname, $dbhost, $dbuser, $dbpasswd";
        if (! $this->db->Open(DBNAME, DBHOST, DBUSER, DBPASS)) {
            $this->db->Kill();
        }
        $this->sql = "SET SESSION SQL_BIG_SELECTS=1"; //sets the session for big selects/joins
        if (!$this->db->Query($this->sql)) {
            $this->db->Kill();
        }
    }
    
    public function getCountrySelectMenu($country_id = NULL){
        $this->country_id = $country_id;
        $this->top_countries = $GLOBALS['top_countries']; //from settings
        
        $this->sql = "SELECT * from countries order by countries_name_es asc";
	
		if ( !($this->result = $this->db->Query($this->sql)) ) { 
            printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
            $this->db->Kill();
        }
	
		$this->out = "<select id=\"country\" name=\"country\" style=\"width:160px;\">\n<option value=\"\">--".LA_COUNTRY."--</option>\n";
		while ($this->row = mysql_fetch_assoc($this->result)) {
			if(!empty($this->country_id) && $this->row['countries_id'] == $this->country_id ) {$this->selected = 'selected="selected"'; }
			else { $this->selected = ""; }	
			if(in_array($this->row['countries_iso_code_2'],$this->top_countries)){
				$this->countries_menu_top .= sprintf("<option value=\"%s\">%s</option>\n",
					$this->row['countries_id'],$this->row['countries_name_es']);
			}
			$this->countries_menu_bot .= sprintf("<option %s value=\"%s\">%s</option> \n", $this->selected, $this->row['countries_id'], $this->row['countries_name_es']);  
			
			$this->menu_div = "<option value=\"\">---------------</option>\n";
		} 
        $this->out = $this->out . $this->countries_menu_top . $this->menu_div . $this->countries_menu_bot;
		$this->out .= "</select>\n";
        return $this->out;
    }
    
	public function getProfilePic($uid, $size = "square"){
	//--------------------------------------------------------------------------------------------------
		if(!$uid) return -1;
		$this->uid = $uid;

		$this->sql = "SELECT p.* FROM ".USERS_GALLERY_TABLE." as p"
		." WHERE"
		." p.photo_uid='".$uid."' "
		." AND p.use_in_profile=1 LIMIT 1";
		
		if ( !($this->result = $this->db->Query($this->sql)) ) { 
            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
            $this->db->Kill();
        }
		
		//$profile_pic_result = mysql_query($sql);
		$this->profile_pic_rows = mysql_num_rows($this->result);
		if($this->profile_pic_rows){
				$this->profile_pic_row = mysql_fetch_assoc($this->result);
				list($this->photo_uid,$this->imgname,$this->extention) = explode(".",$this->profile_pic_row['photo_filename']);
				$this->photoNames = $this->getPhotoFileNames($this->uid,$this->profile_pic_row['photo_filename']);
				if($bigpic == 1){
					$this->profile_pic = $this->photoNames["large"];
				} else {
					$this->profile_pic = $this->photoNames["square"]; //profile
				}
			
			
		}else{
			if($size == "square"){ //if size requested is square and no pic is found give the generic ones
				$this->user_gender = $this->get_user_gender($this->uid);
				if($this->user_gender == 1) { 
					$this->profile_pic = "/images/nofoto_m.jpg";
				} else {                                   
					$this->profile_pic = "/images/nofoto_f.jpg";   
				}
			} else {
				$this->profile_pic = false; 
			}
		}
		return $this->profile_pic;
	}
		
	public function getPhotoFileNames($uid, $imgname){
				$this->uid = $uid;
				$this->imgname = $imgname;
				list($this->photo_uid,$this->imgname,$this->extention) = explode('.', $this->imgname);
				$this->basefilename = $this->imgname;
				$this->largefilename = $this->photo_uid.".".$this->basefilename."_l.".$this->extention;
				$this->mediumfilename = $this->photo_uid.".".$this->basefilename."_m.".$this->extention;
				$this->smallfilename = $this->photo_uid.".".$this->basefilename."_s.".$this->extention;
				$this->squarefilename = $this->photo_uid.".".$this->basefilename."_sq.".$this->extention;
				
				$this->largefilename = MEMBER_IMG_DIR_URL."/".$uid."/".$this->largefilename;
				$this->mediumfilename = MEMBER_IMG_DIR_URL."/".$uid."/".$this->mediumfilename;
				$this->smallfilename = MEMBER_IMG_DIR_URL."/".$uid."/".$this->smallfilename;
				$this->squarefilename = MEMBER_IMG_DIR_URL."/".$uid."/".$this->squarefilename;
				
				return array(
					"large"=>$this->largefilename, 
					"medium"=>$this->mediumfilename,
					"small"=>$this->smallfilename,
					"square"=>$this->squarefilename
				);
				
	}

	public function get_user_gender($uid){
	//--------------------------------------------------------------------------------------------------
		if(!$uid) return -1;
		$this->uid = $uid;
		
		$this->sql = "SELECT user_gender from ".SITE_USERS_TABLE." where "; 
		$this->sql .= USER_ID_FIELD." = '" . $this->uid . "'"
					   ." limit 1";
		
		if ( !($this->result = $this->db->Query($this->sql)) ) { 
            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
            $this->db->Kill();
        } else {
			$this->num_rows = mysql_num_rows($this->result);
			if ($this->num_rows < 1) {
				return 0;
			}else{
				$this->profile = mysql_fetch_assoc($this->result);
			}
			return $this->profile['user_gender'];
		}
	
	}
	
	function getLoginLink(){
		if(isset($_SESSION['user_id'])) {
			//header('Location: ' . '/login/?redirect=/micuenta?p='.$_REQUEST['p']);
			//exit;
			return '<a href="/login">Login</a>';
		}else{
			return '<a href="/logout">Logout</a>';
		}
	}
	
	function fillRequest($datastream){
		$this->data = $this->parseQueryString($datastream);		

		if (!empty($this->data)){
			if(isset($_COOKIE['f']))
				$_COOKIE['f'] = $this->data['seeks_gender']==2?1:0;
			else 
				setcookie('f',$this->data['seeks_gender']==2?1:0);
				
			if(isset($_COOKIE['m']))
				$_COOKIE['m'] = $this->data['seeks_gender']==1?1:0;
			else 
				setcookie('m',$this->data['seeks_gender']==1?1:0);
			
			$_REQUEST['min_age'] = $this->data['min_age']; 
			if(isset($_COOKIE['min_age']))
				$_COOKIE['min_age'] = $this->data['min_age'];
			else 
				setcookie('min_age',$this->data['min_age']);

			$_REQUEST['max_age'] = $this->data['max_age']; 
			if(isset($_COOKIE['max_age']))
				$_COOKIE['max_age'] = $this->data['max_age'];
			else 
				setcookie('max_age',$this->data['max_age']);
			
			$_GET = $this->data;
		} else {
			return FALSE;
		}
		
		//var_dump($_GET);
		//exit;
	}
	
	public function xmlMiniProfiles($datastream=''){
	//--------------------------------------------------------------------------------------------------
		if(!empty($datastream)) 
			$this->fillRequest($datastream);

		
		
		$this->sql = ""; //clear the sql var
		

		//Build the sql statement
		
		if (!empty($_REQUEST['min_age'])) { 
			$this->min_bday = date('Y-m-d',mktime(0, 0, 0, date("m"),  date("d")-1,  date("Y")-($_REQUEST['min_age'])));
		}	
		if (!empty($_REQUEST['max_age'])) { 
			$this->max_bday = date('Y-m-d',mktime(0, 0, 0, date("m"),  date("d")-1,  date("Y")-($_REQUEST['max_age']+1)));
		}

		
		//define the results per page
		if(!empty($_GET['rpp'])) {
			$this->rpp = $_GET['rpp'];
		}else{
			$this->rpp = PEOPLE_SEARCH_RPP;
		}
		
		$this->sqlstart  = "SELECT u.*, c.".COUNTRY_NAME_FIELD." as country_name from ".SITE_USERS_TABLE." as u ";
		$this->sqlcount = "SELECT count(*) as total_count from ".SITE_USERS_TABLE." as u ";
		$this->sql = "LEFT JOIN ".COUNTRY_TABLE." as c ON c.".COUNTRY_ID_FIELD." = u.user_country_id ";
		if (!empty($_GET['photo_only'])) { 
			$this->sql .= "join ".USERS_GALLERY_TABLE." ";
		}
		if (!empty($_REQUEST['user_country_id'])) { 
			$this->sql .= "WHERE  user_country_id=".$_REQUEST['user_country_id']." "; 
			$this->use_and = 1;
		}
		if(!(!empty($_GET['m']) && !empty($_GET['f']))){
			if (!empty($_GET['m'])) {
				$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
				$this->sql .= $this->and."user_gender=1 ";
				$this->use_and = 1;
			}
			if (!empty($_GET['f'])) {
				$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
				$this->sql .= $this->and."user_gender=2 ";
				$this->use_and = 1;
			}
		}
		if (!empty($_REQUEST['min_age'])) {
			$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
			$this->sql .= $this->and." user_birthdate < '".$this->min_bday."'";
			$this->use_and = 1;		
		}
		if (!empty($_REQUEST['max_age'])) {
			$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
			$this->sql .= $this->and." user_birthdate > '".$this->max_bday."'";
			$this->use_and = 1;		
		}
		
		if (!empty($_GET['photo_only'])) { 
			$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
			$this->sql .= $this->and."photo_uid=user_id and use_in_profile=1 ";
			$this->use_and = 1;
		}
		
		if (!empty($_REQUEST['username'])) { 
			$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
			$this->sql .= $this->and."user_username LIKE '%".$_REQUEST['username']."%' ";
			$this->use_and = 1;
		}
		
		if (!empty($_REQUEST['user_city'])) { 
			$this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
			$this->sql .= $this->and."user_city LIKE '%".$_REQUEST['user_city']."%' ";
			$this->use_and = 1;
		}
		
		//mod for cancelled accounts
		    $this->and = ( $this->use_and == 1 ) ? 'AND ' : 'WHERE ';
			$this->sql .= $this->and."status = 1 ";
			$this->use_and = 1;
		
		$this->sqlorderby = " ORDER BY ".USER_LAST_LOGIN_FIELD." desc";
		$this->sql_limit .= " limit ".$this->rpp;
		
		if(!empty($_GET['p'])) {
			$this->sql_limit .= " OFFSET ".(($_GET['p']-1) * $this->rpp);
		}
		$this->sqlcount = $this->sqlcount . $this->sql;
		$this->sql = $this->sqlstart . $this->sql . $this->sqlorderby . $this->sql_limit;
		
		
		header('Content-type: text/xml');
		$this->xml = '<?xml version="1.0" ?>'."\n";
		$this->xml .= "<xml>\n";
		//$this->xml .= "<statement>".'mnb'.$this->min_bday.'mxb'.$this->max_bday.":".$this->sql."</statement>\n";
		
		if ( !($this->countresult = $this->db->Query($this->sqlcount)) ) { 
            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sqlcount);
            $this->db->Kill();
        } else {
			$this->count_rows = mysql_num_rows($this->countresult);
			$this->countrow = mysql_fetch_assoc($this->countresult);
			$this->totalcount = $this->countrow['total_count']; 
		}

		// let's catch the results into profile_array so we release the db results
		// so we can perform a subquery for the profile pics
		if(!$this->profile_array = $this->db->QueryArray($this->sql)){ 
            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
            $this->db->Kill();
        } else {
			$this->total_rows = count($this->profile_array);
			//do we have a row with that username?
			if ($this->total_rows < 1) {
			//There are no results for this search
				$this->xml .= "<status>2</status>\n";
				$this->xml .= "<users>".$this->sql."\n";
				
				//header("Location: ".$this->cfgHomeUrl."/logout.php");
			}else{
				$this->xml .= "<status>1</status>\n";
				$this->xml .= "<totalcount>".$this->totalcount."</totalcount>\n";
				$this->xml .= "<users>\n";
				
				foreach ($this->profile_array as $this->profile){
					//$this->profile = mysql_fetch_assoc($this->result);
					$this->profile['user_username'] = preg_replace('/�/','n',$this->profile['user_username']);
	
					$this->xml .= '    <user ';
					$this->xml .= 'user_id="'.$this->profile['user_id'].'" ';
					$this->xml .= 'user_username="'.htmlentities($this->profile['user_username']).'" ';
					//$this->xml .= 'country="'.get_user_country($this->profile['user_country_id']).'" ';
					$this->xml .= 'country="'.htmlentities($this->profile['country_name']).'" ';
					$this->xml .= 'user_city="'.urlencode(ucwords(strtolower($this->profile['user_city']))).'" ';
					$this->xml .= 'photo="'.$this->getProfilePic($this->profile['user_id']).'" ';
					$this->xml .= ' />'."\n";
				}			
				
			}
			
		}
		$this->xml .= "</users>\n";
		//$this->xml .= "<sql>".$this->sqlcount."</sql>\n";
		$this->xml .= "</xml>";
		return $this->xml;
		//echo "\n".$this->sql."\n".$this->sqlcount;
	}

	public function homePage(){
	//--------------------------------------------------------------------------------------------------
		
		
		$this->js = '<script language="JavaScript" src="'.JAVASCRIPT_DIR_URL.'login.js"></script>';
	
		$this->age_menu = $this->buildAgeRangeMenu(18, 120, "min_age", 25, "indexFormElements")
						.' y '.$this->buildAgeRangeMenu(18, 120, "max_age", 45, "indexFormElements");
		
		$countries_menu = $this->getCountrySelectMenu();
		require_once(INCLUDES_DIR.'/smarty_setup.php');
		$this->smarty = new Smarty_su;
		$this->smarty->assign("js_top_load",$this->js);	
		$this->smarty->assign("title","AmigoCupido.com - Buscar Pareja ,Latin Dating, Citas en Linea : Citas con hispanos solteros:");
		$this->smarty->assign("age_menu",$this->age_menu);
		$this->smarty->assign("countries_menu",$countries_menu);
		//$this->smarty->assign("content_wide",$this->content);
	
		$this->smarty->display('block_home.tpl');	
	}
	
	public function printMemberHome(){
		$this->lang = $GLOBALS['lang'];
		$this->js = '<script language="JavaScript" src="'.JAVASCRIPT_DIR_URL.'jquery.js"></script>'."\n";
		$this->js .= '<script language="JavaScript" src="'.JAVASCRIPT_DIR_URL.'memberHome.js"></script>';
		
		require_once(INCLUDES_DIR.'/smarty_setup.php');
		$this->smarty = new Smarty_su;
		$this->smarty->assign('js', $this->js);
		$this->smarty->assign("js_top_load",'<!--no js-->');
		//echo "logged in and ready to go ".$this->getNewMessageCount();
		//echo $this->lang['hello'];
		$this->smarty->assign("lang",$this->lang);
		$this->smarty->display('block_memberHome.tpl');
	}
	
	public function printMembersPage($datastream=''){
	//--------------------------------------------------------------------------------------------------
		global $lang;
		$this->lang = $lang;	
		if(!empty($datastream)) 
			$this->fillRequest($datastream);
		//var_dump($_COOKIE);
		//exit;
		
		if (!empty($_REQUEST['min_age'])){ $this->min_age = mysql_real_escape_string($_REQUEST['min_age']); }
		else{ $this->min_age = SEARCH_MIN_AGE_DEFAULT;}
		if (!empty($_REQUEST['max_age'])){ $this->max_age = mysql_real_escape_string($_REQUEST['max_age']); }
		else{ $this->max_age = SEARCH_MAX_AGE_DEFAULT;}
		 
		if(isset($_COOKIE['country'])){
			$this->countries_menu = $this->getCountrySelectMenu($_COOKIE['country']);	
		}else {
			$this->countries_menu = $this->getCountrySelectMenu();
		} 

		
		//--------------------------------------------------------------
		
		$this->data = array();
		require_once(INCLUDES_DIR.'/smarty_setup.php');
		$this->smarty = new Smarty_su;
			
		$this->smarty->assign("title", $this->lang['people_search']);
		
		$this->top_loads = '
		<script language="javascript" src="../js/prototype.js"></script>
		<script language="javascript" src="../js/scriptaculous/scriptaculous.js"></script>
		<script language="javascript" src="../js/people_search-js.php"></script>';

		$this->smarty->assign("js_top_load",$this->top_loads);
		$this->smarty->assign("lang",$this->lang);
		
		if ($_COOKIE['f'] == 1 || !isset($_COOKIE['f'])) $this->data['check_f'] = true;
		if ($_COOKIE['m'] == 1 || !isset($_COOKIE['m'])) $this->data['check_m'] = true;
		if(!$this->data['check_f'] && !$this->data['check_m']){ //if nothing is selected then select both
			$this->data['check_f'] = true;
			$this->data['check_m'] = true;
		}
	
		//if(isset($_COOKIE['min_age'])) echo $_COOKIE['min_age']; else echo $this->min_age; 
		if(isset($_COOKIE['min_age'])) $this->min_age = intval($_COOKIE['min_age']); else $this->min_age = $this->min_age;
		if(isset($_COOKIE['max_age'])) $this->max_age = intval($_COOKIE['max_age']); else $this->max_age = $this->max_age;
		if($this->min_age < SEARCH_MIN_AGE_DEFAULT || $this->min_age > SEARCH_MAX_AGE_DEFAULT)
			$this->min_age = SEARCH_MIN_AGE_DEFAULT;
		if($this->max_age > SEARCH_MAX_AGE_DEFAULT || $this->max_age < SEARCH_MIN_AGE_DEFAULT)
			$this->max_age = SEARCH_MAX_AGE_DEFAULT;

		$this->data['min_age'] = $this->min_age;
		$this->data['max_age'] = $this->max_age;
		
		$this->smarty->assign("data",$this->data);
		$this->smarty->assign("countries_menu",$this->getCountrySelectMenu());
	
		$this->smarty->display('block_findFriends.tpl');
	}

	/**
	 * Builds a select menu for selecting age like min age and max age
	 * @param int $from the age from which the menu starts
	 * @param int $to the age at which the menu stops
	 * @param string $id id property of the menu
	 * @param int $select the age at which the menu is initially selected at (optional)
	 * @param string_type $class the class property of the menu (optional)
	 * @return string An html select menu for selecting age
	 */
	public function buildAgeRangeMenu($from,$to,$id,$select="",$class=""){
		$this->out = "";
		$this->menuClass = !empty($class)?'class="'.$class.'"':"";
		$this->out = '<select name="'.$id.'" id="'.$id.'" '.$class.' style="width:45px;">'."\n";
		for ($this->i = $from; $this->i <= $to; $this->i++) {
			if(!empty($select) && $select == $this->i)
				$this->out .= '<option value="'.$this->i.'" selected="selected">'.$this->i.'</option>'."\n";
			else
				$this->out .= '<option value="'.$this->i.'">'.$this->i.'</option>'."\n";
		}
		$this->out .= '</select>'."\n";
		return $this->out;
	                        
	}
	
	/**
	 * Get json encoded records of friends
	 * @param string $userid
	 * @param string $limit How many records to return, default is 10
	 * @param string $friend_status 0 for unconfirmed, 1 for confirmed and nothing for both
	 * @return string json_encoded records or false if there's no user_id
	 */
	public function jsonFriends($userid='',$limit="10",$friend_status="all"){
		$this->uid = !empty($userid)?$userid:$_SESSION[SESSION_VARIABLE];
		if($friend_status == "confirmed")
			$this->friend_status = "1";
		elseif ($friend_status == "unconfirmed")
			$this->friend_status = "0";
		elseif ($friend_status == "all") 
			$this->friend_status = "";

		//return $this->uid;
		if(!empty($this->uid)){
			$this->sql = "SELECT b.buddy_uid as uid, b.confirmed as confirmed, u.user_username as username from ".BUDDIES_TABLE." as b "
						." JOIN users as u on u.user_id = b.buddy_uid"
						." WHERE user_uid = ".$this->uid;
			$this->sql .= ($friend_status != "all")? " AND b.confirmed = ".$this->friend_status:'';
			$this->sql .= " ORDER BY confirmed, date_added desc ";
			$this->sql .= (!empty($limit))? " LIMIT ".intval($limit):'';
			//return $this->sql;
			//if ( !($this->result = $this->db->Query($this->sql)) ) { 
			if(!($this->resultarray = $this->db->QueryArray($this->sql))) {
	            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sqlcount);
	            $this->db->Kill();
	        } else {
				//$this->count_rows = mysql_num_rows($this->result);
				//$this->resultarray = mysql_fetch_assoc($this->result);
				//return $this->resultarray;
				return json_encode($this->resultarray);
			}
		} else {
			return false;
		}
	}
	
	public function parseQueryString($str) {
	    $this->op = array();
	    $this->pairs = explode("&", $str);
	    foreach ($this->pairs as $this->pair) {
	        list($this->k, $this->v) = array_map("urldecode", explode("=", $this->pair));
	        $this->op[$this->k] = $this->v;
	    }
	    return $this->op;
	} 
	
	public function redirect($url,$permanent = false)
	{
		if (strstr(urldecode($url), "\n") || strstr(urldecode($url), "\r"))
		{
			message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
		}
		if($permanent)
			header('HTTP/1.1 301 Moved Permanently');
			
		header('Location: '.$url);
		exit();
	}
	
	public function getMessages($uid,$msgType='new'){
		global $lang;
		require_once(INCLUDES_DIR.'/smarty_setup.php');
		$this->smarty = new Smarty_su;
		$this->top_loads = '
		<script language="javascript" src="../js/jquery.js"></script>
		<script language="javascript" src="../js/messages.js"></script>';
		$this->smarty->assign("js_top_load",$this->top_loads);
		$this->smarty->assign("lang",$lang);
		//$this->smarty->display('block_messages.tpl');
		$this->smarty->display('block_messages.tpl');
	}
	
	/**
	 * Gets the unread and new message count
	 * @param string $userid
	 * @return int Number of messages or boolean false if not logged in
	 */
	function getNewMessageCount($userid="") { //
		// Private messaging
		//$this->uid = !empty($userid)?$userid:$_SESSION[SESSION_VARIABLE];

		if (isset($_SESSION[SESSION_VARIABLE])){ //let's do this only if logged in
			$this->uid = !empty($userid)?$userid:$_SESSION[SESSION_VARIABLE];
			
			$this->sql = "SELECT COUNT(*)  as messageCount 
			FROM " . PRIVMSGS_TABLE . " as pm
			JOIN ".SITE_USERS_TABLE." as u on u.user_id = pm.privmsgs_from_userid
			WHERE privmsgs_to_userid = ". $this->uid."
			AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . ", " . PRIVMSGS_UNREAD_MAIL . ")";
			//$this->result	= @mysql_query($sql);
			//echo "sql:".$this->sql;
			//exit;
			
			if ( !($this->msgresult = $this->db->Query($this->sql)) ) { 
			//if (!$this->messageCount = $this->db->QuerySingleValue($this->sql)){
	            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
	            $this->db->Kill();
	        } 
	        //$this->messageCount = mysql_f
			//return $this->messageCount;
			return mysql_result($this->msgresult,0);
			} else {
			return false;
		}
	}

	/**
	 * Gets the messages in json format by default gets the new messages
	 * @param string $userid
	 * @param string the type of messages to get
	 * @param int Number of characters of the message that you want returned 
	 * @return int Number of messages or boolean false if not logged in
	 */
	function getJsonMessages($userid="",$type="new",$msg_chars=0) { //
		$this->messagesArray = $this->getMessagesArray($userid,$type,$msg_chars);
		//if (!empty($this->messagesArray)){
			return json_encode($this->messagesArray);
		//} else {
			//return false;
		//}
	}
	
	
	public function getMessagesArray($userid="",$type="new",$msg_chars=0) { 
	
	/*defined('PRIVMSGS_READ_MAIL') or define('PRIVMSGS_READ_MAIL', 0);
	defined('PRIVMSGS_NEW_MAIL') or define('PRIVMSGS_NEW_MAIL', 1);
	defined('PRIVMSGS_SENT_MAIL') or define('PRIVMSGS_SENT_MAIL', 2);
	defined('PRIVMSGS_SAVED_IN_MAIL') or define('PRIVMSGS_SAVED_IN_MAIL', 3);
	defined('PRIVMSGS_SAVED_OUT_MAIL') or define('PRIVMSGS_SAVED_OUT_MAIL', 4);
	defined('PRIVMSGS_UNREAD_MAIL') or define('PRIVMSGS_UNREAD_MAIL', 5);*/
		
		if (isset($_SESSION[SESSION_VARIABLE])){ //let's do this only if logged in
			$this->uid = !empty($userid)?intval($userid):intval($_SESSION[SESSION_VARIABLE]);
			
			// according to phpbb setttings
			switch (strtolower($type)) {
			    case 'read':
			        $this->type = 'IN(0)';
			        break;
			    case 'sent':
			        $this->type = 'IN(2)';
			        break;
			    case 'saved_in':
			        $this->type = 'IN(3)';
			        break;
			    case 'saved_out':
			        $this->type = 'IN(4)';
			        break;
			    case 'new':
			       	$this->type = 'IN(1,5)';
			        break;
			}
			
			
			$this->sql = "SELECT pm.privmsgs_id as msg_id, 
				privmsgs_subject as subject, 
				pm.privmsgs_type as type,
				u.user_id as from_uid,
				u.user_username as from_username, ";
			$this->sql .= (intval($msg_chars) > 0)? "LEFT(privmsgs_text,".intval($msg_chars).")":"privmsgs_text";
			$this->sql .= " as message, 
			privmsgs_from_userid as from_uid,
			FROM_UNIXTIME(privmsgs_date) as date
			FROM " . PRIVMSGS_TABLE . " pm
			LEFT JOIN phpbb_privmsgs_text t on pm.privmsgs_id = t.privmsgs_text_id
			JOIN ".SITE_USERS_TABLE." u on u.user_id = pm.privmsgs_from_userid
			WHERE privmsgs_to_userid = ".$this->uid."
			AND privmsgs_type ".$this->type."
			ORDER BY date";

			//echo "sql:".$this->sql;
			//exit;
			
			
			//if(!($this->resultarray = @$this->db->QueryArray($this->sql))) {
			if ( !($this->msgsresult = $this->db->Query($this->sql)) ) { 
	            printf('Could not do query at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
				//print 'rows:'.$this->db->RowCount();
	            $this->db->Kill();

	        } 
	        $this->resultarray = array();
			while($this->row = mysql_fetch_array($this->msgsresult,MYSQL_ASSOC)){
			       $this->resultarray[] = $this->row;
			}
			//var_dump($this->resultarray);
			//exit;
			return $this->resultarray;
		}
	}
	
	public function getProfileViewsCount($userid=""){
		$this->uid = !empty($userid)?$userid:$_SESSION[SESSION_VARIABLE];
		$this->sql = "SELECT * from ".USERS_PROFILE_VIEWS_TABLE." where upv_uid=".(int)$this->uid;
		if (!$this->count = $this->db->QuerySingleValue($this->sql)){
			printf('Could not select records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
			$this->db->Kill();
		}
		return $this->count;
	}
	
}


?>