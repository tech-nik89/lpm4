<div class="headline">{$lang.boxes}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.remove}</th>
			<th>{$lang.title}</th>
			<th>{$lang.box}</th>
			<th>{$lang.position}</th>
			<th>{$lang.order}</th>
			<th>{$lang.visible}</th>
			<th>{$lang.requires_login}</th>
		</tr>
		
		{foreach from=$boxes item=b}
		<tr{cycle values=', class="highlight_row"'}>
			<td><input type="checkbox" name="remove_{$b.boxid}" value="1" /></td>
			<td><input type="text" name="title_{$b.boxid}" value="{$b.title}" /></td>
			<td>
				{$b.file}
			</td>
			<td>
				<select name="position_{$b.boxid}">
					<option value="left"{if $b.position=='left'} selected="selected"{/if}>{$lang.pos_left}</option>
					<option value="right"{if $b.position=='right'} selected="selected"{/if}>{$lang.pos_right}</option>
				</select>
			</td>
			<td><input type="text" name="order_{$b.boxid}" value="{$b.order}" style="width:35px;" /></td>
			<td><input type="checkbox" name="visible_{$b.boxid}" value="1" {if $b.visible == 1} checked="checked"{/if} /></td>
			<td><input type="checkbox" name="requires_login_{$b.boxid}" value="1" {if $b.requires_login == 1} checked="checked"{/if} /></td>
		</tr>
		{/foreach}
		
		<tr>
			<td>{$lang.new}</td>
			<td><input type="text" name="title_new" value="" /></td>
			<td>
				<select name="file_new">
					<option value="">-</option>
					{foreach from=$availableList item=av}
						<option value="{$av}">{$av}</option>
					{/foreach}
				</select>
			</td>
			<td>
				<select name="position_new">
					<option value="left">{$lang.pos_left}</option>
					<option value="right">{$lang.pos_right}</option>
				</select>
			</td>
			<td><input type="text" value="" name="order_new" style="width:35px;" /></td>
			<td><input type="checkbox" name="visible_new" value="1" checked="checked" /></td>
			<td><input type="checkbox" name="requires_login_new" value="1" /></td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>