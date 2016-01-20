<div class="headline">{$lang.none_published_articles}</div>
{foreach from=$articles.none_published item=article}
	<form action="" method="post">
		<p>
			<strong>
				<a href="{$article.url}">{$article.title}</a>
			</strong>
			<br />
			{$article.timestamp|date_format}: {$article.preview}
		</p>
		<p>
			<input type="submit" name="publish" value="{$lang.publish}" />
			<input type="hidden" name="articleid" value="{$article.articleid}" />
		</p>
	</form>
{/foreach}