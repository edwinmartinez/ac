<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{block name="title"}Administration{/block}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/js/fancybox/jquery.easing-1.4.pack.js"></script>
<script type="text/javascript">
function sureDeleteUsers(){
	var answer = confirm ("Are you sure you want to delete these users?");
	if (answer){
            return true;
	}
	return false;
}
$(window).load(function() {
    $('input[name="deleteSelectedButton"]').click(function () {

        var answer = confirm ("Are you sure you want to delete these users?")
        if (answer){
            $("#deleteSelected").val('Deleting...');
            $("#action").val('users_delete');
            return true;
	}else{
            return false;
        }
     });
    $("a[id^='openInfo']").click(function(event){
        //alert('hi');
        var oi_array = event.target.id.split("-");
        var uid = oi_array[1];
        //alert(uid);
        $("#ir-"+uid).toggle();
        $("#irdiv-"+uid).html('<a class="inlineFancy" href="/01admin/">This takes content using ajax </a>');
        $("a.inlineFancy").fancybox();
    });
    //$("a.userNameLink").fancybox(); //takes longer
    
});
</script>
<link rel="stylesheet" href="/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<link href="adminstyle.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div id="wrap">
    <div id="navAdmin">
        <strong><a href="index.php">Home</a></strong>
        <a href="?action=user_search">Latest Users</a>
        <a href="?action=latest_emails">Latest Emails</a>
    </div>

    <div id="searchDiv">
        <form id="searchForm" method="post">
        <input type="hidden" name="action" value="do_search" />
        Search In: <select name="search_type">
            <option value="users">Users</option>
            <option value="mails">Emails</option>
        </select>
        <input type="text" name="q" />
        <input type="submit" name="search" value="search" />
        </form>
    </div>