<beamer>
	{foreach from=$allnews item=news}
	<news title="{$news.title}" newsid="{$news.newsid}" timestamp="{$news.timestamp}" />
	{/foreach}
</beamer>