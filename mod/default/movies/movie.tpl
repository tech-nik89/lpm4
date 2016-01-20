<a name="{$movie.urlid}"></a>
<h1>{$movie.title}</h1>

{if $movie.description|trim != ''}
	<p>
		{* <strong>{$lang.description}:</strong><br /> *}
		{$movie.description}
	</p>
{/if}

<iframe
	allowfullscreen="" 
	frameborder="0"
	height="480"
	src="http://www.youtube.com/embed/{$movie.urlid}?{if $autoplay == '1'}autoplay=1{/if}{if $hd == '1'};vq=hd720{/if}"
	width="640">
</iframe>