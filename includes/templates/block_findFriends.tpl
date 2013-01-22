{extends file="index.html"}

{block name=content}

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td width="180">
	 <div id="searchBox" class="leftBox">
            <h2>{$lang.people_search}<a href="#"></a></h2>
			
		<div class="leftBoxSearchContent">
			<form id="form1" name="form1" method="post">
			<input type="hidden" id="p" value="1" />
			<div class="searchItem">
				<strong>{$lang.gender}:</strong><br />
				
                <input id="f" name="f" type="checkbox" value="1" {if $data.check_f == true} checked="checked" {/if} />
                {$lang.woman} 
                           
                <input id="m" name="m" type="checkbox" value="1" {if $data.check_m == true} checked="checked" {/if} /> {$lang.man}
			</div>
			
			<div class="searchItem"> 
				<strong>{$lang.age}:</strong><br />
				{$lang.between} <strong><span id="min_age_txt">{$data.min_age}</span></strong> {$lang.and}
	 			<strong><span id="max_age_txt">{$data.max_age}</span></strong> 

                <input id="min_age" name="min_age" type="hidden" value="{$data.min_age}" />
			  	<input id="max_age" name="max_age" type="hidden" value="{$data.max_age}" />
				 
              Anos
		  
				<div id="track6" style="width:200px;background-color:#aaa;height:5px;position:relative; margin-top:6px; margin-bottom:14px">
					<div id="handle6-1" style="position:absolute;top:0;left:0;width:11px;height:18px;background-image:url(../images/horizontal_handle.gif); background-position:0px -3px;"> </div>
					<div id="handle6-2" style="position:absolute;top:0;left:0;width:11px;height:18px;background-image:url(../images/horizontal_handle.gif); background-position:0px -3px;"> </div>
				</div>
			</div>
			
			<div class="searchItem">
				<strong>{$lang.country}</strong><br />
					{$countries_menu}
			</div>
	
			<div class="searchItem">
					<input id="photo_only" type="checkbox" name="photo_only" value="1" {if $data.photo_only == true} checked="checked" {/if} />
				{$lang.only_profiles_with_photos}<br />
			</div>
			
			<div class="searchItem">	
				   {$lang.results_per_page}
				  <select id="rpp" name="rpp">
				  
				 <script language="javascript"> 
				 //debugger;
				 if (get_cookie("rpp")!="" && get_cookie("rpp")== 20) 
				 	document.write('<option value="20" selected="selected" >20</option>');
				else document.write('<option value="20" >20</option>');
				if (get_cookie("rpp")!="" && get_cookie("rpp")== 40) 
				 	document.write('<option value="40" selected="selected" >40</option>');
				else document.write('<option value="40" >40</option>');	
				if (get_cookie("rpp")!="" && get_cookie("rpp")== 60) 
				 	document.write('<option value="60" selected="selected" >60</option>');
				else document.write('<option value="60" >60</option>');
				</script>
				</select><br />
			</div>
	
				</form>
		</div>
		
		<h2>{$lang.by_city}</h2>
		<div class="leftBoxSearchContent" style="text-align:center;">    
			<div><input id="user_city" name="user_city" type="text"  /></div>
			<div>
			<input id="search_button" type="button" name="Submit" value="{$lang.search}" />
			</div>
		</div>

		
		<h2>{$lang.by_username}</h2>
		<div class="leftBoxSearchContent" style="text-align:center;">    
			<div><input id="user_username" name="user_username" type="text"  /></div>
			<div align="center">
				<input id="search_username_button" type="button" name="Submit" value="{$lang.search}" />
			</div>		
		</div>

	</div>	
			
	</td>
	
    <td width="6"><img src="../images/spacer.gif" width="6" height="10" /></td>
	
    <td>
	<table width="476" border="0" cellspacing="0" class="userTable">
        <tr valign="top">
          <td nowrap="nowrap" class="userCellTitle">{$lang.our_members}!</td>
          <td nowrap="nowrap" class="userCellEdit" id="status">&nbsp;</td>
        </tr>
        <tr valign="top">
          <td colspan="2" id="usersCell">
              <div style="width:100%;text-align:center;margin-top:20px;"><img src="../images/icon_spin_32.gif" /></div>
         </td>
        </tr>
        <tr valign="top">
          <td colspan="2">&nbsp;<div id="statusBottom">Area Status</div></td>
        </tr>
      </table></td>
  </tr>
  <tr valign="top">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>


		<script type="text/javascript" language="javascript">
	var f = document.form1;
	var theValues = new Array();
	for(var i=18;i<=89;i++){
		theValues.push(i);
	}
	var slider6 = new Control.Slider(['handle6-1','handle6-2'],'track6',{
			
			sliderValue:[
				'{$data.min_age}',
				'{$data.max_age}'],
			range:$R(18,89),
			values:theValues,
			restricted:true,
			handleImage:['/images/horizontal_handle.gif','/images/horizontal_handle.gif'],
			onSlide:function(v){
				//$('debug6').innerHTML='slide: '+v.inspect();  
				slide_update(v);
			},
			onChange:function(v){
				//$('debug6').innerHTML='changed! '+v.inspect();
				show_page(1);
			}
		});
		
	function slide_update(v){
		var temp = new Array();
		var numbers = v.toString();
		temp = numbers.split(',');
		f.min_age.value = temp[0];
		f.max_age.value = temp[1];
		$('min_age_txt').innerHTML= temp[0];
		$('max_age_txt').innerHTML= temp[1];
	}
	</script>

{/block}