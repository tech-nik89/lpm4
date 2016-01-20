<h1>{$lang.manage_movies}</h1>

<p align="right">
	<a href="{makeurl mod='movies' mode='add'}">{$lang.add}</a>
</p>

<table width="100%" border="0">
	<tr>
		<th>{$lang.title}</th>
		<th>{$lang.urlid}</th>
		<th>{$lang.order}</th>
		<th>{$lang.language}</th>
		<th>{$lang.hidden}</th>
		<th style="text-align:right;">{$lang.action}</th>
	</tr>
	{foreach from=$movies item=movie}
		<tr{cycle values=', class="highlight_row"'}>
			<td>
				<a href="{makeurl mod='movies' movieid=$movie.movieid}">{$movie.title}</a>
			</td>
			<td>
				<a href="http://www.youtube.com/watch?v={$movie.urlid}" target="_blank" title="{$lang.open_youtube}">{$movie.urlid}</a>
			</td>
			<td>{$movie.order}</td>
			<td>{$movie.language}</td>
			<td>{if $movie.hidden==1}{$lang.yes}{else}-{/if}</td>
			<td style="text-align:right;">
				<a href="{makeurl mod='movies' mode='edit' movieid=$movie.movieid}">{$lang.edit}</a>
				|
				<a href="{makeurl mod='movies' mode='delete' movieid=$movie.movieid}" onclick="return confirm('{$lang.confirm_delete}');">{$lang.delete}</a>
			</td>
		</tr>
	{/foreach}
</table>