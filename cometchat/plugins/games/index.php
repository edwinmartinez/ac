<?php

/*

CometChat - Games Plugin
Copyright (c) 2010 Inscripts

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

error_reporting(E_ALL);
ini_set('display_errors','On');

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php";

if (empty($_GET['action'])) {

$toId = $_GET['id'];

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Please select a game</title> 

<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
    text-align: center;
}

.small {
	font-weight:bold;
}

ul {
  float: left;
  width: 100%; 
  margin: 0;
  padding: 0;
  list-style: none;
}
 
li {
  text-align: left;
  float: left;
  width: 110px;
  margin: 0;
  height: 20px;
  background-image: url(bullet.png);
  background-position: 0 -1px;
  background-repeat: no-repeat;
  padding: 0 0 0 1.5em; 
  cursor: pointer;
} 
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$("li").click(function() {
		var info = $(this).attr('id').split(',');
		var gameId = info[0];
		var width = info[1];
		location.href = 'index.php?action=request&toId={$toId}&gameId='+gameId+'&gameWidth='+width;
	});
});

</script>

</head>
<body>
<div style="width:98%;margin:0 auto;margin-top: 5px;">
<div style="border-left: 1px solid #11648F;border-top: 1px solid #11648F;border-right: 1px solid #11648F;background-color:#3E92BD;color:#fff;padding:5px;font-weight:bold;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size: 14px;letter-spacing:0px;padding-left:10px;text-align:left;">Which game would you like to play?</div>

<div style="border-left: 1px solid #cccccc;border-bottom: 1px solid #cccccc;border-right: 1px solid #cccccc;background-color:#fffff;color:#333;padding:5px;font-weight:normal;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size:11px;padding:20px 10px;padding-bottom:0px;text-align:left;">

<ul class="games">
<li id="256,912">8-Ball Pool</li>
<li id="1,735">Backgammon</li>
<li id="562,815">Battleships</li>
<li id="1607,815">Brilliant Turn</li>
<li id="557,815">Cheat</li>
<li id="6,735">Checkers</li>
<li id="2,735">Chess</li>
<li id="275,815">Conectomato</li>
<li id="86,881">Darts</li>
<li id="325,690">Domino</li>
<li id="4,735">Four in a Row</li>
<li id="64,815">Go</li>
<li id="273,665">GoldMiner</li>
<li id="567,825">Hex Empire</li>
<li id="326,815">Hexaru</li>
<li id="330,665">Kaban Tactics</li>
<li id="602,747">Knights Domain</li>
<li id="329,815">Mancala</li>
<li id="102,845">Marbles</li>
<li id="21,735">Match 4</li>
<li id="272,650">MineSweeper</li>
<li id="274,815">Ramble Scramble</li>
<li id="607,815">Russian Roulette</li>
<li id="15,735">SheepMe</li>
<li id="12,735">Sudoku</li>
<li id="327,845">Super Star Balls</li>
<li id="26,650">Tic Tac Toe</li>
</ul>
<div style="clear:block">&nbsp;</div>
</div>
</div>
</div>

</body>
</html>
EOD;


} else {

if ($_GET['action'] == 'request') {
	$random_from = md5(getTimeStamp()+$userid+'from');
	$random_to = md5(getTimeStamp()+$_GET['toId']+'to');
	$random_order = $random_from.','.$random_to;
	$toId = $_GET['toId'];

	sendMessageTo($_GET['toId'],"has sent you an game request. <a href='#' onclick=\"javascript:jqcc.ccgames.accept('".$userid."','".$random_from."','".$random_to."','".$random_order."','".$_GET['gameId']."','".$_GET['gameWidth']."');\">Click here to accept it</a> or simply ignore this message.");

	sendSelfMessage($_GET['toId'],"has successfully sent a game request.");

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Game request sent!</title> 

<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
    text-align: center;
}

.small {
	font-weight:bold;
}

ul {
  float: left;
  width: 100%; 
  margin: 0;
  padding: 0;
  list-style: none;
}
 
li {
  text-align: left;
  float: left;
  width: 110px;
  margin: 0;
  height: 20px;
  background-image: url(bullet.png);
  background-position: 0 -1px;
  background-repeat: no-repeat;
  padding: 0 0 0 1.5em; 
  cursor: pointer;
} 
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$("li").click(function() {
		var info = $(this).attr('id').split(',');
		var gameId = info[0];
		var width = info[1];
		location.href = 'index.php?action=request&toId={$toId}&gameId='+gameId+'&gameWidth='+width;
	});
});

</script>

</head>
<body onload="setTimeout('window.close()',2000);">

<div style="width:98%;margin:0 auto;margin-top: 5px;">
<div style="border-left: 1px solid #11648F;border-top: 1px solid #11648F;border-right: 1px solid #11648F;background-color:#3E92BD;color:#fff;padding:5px;font-weight:bold;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size: 14px;letter-spacing:0px;padding-left:10px;text-align:left;">Which game would you like to play?</div>

<div style="border-left: 1px solid #cccccc;border-bottom: 1px solid #cccccc;border-right: 1px solid #cccccc;background-color:#fffff;color:#333;padding:5px;font-weight:normal;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size:11px;padding:20px 10px;padding-bottom:0px;text-align:left;">

<div class="games">
User has been sent game request successfully. You should receive a reply shortly. Closing window.
</div>
<div style="clear:block">&nbsp;</div>
</div>
</div>
</div>

</body>
</html>
EOD;

}

if ($_GET['action'] == 'accept') {
	sendMessageTo($_POST['to'],"has accepted your game request. <a href='#' onclick=\"javascript:jqcc.ccgames.accept_fid('".$userid."','".$_POST['tid']."','".$_POST['fid']."','".$_POST['rid']."','".$_POST['gameId']."','".$_POST['gameWidth']."');\">Click here to launch the game window</a>");
}

if ($_GET['action'] == 'play') {

	$fid = $_GET['fid'];
	$tid = $_GET['tid'];
	$rid = $_GET['rid'];
	$gameid = $_GET['gameId'];
	$auth = md5($fid.$rid.'100'.$gameid.'fdd4605ba06214842e3caee695bd2787');
	$rid = urlencode($rid);

	global $userid;
	global $db;
	global $language;

	$sql = ("select ".DB_USERTABLE_NAME." as name from ".TABLE_PREFIX.DB_USERTABLE." where ".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	$query = mysql_query($sql);
	echo mysql_error();
	$user = mysql_fetch_array($query);
	$name = urlencode($user['name']);

	echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Playing Game!</title>
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="js/AC_RunActiveContent.js" language="javascript"></script>
<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
    text-align: center;
}

body{ overflow-x:hidden;overflow-y:hidden; }

</style>
</head>
<body bgcolor="#fff"> 

<iframe src="http://games.cometchat.com/channel_auth.asp?channel_id=27377&uid={$fid}&nick_name={$name}&method_type=matching&matching_uids={$rid}&matching_stake=100&matching_game_id={$gameid}&auth_sig={$auth}" height="710" width="1000" scrolling="no"></iframe>
</body>
</html>
 


EOD;
}

}