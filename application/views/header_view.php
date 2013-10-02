<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?php echo (isset($title)) ? $title : "AmigoCupido" ?> </title>
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/ac-v2.css"  media="all" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-responsive.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.jscrollpane.css"  media="all" />
 <script src="<?php echo base_url();?>js/jquery.js"></script>
 <!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
 <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<!--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>-->
 <script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
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
		<div class="messageItem-date">{{ msg_time }}</div>
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
            	<?php if(($this->session->userdata('username')!="")) : ?>
              	<li class="active"><a href="#"><?php echo $this->lang->line('common_home'); ?></a></li>
              	<li><?php echo anchor('user/timeline', $this->session->userdata('username')); // TODO: correct this link  ?></li>
              	<li><?php echo anchor('/finder', $this->lang->line('common_people_search')); ?></li>

              	<?php endif;  ?>
            </ul>
            <?php  if($this->session->userdata('username') == '' ) :?>
		           <?php $attributes = array('class' => 'navbar-form pull-right', 'id' => 'loginForm', 'autocomplete' => 'off');
		           		echo form_open("user/login",$attributes); ?>
		              <input class="span2" name="login" id="login" type="text" placeholder="<?php echo $this->lang->line('users_email'); ?>" autocomplete="off" >
		              <input class="span2" name="pass" id="pass" type="password" placeholder="<?php echo $this->lang->line('users_password'); ?>" autocomplete="off">
		              <button type="submit" class="btn"><?php echo $this->lang->line('users_signin'); ?></button>
		            </form>
            <?php endif; ?>


<?php if(($this->session->userdata('username')!="")): ?>
        <div class="nav">
        <li class="dropdown">
                <a href="#account" id="accountLink" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->lang->line('common_my_account'); ?>  <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><?php echo anchor('editprofile', $this->lang->line('common_edit_my_profile')); ?></li>
                  <li><?php echo anchor('editaccount', $this->lang->line('common_my_account_settings')); ?></li>
                  <li><?php echo anchor('editprivacy', $this->lang->line('common_privacy_settings')); ?></li>
                  <li class="divider"></li>
                  <!--<li class="nav-header">Nav header</li>-->
                  <li><?php echo anchor('/logout', 'Logout'); ?></li>
                </ul>
              </li>
    	</div>
 <?php endif; ?>
          </div>

        </div>
      </div>
    </div>

	<div class="container-fluid">
