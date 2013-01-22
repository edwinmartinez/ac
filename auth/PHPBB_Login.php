<?

/*
 * PHPBB_Login allows you to integrate your own login system
 * with phpBB. Meaning that you can have one login valid across
 * both your website and phpBB.
 *
 * To take full advantage of this PHPBB_Login class you just
 * need to modify your own login system to include a call
 * to the relevant methods in here.
 *
 * This system is reliant on the website username being exactly
 * the same as the phpBB username. To insure this, I recommend
 * disabling the ability to change usernames from within the 
 * phpBB admin control panel.
 *
 * Distributed under the LGPL license:
 * http://www.gnu.org/licenses/lgpl.html
 *
 * Duncan Gough
 * 3rdSense.com
 *
 * Home  http://www.suttree.com
 * Work  http://www.3rdsense.com
 * Play! http://www.playaholics.com
 */

class PHPBB_Login {

    function PHPBB_Login() {
    }

    function login( $phpbb_user_id ) {
        global $db, $board_config;
        global $_COOKIE, $_GET, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process

        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( './forum/config.php' );
    
        define('IN_PHPBB',true);

        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = "./forum/";
        
        require_once( $phpbb_root_path . "extension.inc" );
        require_once( $phpbb_root_path . "common.php" );

        return session_begin( $phpbb_user_id, $user_ip, PAGE_INDEX, FALSE, TRUE );
    
    }

    function logout( $session_id, $phpbb_user_id ) {
        global $db, $lang, $board_config;
        global $_COOKIE, $_GET, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process

        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( './forum/config.php' );
    
        define('IN_PHPBB',true);
        
        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = "./forum/";

        require_once( $phpbb_root_path . "extension.inc" );
        require_once( $phpbb_root_path . "common.php" );

        session_end( $session_id, $phpbb_user_id );
    
        // session_end doesn't seem to get rid of these cookies,
        // so we'll do it here just in to make certain.
        setcookie( $board_config[ "cookie_name" ] . "_sid", "", time() - 3600, " " );
        setcookie( $board_config[ "cookie_name" ] . "_mysql", "", time() - 3600, " " );

    }

}

?>