<div class="headline">{$lang.buddylist}</div>


{section name=i loop=$buddies}
	<div class="list">
		<a href="{$buddies[i].url}" style="display:block;">{$buddies[i].nickname}</a>
	</div>
{/section}