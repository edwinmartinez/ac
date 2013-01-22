<?php

// USAGE:
include('includes/config.php');
require_once("./includes/easyrss/class.easyrss.php");
$rss = new easyRSS;


$males = get_users (6,1,USER_CREATED_FIELD,'desc',1);
$females = get_users (6,2,USER_CREATED_FIELD,'desc',1);
$members = array_merge($females,$males);
/*echo "<pre>";
print_r($members);
echo "</pre>";*/

for($i=0;$i<=count($members)-1;$i++){
//2007-02-14 12:09:59
if($members[$i]['user_gender'] == 1){
	$members[$i]['sex'] = 'M';
}elseif($members[$i]['user_gender'] == 2){
	$members[$i]['sex'] = 'F';
}

list($date,$hour) = explode(' ',$members[$i]['user_created']);
list($year,$month,$day) = explode('-',$date);
list($hour,$min,$sec) = explode(':',$hour);
$items[$i]['pubDate'] = mktime($hour,$min,$sec,$month,$day,$year);
$items[$i]['title'] = $members[$i]['user_username'].' ('.$members[$i]['sex'].' '.$members[$i]['age'].') '.utf8_encode($members[$i]['user_city']).', '.$members[$i]['country'];
$items[$i]['description']='<img src="'.SITE_URL.$members[$i]['photo'].'" />'."<br>".truncate(htmlentities($members[$i]['about_me']),55);
//$items[$i]['pubDate']='';
$items[$i]['link']= PROFILE_DIR_URL.'/'.$members[$i]['user_username'];
$items[$i]['guid']= PROFILE_DIR_URL.'/'.$members[$i]['user_username'];
}

$SortOrder=0;
$items = sortByField($items,'pubDate',$SortOrder);
/*echo '<pre>';
print_r($items);
echo '</pre>';*/

// 2nd example
$rss_array = array(
"encoding"=>"UTF-8",
"language"=>"en-us",
"title"=>"AmigoCupido.com Nuevos Miembros", // This field is mandatory
"description"=>"Los Miembros mas calientes de AmigoCupido.com", // This field is mandatory
"link"=>"http://www.amigocupido.com", // This field is mandatory
"items"=>$items
);
header("Content-type: application/xml");
echo $rss->rss($rss_array, "latestusers.xsl"); // Second parameter is not required

?>