<?PHP

function phpbb_logout( $session_id, $phpbb_user_id ) {
	global $db, $lang, $board_config,$phpbb_root_path,$phpEx;
	global $_COOKIE, $_GET, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process
        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( $_SERVER['DOCUMENT_ROOT'].'/foros/config.php' );
    
        define('IN_PHPBB',true);

        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = $_SERVER['DOCUMENT_ROOT']."/foros/";

	require_once( $phpbb_root_path . "extension.inc" );
	require_once( $phpbb_root_path . "common.php" );

	session_end( $session_id, $phpbb_user_id );

	// session_end doesn't seem to get rid of these cookies,
	// so we'll do it here just in to make certain.
	setcookie( $board_config[ "cookie_name" ] . "_sid", "", time() - 3600, " " );
	setcookie( $board_config[ "cookie_name" ] . "_mysql", "", time() - 3600, " " );

}



if (phpversion() >= 4) {
	// phpversion = 4
	session_start();
	// session hack to make sessions on old php4 versions work
	
	//logout from phpbb
	if(isset($_SESSION['user_id']))
		phpbb_logout( session_id(), $_SESSION['user_id']) ;
	
	if (phpversion() > 4.0) {
		unset($_SESSION['login']);
		unset($_SESSION['password']);
		unset($_SESSION['user_id']);
	} else {
		session_unregister("login");
		session_unregister("password");
	}
	session_destroy();
	$sessionPath = session_get_cookie_params(); 
	setcookie(session_name(), "", 0, $sessionPath["path"], $sessionPath["domain"]); 
} else {
	// phpversion = 3
	session_destroy_php3();
   setcookie($cookieName, "", 0);
}
?>