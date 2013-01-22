{extends file='index.html'}

{block name=content}
<div id="homeContentWideTop">

		    <div id="homeLeftCol">
			<table border="0" cellpadding="4" cellspacing="0" >
			<tr><td width="193" valign="top">
		      <h1>Bienvenido!</h1>
			  <strong>Aqui vas a encontrar pareja</strong> <!--mediante citas en linea.-->
			  Quieres buscar pareja? Pues ya llegaste. Dejanos probarte que nuestro servicio de encontrar pareja por medio de la Internet puede llegar a darte una relacion duradera y sincera. Subcribete ya a AmigoCupido para conoser aquella persona que anda buscando su media naranja como t&uacute;.  Nuestro trabajo es de hacer que esta experiencia sea la mejor y mas agradable posible para ti. Desde el contenido y consejos en nuestros articulos hasta filtrar aquellas personas no deseadas.<br />
			 
			  
			  </td>
			  <td width="2" valign="top"><img src="images/spacer.gif" width="2" height="100" /></td>
			  <td width="196" valign="top"><h1>Busca Por:<br />
			  </h1>
			  
			    <table width="190" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFF99">
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" valign="bottom">&nbsp;</td>
                    <td rowspan="3" align="left" valign="middle"><img src="images/spacer.gif" width="10" height="1" alt="" /></td>
                    <td align="left" valign="bottom">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" valign="bottom">Soy</td>
                    <td align="left" valign="bottom">buscando</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" valign="top">
					<form action="/gente/" name="form1">
					<select name="gender" id="gender" class="indexFormElements" style="width:70px;">
                      <option value="1">Hombre</option>
                      <option value="2" selected="selected">Mujer</option>
                                                            </select></td>
                    <td align="left" valign="top"><select name="seeks_gender" id="seeks_gender" class="indexFormElements" style="width:70px;">
                      <option value="1">Hombre</option>
                      <option value="2">Mujer</option>
                                                            </select></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">entre las edades de </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">
                    {if isset($age_menu)}
                    	{$age_menu}
                    {/if}
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">&nbsp;</td>
                  </tr>
                   {if isset($countries_menu)}
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">en</td>
                  </tr>
                 
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">
                        {$countries_menu}
                    </td>
                  </tr>
                  {/if}
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">
                      <input type="submit" name="Submit" value="Buscar" />
					  </form>	
                    </td>
                  </tr>
                </table>		    
			  </td>
			  </tr>
	  </table>
	<table border="0">
		<tr>
		<td>
		<div style="height:54px; width:310px; margin-right:8px;background: url(images/bg_note.gif) no-repeat; padding:10px 0px 0px 18px;">AmigoCupido es un servicio totalmente <strong>Gratis!</strong><br />No compartimos tu informacion o tu email con nadie.
		</div>
		</td>
		</tr>
	</table>
  
	  
		    </div>
	  
	  	    <div id="homeRightPic" style="float:right;"><img src="images/home_right_pic.png" alt="amigos en la web" /></div>
	<div class="loginBox">
		<form method="post"  onsubmit="return checkData()" action="/login/"  id="loginForm">
		<label>Apodo:<input type="text" name="username" class="text" /></label>
		<label> Contrase&ntilde;a: <input type="password" name="password" class="text" />
		<input type="submit" hspace="7" vspace="4" alt=" Entrar " value=" Entrar " class="buttons" /></label>
	
		No estas registrado? Vamos... <strong><a href="/registrate/" style="color:#0033FF; font-size:14px">&iexcl;Registrate Ya!</a></strong>
		<br />Se te olvido tu <a href="http://www.amigocupido.com/olvide_contrasena/">contrase&ntilde;a?</a>
		</form>
		
		<div class="clear" style="clear:both"></div>
	</div>	
			
			</div>
<div style="clear:both"></div>
{/block}	