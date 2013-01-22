 <div id="homeContentWide">


      
	{if $error ne ""}
      <div id="errorMessage">
      {if $error eq "name_empty"}You must supply a name.
      {elseif $error eq "comment_empty"} You must supply a comment.
      {/if}
      </div>
	{/if}
      
      <p>Casillas en <span style="color:#CC0000;font-weight:bold;">Rojo</span> son requeridas.</p>
	  <?php if (!empty($error)) echo '<p><div style="color:#FF0000;background-color: #ffffe1;padding:30px;margin:20px 0px;">'.$lang['there_are_errors_in_form'] .'</div></p>'; ?>
	  
    </div>
	<form action="{$SCRIPT_NAME}?action=submit" method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="step" value="1" />
	<input type="hidden" name="mode" value="register" />
    <fieldset>
    <legend>Informacion Personal </legend>
      <div class="notes">
        <h4>Registrate</h4>
        <p class="last">Solo toma unos segundos! </p>
      </div>
      {if isset($error) }
      {/if}
      <? if (isset($error['first_name'])) { echo "<div class=\"error\">\n"; } else { ?>
      <div class="required" id="firstNameArea">
        <?php } if (isset($error['first_name'])) echo "<p class=\"error\"> ".$error['first_name']."</p>"; ?>
        <label for="first_name">Nombre:</label>
        <input type="text" name="first_name" id="first_name" class="inputText" size="18" maxlength="100" 
		value="<?php echo $first_name; ?>" />

      </div>
	  <? if (isset($error['last_name'])) { 
	  		echo "<div class=\"error\">\n"; 
	  	} else { 
	  		echo '<div class="required" id="lastNameArea">';
        } if (isset($error['last_name'])) echo "<p class=\"error\"> ".$error['last_name']."</p>"; ?>
        <label for="last_name">Apellido:</label>
        <input type="text" name="last_name" id="last_name" class="inputText" size="18" maxlength="100" 
		value="<?php echo $last_name; ?>" />

      </div>
	  <? if (isset($error['email'])) { echo "<div class=\"error\">\n"; 
	  	} else { 
      		echo '<div class="required" id="emailArea">';
	    } 
	    if (isset($error['email'])) echo "<p class=\"error\"> ".$error['email']."</p>"; ?>
        <label for="email"><?php echo $lang['email']; ?>:</label>
        <input type="text" name="email" id="email" class="inputText" size="18" maxlength="250" 
		value="<?php echo $email; ?>" />
      </div>
	  
	  <? if (isset($error['confirm_email'])) { 
	  		echo "<div class=\"error\">\n"; 
	  	} else {
			echo '<div class="required">';
        } 
        if (isset($error['confirm_email'])) echo "<p class=\"error\"> ".$error['confirm_email']."</p>"; ?>
        <label for="confirm_email"><?php echo $lang['confirm_email']; ?>:</label>
        <input type="text" name="confirm_email" id="confirm_email" class="inputText" size="18" maxlength="250" 
		value="<?php echo $confirm_email; ?>" />
        <small>Debe de ser igual al email que entraste en la casilla anterior.</small>

      </div>
	  
	  
	  <div class="required" id="birth_date">
	  	<label><?=$lang['birth_date']?>:</label>

		
		<?php
		$selected = ''; //let's reset it
		echo "<select name=\"birth_day\" id=\"birth_day\">";
		echo "<option value=\"\">".$lang['day']."</option>";
		for($d=1;$d<32;$d++) {
			$did = $d;
			if($did<10) $did = "0".$did;
			 if(!empty($birth_day)) {
			    //echo "bd: " .$birth_day . " did:".$did."<br>";
				if ($birth_day == $did){ $selected = "selected=\"selected\""; }
				else { $selected = ''; }
			}
			echo "<option " . $selected . " value=\"".$did."\">".$d."</option>\n\n";
		}
		echo "</select>\n\n";
		
		$selected = ''; //let's reset it
		echo "<select name=\"birth_month\" id=\"birth_month\">\n";
		echo "<option value=\"\">".$lang['month']."</option>\n";
		$months = array();
		$month_names = split(",",$lang['months'],12);
		for($m=0;$m<12;$m++) {
			$mid = $m+1;
			if($mid<10) $mid = "0".$mid;
			if ($birth_month == $mid){ $selected = "selected=\"selected\""; }
				else { $selected = ''; }
			echo "<option " . $selected ."value=\"".$mid."\">".trim($month_names[$m])."</option>\n";
		}
		echo "</select>\n\n";
		
		$selected = ''; //let's reset it
		echo "<select name=\"birth_year\" id=\"birth_year\">";
		echo "<option value=\"\">".$lang['year']."</option>";
		for($y=1914;$y < date('Y')-16;$y++) {
		    if(!empty($birth_year)) {
				if ($birth_year == $y){ $selected = "selected=\"selected\""; }
				else { $selected = ''; }
			} else {
				if($y == 1970) { 
					echo "<option selected=\"selected\">".$lang['year']."</option>\n"; }
			}
			echo "<option ". $selected . " value=\"".$y."\">".$y."</option>\n";
		}
		echo "</select>\n\n";
		?>


<small>Debes de tener  18 a&ntilde;os o mas para usar este servicio.</small>
	  </div>
	  
	  
	  
	  	<? if (isset($error['gender'])) { echo "<div class=\"error\">\n"; 
	  	} else {
	    	echo '<div class="required" id="genderArea">';
		}
		if (isset($error['gender'])) echo "<p class=\"error\"> ".$error['gender']."</p>"; ?>
        <label for="gender"><?php echo $lang['my_gender_is']; ?></label>
		   <?php echo $gender_menu; ?>
      </div>		  
	  
	  	 <? if (isset($error['seeks_gender'])) { echo "<div class=\"error\">\n"; 
	  	 } else { 
	    	echo '<div class="required" id="seeksGenderArea">';
		 }
		 if (isset($error['seeks_gender'])) echo "<p class=\"error\"> ".$error['seeks_gender']."</p>"; ?>
        <label for="seeks_gender"><?php echo $lang['seeks_gender']; ?></label>
		   <?php echo $seeks_gender_menu; ?>
      </div>
	  
	  
	  	<? if (isset($error['country'])) { echo "<div class=\"error\">\n"; 
	  	} else { 
	  		echo '<div class="required">';
		} 
		if (isset($error['country'])) echo "<p class=\"error\"> ".$error['country']."</p>"; ?>
        <label for="country"><?php echo $lang['country_of_residence']; ?>:</label>
          <select name="country" id="country" class="selectOne" onchange="loadStates(this)">
		  <option value=""><?php echo $lang['select_your_country']; ?></option>
          <option value="">---------------</option>
		   <?php echo $countries_menu; ?>
        </select>
      </div>	  
	  
	  <div  class="required" >
	  	<label for="state">Estado/Provincia:</label>
		<iframe id=state name=state src="remote.php?action=regionmenu&countryid=<?php 
		if(!empty($country)) echo $country; 
		else echo "0";
		if(!empty($state_id)) echo "&stateid=".$state_id;
		 ?>" WIDTH=240 HEIGHT=26 FRAMEBORDER=0 SCROLLING=no MARGINWIDTH=0 MARGINHEIGHT=0></iframe>
	  </div>
	     
      <div class="required" id="cityArea">
        <label for="city">Ciudad/Municipio:</label>
        <input type="text" name="city" id="city" class="inputText" size="18" maxlength="100" 
		value="<?php echo $city; ?>" />
      </div>

      <div class="optional" id="postalCodeArea">
        <label for="postal">Zip/C�digo Postal:</label>
        <input type="text" name="postal_code" id="postal_code" class="inputText" size="18" maxlength="50" 
		value="<?php echo $postal_code; ?>" />
      </div>
    </fieldset>


    
    <fieldset>
    <legend>Informacion de Login</legend>
      <div class="notes">
        <h4>Informacion <?php echo $username; ?></h4>
        <p>Tu apodo y   contrase&ntilde;a  deben de ser de por lo menos 6 caracteres de largo y son sensitivos a letras may&uacute;sculas. Por favor no pongas caracteres con acentos. </p>
      </div>
	  
	 <? if (isset($error['username'])) { echo "<div class=\"error\">\n"; 
	 	} else {
	 	echo '<div class="required" id="userNameArea">';
	    }
	    if (isset($error['username'])) echo "<p class=\"error\"> ".$error['username']."</p>"; ?>
	    <span id="userMessage"></span>
        <label for="username">Apodo:</label>
        <input type="text" name="username" id="username" class="inputText" size="18" maxlength="25" 
		value="<?php echo $username; ?>" />

        <small>
		<span style="text-align:left;"><input name="user_availability" type="button" onclick="xmlhttpPost('remote.php','action=checkuser&amp;username='+document.form1.username.value,'displayUserNameResult')" value="ver disponibilidad" /></span>
		<br />
        Solo puede contener  letras, numeros, y gui&oacute;n bajo (_) entre 6 y 20 caracteres de largo.</small>
	</div>
		
	  <? if (isset($error['password'])) { echo "<div class=\"error\">\n"; 
	  	} else {
      		echo '<div class="required" id="passwordArea">';
        }
        if (isset($error['password'])) echo "<p class=\"error\"> ".$error['password']."</p>"; ?>
        <label for="password"><?php echo $lang['password']; ?> :</label>
        <input type="password" name="password" id="password" class="inputPassword" size="18" maxlength="25" 
		value="<?php echo $password; ?>" />
        <small>Entre 6 y 25 caracteres de largo.</small>      </div>
		
      <? if (isset($error['confirm_password'])) { echo "<div class=\"error\">\n";
      	} else { 
      		echo  '<div class="required" id="passwordConfirmArea">';
        }
        if (isset($error['donfirm_password'])) echo "<p class=\"error\"> ".$error['donfirm_password']."</p>"; ?>
        <label for="confirm_password"><?php echo $lang['password_confirm']; ?> :</label>
        <input type="password" name="confirm_password" id="confirm_password" class="inputPassword" size="18" maxlength="25" 
		value="<?php echo $confirm_password; ?>" />
        <small>Debe de ser igual al de la casilla anterior .</small>
      </div>
	  
	  <? if(CAPTCHA_REG_ENABLED == 1) { ?>
	  <div class="required" id="captcha">
	  <label for="captcha_image">Verificacion de caracteres:</label>
	  <img src="includes/captcha/captcha.php" alt="CAPTCHA" />
            <input type="text" name="captchastring" size="10">
			<small>Escribe las letras y numeros que ves en la imagen de la izquierda</small>
	  </div>
	  <? } ?>
	  
	  <div class="required" id="terms">
         
        <small><input name="acceptterms" type="checkbox" id="acceptterms" <?php if (isset($acceptterms)) echo  'checked="checked"'; ?> value="1"> <?php echo $lang['accept_terms']; ?></small> 
      </div>
	  
      </fieldset>

    <fieldset>
      <div class="submit">

        <div>
          <input type="button" class="inputSubmit" value="<?php echo $lang['subscribe']; ?> &raquo;" onclick="validate()" />
		 <!-- <input type="submit" /> -->
        </div>
      </div>
    </fieldset>
  </form>
	
<?php
//}
?>
	
	</div>