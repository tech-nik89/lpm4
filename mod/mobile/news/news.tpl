<div class="headline">{$news.title}</div>
<p align="right">{$news.time} | {$lang.author}: <a href="{$news.user_url}">{$news.nickname}</a></p>
<p>{$news.text}</p>
<p>&nbsp;</p>
<a name="comments"></a>
{if count($comments) > 0}
	<div class="headline">{$lang.comments}</div>
	{section name=i loop=$comments}
		<div class="comment">
			<p>
				<strong>{$comments[i].nickname}</strong> ({$comments[i].time})<br />
				{$comments[i].text}
			</p>
		</div>
	{/section}
{/if}

{if $loggedin == true}
	<form action="" method="post">
		<div class="headline">{$lang.comment_add}</div>
		<textarea style="width:100%; height:150px;" name="comment"></textarea>
		<p><input type="submit" name="add" value="{$lang.add}" />
	</form>
{/if}