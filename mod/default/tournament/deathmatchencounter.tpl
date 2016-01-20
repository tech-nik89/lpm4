<div style="line-height:20px; text-align:center; overflow:hidden; white-space:nowrap;">
	{if $winner eq 1 OR $draw eq 1}
		<strong>
	{/if}
	{if $player1url eq ''}
		<em>{$player1name}</em>
	{else}
		<a href="{$player1url}"><abbr style="text-decoration: none; border-bottom: 0px" title="{$player1name}">{$player1name}</abbr></a>
	{/if}
	{if $winner eq 1 OR $draw eq 1}
		</strong>
	{/if}
</div>
<div style="line-height:10px; text-align:center;">
	{if $link eq null}
		<span style="display:block;">vs.</span>
	{else}
		<a style="display:block;" href="{$link}">vs.</a>
	{/if}
</div>
<div style="line-height:20px; text-align:center; overflow:hidden; white-space:nowrap;">
	{if $winner eq 2 OR $draw eq 1}
		<strong>
	{/if}
	{if $player2url eq ''}
		<em>{$player2name}</em>
	{else}
		<a href="{$player2url}"><abbr style="text-decoration: none; border-bottom: 0px" title="{$player2name}">{$player2name}</abbr></a>
	{/if}
	{if $winner eq 2 OR $draw eq 1}
		</strong>
	{/if}
</div>