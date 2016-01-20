<div style="white-space:nowrap; height:50px; width:{math equation="(x+8)*(y-1)/2 + x" x=$encounterWidth y=$colspan}px;">
	<div style="float:left; width:4px; height:50px; padding:0px; overflow:hidden;">
		{if $arrow}
			<img src="./mod/default/tournament/images/right_arrow.png" border="0" alt="&gt" style="vertical-align: bottom;"/>
		{/if}
	</div>
	<div style="float:right; width:{math equation="(x+8)*(y-1)/2 + x - 4" x=$encounterWidth y=$colspan}px;">
		<div style="margin-top:1px; margin-bottom:1px; border:1px solid {$color}; height:50px;">
			<div style="line-height:20px; text-align:center; overflow:hidden; white-space:nowrap;">
				{if $winner eq 1}
					<strong>
				{/if}
				{if $player1url eq ''}
					<em>{$player1name}</em>
				{else}
					<a href="{$player1url}"><abbr style="text-decoration: none; border-bottom: 0px;" title="{$player1name}">{$player1name}</abbr></a>
				{/if}
				{if $winner eq 1}
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
				{if $winner eq 2}
					<strong>
				{/if}
				{if $player2url eq ''}
					<em>{$player2name}</em>
				{else}
					<a href="{$player2url}"><abbr style="text-decoration: none; border-bottom: 0px;" title="{$player2name}">{$player2name}</abbr></a>
				{/if}
				{if $winner eq 2}
					</strong>
				{/if}
			</div>
		</div>
	</div>
</div>