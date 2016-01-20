<form action="" method="post">
	{foreach from=$form.elements item=element}
		{if $element.type == -1}
			<hr size="1" noshadow="noshadow" />
		{/if}
		{if $element.type == 0}
			<!-- headline -->
			<div class="headline">{$element.title}</div>
		{/if}
		{if $element.type == 1}
			<p>{$element.title}</p>
		{/if}
		{if $element.type == 2}
			<table width="100%" border="0">
				<tr>
					<td width="150">{if $element.required == 1}*{/if}{$element.title}:</td>
					<td><input type="text" name="field_{$element.elementid}" style="width:100%;" value="{$submit[$element.elementid]}" /></td>
				</tr>
			</table>
		{/if}
		{if $element.type == 3}
			<table width="100%" border="0">
				<tr>
					<td width="150">{if $element.required == 1}*{/if}{$element.title}:</td>
					<td><textarea name="field_{$element.elementid}" style="width:100%;">{$submit[$element.elementid]}</textarea></td>
				</tr>
			</table>
		{/if}
		{if $element.type == 4}
			<table width="100%" border="0">
				<tr>
					<td align="center" width="150"><input type="checkbox" name="field_{$element.elementid}" value="1"{if $submit[$element.elementid] == 1} checked="checked"{/if} /></td>
					<td>{if $element.required == 1}*{/if}{$element.title}</td>
				</tr>
			</table>
		{/if}
		{if $element.type == 5}
			<table width="100%" border="0">
				<tr>
					<td width="150" valign="top">{if $element.required == 1}*{/if}{$element.title}:</td>
					<td>
						{assign var='items' value="\n"|explode:$element.values}
						{foreach from=$items item=item}
							<input type="radio" name="field_{$element.elementid}" value="{$item}"{if $submit[$element.elementid] == $item} checked="checked"{/if}>{$item}</input><br />
						{/foreach}
					</td>
				</tr>
			</table>
		{/if}
		{if $element.type == 6}
			<table width="100%" border="0">
				<tr>
					<td width="150" valign="top">{if $element.required == 1}*{/if}{$element.title}:</td>
					<td>
						{assign var='items' value="\n"|explode:$element.values}
						<select name="field_{$element.elementid}">
							{foreach from=$items item=item}
								<option value="{$item}"{if $submit[$element.elementid] == $item} selected="selected"{/if}>{$item}</option>
							{/foreach}
						</select>
					</td>
				</tr>
			</table>
		{/if}
	{/foreach}
	<p>
		{$lang.required_descr}
	</p>
	<p>
		<input type="submit" name="submit_form" value="{if $form.submit != ''}{$form.submit}{else}{$lang.submit}{/if}"{if $submitted == true} disabled="disabled"{/if} />
	</p>
</form>