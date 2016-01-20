<div class="headline">{$lang.news_edit}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.remove}</th>
			<th>{$lang.title}</th>
			<th>{$lang.timestamp}</th>
			<th>{$lang.edit_count}</th>
			<th>{$lang.language}</th>
			<th>{$lang.edit}</th>
		</tr>
		
		{section name=i loop=$news}
			<tr class="{cycle values='highlight_row,'}">
				<td width="20"><input type="checkbox" name="remove_{$news[i].newsid}" id="remove_{$news[i].newsid}" value="1" /></td>
				<td><label for="remove_{$news[i].newsid}">{$news[i].title}</label></td>
				<td>{$news[i].time}</td>
				<td>{$news[i].edit_count}</td>
				<td>{if $news[i].language == ""}-{else}{$news[i].language}{/if}</td>
				<td><a href="{$news[i].edit}">{$lang.edit}</a></td>
			</tr>
		{/section}
		
	</table>
	
	<p><input type="submit" name="remove" value="{$lang.remove_selected}" />
	
</form>