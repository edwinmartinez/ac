<?php
/*
Page:           rpc.php
Created:        Aug 2006
Last Mod:       Mar 18 2007
This page handles the 'AJAX' type response if the user
has Javascript enabled.
--------------------------------------------------------- 
ryan masuga, masugadesign.com
ryan@masugadesign.com 
Licensed under a Creative Commons Attribution 3.0 License.
http://creativecommons.org/licenses/by/3.0/
See readme.txt for full credit details.
--------------------------------------------------------- */
//header("Cache-Control: no-cache");
//header("Pragma: nocache");
header('Content-Type: text/xml');

require("../includes/config.php"); // get the db connection info
mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
mysql_select_db($dbname);
$rating_dbname = $dbname;
$rating_tableName = 'ratings';

//getting the values
$vote_sent = preg_replace("/[^0-9]/","",$_REQUEST['j']);
$id_sent = preg_replace("/[^0-9a-zA-Z]/","",$_REQUEST['q']);
$ip_num = preg_replace("/[^0-9\.]/","",$_REQUEST['t']);
$units = preg_replace("/[^0-9]/","",$_REQUEST['c']);
$ip = $_SERVER['REMOTE_ADDR'];

//if ($vote_sent > $units) die("Sorry, vote appears to be invalid."); // kill the script because normal users will never see this.


//connecting to the database to get some information
$query = mysql_query("SELECT total_votes, total_value, used_ips FROM ratings WHERE id='$id_sent' ")or die(" Error: ".mysql_error());
$numbers = mysql_fetch_assoc($query);
$checkIP = unserialize($numbers['used_ips']);
$count = $numbers['total_votes']; //how many votes total
$current_rating = $numbers['total_value']; //total number of rating added together and stored
$sum = $vote_sent+$current_rating; // add together the current vote value and the total vote value
$tense = ($count==1) ? "vote" : "votes"; //plural form votes/vote


// checking to see if the first vote has been tallied
// or increment the current number of votes
($sum==0 ? $added=0 : $added=$count+1);

// if it is an array i.e. already has entries the push in another value
((is_array($checkIP)) ? array_push($checkIP,$ip_num) : $checkIP=array($ip_num));
$insertip=serialize($checkIP);

//IP check when voting
$voted=mysql_num_rows(mysql_query("SELECT used_ips FROM ratings WHERE used_ips LIKE '%".$ip."%' AND id='".$id_sent."' "));
if(!$voted) {     //if the user hasn't yet voted, then vote normally...
	if (($vote_sent >= 1 && $vote_sent <= $units) && ($ip == $ip_num)) { // keep votes within range, make sure IP matches - no monkey business!
		$update = "UPDATE ratings SET total_votes='".$added."', total_value='".$sum."', used_ips='".$insertip."' WHERE id='$id_sent'";
		//$update = "UPDATE ratings SET total_votes='".$added."', total_value='".$sum."' WHERE id='$id_sent'";
		$result = mysql_query($update);		
	} 
} //end for the "if(!$voted)"
// these are new queries to get the new values!
$newtotals = mysql_query("SELECT total_votes, total_value, used_ips FROM ratings WHERE id='$id_sent' ")or die(" Error: ".mysql_error());
$numbers = mysql_fetch_assoc($newtotals);
$count = $numbers['total_votes'];//how many votes total
$current_rating = $numbers['total_value'];//total number of rating added together and stored
$tense = ($count==1) ? "vote" : "votes"; //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote

$new_back = '<span class="thanks">Thanks for voting!</span></p>';

//$allnewback = join("\n", $new_back);

// ========================

//name of the div id to be updated | the html that needs to be changed
$output = "unit_long$id_sent|$allnewback";
//echo $output;
echo '<?xml version="1.0"?>'."\n";
echo '<rating>' . $vote_sent . '</rating>';
?>