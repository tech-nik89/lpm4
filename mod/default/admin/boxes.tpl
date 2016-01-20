<div class="headline">{$lang.boxes}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th width="35">{$lang.remove}</th>
			<th>{$lang.title}</th>
			<th>{$lang.position}</th>
			<th>{$lang.visible}</th>
			<th>{$lang.requires_login}</th>
			<th>{$lang.domain}</th>
		</tr>
		
		{foreach from=$boxes item=b}
		<tr{cycle values=', class="highlight_row"'}>
			<td align="center"><input type="checkbox" name="remove_{$b.boxid}" value="1" /></td>
			<td valign="top"><input type="text" name="title_{$b.boxid}" value="{$b.title}"  style="width:120px;" /><br />
				{$lang.box}: {$b.file}
			</td>
			<td valign="top">
				<select name="position_{$b.boxid}" style="width:80px;">
					<option value="left"{if $b.position=='left'} selected="selected"{/if}>{$lang.pos_left}</option>
					<option value="right"{if $b.position=='right'} selected="selected"{/if}>{$lang.pos_right}</option>
				</select>
				<br />
				<input type="text" name="order_{$b.boxid}" value="{$b.order}" style="width:80px;" />
			</td>
			<td valign="top"><input type="checkbox" name="visible_{$b.boxid}" value="1" {if $b.visible == 1} checked="checked"{/if} /></td>
			<td valign="top"><input type="checkbox" name="requires_login_{$b.boxid}" value="1" {if $b.requires_login == 1} checked="checked"{/if} /></td>
			<td valign="top">
				<select name="domainid_{$b.boxid}" style="width:100%;">
					<option value="0">{$lang.all}</option>
					{foreach from=$dlist item=item}
						<option value="{$item.domainid}"{if $b.domainid == $item.domainid} selected="selected"{/if}>{$item.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		{/foreach}
		
		<tr{cycle values=', class="highlight_row"'}>
			<td valign="top" align="center">{$lang.new}</td>
			<td valign="top">
				<input type="text" name="title_new" value="" style="width:120px;" /><br />
				<select name="file_new" style="width:120px;">
					<option value="">-</option>
					{foreach from=$availableList item=av}
						<option value="{$av}">{$av}</option>
					{/foreach}
				</select>
			</td>
			<td valign="top">
				<select name="position_new"  style="width:80px;">
					<option value="left">{$lang.pos_left}</option>
					<option value="right">{$lang.pos_right}</option>
				</select>
				<br />
				<input type="text" value="0" name="order_new" style="width:80px;" />
			</td>
			<td valign="top"><input type="checkbox" name="visible_new" value="1" checked="checked" /></td>
			<td valign="top"><input type="checkbox" name="requires_login_new" value="1" /></td>
			<td valign="top">
				<select name="domainid_new" style="width:100%;">
					<option value="0">{$lang.all}</option>
					{foreach from=$dlist item=item}
						<option value="{$item.domainid}">{$item.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>