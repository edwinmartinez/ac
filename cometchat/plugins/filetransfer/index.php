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

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php";

$toId = $_GET['id'];

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Send a file</title> 

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
</style>

<script type="text/javascript" src="styleinput.js"></script>

<style type="text/css" title="text/css">

.SI-FILES-STYLIZED label.cabinet
{
	width: 97px;
	height: 25px;
	background: url(send.gif) 0 0 no-repeat;

	display: block;
	overflow: hidden;
	cursor: pointer;
}

.SI-FILES-STYLIZED label.cabinet input.file
{
	position: relative;
	height: 100%;
	width: auto;
	opacity: 0;
	-moz-opacity: 0;
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
}

</style>

</head>
<body><form name="upload" action="upload.php" method="post" enctype="multipart/form-data">
<div style="width:98%;margin:0 auto;margin-top: 5px;">
<div style="border-left: 1px solid #11648F;border-top: 1px solid #11648F;border-right: 1px solid #11648F;background-color:#3E92BD;color:#fff;padding:5px;font-weight:bold;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size: 14px;letter-spacing:0px;padding-left:10px;text-align:left;">Which file would you like to send?</div>

<div style="border-left: 1px solid #cccccc;border-bottom: 1px solid #cccccc;border-right: 1px solid #cccccc;background-color:#fffff;color:#333;padding:5px;font-weight:normal;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size:11px;padding:20px 10px;text-align:left;">

<div style="padding-bottom:15px;text-align:left;">Please select a file by using the button below.</div>
<div id="select-0" style="text-decoration:none;color:#333;text-align:left;display:block;height:30px;padding-bottom:10px;"><label class="cabinet"><input type="file" class="file" name="Filedata" onchange="javascript:document.upload.submit()"/></label></div>

<div style="clear:block"></div>


<div style="text-align:left;"><b>WARNING:</b> Do not send copyrighted material for which you don't own the rights or have permission from the owner.</div>

<input type="hidden" name="to" value="{$toId}">

</div>
</div>
</div>

<script>
SI.Files.stylizeAll();
</script>
</form>
</body>
</html>
EOD;
