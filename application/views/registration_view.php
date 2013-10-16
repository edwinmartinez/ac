
	<?php if($topmessage != ''){ ?>
	<div class="row-fluid">
		<div class="span12"><?php echo $topmessage; ?></div>
	</div>
	<?php } ?>
	<div class="row-fluid">


	    <div class="span8">
	    	 <div class="hero-unit">
	      		<h4>Subheading</h4>
	      		<div id="front-page-inner-image" class="hidden-phone"></div>
	      		<div id="contentTextHome"><?php echo $this->lang->line('common_content_home'); ?></div>
	   		</div>
	   </div>

		<!-- registration form -->
		<div class="span4">

			<h3><?php echo $this->lang->line('common_signup'); ?></h3>

			<div id="signUpForm">
				<?php echo form_open("user/registration",'id="registerForm" autocomplete="off"'); ?>

			<div>
				<label for="country"><?php echo $this->lang->line('users_country_of_residence'); ?></label>
				<select id="country" name="country">
					<option value="0"><?php echo $this->lang->line('users_select_your_country'); ?></option>
					<?php foreach ($countries as $country) {
						echo '<option value="'.$country['countries_id'].'" '. set_select('country', $country['countries_id']).'>'.$country['countries_name_es'].'</option>'."\n";
					}?>
				</select>
			</div>

			<div id="dobFields">
				<label><?php echo $this->lang->line('users_dob'); ?></label>

				<div>
					<select name="birth_day" id="birth_day">
						<option value="0" <?php echo set_select('birth_day', '', TRUE); ?>><?php echo $this->lang->line('users_day'); ?></option>
						<?php for ($i=1; $i <= 31; $i++) {
							$did = $i;
							if($did<10) $did = "0".$did;
							echo '<option value="'.$did.'" '. set_select('birth_day', $did).' >'.$i.'</option>'."\n";
						}?>
					</select>

					<select name="birth_month" id="birth_month">
						<option value="0" <?php echo set_select('birth_month', '', TRUE); ?>><?php echo $this->lang->line('users_month'); ?></option>
						<option value="01" <?php echo set_select('birth_month', '01'); ?>><?php echo $this->lang->line('users_jan'); ?></option>
						<option value="02" <?php echo set_select('birth_month', '02'); ?>><?php echo $this->lang->line('users_feb'); ?></option>
						<option value="03" <?php echo set_select('birth_month', '03'); ?>><?php echo $this->lang->line('users_mar'); ?></option>
						<option value="04" <?php echo set_select('birth_month', '04'); ?>><?php echo $this->lang->line('users_apr'); ?></option>
						<option value="05" <?php echo set_select('birth_month', '05'); ?>><?php echo $this->lang->line('users_may'); ?></option>
						<option value="06" <?php echo set_select('birth_month', '06'); ?>><?php echo $this->lang->line('users_jun'); ?></option>
						<option value="07" <?php echo set_select('birth_month', '07'); ?>><?php echo $this->lang->line('users_jul'); ?></option>
						<option value="08" <?php echo set_select('birth_month', '08'); ?>><?php echo $this->lang->line('users_aug'); ?></option>
						<option value="09" <?php echo set_select('birth_month', '09'); ?>><?php echo $this->lang->line('users_sep'); ?></option>
						<option value="10" <?php echo set_select('birth_month', '10'); ?>><?php echo $this->lang->line('users_oct'); ?></option>
						<option value="11" <?php echo set_select('birth_month', '11'); ?>><?php echo $this->lang->line('users_nov'); ?></option>
						<option value="12" <?php echo set_select('birth_month', '12'); ?>><?php echo $this->lang->line('users_dec'); ?></option>
					</select>

					<select name="birth_year" id="birth_year">
						<option value="0"><?php echo $this->lang->line('users_year'); ?></option>
						<?php for ($i=2013; $i > 1900; $i--) {
							$did = $i;
							//if($did<10) $did = "0".$did;
							echo '<option value="'.$i.'" '. set_select('birth_year', $i).' >'.$i.'</option>'."\n";
						}?>
					</select>
					<label for="birth_day" class="error" style="display:none;"></label>
					<label for="birth_month" class="error" style="display:none;"></label>
					<label for="birth_year" class="error" style="display:none;"></label>
				</div>

			</div>


			<div class="clear">
				<label for="gender"><?php echo $this->lang->line('users_select_your_gender'); ?></label>
			<!--<label for="gender"><?php echo $this->lang->line('users_select_your_gender'); ?></label>
			<select name="gender" id="gender">
				<option value=""><?php echo $this->lang->line('users_select_your_gender_placeholder'); ?></option>
				<option value="2"><?php echo $this->lang->line('users_female'); ?></option>
				<option value="1"><?php echo $this->lang->line('users_male'); ?></option>
			</select>-->
				<input type="radio" name="gender"  class="ui-gender-radio" value="2" <?php echo set_radio('seeks_gender', '2'); ?> /> <label><?php echo $this->lang->line('users_female'); ?></label>
				<input type="radio" name="gender"  class="ui-gender-radio" value="1" <?php echo set_radio('seeks_gender', '1'); ?> /> <label><?php echo $this->lang->line('users_male'); ?></label>
				<label for="gender" class="error" style="display:none;"></label>
			</div>

			<div class="clear">
			<label for="seeks_gender"><?php echo $this->lang->line('users_seeks_gender'); ?></label>
				<input type="radio" name="seeks_gender"  class="ui-gender-radio" value="2" <?php echo set_radio('seekgender', '2'); ?> /> <label><?php echo $this->lang->line('users_female'); ?></label>
				<input type="radio" name="seeks_gender"  class="ui-gender-radio" value="1" <?php echo set_radio('gender', '1'); ?> /> <label><?php echo $this->lang->line('users_male'); ?></label>
				<label for="seeks_gender" class="error" style="display:none;"></label>
			</div>

			<div class="clear">
				<label for="username"><?php echo $this->lang->line('users_username'); ?></label>
				<input type="text" id="username" name="username" value="<?php echo set_value('username'); ?>" placeholder="<?php echo $this->lang->line('users_username'); ?>" required />
			</div>
			<div>
				<label for="email_address"><?php echo $this->lang->line('users_email'); ?>:</label>
				<input type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address'); ?>" />
			</div>
			<div>
				<label for="password"><?php echo $this->lang->line('users_password'); ?>:</label>
				<input type="password" id="password" name="password" value="<?php echo set_value('password'); ?>" />
			</div>
			<div>
				<label for="confirm_password"><?php echo $this->lang->line('users_confirm_password'); ?>:</label>
				<input type="password" id="confirm_password" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" />
			</div>
			<div>
			<div class="clear">
				<?php echo validation_errors('<p class="text-error">'); ?>
			</div>
			  <a href="#" class="btn btn-primary btn-large" id="register-button"><?php echo $this->lang->line('common_register'); ?></a>
			  </div>
			  <script type="text/javascript">

			  	$(document).ready(function(){

					// add new rule here
					 $.validator.addMethod("valueNotEquals", function(value, element, arg){
					  return arg != value;
					 }, "Value must not equal arg.");

			  	    $("#registerForm").validate({
			  	    	success: function(label) {
    						//label.addClass("valid").html('<li class="icon-ok"></li>')
  						},
  						rules: {
							country:		{ valueNotEquals: "0" },
							birth_day: 		{ valueNotEquals: "0" },
							birth_month: 	{ valueNotEquals: "0" },
							birth_year: 	{ valueNotEquals: "0" },
						    username: {
						    	required: true,
						    	minlength: 5,
						    	remote: "/api/v1/checkusername.json"
						    },
						    gender: { required: true },
						    seeks_gender: { required: true },
						    email_address: {
						      required: true,
						      email: true,
						      remote: "/api/v1/checkemail.json"
						    },
						    password: {
						    	required: true,
						    	minlength: 6
						    },
							confirm_password: {
								required: true,
								minlength: 5,
								equalTo: "#password"
							}
						  },

						messages: {
							country: { valueNotEquals: "Please select a country!" },
							birth_day: 		{ valueNotEquals: "<?php echo $this->lang->line('users_dob_day_missing'); ?>" },
							birth_month: 	{ valueNotEquals: "<?php echo $this->lang->line('users_dob_month_missing'); ?>" },
							birth_year: 	{ valueNotEquals: "<?php echo $this->lang->line('users_dob_year_missing'); ?>" },
							email_address: {
								required:"Enter your email",
								email: "<?php echo $this->lang->line('common_enter_a_valid_email'); ?>",
								remote: "<?php echo $this->lang->line('common_email_exists'); ?>"
							},
							gender: {
								required: "<?php echo $this->lang->line('users_select_your_gender'); ?>"
							},
							seeks_gender: {
								required: "<?php echo $this->lang->line('users_select_seeks_gender'); ?>"
							},
							username: {
								required: "<?php echo $this->lang->line('common_enter_your_username'); ?>",
								minlength: jQuery.format("<?php echo $this->lang->line('common_enter_at_least_x_characters'); ?>"),
								remote: "<?php echo $this->lang->line('common_username_exists'); ?>"
							},
							password: {
								required: "<?php echo $this->lang->line('common_provide_a_password'); ?>",
								minlength: jQuery.format("<?php echo $this->lang->line('common_enter_at_least_x_characters'); ?>")
							},
							confirm_password: {
								required: "<?php echo $this->lang->line("common_repeat_your_password"); ?>",
								minlength: jQuery.format("<?php echo $this->lang->line('common_enter_at_least_x_characters'); ?>"),
								equalTo: "<?php echo $this->lang->line("common_repeat_your_password"); ?>"
							}
						}
					});
				  $("#register-button").click(function(event){
						event.preventDefault();
						$("#registerForm").submit();
					 });
				});
			  </script>
		 <?php echo form_close(); ?>
		</div><!--<div class="reg_form">-->
		</div>

	</div>
