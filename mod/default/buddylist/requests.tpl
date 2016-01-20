<div class="headline">{$lang.buddy_requests}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.prename}</th>
		<th>{$lang.lastname}</th>
		<th>&nbsp;</th>
	</tr>
{section name=i loop=$requests}
	<tr>
		<td><a href="{$requests[i].url}">{$requests[i].nickname}</a></td>
		<td>{$requests[i].prename}</td>
		<td>{$requests[i].lastname}</td>
		<td>
			<form action="" method="post">
				<input type="hidden" name="userid" value="{$requests[i].userid}" />
				<input type="submit" name="accept" value="{$lang.accept}" />
				<input type="submit" name="discard" value="{$lang.discard}" />
			</form>
		</td>
	</tr>
{/section}
</table>