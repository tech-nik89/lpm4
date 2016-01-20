<client>
	<login valid="{if $login.valid}yes{else}no{/if}" userid="{$login.userid}" />
	{foreach from=$buddies item=buddy}
	<buddy nickname="{$buddy.nickname}" mailadress="{$buddy.email}" userid="{$buddy.userid}" />
	{/foreach}
</client>