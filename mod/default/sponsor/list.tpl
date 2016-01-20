{section name=i loop=$sl}

<div style="padding-bottom:8px;">

	<div class="headline">{$sl[i].name}</div>
	<p><a href="{$sl[i].homepage}" target="_blank">{$sl[i].homepage}</a></p>
	<p><img src="media/sponsor/{$sl[i].image}" border="0" /></p>
	<p>{$sl[i].description}</p>
	
</div>

{/section}