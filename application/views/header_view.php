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

<body>	
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?php echo base_url();?>" alt="AmigoCupido"><img src="<?php echo base_url();?>img/logo.png" width="149" height="43"></a>
          <div id="site-slogan" class="visible-desktop"><?php echo $this->lang->line('common_slogan'); ?></div>

 <?php if(($this->session->userdata('username')!="")): ?>          
        <div class="nav">
        <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->lang->line('common_messages'); ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
    	</div>
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
                  <li><a href="#"><?php echo $this->lang->line('common_edit_my_profile'); ?></a></li>
                  <li><a href="#"><?php echo $this->lang->line('common_my_account_settings'); ?></a></li>
                  <li><a href="#"><?php echo $this->lang->line('common_privacy_settings'); ?></a></li>
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
