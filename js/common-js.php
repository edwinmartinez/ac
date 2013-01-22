<?php
Header("content-type: application/x-javascript");

echo '
//Toggle popup link setting: popupsetting[0 or 1, "pop up window attributes" (if 1)]
var popupsetting=[1, "width=550px, height=400px, scrollbars, resizable"]

function popuplinkfunc(imgsrc){
if (popupsetting[0]==1){
var popwin=open(imgsrc.href, "popwin", popupsetting[1])
popwin.focus()
return false
}
else
return true
}';
?>