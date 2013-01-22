{extends file='index.html'}
{block name=content}


<div id="leftCol">
	  <div class="leftBox">
          <h2>{$lang.hello}  {$username}</h2>
        <div class="content">
         	<div id="memberHomeNavLinks">
				<ul>
	            <li><a href="/perfil/{$username}">{$lang.see_my_profile}</a></li>
	            <li><a href="/profiler.php" >{$lang.edit_profile}</a></li>
				<li><a href="/fotos/{$username}">{$lang.add_and_change_photos}</a></li>
	            <li><a href="/micuenta/">{$lang.my_account_settings}</a></li>
				<li><a href="/mi_perfil_contrasena/">{$lang.change_password}</a></li>
			    <li><a href="/logout">Log out</a></li>
				<li><a href="/msgs/">{$lang.messages} <span id="msgCount"></span></a></li>
				</ul>
			</div>
	    </div>
	</div>
	<div id="friendsCol" class="leftBox">
		<h2>{$lang.my_friends}</h2>
		<table id="friendsTable"></table>
	</div>
	<div style="clear:both;">&nbsp;</div>

          <div class="leftBox">
		  	<h2>{$lang.search}</h2>
            <div class="content">
			some content          
			</div>
		</div>

		</div> <!-- end of homeLeftCol -->
		
		
		<div id="rightCol">


        <table width="444" border="0" cellspacing="0" class="userTable">
          <tr valign="top">
            <td nowrap="nowrap" class="userCellTitle">{$lang.new_members}</td>
            <td nowrap="nowrap" class="userCellEdit"><a href="/gente">{$lang.search}</a> </td>
          </tr>
          <tr valign="top">
            <td colspan="2">
			bla bla 0
			</td>
          </tr>
          <tr valign="top">
            <td colspan="2">&nbsp;</td>
          </tr>
          </table>


      <table width="444" border="0" cellspacing="0" class="userTable" id="waitinFriendsTable">
	    <tr valign="top">
          <td nowrap="nowrap" class="userCellTitle">{$lang.waiting_friends}</td>
            
          </tr>
          
			<tr>
			<td>
			waiting buddies</td>
			</tr>
			
        </table>
        
    </div><!--end of homeRightCol-->
	<div style="clear:both;"></div>


{/block}