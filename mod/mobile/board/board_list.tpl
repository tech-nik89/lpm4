
	{section name=i loop=$bl}
    
	<div class="list">
		<img src="{$image_path}/{$bl[i].newposts}" alt="newposts" valign="middle" />
		<a href="{$bl[i].url}">{$bl[i].board}</a>
	</div>
    
	{/section}
