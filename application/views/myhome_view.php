

<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span2" style="border: 1px solid #ccc;">
			something here
			<ul>
				
				<li><?php echo $this->lang->line('common_recent_visits'); ?></li>
				<li><?php echo $this->lang->line('common_my_photos'); ?></li>
			</ul>
			
			
			<div class="row">
				<h3><?php echo $this->lang->line('common_new_members'); ?></h3>
			</div>
			
			<div class="row">
				<h3><?php echo $this->lang->line('common_my_fav'); ?></h3>
			</div>
			
			<div class="row">
				<?php echo $this->lang->line('common_search'); ?>
			</div>
		</div>
		
  		<div class="span10" style="background-color: #CCCCCC;">
  			<h2>Hola, <?php echo $this->session->userdata('username'); ?>!</h2>
  			[the wall]
  			
  			
  			
  		<div id="main-content">Loading...</div>
  		
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>


    <script type="text/x-mustache-template" id="index-template">
      <h2>There are {{ count }} things</h2>
    </script>


<script type="text/x-mustache-template" id="index-original-template">
      <h2>There are {{ count }} recipes</h2>
      <div class='recipes'></div>
    </script>
    <script type="text/x-mustache-template" id="recipe-template">
      <h3>{{ name }}</h3>
      <p>{{ ingredients }}</p>
      <p>
        <button class='btn btn-danger'>Delete</button>
      </p>
    </script>
    
    
    <script type="text/x-mustache-template" id="form-template">
      <legend>Add a recipe</legend>
      <fieldset>
        <div class='control-group'>
          <label class='control-label' for='name'>Name</label>
          <div class='controls'>
            <input type='text' class='input-xlarge' id='name'>
          </div>
        </div>
        <div class='control-group'>
          <label class='control-label' for='ingredients'>Ingredients</label>
          <div class='controls'>
            <input type='text' class='input-xlarge' id='ingredients'>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Add Recipe</button>
        </div>
      </fieldset>
    </script>



<?php 
 if(isset($loadjs)){
 	foreach ($loadjs as $jsscript) {
		 echo '<script src="' . base_url() . 'js/'.$jsscript.'"></script>'."\n";
	 }
 } 
 ?>
 <script type="text/javascript">$(function() { MyHome.boot($('#main-content')); });</script>
 