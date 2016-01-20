{if count($switch_menu) > 0}
	<p>
		{section name=switch loop=$switch_menu}
			{if $switch_menu[switch].active}
				<a href="{$switch_menu[switch].url}">{$switch_menu[switch].title}</a>
			{else}
				{$switch_menu[switch].title}
			{/if}
			{if !$smarty.section.switch.last} | {/if}
		{/section}
	</p>
{/if}