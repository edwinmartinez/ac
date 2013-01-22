<?php

/**
 * Admin Class
 *
 * @version 2.1
 * @author Edwin Martinez
 */
class admin{
    protected $db;
    protected $resultsPerPage = 10;
    protected $pages;
    protected $users;

    public function __construct(){
        require_once("../includes/db_settings.php");
        $this->db = new MySQL();
        //echo DBMS." $dbname, $dbhost, $dbuser, $dbpasswd";
        if (! $this->db->Open(DBNAME, DBHOST, DBUSER, DBPASS)) {
            $this->db->Kill();
        }
        $this->sql = "SET SESSION SQL_BIG_SELECTS=1"; //sets the session for big selects/joins
        if (!$this->db->Query($this->sql)) {
            $this->db->Kill();
        }
    }
    /**
     * Create a general report
     * 
     * displays a general report view
     */
    public function adminHome() {
        $this->smarty = new Smarty_su();
        $this->out = '<div id="reportDiv"><h2>Overview</h2>';
        //$this->out .= DBNAME." ".DBHOST." ".DBUSER." ".DBPASS;
        $this->out .= '	<div><strong>Number of All Users:</strong> '.$this->getAllUsersCount().'</div>'."\n";
        $this->out .=  '	<div><strong>Users Created in the last 24 hours:</strong> '.$this->getLatestUsersCount(1).'</div>'."\n";
        $this->out .=  '	<div><strong>Users Created in the last 7 days:</strong> '.$this->getLatestUsersCount(7).'</div>'."\n";
        $this->out .=  '	<div style="margin-top:20px;"><strong>Users Created in the last day by country</strong></div>'."\n";
        foreach($this->getLatestSubscriptionsByCountry() as $row ){
			$this->out .=  '	<div>'.$row['country']. ' - '.$row['count'].'</div>'."\n";
	}
                $this->out .=  '	<div style="margin-top:20px;"><strong>Users Created in the last 7 days by country</strong></div>'."\n";
        foreach($this->getLatestSubscriptionsByCountry(7) as $row ){
			$this->out .=  '	<div>'.$row['country']. ' - '.$row['count'].'</div>'."\n";
	}
        $this->out .= "</div>";
        //echo '	<div><strong>Users by country:</strong> '.$this->getLatestSubscriptionsByCountry().'</div>'."\n";
        $this->smarty->assign("content",  $this->out);
        $this->smarty->display('admin_index.tpl');
    }
    /**
     * Displays a view of all the users basic info
     *  
     * 
     */
    public function showAllUsersView(){
        $this->smarty = new Smarty_su();
        $this->smarty->assign("content",$this->usersView($this->getAllUserRows()));
        $this->smarty->display('admin_index.tpl');
    }
    /**
     * Gets a view of all matched users in a search
     * 
     * @return string gets a list of all matched users 
     */
    public function showUsersSearchView(){
        $this->smarty = new Smarty_su();
        $this->smarty->assign("content",$this->usersView($this->getSearchedUsers()));
        $this->smarty->display('admin_index.tpl');
    }
    /**
     * Display the results of a mail search query 
     * 
     */
    public function mailsSearchView(){
    	echo "searching...";
        $this->smarty = new Smarty_su();
        //$this->smarty->assign("mails",$this->getSearchedMails());
        //$this->smarty->display('newMailList.tpl');
        //$this->smarty->assign("content",$this->mailsListView($this->getSearchedMails()));
        $this->smarty->assign("content",$this->getSearchedMails());
        $this->smarty->display('admin_index.tpl');
    }
    /**
     * creates a view of the matched emails in a search query
     * 
     * @return string html list of the matched emails
     */
    protected function mailsListView($mails){
        $this->mails = $mails;
        // to do 
        return $this->mails;
    }
    /**
     * return a html list of user from an associative array
     * 
     * @param array an associative array of users
     * @return string html list of users
     */
    protected function usersView($userRows){
        $this->users = $userRows;
        $this->button_bar = '<div class="tableButtonBar"><div><input id="deleteSelected" name="deleteSelectedButton" type="submit" value="Delete Selected"></div></div>'."\n";

        $this->out .= '<form if="mainForm" method="post">'."\n";
        $this->out .=  $this->button_bar;
        if (!empty($this->users[0])){
	        $this->out .=  '<table class="usersTable">'."\n";
	        foreach($this->users as $this->row){
				$this->out .=  '<tr class="userRow">'
	                        .'<td class="userCell checkBoxCell"><input name="uid[]" type="checkbox" value="'.$this->row['user_id'].'" /></td>'
	                        .'<td class="userCell openInfoCell"><a class="openInfo"title="'.$this->row['user_id'].'" href="#" id="openInfo-'.$this->row['user_id'].'">[+]</a></td>'
	                        .'<td class="userCell uidCell">'.$this->row['user_id'].'</td>'
	                        .'<td class="userCell usernameCell"><a class="userNameLink" href="/perfil/'.$this->row['user_username'].'">'.$this->row['user_username'].'</a></td>'
	                        .'<td class="userCell emailCell">'.$this->row['user_email'].'</td>'
	                        .'<td class="userCell">'.$this->row['user_last_name'].'</td>'
	                        .'<td class="userCell">'.$this->row['user_first_name'].'</td>'
	                        .'<td class="userCell">'.$this->row['countries_iso_code_2'].'</td>'
	                        .'<td class="userCell">'.$this->row['user_created'].'</td>'
	                        .'<td class="userCell">'.$this->row['user_last_login'].'</td>'
	                        .'<td class="userCell">'.$this->row['about_me'].'</td>'
	                        .'<td class="userCell">'.$this->row['reg_from_ip'].'</td>'
	                        .'<tr class="infoRow" id="ir-'.$this->row['user_id'].'"><td colspan="12"><div id="irdiv-'.$this->row['user_id'].'">something</div></td></tr>'
	                        .'</tr>'."\n";
			}
	
	        // previous onclick behavior: onclick="return sureDeleteUsers();"
	        
	        $this->out .=  "</table>\n";
	        $this->out .=  '<div><input type="hidden" id="action" name="action" value=""></div>';
	        $this->out .=  $this->button_bar;
        }else {
        	$this->out = '<div class="messageDiv">No users found</div>';
        }

	$this->out .=  '</form>';

        return $this->out;

    }
    public function showDeletedUsers(){
        $this->smarty = new Smarty_su();
        $this->smarty->assign("content", $this->deleteUsers());
        $this->smarty->display('admin_index.tpl');
    }

    /**
     * Deletes users from all tables
     *
     * @return string Status of the process
     */
    protected function deleteUsers(){
        foreach($_POST['uid'] as $this->key=>$this->uid){
            $this->out .=  '<br><br>'.($this->key + 1).':'.$this->uid.'<br>';

            if($this->userExist($this->uid)){ //let's check if this user exists else something weird happened :(
                // Deal with the user pictures first ---------------------
                $this->deletedPicCount = $this->deleteUserPics($this->uid);
                $this->out .= "User has ".$this->deletedPicCount." photos deleted<br>\n";

                // Now let's delete messages ----------------------------
                list($this->msgs_count_from,  $this->msgs_count_to) = $this->getUserMsgCount($this->uid);
                $this->out .=  "$this->msgs_count_from messages from this user<br>\n";
                $this->out .=  "$this->msgs_count_to messages to this user<br>\n";
                $this->deleteUserMessages($this->uid);
                $this->deleteUserFromPhpbbUsers($this->uid);

                
                $this->sql = "DELETE "
                        ."u, pct, bud, favp, profv, upt, blog "
                        ." FROM ".SITE_USERS_TABLE." AS u"
                        ." LEFT JOIN ".PROFILE_COMMENTS_TABLE." AS pct ON pct.commenter_uid=u.user_id OR pct.user_id=u.user_id"
                        ." LEFT JOIN ".BUDDIES_TABLE." AS bud ON bud.user_uid=u.user_id OR bud.buddy_uid=u.user_id"
                        ." LEFT JOIN ".FAVORITE_PEOPLE_TABLE." AS favp ON favp.user_uid=u.user_id OR favp.fav_uid=u.user_id"
                        ." LEFT JOIN ".USERS_PROFILE_VIEWS_TABLE." AS profv ON profv.viewer_uid=u.user_id OR profv.upv_uid=u.user_id"
                        ." LEFT JOIN ".USER_PREFERENCES_TABLE." AS upt ON upt.user_id=u.user_id"
                        ." LEFT JOIN ".USERS_BLOGS_TABLE." as blog ON blog.ub_uid=u.user_id"
                        ." WHERE u.user_id = " .$this->uid;
                // Execute our query
                if (!$this->db->Query($this->sql)) {
                    $this->db->Kill();
                }

                $this->out .=  "User ".$this->uid." has been deleted succesfully.";

            } else {
                $this->out .=  "User:<strong>".$this->uid."</strong> does not exist<br>";
            }
        } //end of foreach
        return $this->out;

    }
    /**
     * Checks wheather user exists
     *
     * @param int $uid user id
     * @return int 1 if user exists or 0 if not
     */
    public function userExist($uid){
        $this->sql = 'SELECT count(*) as total from '.SITE_USERS_TABLE." WHERE user_id = '".$uid."' ";
        return $this->db->QuerySingleValue($this->sql);
    }
    /**
     * Gets all the parameters of the users pictures in an multi-dimensional associative array
     * 
     * @param int $uid
     * @return array|boolean 
     */
    public function getUserPics($uid){
        $this->sql = 'SELECT * from '.USERS_GALLERY_TABLE." where photo_uid=".$uid ;
        if (!$this->db->Query($this->sql)) $this->db->Kill();
        $this->rowCount = $this->db->RowCount();
        if(!empty($this->rowCount)){
            return $this->db->QueryArray($this->sql,MYSQL_ASSOC);
        }else{
            return 0;
        }
    }
    /**
     * Returns a single value of the count of the latest users
     * 
     * @param string $interval
     * @param string $interval_type
     * @return int 
     */
    public function getLatestUsersCount($interval="1",$interval_type="day") {
        $this->sql = "SELECT count( * ) as count FROM users WHERE user_created >= date_sub( now( ) , INTERVAL $interval $interval_type )";
        return $this->db->QuerySingleValue($this->sql);
    }
    /**
     * Gets the latest subcriptions by country
     *
     * @param int $interval Interval for the time i.e. [interval] days
     * @param string $time type of interval (i.e. day, hour, etc)
     * @param int $limit Limit of how many records to return
     * @return array Returns returns an array with the contry and count
     */
    public function getLatestSubscriptionsByCountry($interval='1',$time='day',$limit="10"){
        $this->sql = "SELECT c.countries_name_es as country, count(*) as count ".
        "FROM users u, countries c WHERE user_created >= date_sub( now( ) , INTERVAL $interval $time ) ".
        "and u.user_country_id = c.countries_id group by user_country_id order by count desc limit $limit";
        return $this->db->QueryArray($this->sql);
    }
    /**
     * Count all users
     * 
     * @return int returns a single value with the count of all users in the system
     */
    public function getAllUsersCount() {
        $this->sql = "select count(*) as count from users";
        return $this->db->QuerySingleValue($this->sql);
    }
    /**
     * Deletes all pictures of a user
     *
     * @param int $uid user id
     * @return int 1 on success
     */
    public function deleteUserPics($uid){
        $this->uid = $uid;
        $this->pic_count = $this->db->QuerySingleValue("SELECT count(*) from ".USERS_GALLERY_TABLE." where photo_uid=".$this->uid);
                if($this->pic_count){
                    //delete pictures and its comments
                    // doing 2 sql statements instead of 1 because the db wrapper throws warning with no records on QueryArray method
                    $this->get_pics_sql = "SELECT photo_id, photo_filename from ".USERS_GALLERY_TABLE." where photo_uid=".$this->uid ;
                    $this->pics_array = $this->db->QueryArray($this->get_pics_sql,MYSQL_ASSOC);

                    echo "user has ".$this->pic_count." pics<br>";
                    // --- Loop through the records -------------------------------------
                    foreach($this->pics_array as $this->row){
                        $this->deleteThisPic($this->row['photo_id']);
                    }

                }
                return $this->pic_count;
    }
    /**
     * gets the count of messages from and to the user
     * 
     * @param int $uid the id of the user
     * 
     * @return array Returns an array with the count of from and to messages of user
     */
    public function getUserMsgCount($uid){
         $this->uid = $uid;

        // get some messages stats
        $this->sql = "SELECT COUNT(*) as count from ".PHPBB_MESSAGES_TABLE." AS phme where phme.privmsgs_from_userid=".$this->uid;
        $this->msg_from_count = $this->db->QuerySingleValue($this->sql);
        $this->sql = "SELECT COUNT(*) as count from ".PHPBB_MESSAGES_TABLE." AS phme where phme.privmsgs_to_userid=".$this->uid;
        $this->msg_to_count = $this->db->QuerySingleValue($this->sql);
        return array($this->msg_from_count, $this->msg_to_count);
    }
    /**
     * Go throught the messages table and delete messages to and from this user
     * 
     * @param int $uid
     * @return int 1 if succesful
     */
    public function deleteUserMessages($uid){
        $this->uid = $uid;

        $this->sql = "DELETE phme, phmetxt"
                        ." FROM ".SITE_USERS_TABLE." AS u"
                        ." LEFT JOIN ".PHPBB_MESSAGES_TABLE." AS phme ON phme.privmsgs_from_userid=u.user_id OR phme.privmsgs_to_userid=u.user_id"
                        ." LEFT JOIN ".PHPBB_MESSAGES_TEXT_TABLE." AS phmetxt ON phmetxt.privmsgs_text_id=phme.privmsgs_id"
                        ." WHERE u.user_id = " .$this->uid;
        if ( !($this->result = $this->db->query($this->sql))){
                printf('Could not delete record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
                exit;
        }
        return 1;
    }
    /**
     * deletes user from phpbb users table
     *
     * @param int $uid user id
     * @return int 1 if successful
     */
    protected function deleteUserFromPhpbbUsers($uid){
        $this->uid = $uid;
        $this->sql = "DELETE phuser"
                        ." FROM ".SITE_USERS_TABLE." AS u"
                        ." LEFT JOIN ".PHPBB_USERS_TABLE." AS phuser ON phuser.user_id=u.user_id"
                        ." WHERE u.user_id = " .$this->uid;
        if ( !($this->result = $this->db->query($this->sql))){
                printf('Could not delete record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
                exit;
        }
        return 1;
    }
    /**
     * Deletes one picture file and related comments and tags
     *
     * @param int $pic_id id of the picture to delete
     * @return int 1 on success or 0 on failure
     */
    public function deleteThisPic($pic_id){
        if(!empty ($pic_id)){
            $this->pic_id = $pic_id;
            $this->sql = "select * from ".USERS_GALLERY_TABLE." where photo_id=".  $this->pic_id;

            if ( !($this->result = $this->db->query($this->sql))){
                                            printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
                                            exit;
            }
            $this->row = mysql_fetch_assoc($this->result);
            $this->picname = $this->row['photo_filename'];
            $this->photo_uid = $this->row['photo_uid'];
            list($this->id,$this->basename,$this->ext) = split('[.-]',$this->picname);

            //-- create the names of the various files: square, medium, small, large
            $this->basename = $this->id.'.'.$this->basename;
            $this->squarepic = $this->basename.'_sq.'.$this->ext;
            $this->medpic  = $this->basename.'_m.'.$this->ext;
            $this->smpic = $this->basename.'_s.'.$this->ext;
            $this->largestpic = $this->basename.'_l.'.$this->ext;
            $this->dirname = MEMBER_IMG_DIR_URL.'/'.$this->photo_uid;

            $this->filestocheck = array($this->squarepic,$this->medpic,$this->smpic,$this->largestpic);
            foreach($this->filestocheck as $this->thispic){
                    if (file_exists(MEMBER_IMG_DIR_PATH.'/'. $this->photo_uid.'/'.  $this->thispic)) {
                            echo "Deleting: ".MEMBER_IMG_DIR_PATH.'/'.$this->photo_uid.'/'.$this->thispic."<br>";
                            unlink(MEMBER_IMG_DIR_PATH.'/'.$this->photo_uid.'/'.$this->thispic);
                    }
            }
            $this->sql = "delete gal, tags, com "
                    ." FROM ".USERS_GALLERY_TABLE." AS gal"
                    ." LEFT JOIN ".TAGS_TO_GALLERY_TABLE." AS tags ON tags.photo_id=gal.photo_id"
                    ." LEFT JOIN ".USERS_GALLERY_COMMENTS_TABLE." AS com on com.photo_id=gal.photo_id"
                    ." WHERE gal.photo_id=".$this->pic_id;

            if ( !($this->result = $this->db->query($this->sql))){
                                            printf('Could not delete record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
                                            exit;
            }


            return 1;
        }else{
            echo 'pic id is empty for delete_this_pic<br>';
            return 0;
        }
    }
    /**
     * Gets rows in an associative array of all the users in the users table
     * 
     * @param string $orderby
     * @param string $direction
     * @return array 
     */
    protected function getAllUserRows($orderby = "user_created", $direction ="desc"){
        $this->pages = new Paginator();
        $this->pages->items_per_page = $this->resultsPerPage;
        $this->pages->items_total = $this->getAllUsersCount();
        $this->pages->mid_range = 10;
        $this->pages->paginate();

        $this->sql = "SELECT * FROM  ".SITE_USERS_TABLE." as u left outer join countries as c"
        	." ON u.user_country_id = c.countries_id"
        	." ORDER BY ".$orderby." ".$direction;
        $this->sql .= $this->pages->limit;
        $this->users = $this->db->QueryArray($this->sql,MYSQL_ASSOC);
        return $this->users;
    }
        /**
	 * Gets the all the users that match the keyword
	 *
	 * @param string $q query keyword
         * @return array the user rows that match the search | boolean false
         */
    protected function getSearchedUsers(){
        $this->q = $_POST['q'];
        if($this->q = mysql_real_escape_string($this->q)){
        $this->sql = "SELECT * FROM ".SITE_USERS_TABLE." as u"
                    ." LEFT JOIN countries as c ON u.user_country_id = c.countries_id"
                    ." WHERE `user_id` LIKE '%".$this->q."%'"
                    ." OR `user_username` LIKE '%".$this->q."%' "
                    ." OR 'user_first_name' = '%".$this->q."%' "
                    ." OR 'user_last_name' = '%".$this->q."%' "
                    ." OR about_me LIKE '%".$this->q."%' "
                    ." OR `user_email` LIKE '%".$this->q."%' "
                    ." OR `reg_from_ip` LIKE '%".$this->q."%' "
                    ." ORDER BY user_created desc "
                    ." limit 100";
        //echo $this->sql;
        $this->users = $this->db->QueryArray($this->sql,MYSQL_ASSOC);
        return $this->users;
        }else{
            return false;
        }
    }
    /**
     * Searches through the emails for keywords. 
     * --Looking for spammers
     * 
     * @return array an associative array from the search query | boolean false
     */
    protected function getSearchedMails($limit=50){
        $this->q = $_POST['q'];
        if($this->q = mysql_real_escape_string($this->q)){
        $this->sql = "SELECT u.user_id as from_user_id, u.user_username as from_username, u2.user_username as to_username"
                    .", u2.user_id as to_user_id"
                    .", m.*, mt.*, left(mt.privmsgs_text,400) as privmsgs_text, FROM_UNIXTIME(m.privmsgs_date) as message_date"
                    ." FROM ".SITE_USERS_TABLE." as u"
                    ." LEFT JOIN phpbb_privmsgs m ON m.privmsgs_from_userid = u.user_id OR m.privmsgs_to_userid = u.user_id"
                    ." LEFT JOIN phpbb_privmsgs_text mt ON  mt.privmsgs_text_id = m.privmsgs_id "
                    ." LEFT JOIN ".SITE_USERS_TABLE." u2 ON u2.user_id = m.privmsgs_to_userid"
                    ." WHERE mt.privmsgs_text LIKE '%".$this->q."%' "
                    ." OR m.privmsgs_subject LIKE '%".$this->q."%' "
                    ." ORDER BY m.privmsgs_date desc"
                    ." limit ".$limit;
        return $this->sql;
            $this->mails = $this->db->QueryArray($this->sql,MYSQL_ASSOC);
            return $this->mails;
        }else{
            return false;
        }
    }
    /**
     * displays latest emails
     * 
     */
    public function showLatestMails(){
        $this->mailRows = $mailRows;
        $this->smarty = new Smarty_su();
        $this->smarty->assign("mails",$this->getLatestMails(100));
        $this->smarty->display('newMailList.tpl');
    }
    

    /**
     * Gets latest emails
     * 
     * @param int Limit of mails
     * @return array an associative array from the query
     */
    protected function getLatestMails($limit=50){
         $this->sql ="select mt.*, left(mt.privmsgs_text,400) as message_text"
        .", FROM_UNIXTIME(m.privmsgs_date) as message_date, m.*"
        .", concat( 
		conv(substr(privmsgs_ip, 1, 2), 16, 10), '.', 
		conv(substr(privmsgs_ip, 3, 2), 16, 10), '.', 
		conv(substr(privmsgs_ip, 5, 2), 16, 10), '.', 
		conv(substr(privmsgs_ip, 7, 2), 16, 10)) as message_ip"
        .", u.user_username as from_username, u2.user_username as to_username"
        ." FROM phpbb_privmsgs_text mt, phpbb_privmsgs m, users u, users u2"
	." WHERE m.privmsgs_id = mt.privmsgs_text_id "
	." AND u.user_id = m.privmsgs_from_userid "
	." AND u2.user_id = m.privmsgs_to_userid "
	." order by m.privmsgs_date desc"
        ." limit ".$limit;
        if(!($this->ret = $this->db->QueryArray($this->sql,MYSQL_ASSOC))){
                printf('Could not delete record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $this->sql);
                exit;
            
        }
        return $this->ret;
    }

}

?>
