<div class="headline">{$lang.form}</div>

<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td width="150">{$lang.title}:</td>
			<td><input type="text" name="title" value="{$form.title}" style="width:100%;" /></td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.key}:</td>
			<td><input type="text" name="key" value="{$form.key}" style="width:100%;" /></td>
		</tr>
		<tr>
			<td>{$lang.description}:</td>
			<td><textarea name="description" style="width:100%;">{$form.description}</textarea></td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.action}:</td>
			<td>
				<select name="action" style="width:100%;">
					<option value="store"{if $form.action == 'store'} selected="selected"{/if}>{$lang.store_to_db}</option>
					<option value="mail"{if $form.action == 'mail'} selected="selected"{/if}>{$lang.send_mail}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>{$lang.target_address}:</td>
			<td><input type="text" name="address" value="{$form.address}" style="width:100%;" /></td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.submit_button}:</td>
			<td><input type="text" name="submit" value="{$form.submit}" style="width:100%;" /></td>
		</tr>
		<tr>
			<td>{$lang.submit_message}:</td>
			<td><input type="text" name="submit_message" value="{$form.submit_message}" style="width:100%;" /></td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
		<input type="submit" name="delete" value="{$lang.delete}" onclick="return confirm('{$lang.delete_form_ask}');" />
	</p>
</form>

<div class="headline">{$lang.elements}</div>
{if $form != null}
	{foreach from=$form.elements item=element}
		<div{cycle values=', class="highlight_row"'} style="padding:4px;">
			<form action="" method="post">
				<table width="100%" border="0">
					<tr>
						<td colspan="1">{$lang.title}:</td>
						<td colspan="3"><input type="text" name="title" value="{$element.title}" style="width:100%;" /></td>
						<td>{$lang.type}:</td>
						<td>
							<select name="type" style="width:100%;">
								<option value="-1"{if $element.type == -1} selected="selected"{/if}>{$lang.separator}</option>
								<option value="0"{if $element.type == 0} selected="selected"{/if}>{$lang.headline}</option>
								<option value="1"{if $element.type == 1} selected="selected"{/if}>{$lang.label}</option>
								<option value="2"{if $element.type == 2} selected="selected"{/if}>{$lang.textbox}</option>
								<option value="3"{if $element.type == 3} selected="selected"{/if}>{$lang.textarea}</option>
								<option value="4"{if $element.type == 4} selected="selected"{/if}>{$lang.checkbox}</option>
								<option value="5"{if $element.type == 5} selected="selected"{/if}>{$lang.radiobutton}</option>
								<option value="6"{if $element.type == 6} selected="selected"{/if}>{$lang.listbox}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="5%">{$lang.order}:</td>
						<td width="20%"><input type="text" name="order" value="{$element.order}" style="width:50px;" /></td>
						<td width="5%">{$lang.required}:</td>
						<td width="20%"><input type="checkbox" value="1" name="required"{if $element.required == 1} checked="checked"{/if} /></td>
						<td width="10%" valign="top">{$lang.values}:</td>
						<td width="40%"><textarea name="values" style="width:100%;">{$element.values}</textarea></td>
					</tr>
				</table>
				<p>
					<input type="hidden" name="elementid" value="{$element.elementid}" />
					<input type="submit" name="edit_element" value="{$lang.save}" />
					<input type="submit" name="delete_element" value="{$lang.delete}" />
				</p>
			</form>
		</div>
	{/foreach}
	<hr noshade size="1" />
	<form action="" method="post">
		<table width="100%" border="0">
			<tr>
				<td colspan="1">{$lang.title}:</td>
				<td colspan="3"><input type="text" name="title" value="" style="width:100%;" /></td>
				<td>{$lang.type}:</td>
				<td>
					<select name="type" style="width:100%;">
						<option value="-1">{$lang.separator}</option>
						<option value="0">{$lang.headline}</option>
						<option value="1">{$lang.label}</option>
						<option value="2">{$lang.textbox}</option>
						<option value="3">{$lang.textarea}</option>
						<option value="4">{$lang.checkbox}</option>
						<option value="5">{$lang.radiobutton}</option>
						<option value="6">{$lang.listbox}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="5%">{$lang.order}:</td>
				<td width="20%"><input type="text" name="order" value="0" style="width:50px;" /></td>
				<td width="5%">{$lang.required}:</td>
				<td width="20%"><input type="checkbox" value="1" name="required" /></td>
				<td width="10%" valign="top">{$lang.values}:</td>
				<td width="40%"><textarea name="values" style="width:100%;"></textarea></td>
			</tr>
		</table>
		<p>
			<input type="submit" name="add_element" value="{$lang.add}" />
		</p>
	</form>
{else}
	<p>
		{$lang.elements_visible_save}
	</p>
{/if}