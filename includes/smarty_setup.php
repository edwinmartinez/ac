<?php

// load Smarty library
require('smarty/libs/Smarty.class.php');

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
        $this->setTemplateDir($_SERVER['DOCUMENT_ROOT'].'/includes/templates/');
      
        //$this->template_dir = $_SERVER['DOCUMENT_ROOT'].'/includes/templates/';
        $this->setCompileDir($_SERVER['DOCUMENT_ROOT'].'/includes/templates/templates_c/');
        $this->setConfigDir($_SERVER['DOCUMENT_ROOT'].'/includes/templates/configs/');
        $this->setCacheDir($_SERVER['DOCUMENT_ROOT'].'/includes/templates/cache/');

				
				//$this->debugging = true;
/*				$this->template_dir = '/home/ac/public_html'.'/includes/templates/';
        $this->compile_dir  = '/home/ac/public_html'.'/includes/templates/templates_c/';
        $this->config_dir   = '/home/ac/public_html'.'/includes/templates/configs/';
        $this->cache_dir    = '/home/ac/public_html'.'/includes/templates/cache/';*/

        $this->caching = false;
        $this->assign('app_name', 'AmigoCupido');
		$this->assign('ads',true);
   }

}
?> 
