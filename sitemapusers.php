<?php
include('includes/config.php'); 
include('includes/googlesitemapgen/googlesitemapgen.php');
// Create the GoogleSiteMap XML String
$map=new GoogleSiteMap();
//Add associative array of URL with full arguments


mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
$sql = "SELECT user_id, user_username as username, user_email from ".SITE_USERS_TABLE." where 1";
if ( !($result = mysql_query($sql)) ) {
	printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	exit;
}else{
	$total_rows = mysql_num_rows($result);
	if ($total_rows > 0) {
		for($i=0;$i<$total_rows;$i++){
			$row = mysql_fetch_assoc($result);
			$a[$i] = array(
				loc=>PROFILE_DIR_URL.'/'.strtolower($row['username']),
				//lastmod=>"",
				changefreq=>"monthly",
				priority=>"0.4"
				);
		}
		
		$map->Add($a);
		//close the XML String
		$map->Close();
		//Output the XML String
		$map->View();
	}
}
	



/*$a=array(
	array(loc=>"http://www.test.com/index.php?cat=boutique&id=7",lastmod=>"",changefreq=>"always",priority=>"0.2"),
	//Add another url with full option
 	array(loc=>"http://www.test.com/index.php?cat=boutique&id=8",lastmod=>"2006-07-11T11:39:38+02:00",changefreq=>"never",priority=>"0.9")
);*/


?>