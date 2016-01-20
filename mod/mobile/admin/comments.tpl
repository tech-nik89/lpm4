<div class="headline">{$lang.comments}</div>
<form action="" method="post">
	<p>{$lang.find}: <input type="text" name="find" value="{$find}" /> <input type="submit" name="go" value="{$lang.go}" /></p>
</form>
<p>{$pages}</p>
<table width="100%" border="0" cellpadding="5" cellspacing="1">

	<tr>
		<th><a href="{$sort.nickname}">{$lang.nickname}</a></th>
		<th><a href="{$sort.mod}">{$lang.mod}</a></th>
		<th><a href="{$sort.comment}">{$lang.comment}</a></th>
		<th><a href="{$sort.timestamp}">{$lang.timestamp}</a></th>
		<th>{$lang.remove}</th>
	</tr>

	{section name=i loop=$comments}
	<tr class="{cycle values=',highlight_row'}">
		<td><a href="{$comments[i].url}">{$comments[i].nickname}</a></td>
		<td>{$comments[i].mod}</td>
		<td>{$comments[i].text}</td>
		<td>{$comments[i].time}</td>
		<td>
			<form action="" method="post">
				<input type="submit" name="remove" value="{$lang.remove}" />
				<input type="hidden" name="commentid" value="{$comments[i].commentid}" />
			</form>
		</td>
	</tr>
	{/section}
	
</table>
<p>{$pages}</p>