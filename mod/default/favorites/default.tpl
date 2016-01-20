<div class="headline">{$lang.favorites}</div>

{if count($favs) > 0}
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		{foreach from=$favs item=fav}
			<tr class="{cycle values=',highlight_row'}">
				<td>
					<a href="{$fav.url}">{$fav.name}</a>
				</td>
				<td align="right" width="80">
					<form action="" method="post">
						<input type="hidden" name="favoriteid" value="{$fav.favoriteid}" />
						<input type="submit" name="delete" value="{$lang.delete}" />
					</form>
				</td>
			</tr>
		{/foreach}
	</table>
{else}
	<p>{$lang.nofavorites}</p>
{/if}