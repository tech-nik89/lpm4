<div class="headline">{$lang.new_entry}</div>

<form action="" method="post">
<div style="display:none;">
	E-Mail: <input type="text" name="email" value="" />
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<td colspan="2">{$lang.author}: &nbsp;&nbsp;<input type="text" name="author" value="{$nickname}" /></td>
	</tr>
	<tr>
		<td colspan="2"><textarea name="message" style="width:100%; height:120px;"></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="add" value="{$lang.add}" /></td>
	</tr>
	
</table>
<div class="headline">{$lang.guestbook}</div>
<table width="100%" border="0" cellspacing="1" cellpadding="5">
	
	{section name=i loop=$list}
	<tr>
		<td>{$lang.time}: <b>{$list[i].time}</b> | {$lang.author}: <b>{$list[i].author}</b></td>
	</tr>
	<tr>
		<td colspan="2">{$list[i].message}</td>
	</tr>
	<tr>
		{if $isallowed == false}
		<td colspan="2"><hr size="1" /></td>
		{else}
		<td><hr size="1" /></td>
		<td width="100">
			<form action="" method="post">
				<input type="submit" name="delete" value="{$lang.delete}" />
				<input type="hidden" name="guestbookid" value="{$list[i].guestbookid}" />
			</form>
		</td>
		{/if}
	</tr>
	{/section}
	
</table>
</form>

<p>{$pages}</p>