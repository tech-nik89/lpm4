<table width="100%" border="0" cellpadding="5" cellspacing="1">

	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.email}</th>
		<th>{$lang.name}</th>
		<th width="120">{$lang.options}</th>
	</tr>
	
	{section name=users loop=$users}
		<tr class="{cycle values=',highlight_row'}" valign="top">
			<td><a href="{$users[users].url_show_profile}">{$users[users].nickname}</a></td>
			<td>{$users[users].email}</td>
			<td>{$users[users].prename} {$users[users].lastname}</td>
			<td><a href="{$users[users].url_deposit}">
				<a href="#" onclick="toggleOptions({$users[users].userid}); return false;">{$lang.options}</a>
				<div id="divOptions_{$users[users].userid}" style="display:none;">
					&raquo; <a href="{$users[users].url_edit}">{$lang.options_edit}</a><br />
					&raquo; <a href="{$users[users].url_memberships}">{$lang.options_memberships}</a><br />
					&raquo; <a href="{$users[users].url_delete}">{$lang.options_delete}</a><br />
					&raquo; <a href="{$users[users].url_deposit}">{$lang.options_deposit}</a>
				</div>
			</td>
		</tr>
	{/section}
	
</table>