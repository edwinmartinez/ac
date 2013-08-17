 <div class="container-fluid">

	<h3><?php echo $title; ?></h3>
	
	
	<div class="span3">
			
		<div class="well sidebar-nav">
            <ul class="nav nav-list">
				<li><?php echo $this->lang->line('common_email'); ?></li>
				<li><?php echo $this->lang->line('common_change_password'); ?></li>
				<li><?php echo $this->lang->line('common_cancel_account'); ?></li>
			</ul>
		</div>
			

	</div>
	
	<div class="span9">

<div id="statusMessage" class="hidden">
<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <h4 id="statusMessageTitle"><?php echo $this->lang->line('common_success'); ?></h4>
  <div id="statusMessageContent">message</div>
</div>
</div>

		 <div class="row-fluid">	
			<form name="user-email-form" id="user-email-form">
		<?php echo form_label($this->lang->line('common_email').':', 'user_email'); ?>
			<div class='col-lg-4'>
			<?php echo form_input(array(
				'name'=>'user_email',
				'id'=>'user_email',
				'class' => 'form-control',
				'placeholder' => $this->lang->line('common_email'),
				'value'=>$user_info->user_email)
			);?>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-primary" type="submit" name="user-email-submit" id="user-email-submit"><?php echo $this->lang->line('common_save'); ?></button>
			</div>
			</form>
		</div>
		
		 <div class="row-fluid">
			<hr>
			
			
			<div><h3><?php echo $this->lang->line('common_password_change'); ?></h3></div>
			<form name="changepassword_form" id="changepassword_form">
			<label><?php echo $this->lang->line('common_current_password'); ?></label>
			<input type="text" name="old_password" id="old_password" class="form-control input-large" required />
			<label><?php echo $this->lang->line('common_new_password'); ?></label>
			<input type="text" name="new_password" id="new_password" class="form-control input-large" required />
			<label><?php echo $this->lang->line('common_retype_password'); ?></label>
			<input type="text" name="new_password_confirm" id="new_password_confirm" class="form-control input-large" />
			<div>
			<button class="btn btn-primary" type="submit" name="change_password_submit" id="change_password_submit"><?php echo $this->lang->line('common_save'); ?></button>
			</div>
			</form>
		</div>
	
		
		<div class="row-fluid">
			<hr>
			<form name="cancelaccount_form" id="cancelaccount_form">
			<h3><?php echo $this->lang->line('common_cancel_account'); ?></h3>
			<div>
				<?php echo $this->lang->line('common_cancel_account_instructions'); ?>
			</div>
			<div>
				<label><?php echo $this->lang->line('common_cancel_account_reason'); ?></label>
			<textarea name="cancel_account_reason" id="cancel_account_reason"></textarea>
			</div>
			<div>
				
			<button type="submit" class="btn btn-default" name="cancel_account"><?php echo $this->lang->line('common_cancel_account'); ?></button>
			
			</div>
			<!--</form>-->
		</div>
		
		</div>
	</div>
	
</div>


<div id="myModal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo $this->lang->line('common_cancel_account_success'); ?></h3>
  </div>
  <div class="modal-body">
    <p><?php echo $this->lang->line('common_cancel_account_goodbye'); ?></p>
  </div>
  <div class="modal-footer">
  	<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line('common_close'); ?></button>
    <!--<a href="#" class="btn btn-primary">Save changes</a>-->
  </div>
</div>

<script>
	var serviceUrl = '/index.php/api/v1/';
	var validForm = true;
	//var theForm;
	var somedata;
	var serviceFunc;
	$(function(){
		$("#user-email-submit").on("click", function(event){
  			event.preventDefault();
  			//console.log($(this).parents('form:first').serialize());
  			theForm = $(this).parents('form:first');
  			serviceFunc = 'emailchange.json';
  			sendForm(theForm);  			
		});
		// $('#changepassword_form').on('submit', function(e) {
          //  e.preventDefault(); // <-- important
            //alert('hi');
		//});
		
	});

 var sendForm = function (theForm) {
    if(typeof serviceUrl != 'undefined' && serviceFunc != 'undefined') {
    	formreqUrl = serviceUrl + serviceFunc ;
   } else {
   		alert('undefined services');
   		return false;
   }
   $('#statusMessage').addClass('hidden');
    if("validForm" in window && validForm == true){
     //console.log("validForm:",validForm);
    $.ajax({
        
        //dataType: 'jsonp',
        type: 'post',
        dataType: 'json', 
        url: formreqUrl, 
        data: theForm.serialize(),
        success: function(json) {
            //$('#ContactForm').find('.form_result').html(response);
            console.log(json);
            if(json.success == 'true') {
	            theForm.prepend($('#statusMessage'));
	            $('#statusMessage').removeClass('hidden');
	            $('#statusMessage .alert').removeClass('alert-error');
	            $('#statusMessage .alert').addClass('alert-success');
	            $('#statusMessageTitle').html('<?php echo $this->lang->line('common_success'); ?>')
	            $('#statusMessageContent').html(json.message);
	        
	            //$('#statusMessage').fadeOut(5000);
	            theForm.find("input[type=text], textarea").val("");
	            if(json.new_email){
	            	$('#user_email').val(json.new_email);
	            }
	            
	            if(serviceFunc == 'cancelaccount.json'){
	            	$('#myModal').modal('show');
	            	$('#myModal').on('hidden', function () {
					 window.location.replace("/logout");
					});
	            }
            } else {
            	theForm.prepend($('#statusMessage'));
	            $('#statusMessage').removeClass('hidden');
	            $('#statusMessage .alert').removeClass('alert-success');
	            $('#statusMessage .alert').addClass('alert-error');
	            $('#statusMessageTitle').html('<?php echo $this->lang->line('common_error'); ?>')
	            $('#statusMessageContent').html(json.message);
	           // $('#statusMessage').fadeOut(5000);
            }
            
            
            //$("#user-form-div").html(json.html);
        }
    });
   } else {
       console.log('validForm not set or false');
   }
    return false;
}

$.validator.setDefaults({
	submitHandler: function() { alert("submitted!"); }
});
	
var valemailchange = $("#user-email-form").validate({
	rules: {
		user_email: {
			required: true,
			email: true
		}
	},
		submitHandler: function(form) {
			serviceFunc = 'emailchange.json';
			sendForm($(form));
			
		}
});
	
var valpasschange =	$("#changepassword_form").validate({
		rules: {
	  	// simple rule, converted to {required:true}
			old_password: "required",
		// compound rule
			new_password : {
				required: true,
				minlength: 6,
				maxlength: 18,
			},
			new_password_confirm: {
		  		required: true,
		  		minlength: 6,
		  		maxlength: 18,
		  		equalTo: "#new_password"
			}
		},
		messages: {
			old_password: {
				required: "<?php echo $this->lang->line('common_please_provide_a_password'); ?>"
			},
			new_password: {
				required: "<?php echo $this->lang->line('common_please_provide_a_password'); ?>",
				minlength: "<?php echo $this->lang->line('common_password_must_be_at_least_x_characters_long'); ?>",
				maxlength: "<?php echo $this->lang->line('common_password_must_be_at_most_x_characters_long'); ?>"
			},
			new_password_confirm: {
				required: "<?php echo $this->lang->line('common_please_provide_a_password'); ?>",
				minlength: "<?php echo $this->lang->line('common_password_must_be_at_least_x_characters_long'); ?>",
				equalTo: "<?php echo $this->lang->line('common_please_enter_the_same_password'); ?>",
				maxlength: "<?php echo $this->lang->line('common_password_must_be_at_most_x_characters_long'); ?>"
			}
		},
		submitHandler: function(form) {
			serviceFunc = 'changepassword.json';
			sendForm($(form));
			
		}
	});
	
$('#cancelaccount_form').validate({
	rules: {
		cancel_account_reason: {
			maxlength: 140
		}
	},
	messages: {
		cancel_account_reason: {
			 required: true,
			maxlength: "<?php echo $this->lang->line('common_cancel_reason_must_be_at_most_x_characters_long'); ?>"
		}
	},
	submitHandler: function(form) {
		serviceFunc = 'cancelaccount.json';
		sendForm($(form));
	}
});
</script>