<?php
require("includes/config.php");
function fix_things(){
	global $dbhost,$dbuser,$dbpasswd,$dbname,$phpbb_root_dir;
	$icon_count = 0;
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
				die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	$sql = "SELECT count(*) as cnt, u.user_username as username, g.photo_uid, g.use_in_profile from " . USERS_GALLERY_TABLE 
			." g, ".SITE_USERS_TABLE." u WHERE u.user_id = g.photo_uid group by u.user_id";
					
		if ( !($result = mysql_query($sql)) ){
				printf('Could not select records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
		}
	while($row = mysql_fetch_assoc($result)){
		echo $row['cnt']." ".$row['username']."<br>\n";
		$sql2 = "SELECT photo_id,use_in_profile,uploaded_date from ".USERS_GALLERY_TABLE." where photo_uid=".$row['photo_uid'];
		$found_icon = 0;
		if ( !($result2 = mysql_query($sql2)) ){printf('Could not select records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql2);}
		while($row2 = mysql_fetch_assoc($result2)){
			echo "&nbsp;&nbsp;-".$row2['photo_id']." use:".$row2['use_in_profile']." ".$row2['uploaded_date']."<br>\n";
			if($row2['use_in_profile'] == 1){
				$found_icon = 1;
				$icon_count++;
			}
		}
		if(!$found_icon){ 
			echo "&nbsp;&nbsp;<font color=red>no icon</font><br>\n";
			$sql3 = "UPDATE  ".USERS_GALLERY_TABLE." set use_in_profile=1 where photo_uid=".$row['photo_uid']." limit 1";
			if ( !($result2 = mysql_query($sql3)) ){printf('Could not update records at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql3);}
			echo "&nbsp;&nbsp;<font color=green>fixed</font><br>\n";
		}
		
	}

	echo "There are ".mysql_num_rows($result)." users with pictures and ".$icon_count." have icons<br>\n";
}



///if($_POST['cmd'] == 'fixit'){
	fix_things();
//}else{
	?>
<form method="post" action="icon_fix3.php">
	<input type="button" name="cmd" value="fixit"  />
</form>	
<?php
 print_r($_POST);
 
//}
?>