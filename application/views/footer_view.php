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
			'underscore.min.js',
			'backbone.js',
			'mustache.js',
			//'backbone-localstorage.js',
			'myhome.js'
	);

 	foreach ($loadjs as $jsscript) {
		 echo '<script src="' . base_url() . 'js/'.$jsscript.'"></script>'."\n";
	 }
  
 ?>
  <script type="text/javascript">
   $(function() { 
 	  MyHome.boot($('#msgDropdown')); 
 	});
 </script>
</html>