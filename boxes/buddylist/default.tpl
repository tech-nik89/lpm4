{foreach from=$box_buddies item=buddy}
	&raquo; 
	<a href="{$buddy.url}">
	{if $buddy.online}
		<span style="color:#009900;">
	{/if}
	{$buddy.nickname}
	{if $buddy.online}
		</span>
	{/if}
	</a>
	<br />
{/foreach}