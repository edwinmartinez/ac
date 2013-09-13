

<div class="container-fluid">
	<div class="row-fluid">

		<div class="span2" style="border: 1px solid #ccc;">
			something here
			<ul>
				<li><?php echo $this->lang->line('common_recent_visits'); ?></li>
				<li><?php echo $this->lang->line('common_my_photos'); ?></li>
			</ul>


			<div class="row">
				<h4><?php echo $this->lang->line('common_new_members'); ?></h4>
				<div class="new-users-container"></div>
			</div>

			<div class="row">
				<h4><?php echo $this->lang->line('common_my_fav'); ?></h4>
			</div>

			<div class="row">
				<?php echo $this->lang->line('common_search'); ?>
			</div>
		</div>

  		<div class="span10">
  			<h3>Hola, <?php echo $this->session->userdata('username'); ?>!</h3>


			<div class="tabbable"> <!-- Only required for left/right tabs -->
			  <ul class="nav nav-tabs" id="statusTabs">
			    <li id="addStatusTab" class="active"><a href="#statusUpdateForm" data-toggle="tab"><?php echo $this->lang->line('common_update_status'); ?></a></li>
			    <li id="addPhotoTab"><a href="#addPhotoForm" data-toggle="tab"><?php echo $this->lang->line('common_add_photos'); ?></a></li>
			  </ul>
			  <div class="tab-content">
			    <div class="tab-pane active" id="statusUpdateForm">
			      <p>I'm in Section 1.</p>
			    </div>
			    <div class="tab-pane" id="addPhotoForm">
			    	<?php echo form_open_multipart('user/add_photo','id="photoform"');?>
			      <!--<form method="post" id="photoForm" action="/user/add_photo">-->
			      	<div class='control-group'>
			      		<!--<div>
			      			<textarea name="photocaption" rows="3" style="width: 90%"></textarea>
			      		</div>-->
			      		<div>
			      			<input type="file" id="filePhoto" name="filePhoto[]" /> <input type="submit" id="formsubmit"/>
			      		</div>
			      	</div>
			      </form>
			    </div>
			  </div>
			</div>

  			<div id="feeds-container">Loading...</div>


  		</div>


	</div><!--<div class="row-fluid">-->
</div>


<script type="text/x-mustache-template" id="user-list-template">

</script>

<script type="text/x-mustache-template" id="user-unit-template">
<div class="userUnit">
		<div class="userUnit-imgDiv"><img class="userUnit-img" src="{{profile_img}}" /></div>
		<div class="userUnit-info">
			<div class="userUnit-age">{{age}}</div>
			<div class="userUnit-username">{{username}}</div>
		</div>
</div>
</script>

<script type="text/x-mustache-template" id="wall-list-template">

</script>

<script type="text/x-mustache-template" id="wall-unit-template">
	<div class="profile-pic"><img src="{{profile_pic}}" /></div>
	<div class="statusfeed_username">{{username}}</div>
	{{#statusfeed_img}}<div class="statusfeed_img"><img src="{{statusfeed_img}}" border="0"></div>{{/statusfeed_img}}
	<div class="statusfeed_content">{{statusfeed_content}}</div>
	<div class="statusfeed_actionlinks"><span><a class="statusfeed_likelink" href="/likeit/{{statusfeed_id}}"><?php echo $this->lang->line('common_like'); ?></a></span>
		<span><a class="statusfeed_commentlink" href="{statusfeed_id}}"><?php echo $this->lang->line('common_comment'); ?></a></span>
		<i class="icon-thumbs-up"></i>
		</div>
</script>

<!-- status form -->
<script type="text/x-mustache-template" id="statusForm-template">
	<fieldset>
			<div class='control-group'>
			<div style="height:0px;overflow:hidden">
			   <input type="file" id="fileInput" name="fileInput" />
			</div>
          <div class='controls'>
            <input type="text" class='input-xlarge threadMessageItem-replyField' id="status_text" name="status_text" />
          </div>

	       <div class="btn-toolbar">
				<div class="btn-group controls">
				  <!--<button class="btn">Left</button>-->
				  <a class="btn" href="#" onclick="chooseFile();"><i class="icon-camera"></i></a>
				 <!-- <button class="btn">Right</button>-->
				</div>
				<div class="btn-group controls">
					<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('common_post'); ?></button>
				</div>



			</div>
        </div>
		        <div class="form-actions">

		        </div>
	</fieldset>
</script>
 <script>
 	function chooseFile() {
      //$("#fileInput").click();
      $('#statusTabs a:last').tab('show');
   }

   //autosubmit
$('input[type=file]').change(function() {
    // select the form and submit
  var filePhotoField = $('#filePhoto');
  if (filePhotoField.val().length != 0 ) {
  	$('form').submit();
  }


});
 </script>