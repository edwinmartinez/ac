<?php

// USAGE:
require_once("./class.easyrss.php");
$rss = new easyRSS;

// Check out these two examples to find out how it works:
/*
// 1st example:
$a = $rss->parse(implode(file("./rss.xml")), "Y-m-d H:i:s"); // If the second parameter is undefined, the unix timestamp will be returned instead of the string returned by php's date() function
print_r($a);
*/

// 2nd example
$rss_array = array(
"encoding"=>"UTF-8",
"language"=>"en-us",
"title"=>"My blog", // This field is mandatory
"description"=>"I am using EasyRSS to create this stuff", // This field is mandatory
"link"=>"http://www.amigocupido.com", // This field is mandatory
"items"=>array(
	0=>array(
		"title"=>"My first blog", // This field is mandatory
		"description"=>"Hello! This is my first time using EasyRSS", // This field is mandatory
		"pubDate"=>"",
		"link"=>"http://localhost/first"
	),
	1=>array(
		"title"=>"Exceptions of using XSL stylesheet", // This field is mandatory
		"description"=>"When using XSL stylesheet, remember that Mozilla does not parse HTML tags. Though IE6 accepts HTML <b>tags</b> and parses them, Mozilla does not (and will never do). So using disable-output-escaping=&quot;yes&quot; parameter in &lt;xsl:text&gt; and &lt;xsl:value-of&gt; would be effective in MSIE only.", // This field is mandatory
		"pubDate"=>1093387161, // pubDate MUST BE the unix timestamp
		"link"=>"http://bugzilla.mozilla.org/show_bug.cgi?id=98168"
	)
)
);
header("Content-type: application/xml");
echo $rss->rss($rss_array, "rss.xsl"); // Second parameter is not required

?>