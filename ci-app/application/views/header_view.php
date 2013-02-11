<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?php echo (isset($title)) ? $title : "My CI Site" ?> </title>
 <link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
 <link rel="stylesheet" type="text/css" href="/css/home.css" />
 <link rel="stylesheet" type="text/css" href="/css/bootstrap-responsive.css" />
 <script src="/js/jquery.js"></script>
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
          <a class="brand" href="#" alt="AmigoCupido"><img src="/img/logo.png" width="149" height="43"></a>
          <div id="site-slogan">Amigos, media naranga y mas</div>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#"><?php echo $this->lang->line('common_home'); ?></a></li>
              <li><a href="#about"><?php echo $this->lang->line('common_about'); ?></a></li>
              <li><a href="#contact"><?php echo $this->lang->line('common_contact'); ?></a></li>
            </ul>
           <?php echo form_open("user/login",'class="navbar-form pull-right"'); ?>
              <input class="span2" type="text" placeholder="<?php echo $this->lang->line('users_email'); ?>">
              <input class="span2" type="password" placeholder="<?php echo $this->lang->line('users_password'); ?>">
              <button type="submit" class="btn"><?php echo $this->lang->line('users_signin'); ?></button>
            </form>
          </div>
          
        </div>
      </div>
    </div>
	
	
