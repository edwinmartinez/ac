<?php

// load Smarty library
require($_SERVER['DOCUMENT_ROOT'].'/includes/smarty/libs/Smarty.class.php');

// The setup.php file is a good place to load
// required application library files, and you
// can do that right here. An example:
// require('guestbook/guestbook.lib.php');

class Smarty_su extends Smarty {

   function __construct()
   {

        // Class Constructor.
        // These automatically get set with each new instance.
       parent::__construct();

        //$this->Smarty();
        $this->setTemplateDir($_SERVER['DOCUMENT_ROOT'].'/01admin/includes/');
      
        //$this->template_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/';
        $this->compile_dir  = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/templates_c/';
        $this->config_dir   = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/configs/';
        $this->cache_dir    = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/cache/';

				
        //$this->debugging = true;

        $this->caching = false;
        $this->assign('app_name', 'AmigoCupido');
        $this->assign('ads',true);
   }

}
?> 
