<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?php echo (isset($title)) ? $title : "AmigoCupido" ?> </title>
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/ac-v2.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-responsive.css" />
 <script src="<?php echo base_url();?>js/jquery.js"></script>
 <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
 
</head>
 
 <script type="text/x-mustache-template" id="messagesHeader-template">
 	       
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">m {{ count }} <b class="caret"></b></a>
                <ul class="dropdown-menu dropdown-messages">
					<li class="nav-header" id="messagesHeader-title">
						<div>
							<a href="<?php echo base_url().'messages/'; ?>"><?php echo $this->lang->line('messages_messages'); ?></a> 
						</div>
						<div>
							<a href="#">Crear Nuevo Mensaje</a></li>
						</div>
					<li class="divider"></li>
					<div>something</div>
				
                </ul>
              
 </script>
 
 <script type="text/x-mustache-template" id="messageItem-template">
 	<div class="messageItem {{msg_type}}" rel="<?php echo base_url();?>messages/index/{{msg_thread_username}}">
		<div class="messageItem-user">
			<div class="messageItem-userImg"><img src="{{msg_user_img_url}}" /></div> 
			<div class="messageItem-username">{{msg_thread_username}}</div>
		</div> 
		<div class="messageItem-msg">
			<a href="#">{{ msg_text }}</a>
		</div>
		<div class="messageItem-date">{{ msg_date }}</div>
	</div>
 </script>

<body>	
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!--<div class="brandMobile hidden-desktop"></div>-->
          <div class="brandDesktop">
          <a class="brand" href="<?php echo base_url();?>" alt="AmigoCupido"><img src="<?php echo base_url();?>img/logo.png" width="149" height="43"></a>
          <div id="site-slogan" class="visible-desktop"><?php echo $this->lang->line('common_slogan'); ?></div>
		</div>
 <?php if(($this->session->userdata('username')!="")): // msg menu insertion-------------------------- ?>          
        <div class="nav" id="msgDropdown"> </div>
 <?php endif; ?>         
          <div class="nav-collapse collapse"> 
          	
            <ul class="nav">
              <li class="active"><a href="#"><?php echo $this->lang->line('common_home'); ?></a></li>
              
              
              <?php if(($this->session->userdata('username')!="")) : ?>
              	<li><?php echo anchor('user/timeline', $this->session->userdata('username')); // TODO: correct this link  ?></li>
              	<li><?php echo anchor('user/friendfinder', $this->lang->line('common_people_search')); ?></li>
         
              	<?php endif;  ?>
            </ul>
            <?  if($this->session->userdata('username') == '' ) :?>
		           <?php echo form_open("user/login",'class="navbar-form pull-right"'); ?>
		              <input class="span2" name="login" type="text" placeholder="<?php echo $this->lang->line('users_email'); ?>">
		              <input class="span2" name="pass" type="password" placeholder="<?php echo $this->lang->line('users_password'); ?>">
		              <button type="submit" class="btn"><?php echo $this->lang->line('users_signin'); ?></button>
		            </form>
            <?php endif; ?>
            
                      	
<?php if(($this->session->userdata('username')!="")): ?>          
        <div class="nav">
        <li class="dropdown">
                <a href="#account" id="accountLink" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->lang->line('common_my_account'); ?>  <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><?php echo anchor('user/editprofile', $this->lang->line('common_edit_my_profile')); ?></li>
                  <li><?php echo anchor('user/editaccount', $this->lang->line('common_my_account_settings')); ?></li>
                  <li><?php echo anchor('user/editprivacy', $this->lang->line('common_privacy_settings')); ?></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><?php echo anchor('user/logout', 'Logout'); ?></li>
                </ul>
              </li>
    	</div>
 <?php endif; ?>  
          </div>
          
        </div>
      </div>
    </div>
	
	<div class="container-fluid">
