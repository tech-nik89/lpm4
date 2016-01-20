{section name=i loop=$news}
	<div class="headline">{$news[i].title}</div>
	<p align="right">{$news[i].time}{if $hide_author != '1'} | {$lang.author}: <a href="{$news[i].user_url}">{$news[i].nickname}</a>{/if}</p>
	{$news[i].text}
	<p align="right"><a href="{$news[i].url}#comments">{$news[i].comments} {$lang.comments}</a></p>
{/section}