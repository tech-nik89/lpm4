<div class="headline">{$lang.buddylist}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.prename}</th>
		<th>{$lang.lastname}</th>
		<th>&nbsp;</th>
	</tr>
{section name=i loop=$buddies}
	<tr>
		<td><a href="{$buddies[i].url}">{if $buddies[i].online}<span style="color:#00EE00">{/if}{$buddies[i].nickname}{if $buddies[i].online}</span>{/if}</a></td>
		<td>{$buddies[i].prename}</td>
		<td>{$buddies[i].lastname}</td>
		<td>
			<form action="" method="post">
				<input type="hidden" name="userid" value="{$buddies[i].userid}" />
				<input type="submit" name="delete" value="X" />
			</form>
		</td>
	</tr>
{/section}
</table>