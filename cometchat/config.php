<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* BASE URL */

define('BASE_URL','cometchat/');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LANGUAGE */

$language[0]	=	"Opciones del Chat";
$language[1]	=	"Escribe tu estatus y presiona la tecla enter!";
$language[2]	=	"Mi Estatus";
$language[3]	=	"Available";
$language[4]	=	"Busy";
$language[5]	=	"Invisible";
$language[6]	=	"Add Friend";
$language[7]	=	"<a href=\"./friends.php\">Add more friends</a>";
$language[8]	=	"Por favor logea al sistema para usar el Chat";
$language[9]	=	"Who\'s Online";
$language[10]	=	"Yo";
$language[11]	=	"Go Offline";
$language[12]	=	"Who\'s Online";
$language[13]	=	"Desactivar notificaciones de sonido";
$language[14]	=	"You have no friends in your friend list, please add a few friends to use chat";
$language[15]	=	"Nuevos mensajes...";
$language[16]	=	"http://www.amigocupido.com/login/"; // Login link when user clicks on yellow triangle (specify only link i.e. http://yoursite.com/login.php)
$language[17]	=	"Offline";

$status['available']	=	"I'm available";
$status['busy']			=	"I'm busy";
$status['offline']		=	"I'm offline";
$status['invisible']	=	"I'm offline";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ICONS */

$trayicon[] = array('home.png','Home','/');
$trayicon[] = array('chatrooms.png','Chatrooms',BASE_URL.'modules/chatrooms/index.php','_popup','500','300');
$trayicon[] = array('themechanger.png','Change theme',BASE_URL.'modules/themechanger/index.php','_popup','200','100');
$trayicon[] = array('games.png','Single Player Games',BASE_URL.'modules/games/index.php','_popup','650','490');
$trayicon[] = array('share.png','Share This Page','http://www.addthis.com/bookmark.php','_popup','480','400');
$trayicon[] = array('translate.png','Translate This Page',BASE_URL.'modules/translate/index.php','_popup','300','200');
// $trayicon[] = array('twitter.png','Twitter',BASE_URL.'modules/twitter/index.php','_popup','500','300');
// $trayicon[] = array('facebook.png','Facebook Fan Page',BASE_URL.'modules/facebook/index.php','_popup','500','470');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* PLUGINS */

$plugins = array(

	'filetransfer',
	'divider',
	'clearconversation',
	'chathistory',
	'chattime',
	'games',
	'handwrite'

);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SMILEYS */

$smileys = array( 

	':)'	=>	'smiley',
	':-)'	=>	'smiley',
	':('	=>	'smiley-sad',
	':-('	=>	'smiley-sad',
	':D'	=>	'smiley-lol',
	';-)'	=>	'smiley-wink',
	';)'	=>	'smiley-wink',
	':o'	=>	'smiley-surprise',
	':-o'	=>	'smiley-surprise',
	'8-)'	=>	'smiley-cool',
	'8)'	=>	'smiley-cool',
	':|'	=>	'smiley-neutral',
	':-|'	=>	'smiley-neutral',
	":'("	=>	'smiley-cry',
	":'-("	=>	'smiley-cry',
	":p"	=>	'smiley-razz',
	":-p"	=>	'smiley-razz',
	":s"	=>	'smiley-confuse',
	":-s"	=>	'smiley-confuse',
	":x"	=>	'smiley-mad',
	":-x"	=>	'smiley-mad',

);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* BANNED WORDS */

$bannedWords = array(

	'asshole','fuck','bastard','bitch','puta', 'mierda', 'verga'

); 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADMIN */

define('ADMIN_USER','2kyomama');
define('ADMIN_PASS','2kyomama');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* COOKIE */

$cookiePrefix = 'cc_';				// Modify only if you have multiple CometChat instances on the same site

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* THEME */

$theme = 'default';					// Default theme, if no cookie preference is found

if (!empty($_COOKIE[$cookiePrefix."theme"])) {
	$theme = $_COOKIE[$cookiePrefix."theme"];
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* MISCELLANEOUS */

$autoPopupChatbox = 0;				// Auto-open chatbox when a new message arrives
$messageBeep = 1;					// Beep on arrival of new messages (user can over-ride this setting)
$minHeartbeat = 3000;				// Minimum poll-time
$maxHeartbeat = 12000;				// Maximum poll-time
define('REFRESH_BUDDYLIST','60');	// Time in seconds after which the user's "Who's Online" list is refreshed
define('ONLINE_TIMEOUT','30');		// Time in seconds after which a user is considered offline
define('DISABLE_SMILEYS','0');		// Set to 1 if you want to disable smileys
define('DISABLE_LINKING','0');		// Set to 1 if you want to disable auto linking
define('DISABLE_YOUTUBE','0');		// Set to 1 if you want to disable YouTube thumbnail

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

define('DEV_MODE','1');					// Set to 1 only during development
define('ERROR_LOGGING','1');			// Set to 1 to log all errors (error.log file)
define('SET_SESSION_NAME','');			// Session name
define('DO_NOT_START_SESSION','0');		// Set to 1 if you have already started the session
define('DO_NOT_DESTROY_SESSION','0');	// Set to 1 if you do not want to destroy session on logout

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */

define('DB_SERVER',			'localhost'							);
define('DB_PORT',			'3306'								);
define('DB_USERNAME',			'martinm4_ac'							);
define('DB_PASSWORD',			'2kYomama'							);
define('DB_NAME',			'martinm4_acdb'							);
define('TABLE_PREFIX',			''								);
define('DB_USERTABLE',			'users'								);
define('DB_USERTABLE_NAME',		'user_username'							);
define('DB_USERTABLE_USERID',		'user_id'							);
define('DB_USERTABLE_LASTACTIVITY',	'lastactivity'							);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* FUNCTIONS */

function getUserID() {
	$userid = 0;
	
	if (!empty($_SESSION['user_id'])) {
		$userid = $_SESSION['user_id'];
	}

	return $userid;
}


function getFriendsList($userid,$time) {
	$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX."friends join ".TABLE_PREFIX.DB_USERTABLE." on  ".TABLE_PREFIX."friends.toid = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX."friends.fromid = '".mysql_real_escape_string($userid)."' order by username asc");
	return $sql;
}

function getUserDetails($userid) {
	$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	return $sql;
}

function updateLastActivity($userid) {
	$sql = ("update `".TABLE_PREFIX.DB_USERTABLE."` set ".DB_USERTABLE_LASTACTIVITY." = '".getTimeStamp()."' where ".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	return $sql;
}

function getUserStatus($userid) {
	 $sql = ("select cometchat_status.message, cometchat_status.status from cometchat_status where userid = '".mysql_real_escape_string($userid)."'");
	 return $sql;
}

function getLink($link) {
    return 'users.php?id='.$link;
}

function getAvatar($image) {
    if (is_file(dirname(dirname(__FILE__)).'/images/'.$image.'.gif')) {
        return 'images/'.$image.'.gif';
    } else {
        return 'images/noavatar.gif';
    }
}


function getTimeStamp() {
	return time();
}

function processTime($time) {
	return $time;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* HOOKS */

function hooks_statusupdate($userid,$statusmessage) {
	
}

function hooks_forcefriends() {
	
}

function hooks_activityupdate($userid,$status) {

}

function hooks_message($userid,$unsanitizedmessage) {
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////