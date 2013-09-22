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
	if ($this->uri->segment(1) == 'messages') {
	    $loadjs[] = 'myhome.js';
	} elseif ($this->uri->segment(1) == 'myhome') {
		$loadjs[] = 'myhome.js';
		$loadjs[] = 'users.js';
		$loadjs[] = 'users-statusfeed.js';
	}

 	foreach ($loadjs as $jsscript) {
		 echo '<script src="' . base_url() . 'js/'.$jsscript.'"></script>'."\n";
	 }
  
 ?>
  <script type="text/javascript">
   $(function() { 
  	
  	<?php if ($this->uri->segment(1) == '') { //<-- if homepage
  		// if we have a login field focus on it
  		echo '$("#login").focus();';
  		}
	?>
  	
   	<?php if($this->uri->segment(1) == 'messages') {
   		 echo 'MyHome.username = '."'".$username."';\n";
   		 echo 'MyHome.username_img_url = '."'".$username_img_url."';\n";
		 echo 'MyHome.thread_username = '."'".$thread_username."';\n";
	}
	if ($this->uri->segment(1) == 'messages' || $this->uri->segment(1) == 'myhome') {	?>
 	  MyHome.boot($('#msgDropdown')); 
 	  Backbone.history.start();
 	
 	<?php } ?>
 	});
 	
 	
 	<?php if ($this->uri->segment(1) != '') { //<-- if not homepage ?>
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
	
	$(document).ready(function() {
  		$("abbr.timeago").timeago();
	});
 </script>
</html>