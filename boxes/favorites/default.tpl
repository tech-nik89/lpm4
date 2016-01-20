{if $box_favs_enabled}
	<p>
		{if count($box_favs) > 0}
		<ul>
			{foreach from=$box_favs item=fav}
				<li>
					<a href="{$fav.url}">{$fav.name}</a>
				</li>
			{/foreach}
		</ul>
		{else}
			{$lang.nofavorites}
		{/if}
		<p align="center">
			<a href="{$box_favs_url}">{$lang.openfavorites}</a>
		</p>
	</p>
	{if $box_favs_inmodule == false}
		<p>
			<form action="" method="post">
				<hr size="1" noshadow />
				<input type="submit" name="btnAddToFavorites" value="{$lang.addtofavorites}"
					style="width:100%; margin-top:4px;"/>
			</form>
		</p>
	{/if}
{else}
	<p>
		{$lang.enablefavorites}
	</p>
{/if}