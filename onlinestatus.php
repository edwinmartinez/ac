<?php 
include_once('includes/config.php');
session_start();
//select db
	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);

	//$uid = $_REQUEST['uid'];
	$uid = $_SESSION['user_id'];

require_once('includes/usersOnline.class.php');

 $uo = new usersOnline($uid);
 $friends = $uo->check_in();
 


	$json = '{
	"friendsonline":'.count($friends).',
    "friendsdata": ['."\n";
		    if(!empty($friends)){
				foreach($friends as $friend){
				$json .= '    {'."\n";
				$json .= '        "uid":"'.$friend['uid'].'",'."\n";
				$json .= '        "username":"'.$friend['username'].'"'."\n";
				$json .= '    },'."\n";
				}
				$json = substr($json, 0, -2); //remove the last new line and comma
			}
    $json .= '
	]
}';
	echo $json;



?>