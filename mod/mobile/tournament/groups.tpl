<div class="headline">{$lang.tournament_table}</div>

{foreach item=round from=$tbl}

<div class="headline">{$lang.round} {$round.round}</div>
{if $round.map != ''}
	<p>
		{$lang.map}: {$round.map}
	</p>
{/if}
<table width="100%" border="0" cellpadding="1" cellspacing="5">
	
	<tr>
		
		{foreach from=$round.groups item=group}
			
			{cycle values=',,,</tr><tr>'}
			
			<td width="33%" valign="top">
				<p>
					{if $group.url != ''}
						<a href="{$group.url}">
							<strong>{$lang.group} {$group.group}</strong>
						</a>
					{else}
						<strong>{$lang.group} {$group.group}</strong>
					{/if}
				</p>
				<table width="100%" border="0">
					{foreach from=$group.plist item=p}
						<tr>
							<td width="35">{$p.rank}.</td>
							<td><a href="{$p.url}">{$p.name}</a></td>
						</tr>
					{/foreach}
				</table>
			</td>
			
		{/foreach}
		
	</tr>
	
</table>

{/foreach}