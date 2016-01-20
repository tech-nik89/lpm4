<div class="headline">{$lang.imprint_edit}</div>
	<form action="" method="post">
	
		
	<table border="0" style="width:100%" cellpadding="5" cellspacing="1">
		<tr>
			<td style="width:10%">{$lang.owner_name}:</td>
			<td><input type="text" name="owner_name" value="{$edit.owner_name}" /></td>
		</tr>
		<tr>
			<td>{$lang.street}:</td>
			<td><input type="text" name="owner_street" value="{$edit.owner_street}" /></td>
		</tr>
		<tr>
			<td>{$lang.loc}:</td>
			<td><input type="text" name="owner_loc" value="{$edit.owner_loc}" /></td>
		</tr>
		<tr>
			<td>{$lang.phone}:</td>
			<td><input type="text" name="owner_tel" value="{$edit.owner_tel}" /></td>
		</tr>
		<tr>
			<td>{$lang.email}:</td>
			<td><input type="text" name="owner_mail" value="{$edit.owner_mail}" /></td>
		</tr>
		<tr><td>&nbsp;</td></td>
		<tr>
			<td>{$lang.cont_name}:</td>
			<td><input type="text" name="cont_name" value="{$edit.cont_name}" />
				<input type="checkbox" name="same" onChange="{literal}
					if(document.getElementsByName('same')[0].checked){
						document.getElementsByName('cont_name')[0].value = document.getElementsByName('owner_name')[0].value;
						document.getElementsByName('cont_street')[0].value = document.getElementsByName('owner_street')[0].value;
						document.getElementsByName('cont_loc')[0].value = document.getElementsByName('owner_loc')[0].value;
					}else{
						document.getElementsByName('cont_name')[0].value = '';
						document.getElementsByName('cont_street')[0].value = '';
						document.getElementsByName('cont_loc')[0].value = '';
					}
				{/literal}" value="1" {$edit.same}" /> {$lang.owner_name} = {$lang.cont_name}</td>
		</tr>
		<tr>
			<td>{$lang.street}:</td>
			<td><input type="text" name="cont_street" value="{$edit.cont_street}" /></td>
		</tr>
		<tr>
			<td>{$lang.loc}:</td>
			<td><input type="text" name="cont_loc" value="{$edit.cont_loc}" /></td>
		</tr>
		<tr><td>&nbsp;</td></td>
		<tr>
			<td>{$lang.court}:</td>
			<td><input type="text" name="court" value="{$edit.court}" /></td>
		</tr>
		<tr>
			<td valign="top">{$lang.imprint}:</td>
			<td><textarea name="imprint" style="width:100%;" rows="20">{$edit.imprint}</textarea></td>
		</tr>
	
	</table>
	<input type="submit" name="save" value="{$lang.save}"/>
	</form>