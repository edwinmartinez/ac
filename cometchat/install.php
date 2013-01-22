<?php

/*

CometChat
Copyright (c) 2009 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts 
retains ownership of the Software and any copies of it, regardless of the 
form in which the copies may exist. This license is not a sale of the 
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each 
installed instance of the Software, a separate license is required. 
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts. 

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form. 

The Software source code may be altered (at your risk) 

All Software copyright notices within the scripts must remain unchanged (and visible). 

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to, 
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets, 
software piracy, and patents held by individuals, corporations, or other entities. 

If any of the terms of this Agreement are violated, Inscripts reserves the right 
to revoke the Software license at any time. 

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

include (dirname(__FILE__))."/cometchat_init.php";

$body = '';
$path = '';

if (empty($_GET)) {
	$body = <<<EOD
<form method="post" action="?step=2">
<strong>Thank you for purchasing CometChat</strong><br/><br/>Please enter your CometChat members area username and password to continue:
<br/><br/>
<table width=398><tr>
<td width=30>Username:</td><td><input type="textbox" name="username" class="textbox"></td></tr>
<tr><td>Password:</td><td><input type="password" name="password" class="textbox"></td></tr>
</table>
<br/><br/>
<input type="image" src="images/nextstep.gif">
</form>
EOD;
} else {

if ($_GET['step'] == "2") {

$path = "http://www.cometchat.com/validate2.php?username={$_POST['username']}&password={$_POST['password']}&path={$_SERVER['SERVER_NAME']}/{$_SERVER['SERVER_ADDR']}/{$_SERVER['SCRIPT_NAME']}";

$rollback = 0;
$errors = '';

	$content = <<<EOD
RENAME TABLE `cometchat` to `cometchat_old`;
RENAME TABLE `cometchat_status` to `cometchat_status_old`;

CREATE TABLE  `cometchat` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  `read` int(10) unsigned NOT NULL,
  `direction` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`),
  KEY `direction` (`direction`),
  KEY `read` (`read`),
  KEY `sent` (`sent`)
) DEFAULT CHARSET=utf8;

CREATE TABLE  `cometchat_chatroommessages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `chatroomid` (`chatroomid`),
  KEY `sent` (`sent`)
) DEFAULT CHARSET=utf8;

CREATE TABLE  `cometchat_chatrooms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `lastactivity` (`lastactivity`),
  KEY `createdby` (`createdby`)
) DEFAULT CHARSET=utf8;

CREATE TABLE  `cometchat_chatrooms_users` (
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`userid`),
  KEY `chatroomid` (`chatroomid`),
  KEY `lastactivity` (`lastactivity`)
) DEFAULT CHARSET=utf8;

CREATE TABLE  `cometchat_status` (
  `userid` int(10) unsigned NOT NULL,
  `message` text ,
  `status` varchar(10) default NULL,
  PRIMARY KEY  (`userid`)
) DEFAULT CHARSET=utf8;

EOD;
 
	$q = preg_split('/;[\r\n]+/',$content);

	foreach ($q as $query) {
		if (strlen($query) > 4) {
		$result = mysql_query($query);
			if (!$result) {
				$rollback = 1;
				$errors .= mysql_error()."<br/>\n";
			}
		}
	}

	$extra = '';

	$baseurl = preg_replace('/install.php/i','',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']));

	$file = 'config.php';
	$content = @file_get_contents($file);

	if ($content != '') {

		$myvar = "define('BASE_URL','{$baseurl}');";

		$content = str_replace("define('BASE_URL','cometchat/');",$myvar, $content);

		$f = @fopen($file,'w');
		if($f) {
		  @fwrite($f, $content);
		  @fclose($f);
		} else {
		  $extra = "In config.php set the BASE_URL to {$baseurl} i.e. <b>define('BASE_URL','{$baseurl}');</b>";
		}
	}

	$body = "Database was successfully configured. <a href=\"code.php\">Click here to view the footer code</a><br/><br/>$extra";			
}

}



?>
<html>
<head>
<title>CometChat Setup</title>
<style>
body {
padding:0;
margin:0;
font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
font-size: 14px;
color: #333333;

}
.setup {
width: 398px;
padding:10px;
}

td {
font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
font-size: 14px;
color: #333333;

}

.textbox {
width: 200px;
font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
font-size: 14px;
color: #333333;
border: 1px dotted black;
}
</style>
</head>
<body>
<img src="images/setup.gif"><br clear="all"/>
<img src="<?php echo $path;?>" height=1 width=1>
<div class="setup"><?php echo $body;?>
</div>
</body>
</html>