<div class="headline">{$lang.account}</div>
<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td width="20%"><strong>User-ID:</strong></td>
			<td>{$user.userid}</td>
			<td rowspan="6" align="right" valign="top">{$avatar}</td>
		</tr>
		<tr>
			<td width="20%"><strong>{$lang.email}:</strong></td>
			<td>{$user.email}</td>
		</tr>
		<tr>
			<td><strong>{$lang.nickname}:</strong></td>
			<td>{$user.nickname}</td>
		</tr>
		<tr>
			<td><strong>{$lang.prename}:</strong></td>
			<td>
				{if $disable_editing != '1'}
					<input type="text" name="prename" value="{$user.prename}" />
				{else}
					{$user.prename}
				{/if}
			</td>
		</tr>
		<tr>
			<td><strong>{$lang.lastname}:</strong></td>
			<td>
				{if $disable_editing != '1'}
					<input type="text" name="lastname" value="{$user.lastname}" /></td>
				{else}
					{$user.lastname}
				{/if}
		</tr>
		{if !$disable_birthday}
			<tr>
				<td><strong>{$lang.birthday}:</strong></td>
				<td>
					{if $disable_editing != '1'}
						{html_select_date start_year='-70' end_year='-8' time=$user.birthday}</td>
					{else}
						{$user.birthday|date_format}
					{/if}
			</tr>
		{/if}
	</table>
	{if $disable_editing != '1'}
		<p><input type="submit" name="save" value="{$lang.save}" /></p>
	{/if}
</form>