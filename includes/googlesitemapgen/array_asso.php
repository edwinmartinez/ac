<?php
include_once "googlesitemapgen.php";
// Create the GoogleSiteMap XML String
$map=new GoogleSiteMap();
//Add associative array of URL
$a=array(
		array(loc=>"http://www.test.com/index.php?cat=boutique&id=6"),
		array(loc=>"http://www.test.com/index.php?cat=boutique&id=60")
	);
$map->Add($a);
//close the XML String
$map->Close();
//Output the XML String
$map->View();
?>