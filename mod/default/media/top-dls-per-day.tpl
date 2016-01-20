<div style="padding:3px;">
	<div class="headline">{if $smarty.get.start_day != $smarty.get.end_day}{$smarty.get.start_day}. - {/if}{$smarty.get.end_day}. {$month_str} {$smarty.get.year}</div>
	<ol style="">
		{section name=i loop=$items}
			<li>
				{if $smarty.get.start_day != $smarty.get.end_day}
					<a href="{makeurl mod='media' categoryid=$items[i].categoryid downloadid=$items[i].downloadid}">{$items[i].name}</a>
				{else}
					{$items[i].name}
				{/if}
				({$items[i].downloads})
			</li>
		{/section}
	</ol>
</div>