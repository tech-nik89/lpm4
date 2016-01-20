{section name=i loop=$tl}
	<div class="list">
		<img src="{$image_path}/{$tl[i].newposts}" alt="newposts" valign="middle" />
		<a href="{$tl[i].url}">{$tl[i].thread}</a>
	</div>
{/section}