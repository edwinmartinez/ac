<?php
include('../includes/config.php');
require_once("../includes/mysql.class.php");
require_once('../includes/class.paginator.php');
require_once('smarty_su.class.php');
require_once('admin_model.php');

$admin = new admin();

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'user_search'){
    $admin->showAllUsersView();
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'do_search' && $_REQUEST['search_type']=='users'){
    $admin->showUsersSearchView();	
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'do_search' && $_REQUEST['search_type']=='mails'){
    $admin->mailsSearchView();	
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'users_delete'){
    $admin->showDeletedUsers();	
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'latest_emails'){
    $admin->showLatestMails();	
}
else {
    $admin->adminHome();	
}


?>