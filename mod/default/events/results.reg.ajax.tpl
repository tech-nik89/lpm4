<table width="100%" border="0" cellpadding="1" cellspacing="5">
	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.lastname}</th>
		<th>{$lang.prename}</th>
		<th>{$lang.event_user_registered}</th>
	</tr>
	{section name=i loop=$userList}
		<tr>
			<td>{$userList[i].nickname}</td>
			<td>{$userList[i].lastname}</td>
			<td>{$userList[i].prename}</td>
			<td>
				<input type="checkbox" name="reg_{$userList[i].userid}"
					id="reg_{$userList[i].userid}"
					value="1"{if $userList[i].reg == 1} checked="checked"{/if}
					onclick="setReg({$userList[i].userid});" />
			</td>
		</tr>
	{/section}
</table>