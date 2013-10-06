	<div id="footer">
		
	  <div class="row">
	  	<div class="span3">
	  		<a href="#contact"><?php echo $this->lang->line('common_contact_us'); ?></a>
	  	</div>
	  	<div class="span3">
	  		<a href="#contact"><?php echo $this->lang->line('common_about'); ?></a>
	  	</div>
	  </div>
	  
	  <div class="row">
	  	<div class="span12">&copy; 2013 AmigoCupido</div>
	  </div>
	  
	 </div><!-- <div class="footer">-->
</div><!--/.fluid-container-->

</body>
<?php 
	$myBaseUri = $this->uri->segment(1);
	$loadjs = array(
			'jquery.timeago.js',
			'underscore.min.js',
			'backbone.js',
			'mustache.js',
			'jquery.mousewheel.js',
			'jquery.jscrollpane.min.js',
			//'jquery.nicescroll.min.js',
			//'backbone-localstorage.js',
			//'myhome.js',
			//'users.js'
	);
	if ($myBaseUri == 'messages') {
	    $loadjs[] = 'myhome.js';
	} elseif ($myBaseUri == 'myhome') {
		$loadjs[] = 'myhome.js';
		$loadjs[] = 'users.js'; //latest users
		$loadjs[] = 'users-statusfeed.js';
	} elseif ($myBaseUri == 'finder') {
		$loadjs[] = 'myhome.js';
		$loadjs[] = 'usersfinder.js'; //friendfinder
	} elseif ($myBaseUri == 'editprofile' || $myBaseUri == 'editaccount') {
		$loadjs[] = 'myhome.js';
	}

 	foreach ($loadjs as $jsscript) {
		 echo '<script src="' . base_url() . 'js/'.$jsscript.'"></script>'."\n";
	 }
  
 ?>
  <script type="text/javascript">
   $(function() { 
  	
  	<?php if ($myBaseUri == '') { //<-- if homepage
  		// if we have a login field focus on it
  			echo '$("#login").focus();';
  		} else {
			echo 'MyHome.username = '."'".$this->session->userdata('username')."';\n";
			echo 'MyHome.username_img_url = '."'".$this->session->userdata('username_img_url')."';\n";
		}
	
	?>
  	
   	<?php if($myBaseUri == 'messages') {
		 echo 'MyHome.thread_username = '."'".$thread_username."';\n";
	}
	if ($myBaseUri == 'messages' || $myBaseUri == 'myhome' || $myBaseUri == 'finder' || $myBaseUri == 'editprofile' || $myBaseUri == 'editaccount') {	?>
 	  MyHome.boot($('#msgDropdown')); 
 	  Backbone.history.start();
 	
 	<?php } ?>
 	});
 	
 	
 	<?php if ($myBaseUri != '') { //<-- if not homepage ?>
	 	setInterval(
	 		//function(){logout();},100000
	 		function(){redirect();},
	 		20*60*1000
	 	);
	
		function logout(){
		    if(confirm('Logout?'))
		        redirect();
		    else
		        alert('OK! keeping you logged in')
		}
		
		function redirect(){
		    document.location = "/logout"
		}
	
	<?php } ?>

 </script>
</html>