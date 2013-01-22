<?

/*
 * These examples show you how to integrate the phpBB
 * login in and authentication system with your website.
 * 
 * Since we quite like our login system and it's proven
 * itself to be very extensible, we don't want to replace
 * but we do want to have a universal login system for
 * both our website *and* the forum.
 *
 * To take full advantage of this PHPBB_Login class you'll
 * need to modify your own login system to include a call
 * to the relevant login or logout methods.
 *
 * This way, you can handle all of the website login as normal,
 * and also log the user into phpBB in the same step.
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




/* Example 1: Logging in */

session_start();

/* First, login the user using your own login system, for example; */
$user = new User();

// username and password are implied here,
// they will most likely be form variables
$user->login( $username, $password );

// Then login the user to the forum
$phpBB = new PHPBB_Login();

$phpbb->login( $user->id );




/* Example 2: Logging out */

session_id();

$user = new User();

/* First, logout the user from the forum */
$phpBB = new PHPBB_Login();

$phpbb->logout( session_id(), $user->id) ;

/* Then logout the user from your own login system */
$user->logout( $user->id );

?>