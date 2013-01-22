<?php

class maintenance{

    public function  __construct(){
            $this->delete_old_friend_requests();
    }

    public function delete_old_friend_requests(){
        $this->sql = "delete FROM buddies WHERE confirmed =0 AND buddy_request_date <= date_sub(now(), interval 15 day)";
        //mysql_query($this->sql);
    }
	
}

?>